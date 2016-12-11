<?php

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
		case 'popularity_query':
			popularity_query();
			break;
    }
}

function popularity_query() {
	// The echoed statements get returned to the html
	$user = 'root';
	$pass = 'root';
	$db = 'presidential_elections';

	$db = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect to database");

	$sql = "SELECT p.year, sum(p.popular_vote) as pop_vote, pop.population, (sum(p.popular_vote) / pop.population) as perc_vote FROM participated p, population pop WHERE p.year = pop.year AND p.popular_vote != 0 
		GROUP BY p.year ORDER BY perc_vote DESC";
	$result = $db->query($sql);
	
	// Make the winner table
	echo "<table border='1' align='center'>
	<tr>
	<th style=\"text-align:center\">Election Year</th>
	<th style=\"text-align:center\">Total Popular Vote</th>
	<th style=\"text-align:center\">Total Population That Year</th>
	<th style=\"text-align:center\">% of Population that Voted</th>
	</tr>";
	echo "<tr>";

	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			echo "<tr>";
			echo "<td style=\"text-align:center\">" . $row['year'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['pop_vote'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['population'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['perc_vote'] . "</td>";
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