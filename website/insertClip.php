<html>
<head>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1"/>
    <title>DB 2018 - Insert/Delete result</title>
</head>
<body>
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
        //check genre exists
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
                    $genreID = $resGenre->fetch_assoc();
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
                    $langID = $res->fetch_assoc();
                }
            }

            $insertQuery = "INSERT INTO Clips (rank, cliptitle, votes, clipyear, cliptype) VALUES ("
                . (empty($_POST['input_insertClipRank']) ? 'Null' : $_POST['input_insertClipRank']) . ",
            '$clipTitle',"
                . (empty($_POST['input_insertClipVotes']) ? 'Null' : $_POST['input_insertClipVotes']) . ","
                . (empty($_POST['input_insertClipYear']) ? 'Null' : $_POST['input_insertClipYear']) . ","
                . (empty($_POST['input_insertClipType']) ? 'Null' : "'" . $_POST['input_insertClipType'] . "'") . ");";
            echo $insertQuery;
            if ($conn->query($genreQuery) == true) {
                echo ("Entry successfully added in Clips");
            } else {
                echo ("Error: " . $sql . "<br>" . $conn->error);
            }

            $clipID = getId($conn);

            ### Insert genre relation if necessary
            if (!empty($_POST['input_insertClipGenre'])) {
                $insertHasGenre = "INSERT INTO HasGenre (clipid, genreid) VALUES ('$clipID','$genreID');";
                if ($conn->query($insertHasGenre) != true) {
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
                $conditions = $conditions . "cliprank = " . $_POST['input_insertClipRank'] . " AND ";
            }
            if (!empty($_POST['input_insertClipVotes'])) {
                $conditions = $conditions . "clipvotes = " . $_POST['input_insertClipVotes'] . " AND ";
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
                die("The queries were incorrect: " . $conn->error);
            } else {
                if ($res->num_rows == 0) {
                    die("Your query returned no result. Check again.");
                } elseif ($res->num_rows > 1) {
                    
                    echo ("Your query is ambiguous, more than one result was found. Did you mean :<br>");
                    while ($row = $res->fetch_assoc()) {
                        $text = $row['cliptitle'] . " (" . $row['clipyear'] . ")" . (($row['cliptype'] == 'NULL' or $row['cliptype'] == "") ? "" : " (" . $row['cliptype'] . ")") . "<br>";
                        echo (preg_replace($cliptTypePattern, $clipTypeReplacement, $text));
                    }
                } else { //found 1 result, delete
                    $clip = $res->fetch_assoc();
                    $id = $clip['clipid'];
                    $deleteQueryClip = "DELETE FROM Clips " . $conditions;
                    $conn->query($deleteQueryClip);
                    if (!empty($_POST['input_insertClipLang'])) {
                        $deleteLang = "DELETE FROM HasLang WHERE clipid = $id;";
                        $conn->query($deleteLang);
                    }
                    if (!empty($_POST['input_insertClipGenre'])) {
                        $deleteGenre = "DELETE FROM HasGenre WHERE clipid = $id";
                    }
                    $deleteQueryLang = "";
                    $deleteQueryGenre = "";
                }
            }
        }

        $conn->close();

        ?>
    </div>
</body>