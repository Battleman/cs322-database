<html>
<head>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1"/>
    <title>DB 2018 - Query results</title>
</head>
<body>
    <div>
        <?php
        require_once '/var/webconfig/dbConfig.php';
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        // $sql_country = "SELECT * FROM Countries WHERE country LIKE '%" . $_GET['search'] . "%';";
        $search = $_GET['search'];
        $sql_clips = "SELECT * FROM Clips WHERE cliptitle = " . addslashes($search) . " OR cliptitle LIKE '%$search%' OR cliptitle LIKE '" . preg_replace("/ /", "%", $search) . "';";
        $sql_people = "SELECT * FROM People WHERE fullname = '" . addslashes($search) . "' OR fullname LIKE '%" . addslashes($search) . "%';";

        $resultPeople = $conn->query($sql_people);
        if ($resultPeople === false) {
            die("Result failed, check your query");
        }
        echo "<h3>People</h3><br />";
        if ($resultPeople->num_rows > 0) {
                // output data of each row
            while ($row = $resultPeople->fetch_assoc()) {
                echo "<strong>Name</strong> : " . $row['fullname'] . "<br>
                ";
            }
        } else {
            echo "0 results";
        }

        $resultClips = $conn->query($sql_clips);
        if ($resultClips === false) {
            die("Result failed, check your query");
        }
        if ($resultClips->num_rows > 0) {
            echo "<h3>Clips</h3><br />";
                // output data of each row
            while ($row = $resultClips->fetch_assoc()) {
                echo "<strong>Clip title</strong> : " . $row['cliptitle'] . " (" . $row['clipyear'] . ")<br>
                ";
            }
        } else {
            echo "0 results";
        }


        $conn->close();
        ?> 
    </div>
</body>
</html>