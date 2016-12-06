<?php

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
		case 'build_year_selection_box':
			build_year_selection_box();
			break;
		case 'year_query':
			year_query($_POST['data']);
			break;
    }
}

function build_year_selection_box() {
	// The echoed statements get returned to the html
	$user = 'root';
	$pass = '';
	$db = 'presidential_elections';

	$db = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect to database");

	$sql = "SELECT * FROM election WHERE 1";
	$result = $db->query($sql);
	
	echo "<select id='select_box_id'>";
	
	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			echo "<option value='" . $row['year'] . "'>" . $row['year'] ."</option>";
		}
	} else {
			echo "<option value='" . "DB_ERROR" . "'>" . "DB Access FAILED" ."</option>";
	}
	echo "</select>";
}

function year_query($year) {
	// The echoed statements get returned to the html
	$user = 'root';
	$pass = '';
	$db = 'presidential_elections';

	$db = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect to database");

	$sql = "SELECT * FROM election WHERE election.year=\"" . $db->real_escape_string($year) . "\"";
	$result = $db->query($sql);
	
	// Make the winner table
	echo "<table border='1'>
	<tr>
	<th>Year</th>
	<th>Election Number</th>
	<th>Winner</th>
	</tr>";
	echo "<tr>";

	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			echo "<tr>";
			echo "<td style=\"text-align:center\">" . $row['year'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['num'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['winner'] . "</td>";
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
	
	// Now make the table for all participants
	$sql = "SELECT * FROM participated WHERE participated.year=\"" . $db->real_escape_string($year) . "\"";
	$result = $db->query($sql);
	echo "<br></br>";
	echo "<table border='1'>
	<tr>
	<th>Candidate</th>
	<th>Party</th>
	<th>Electoral Vote</th>
	<th>Popular Vote</th>
	<th>Vice President</th>
	</tr>";
	echo "<tr>";

	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			echo "<tr>";
			echo "<td style=\"text-align:center\">" . $row['candidate'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['party'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['electoral_vote'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['popular_vote'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['vice_president'] . "</td>";
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