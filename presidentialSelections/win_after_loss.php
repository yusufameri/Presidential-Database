<?php

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
		case 'win_after_loss':
			win_after_loss_query();
			break;
    }
}

function win_after_loss_query() {
	// The echoed statements get returned to the html
	$user = 'root';
	$pass = 'root';
	$db = 'presidential_elections';

	$db = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect to database");

	$sql = "select e2.winner as cand, e1.year as year_lose, e1.winner as lose_winner, e2.year as year_win FROM participated p1, participated p2, election e1, election e2 WHERE p1.candidate = p2.candidate 
		AND p1.year < p2.year AND e1.year = p1.year AND e2.year = p2.year AND e1.winner != p1.candidate AND e2.winner = p2.candidate";
	$result = $db->query($sql);
	
	// Make the winner table
	echo "<table border='1' align='center'>
	<tr>
	<th style=\"text-align:center\">Candidate</th>
	<th style=\"text-align:center\">Year Lost</th>
	<th style=\"text-align:center\">Lost Election To</th>
	<th style=\"text-align:center\">Year Won After</th>
	</tr>";
	echo "<tr>";

	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			echo "<tr>";
			echo "<td style=\"text-align:center\">" . $row['cand'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['year_lose'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['lose_winner'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['year_win'] . "</td>";
			echo "</tr>";
		}
	} else {
			echo "<tr>";
			echo "<td>" . "0 Results" . "</td>";
			echo "<td>" . "0 Results" . "</td>";
			echo "<td>" . "0 Results" . "</td>";
			echo "<td>" . "0 Results" . "</td>";
			echo "</tr>";
	}
	echo "</table>";
	
}

?>