<?php

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'get_candidates': // This is no longer used
            get_candidates();
            break;
		case 'build_candidate_selection_box':
			build_candidate_selection_box();
			break;
		case 'candidate_query':
			candidate_query($_POST['data']);
			break;
    }
}

function get_candidates() {
	// The echoed statements get returned to the html
	$user = 'root';
	$pass = 'root';
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

function build_candidate_selection_box() {
	// The echoed statements get returned to the html
	$user = 'root';
	$pass = 'root';
	$db = 'presidential_elections';

	$db = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect to database");

	$sql = "SELECT * FROM `candidate` WHERE 1";
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

function candidate_query($candidate) {
	// The echoed statements get returned to the html
	$user = 'root';
	$pass = 'root';
	$db = 'presidential_elections';

	$db = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect to database");

	$sql = "SELECT * FROM candidate WHERE candidate.name=\"" . $db->real_escape_string($candidate) . "\"";
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