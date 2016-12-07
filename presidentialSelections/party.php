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

	$sql = "SELECT * FROM party WHERE party.name=\"" . $db->real_escape_string($party) . "\"";
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