<?php

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
		case 'swing_query':
			swing_query();
			break;
    }
}

function swing_query() {
	// The echoed statements get returned to the html
	$user = 'root';
	$pass = 'root';
	$db = 'presidential_elections';

	$db = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect to database");

	$sql = "SELECT p1.year AS year, p1.candidate AS candidate, p1.party AS party, p2.year as year1, p2.party AS party1 FROM participated p1, participated p2 WHERE p1.candidate = p2.candidate AND p1.party < p2.party AND p1.party !=\"none\" AND p2.party !=\"none\" ORDER BY p1.year ASC";
	$result = $db->query($sql);
	
	// Make the winner table
	echo "<table border='1' align='center'>
	<tr>
	<th>Year</th>
	<th>Candidate</th>
	<th>Party</th>
	<th>Year</th>
	<th>Candidate</th>
	<th>Party</th>
	</tr>";
	echo "<tr>";

	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			echo "<tr>";
			echo "<td style=\"text-align:center\">" . $row['year'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['candidate'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['party'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['year1'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['candidate'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['party1'] . "</td>";
			echo "</tr>";
		}
	} else {
			echo "<tr>";
			echo "<td>" . "0 Results" . "</td>";
			echo "<td>" . "0 Results" . "</td>";
			echo "<td>" . "0 Results" . "</td>";
			echo "<td>" . "0 Results" . "</td>";
			echo "<td>" . "0 Results" . "</td>";
			echo "<td>" . "0 Results" . "</td>";
			echo "</tr>";
	}
	echo "</table>";
	
}

?>