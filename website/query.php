<head>
    <meta charset="utf-8">
    <title>DB 2018 - Query results</title>
</head>
<?php
?>
<html>

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
$sql_country = 'SELECT * FROM Countries WHERE country = ' . $_POST['input_country'] . ';';
// $sql_clips = 'SELECT * FROM Clips;';
// $sql_people = 'SELECT * FROM People;';
echo $sql_country . '<br>';

$result = $conn->query($sql_country);
echo "Result has type" . gettype($result);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        // echo "Name: " . $row["name"]. " - Email: " . $row["email"]. "<br>";
        echo "ID {$row['countryid']} corresponds to country {$row['country']} <br>";
    }
} else {
    echo "0 results";
}
$conn->close();
?> 
</html>