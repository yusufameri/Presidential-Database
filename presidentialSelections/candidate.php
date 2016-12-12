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
		case 'get_candidate_pic':
			get_candidate_pic($_POST['data']);
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

	// First display birth and death dates
	$sql = "SELECT candidate.name, DATE_FORMAT( candidate.birth_date, '%M %D, %Y') as birth_date, DATE_FORMAT( candidate.death_date, '%M %D, %Y') as death_date FROM candidate WHERE candidate.name=\"" . $db->real_escape_string($candidate) . "\"";
	$result = $db->query($sql);

	if ($result->num_rows > 0) {
		// output data of each row (should just be 1 row)
		while($row = $result->fetch_assoc()) {
			echo "<h1 align='center'>" . $row['name'] . "</h1>";
			$bday = (empty($row['birth_date'])) ? "?" : $row['birth_date'];
			$dday = (empty($row['death_date'])) ? "?" : $row['death_date'];
			echo "<h2 align='center'>" . $bday . " - " . $dday . "</h2>";
		}
	} else {
			echo "<h1>No Result Found</h1>";
	}
	
	// Now display particpation as a candidate
	$sql = "SELECT * FROM participated p, election e WHERE (p.candidate = \"" . $db->real_escape_string($candidate) . "\" OR p.vice_president = \"" . $db->real_escape_string($candidate) . "\") AND p.year = e.year";
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
	<th style=\"text-align:center\">Election Winner</th>
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
			echo "<td style=\"text-align:center\">" . $row['winner'] . "</td>";
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
	
}

function get_candidate_pic($candidate) {
	// The echoed statements get returned to the html
	$user = 'root';
	$pass = 'root';
	$db = 'presidential_elections';

	$db = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect to database");

	$sql = "SELECT * FROM candidate WHERE candidate.name=\"" . $db->real_escape_string($candidate) . "\"";
	$result = $db->query($sql);

	if ($result->num_rows > 0) {
		// output data of each row (should just be 1 row)
		while($row = $result->fetch_assoc()) {
			echo $row['image_url'];
		}
	}
}

?>