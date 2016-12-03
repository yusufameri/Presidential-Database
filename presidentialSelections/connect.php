<?php

$user = 'root';
$pass = '';
$db = 'presidential_elections';

$db = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect to database");

echo"Presidential Selections" . "<br>";
echo"By Yusuf Ameri and Douglas Sexton" . "<br><br>";

$sql = "SELECT * FROM `testtable` WHERE 1";
$result = $db->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "Column1: " . $row["id1"]." Column2: " . $row["id2"]." Column3: " . $row["id3"]. " Column4: " . $row["id4"]."<br>";
    }
} else {
    echo "0 results";
}

?>