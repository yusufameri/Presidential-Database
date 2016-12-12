<?php

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
		case 'build_party_selection_box':
			build_party_selection_box();
			break;
		case 'party_query':
			party_query($_POST['data']);
			break;
    }
}

function build_party_selection_box() {
	// The echoed statements get returned to the html
	$user = 'root';
	$pass = 'root';
	$db = 'presidential_elections';

	$db = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect to database");

	$sql = "SELECT * FROM `party` WHERE 1";
	$result = $db->query($sql);
	
	echo "<select id='select_box_id'>";
	
	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			echo "<option value='" . $row['name'] . "'>" . $row['name'] ."</option>";
		}
	} else {
			echo "<option value='" . "DB_ERROR" . "'>" . "DB Access FAILED" ."</option>";
	}
	echo "</select>";
}

function party_query($party) {
	// The echoed statements get returned to the html
	$user = 'root';
	$pass = 'root';
	$db = 'presidential_elections';

	$db = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect to database");

	$sql = "SELECT e.num, p1.year, p1.candidate, p1.party, p1.electoral_vote, p1.popular_vote, p1.vice_president, p2.party as win_party FROM participated p1, participated p2, election e WHERE p1.party = \"" . $db->real_escape_string($party) . "\" AND p1.year = p2.year AND p1.year = e.year AND e.winner = p2.candidate ORDER BY e.num ASC";
	$result = $db->query($sql);
	
	echo "<table border='1' align='center'>
	<tr>
	<th style=\"text-align:center\">Election Number</th>
	<th style=\"text-align:center\">Year</th>
	<th style=\"text-align:center\">Candidate</th>
	<th style=\"text-align:center\">Party</th>
	<th style=\"text-align:center\">Electoral Vote</th>
	<th style=\"text-align:center\">Popular Vote</th>
	<th style=\"text-align:center\">Vice President</th>
	<th style=\"text-align:center\">Winning Party</th>
	</tr>";
	echo "<tr>";
	
	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			echo "<tr>";
			echo "<td style=\"text-align:center\">" . $row['num'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['year'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['candidate'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['party'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['electoral_vote'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['popular_vote'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['vice_president'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['win_party'] . "</td>";
			echo "</tr>";
		}
	} else {
			echo "<tr>";
			echo "<td style=\"text-align:center\">" . "0 Results" . "</td>";
			echo "<td style=\"text-align:center\">" . "0 Results" . "</td>";
			echo "<td style=\"text-align:center\">" . "0 Results" . "</td>";
			echo "<td style=\"text-align:center\">" . "0 Results" . "</td>";
			echo "<td style=\"text-align:center\">" . "0 Results" . "</td>";
			echo "<td style=\"text-align:center\">" . "0 Results" . "</td>";
			echo "<td style=\"text-align:center\">" . "0 Results" . "</td>";
			echo "<td style=\"text-align:center\">" . "0 Results" . "</td>";
			echo "</tr>";
	}
	echo "</table>";
	
	// Now print some stats
	$sql = "SELECT p2.party, count(p2.party) as party_wins FROM participated p1, participated p2, election e WHERE p1.party = \"" . $db->real_escape_string($party) . "\" AND p1.year = p2.year AND p1.year = e.year AND e.winner = p2.candidate GROUP BY p2.party";
	$result = $db->query($sql);
	
	echo "<h2 align='center'>In elections that the " . $db->real_escape_string($party) . " party has particpated in these parties have had at least 1 victory.</h2>";
	echo "<table border='1' align='center'>
	<tr>
	<th>Party</th>
	<th>Election Wins For That Party</th>
	</tr>";
	echo "<tr>";
	
	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			echo "<tr>";
			echo "<td style=\"text-align:center\">" . $row['party'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['party_wins'] . "</td>";
			echo "</tr>";
		}
	} else {
			echo "<tr>";
			echo "<td style=\"text-align:center\">" . "0 Results" . "</td>";
			echo "<td style=\"text-align:center\">" . "0 Results" . "</td>";
			echo "</tr>";
	}
	echo "</table>";

}

?>