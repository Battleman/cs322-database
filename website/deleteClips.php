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
        <h2>Result from your clip deletion:</h2>
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
            $IDsToDelete = $_POST['IDsToDelete'];
            if(empty($IDsToDelete)){
                
                echo("Nothing selected (". count($IDsToDelete) . "). Redirecting home...");
                // echo "<script>setTimeout(\"location.href = 'index.php';\",1500);</script>";
                die();
            }
            $delQuery = "DELETE FROM Clips WHERE ";
            $n = count($IDsToDelete);
            for($i=0; $i < $n; $i++){
                $delQuery = $delQuery . "clipid=".$IDsToDelete[$i] . ' OR ';
            }
            echo($delQuery . '<br>');
            $delQuery = preg_replace("/ OR $/", ';', $delQuery);
            echo($delQuery . "<br>");
            if(!$conn->query($delQuery)){
                echo "Problem at deletion... " . $conn->error;
            }
            $conn->close();
            ?>
        </div>
        
        <button onclick="window.location.href='index.php'">Go back to home page</button>
    </div>
</body>