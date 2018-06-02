<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1"/>
    <title>DB 2018 - Query results</title>

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
<button onclick="window.location.href='index.php'">Go back to home page</button>
<?php
    require_once '/var/webconfig/dbConfig.php';
    require_once 'phpSnippets.php';
    $conn = new mysqli($servername, $username, $password, $dbname);

    $name = $_POST['insertBio_name'];
    $query = "SELECT fullname, personid FROM People WHERE fullname LIKE '%$name%' LIMIT 100;";
    if(!$res = $conn->query($query)){
        die('Personid wrong: ' . $conn->error);
    }
    if($res->num_rows == 0){
        echo "Nobody was found with that name. Check again";
        die();
    }
    if($res->num_rows > 1) {
        echo "This name is ambiguous. Did you mean:<br>";
        while($row = $res->fetch_assoc()){
            echo $row['fullname'] . "<br>";
        }
        die();
    }
    $personid = $res->fetch_assoc()['personid'];

    switch ($_POST['bioSelect']){
        case 'biography':
            $biography = $_POST['input_Biography'];
            $biographer = $_POST['input_Biographer'];
            $query = "INSERT INTO Biographies(personid, biography, biographer) VALUES ($personid, $biography, $biographer);";
            if($conn->query($query)){
                echo "Successfuly added a biography";
            } else {
                echo "Error, could not insert biography: " . $conn->error;
            }
            break;
        case 'salary':
            //find personid
            $film = $_POST['salaryFilm'];
            $year = $_POST['salaryYear'];
            $salary=$_POST['salarySalary'];
            
            //find current salary to append
            $query = "SELECT salary FROM Bioinfos WHERE personid = $personid;";
            if(!$res = $conn->query($query)){
                die("Find salary with personid is wrong:" . $conn->error);
            }
            $currSalary = $res->fetch_assoc()['salary'];
            $newSalary = "|_$film ($year)_ -> \$$salary]";
            $newSalary = str_replace(']', $newSalary, $currSalary);
            
            $query = "UPDATE Bioinfos SET salary='$newSalary' WHERE personid=$personid;";
            if($conn->query($query)){
                echo "Successfully added the salary !";
            } else {
                echo "Error updating the salary:" . $conn->error;
            }
            break;
        case 'birthDeath':
            $birth = $death = $update = "";
            if(!empty($_POST['birth'])){
                $birth = $_POST['birth'];
                $update = "birth=$birth";
            }
            if(!empty($_POST['death'])){
                $death = $_POST['death'];
                if(empty($_POST['birth'])){
                    $update = "death='$death'";
                } else { 
                    $update = $update . ", death=$death";
                }
            }
            $query = "UPDATE Bioinfos SET $update;";
            if($conn->query($query)){
               echo "Successfuly added birth/death information"; 
            } else {
                echo "Error inserting birth/death information";
            }
            break;
        default:
            break;
    }
    $conn->close();
?>

</body>
</html>