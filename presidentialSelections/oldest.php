<?php

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
		case 'oldest_query':
			oldest_query();
			break;
    }
}

function oldest_query() {
	// The echoed statements get returned to the html
	$user = 'root';
	$pass = 'root';
	$db = 'presidential_elections';

	$db = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect to database");

	$sql = "SELECT candidate.name as name, election.year, election.num, (election.year - year(candidate.birth_date)) as age FROM candidate, election WHERE election.winner = candidate.name ORDER BY age DESC";
	$result = $db->query($sql);
	
	// Make the winner table
	echo "<table border='1' align='center'>
	<tr>
	<th style=\"text-align:center\">Candidate</th>
	<th style=\"text-align:center\">Election Number</th>
	<th style=\"text-align:center\">Election Year</th>
	<th style=\"text-align:center\">Age When Taking Office</th>
	</tr>";
	echo "<tr>";

	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			echo "<tr>";
			echo "<td style=\"text-align:center\">" . $row['name'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['num'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['year'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['age'] . "</td>";
			echo "</tr>";
		}
	} else {
			echo "<tr>";
			echo "<td>" . "0 Results" . "</td>";
			echo "<td>" . "0 Results" . "</td>";
			echo "<td>" . "0 Results" . "</td>";
			echo "</tr>";
	}
	echo "</table>";
	
}

?>