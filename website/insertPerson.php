<html>
<head>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1"/>
    <title>DB 2018 - Insert/Delete result</title>
</head>
<body>
    <?php

    require_once '/var/webconfig/dbConfig.php';
    require_once 'phpSnippets.php';
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    ###############################
    #QUERY CLIPID
    ###############################
    $findFilmID = "SELECT clipid, clipyear, cliptitle, cliptype FROM Clips WHERE cliptitle  LIKE '%" . $_POST['input_insertPersonFilmName'] . "%' ";
    if (!empty($_POST['filmYear'])) {
        $findFilmID = $findFilmID . "AND clipyear = " . $_POST['filmYear'];
    }
    if (!empty($_POST['clipType'])) {
        $findFilmID = $findFilmID . "AND cliptype = '" . $_POST['clipType'] . "'";
    }
    $findFilmID = $findFilmID . ";";

    $res = $conn->query($findFilmID);
    if ($res == false) {
        die("Bad findFilm query");
    }
    if ($res->num_rows == 0) {
        die("<h3>The film you entered wasn't found.</h3>Please check.");
    }
    if ($res->num_rows > 1) {
        echo ("Your title you entered is ambiguous, more than one result was found. Did you mean :<br>");
        while ($row = $res->fetch_assoc()) {
            $text = $row['cliptitle'] . " (" . $row['clipyear'] . ")" . (($row['cliptype'] == 'NULL' or $row['cliptype'] == "") ? "" : " (" . $row['cliptype'] . ")") . "<br>";
            echo (preg_replace($clipTypePattern, $clipTypeReplacement, $text));
        }
        echo '<button onclick="window.location.href=\'index.php\'">Go back to home page</button>';
        die();
    }
    $clipid = $res->fetch_assoc()['clipid'];

    #########################################
    #QUERY PERSONID
    #########################################
    if (isset($_POST['insertNew'])) {
        $insertPersonQuery = "INSERT INTO People (fullname) VALUES ('" . $_POST['input_insertPersonName'] . "');";
        $conn->query($insertPersonQuery);
        $personid = getId($conn);
    } else { //assume insertExist
        $searchPersonQuery = "SELECT * FROM People WHERE fullname LIKE '%" . $_POST['input_insertPersonName'] . "%';";
        $res = $conn->query($searchPersonQuery);
        if ($res == false) {
            die("Bad searchPerson query");
        }
        if ($res->num_rows == 0) {
            die("The name you entered wasn't found. Please check.");
        }
        if ($res->num_rows > 1) {
            echo ("<h3>The name you entered is ambiguous</h3>More than one result was found. Did you mean :<br>");
            while ($row = $res->fetch_assoc()) {
                echo ($row['fullname'] . "<br>\n");
            }
            die();
        }
        $personid = $res->fetch_assoc()['personid'];
    }

    #########################################
    #DETERMINE TABLE, COLUMN NAMES AND VALUES
    ##########################################
    switch ($_POST['roleSelect']) {
        case "producer":
            $table = "Produces";
            $colNames = "personid, clipid, producesroleid";
            $rolesColNames = "role, addinfo"; /*producesroleid deducted*/
            $roleColVals = "'" .
                $_POST['input_insertPersonExactRole_Producer'] . "','" . 
                $_POST['input_insertPersonAddinfo_Producer'] . "'";
            break;
        case "writer":
            $table = "Writes";
            $colNames = "personid, clipid, writesroleid";
            $rolesColNames = "role, worktype, addinfo";
            $roleColVals = "'" .
                $_POST['input_insertPersonRole_Writer'] . "','" .
                $_POST['input_insertPersonWorkType_Writer'] . "','" .
                $_POST['input_insertPersonAddinfo_Writer'] . "'";
            break;
        case "actor":
            $table = "PlaysIn";
            $colNames = "personid, clipid, playsinroleid";
            $rolesColNames = "chars, ordercredits, addinfo";
            $roleColVals = "'" .
                $_POST['input_insertPersonCharacter_Actor'] . "','" .
                $_POST['input_insertPersonOrderCredits_Actor'] . "','" .
                $_POST['input_insertPersonAddinfo_Actor'] . "'";
            break;
        case "director":
            $table = "Directs";
            $colNames = "personid, clipid, directsroleid";
            $rolesColNames = "role, addinfo";
            $rolesColVals = "'" .
                $_POST['input_insertPersonExactRole_Producer'] . "','" .
                $_POST['input_insertPersonAddinfo_Producer'] . "'";
            
            break;
    }
    $roleTable = $table . "Roles";
    $insertQueryRole = "INSERT INTO $roleTable ($rolesColNames) VALUES ($roleColVals);";
    
    if(!$conn->query($insertQueryRole)){
        if(isset($_POST['insertNew'])){ //revert
            $delQuery = "DELETE FROM People WHERE Personid = $personid;";
            $conn->query($delQuery);
        }
        die("Insert ExactRole failed:" . $conn->error);
    }
    $roleid = getID($conn);

    $colVals = "$personid, $clipid, $roleid";
    $insertQuery = "INSERT INTO $table ($colNames) VALUES ($colVals)";
    if($conn->query($insertQuery) == false){
        //TODO revert
        die("Insert Role failed:" . $conn->error);
    }   
    $conn->close();
    ?>
</body>
</html>