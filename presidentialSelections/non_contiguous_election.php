<?php

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
		case 'non_contiguous_election_query':
			non_contiguous_election_query();
			break;
    }
}

function non_contiguous_election_query() {
	// The echoed statements get returned to the html
	$user = 'root';
	$pass = 'root';
	$db = 'presidential_elections';

	$db = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect to database");

	$sql = "SELECT e1.year, e1.winner, e1.num, e2.year as year2, e2.num as num2 FROM election e1, election e2 WHERE e1.winner = e2.winner AND (e2.num - e1.num) > 1 AND e1.num 
		NOT IN (SELECT e11.num FROM election e11, election e12, election e13 WHERE e11.winner = e12.winner AND (e12.num - e11.num) > 1 AND e11.winner = e13.winner AND (e13.num - e11.num) = 1)";
	$result = $db->query($sql);
	
	// Make the winner table
	echo "<table border='1'>
	<tr>
	<th style=\"text-align:center\">Candidate</th>
	<th style=\"text-align:center\">Before Election Number</th>
	<th style=\"text-align:center\">Before Election Year</th>
	<th style=\"text-align:center\">After Election Number</th> 
	<th style=\"text-align:center\">After Election Year</th>
	</tr>";
	echo "<tr>";

	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			echo "<tr>";
			echo "<td style=\"text-align:center\">" . $row['winner'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['num'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['year'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['num2'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['year2'] . "</td>";
			echo "</tr>";
		}
	} else {
			echo "<tr>";
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