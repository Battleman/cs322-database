<html>
<head>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1"/>
    <title>DB 2018 - Insert/Delete result</title>
     <!-- Latest compiled and minified CSS -->
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" type="text/css" media="screen" href="index.css">
<script src="index.js"></script>
</head>
<body>
    <div class="container">
        <h2>Result from your clip insertion/deletion:</h2>
        <div>
            <?php
            require_once '/var/webconfig/dbConfig.php';
            require_once 'phpSnippets.php';
            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            if (isset($_POST['insert'])) {
            ##check genre exists
                if (!empty($_POST['input_insertClipGenre'])) {
                    $genreQuery = "SELECT genreid FROM Genres WHERE genre LIKE '%" . $_POST['input_insertClipGenre'] . "%';";
                    $resGenre = $conn->query($genreQuery);
                    if ($resGenre === false) {
                        die("Check your query");
                    }
                    if ($resGenre->num_rows == 0) {
                        die("The genre ' " . $_POST['input_insertClipGenre'] . " ' does not exist<br>");
                    } elseif ($resGenre->num_rows > 1) {
                        echo ("The pattern for genre you entered was found multiple times. Please select one and retry.<br>Found genres: <br>");
                        while ($row = $resGenre->fetch_assoc()) {
                            echo $row['genre'] . "<br>";
                        }
                        die();
                    } else {
                        $genreID = $resGenre->fetch_assoc()['genreid'];
                    }
                }

            ## check language exists
                if (!empty($_POST['input_insertClipLang'])) {
                    $lang = $_POST['input_insertClipLang'];
                    $LangQuery = "SELECT langid FROM Languages WHERE language = '$lang';";
                    $res = $conn->query($LangQuery);
                    if ($res === false) {
                        die("Check your language query");
                    }
                    if ($res->num_rows == 0) {
                    //if language not present, insert it.
                        $insertLangQuery = "INSERT INTO Languages (language) VALUES ('$lang');";
                        $langID = getId($conn);
                    } else {
                        $langID = $res->fetch_assoc()['langid'];
                    }
                }
                $clipTitle = $_POST['input_insertClipTitle'];
                $insertQuery = "INSERT INTO Clips (rank, cliptitle, votes, clipyear, cliptype) VALUES ("
                    . (empty($_POST['input_insertClipRank']) ? 'Null' : $_POST['input_insertClipRank']) . ",
                '$clipTitle',"
                    . (empty($_POST['input_insertClipVotes']) ? 'Null' : $_POST['input_insertClipVotes']) . ","
                    . (empty($_POST['input_insertClipYear']) ? 'Null' : $_POST['input_insertClipYear']) . ","
                    . (empty($_POST['input_insertClipType']) ? 'Null' : "'" . $_POST['input_insertClipType'] . "'") . ");";

                if ($conn->query($insertQuery) == true) {
                    echo ("<h3>Film successfuly added");
                } else {
                    echo ("Error: " . $sql . "<br>" . $conn->error);
                }

                $clipID = getId($conn);

                ### Insert genre relation if necessary
                if (!empty($_POST['input_insertClipGenre'])) {
                    $insertHasGenre = "INSERT INTO HasGenre (clipid, genreid) VALUES ('$clipID','$genreID');";
                    if ($conn->query($insertHasGenre) == false) {
                        die("Check your insertHasGenre query");
                    }
                }

            ### Insert lang relation if necessary
                if (!empty($_POST['input_insertClipLang'])) {
                    $insertHasGenre = "INSERT INTO HasLang (clipid, langid) VALUES ('$clipID','$langID');";
                    if ($conn->query($insertHasGenre) != true) {
                        die("Check your insertHasLang query");
                    }
                }
            } elseif (isset($_POST['delete'])) {
                $where = $year = $lang = $genre = $rank = $votes = $type = "";
                $conditions = "WHERE cliptitle LIKE '%" . addslashes($_POST['input_insertClipTitle']) . "%' AND ";


                if (!empty($_POST['input_insertClipYear'])) {
                    $conditions = $conditions . " clipyear = " . $_POST['input_insertClipYear'] . " AND ";
                }
                if (!empty($_POST['input_insertClipRank'])) {
                    $conditions = $conditions . "rank = " . $_POST['input_insertClipRank'] . " AND ";
                }
                if (!empty($_POST['input_insertClipVotes'])) {
                    $conditions = $conditions . "votes = " . $_POST['input_insertClipVotes'] . " AND ";
                }
                if (!empty($_POST['input_insertClipType'])) {
                    $conditions = $conditions . "cliptype = '" . $_POST['input_insertClipType'] . "' AND ";
                }

                // echo($conditions . "<br>");
                $conditions = preg_replace("/ AND $/", "", $conditions);
                $conditions = $conditions . ";";
                // echo($conditions);

                $searchQuery = "SELECT * FROM Clips " . $conditions;

                // echo $searchQuery . "<br>";
                // echo $deleteQuery . "<br>";

                $res = $conn->query($searchQuery);
                if ($res == false) {
                    die("The SearchToDelete were incorrect: " . $conn->error);
                } else {
                    if ($res->num_rows == 0) {
                        die("Your query returned no result. Check again.");
                    } elseif ($res->num_rows > 1) {

                        echo ("Your query is ambiguous, more than one result was found. Select the one(s) you want to delete:<br>");
                        echo ("<form action='deleteClips.php' method='POST'>\n");
                        $i = 0;
                        while ($row = $res->fetch_assoc()) {
                            $text = $row['cliptitle'] . " (" . $row['clipyear'] . ")" . (($row['cliptype'] == 'NULL' or $row['cliptype'] == "") ? "" : " (" . $row['cliptype'] . ")") . "<br>";
                            echo ("<input type='checkbox' name='IDsToDelete[]' value='".$row['clipid']."'>" . preg_replace($clipTypePattern, $clipTypeReplacement, $text) . "</input>\n");
                            $i++;
                        }
                        echo "<input type='submit' name='deleteSelected' value='Delete selected films' method='POST'>";
                        echo "</form><br>";
                    } else { //found 1 result, delete
                        $id = $res->fetch_()['clipid'];
                        $deleteQueryClip = "DELETE FROM Clips " . $conditions;
                        $totalSuccess = $conn->query($deleteQueryClip);

                        if (!empty($_POST['input_insertClipLang'])) {
                            $deleteLang = "DELETE FROM HasLang WHERE clipid = $id;";
                            $totalSuccess = $totalSuccess and $conn->query($deleteLang);
                        }
                        if (!empty($_POST['input_insertClipGenre'])) {
                            $deleteGenre = "DELETE FROM HasGenre WHERE clipid = $id";
                            $totalSuccess = $totalSuccess and $conn->query($deleteGenre);
                        }
                        if ($totalSuccess) {
                            echo "The film was correctly deleted";
                        } else {
                            echo "ERROR, the film wasn't well deleted";
                        }
                    }
                }
            }

            $conn->close();

            ?>
        </div>

        
        <button onclick="window.location.href='index.php'">Go back to home page</button>
    </div>
</body>