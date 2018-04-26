<html>
<head>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1"/>
    <title>DB 2018 - Query results</title>
</head>
<body>
    <div>
        <?php
            $servername = "localhost";
            $username = "olivier";
            $password = "olivier";
            $dbname = "db2018";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $sql_country = "SELECT * FROM Countries WHERE country = '" . $_POST['input_search'] . "';";
            $sql_clips = "SELECT * FROM Clips WHERE UPPER(cliptitle) = '" . $_POST['input_search'] . "';";
            $sql_people = "SELECT * FROM Clips WHERE UPPER(cliptitle) = '" . $_POST['input_search'] . "';";
            // $sql_people = 'SELECT * FROM People;';
            echo $sql_country_old;
            $result = $conn->query($sql_country_old);
            if ($result === false) {
                die("Result failed, check your query");
            }
            if ($result->num_rows > 0) {
                // output data of each row
                while ($row = $result->fetch_assoc()) {
                    // echo "Name: " . $row["name"]. " - Email: " . $row["email"]. "<br>";
                    echo "ID {$row['countryid']} corresponds to country {$row['country']} <br>";
                }
            } else {
                echo "0 results";
            }
            $conn->close();
            ?> 
    </div>
</body>
</html>