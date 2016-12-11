<?php

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
		case 'win_wo_pop_query':
			win_wo_pop_query();
			break;
    }
}

function win_wo_pop_query() {
	// The echoed statements get returned to the html
	$user = 'root';
	$pass = 'root';
	$db = 'presidential_elections';

	$db = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect to database");

	$sql = "SELECT e.year as elec_year, p1.candidate as win_cand, p1.electoral_vote as win_elec, p1.popular_vote as win_pop, p2.candidate as lose_cand, p2.electoral_vote as lose_elec, 
		p2.popular_vote as lose_pop FROM election e, participated p1, participated p2 WHERE e.year = p1.year AND e.year = p2.year AND e.winner = p1.candidate AND p2.popular_vote > p1.popular_vote";
	$result = $db->query($sql);
	
	// Make the winner table
	echo "<table border='1' align='center'>
	<tr>
	<th style=\"text-align:center\">Election Year</th>
	<th style=\"text-align:center\">Winning Candidate</th>
	<th style=\"text-align:center\">Winning Candidate Electoral Vote</th>
	<th style=\"text-align:center\">Winning Candidate Popular Vote</th>
	<th style=\"text-align:center\">Losing Candidate</th>
	<th style=\"text-align:center\">Losing Candidate Electoral Vote</th>
	<th style=\"text-align:center\">Losing Candidate Popular Vote</th>
	</tr>";
	echo "<tr>";

	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			echo "<tr>";
			echo "<td style=\"text-align:center\">" . $row['elec_year'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['win_cand'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['win_elec'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['win_pop'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['lose_cand'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['lose_elec'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['lose_pop'] . "</td>";
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
			echo "<td>" . "0 Results" . "</td>";
			echo "</tr>";
	}
	echo "</table>";
	
}

?>