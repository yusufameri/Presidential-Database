<?php

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'get_candidates': // Only one case right now
            get_candidates();
            break;
    }
}

function get_candidates() {
	// The echoed statements get returned to the html
	$user = 'root';
	$pass = '';
	$db = 'presidential_elections';

	$db = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect to database");

	$sql = "SELECT * FROM `candidate` WHERE 1";
	$result = $db->query($sql);
	
	echo "<table border='1'>
	<tr>
	<th>Name</th>
	</tr>";
	echo "<tr>";

	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			echo "<tr>";
			echo "<td>" . $row['name'] . "</td>";
			echo "</tr>";
		}
	} else {
			echo "<tr>";
			echo "<td>" . "0 Results" . "</td>";
			echo "</tr>";
	}
	echo "</table>";
}
?>