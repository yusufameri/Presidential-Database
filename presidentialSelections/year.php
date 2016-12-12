<?php

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
		case 'build_year_selection_box':
			build_year_selection_box();
			break;
		case 'year_query':
			year_query($_POST['data']);
			break;
		case 'poll_query':
			poll_query($_POST['data']);
			break;
    }
}

function build_year_selection_box() {
	// The echoed statements get returned to the html
	$user = 'root';
	$pass = 'root';
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
	$pass = 'root';
	$db = 'presidential_elections';

	$db = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect to database");

	$sql = "SELECT * FROM election WHERE election.year=\"" . $db->real_escape_string($year) . "\"";
	$result = $db->query($sql);
	
	// Make the winner table
	echo "<table border='1' align='center'>
	<tr>
	<th style=\"text-align:center\">Year</th>
	<th style=\"text-align:center\">Election Number</th>
	<th style=\"text-align:center\">Winner</th>
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
	echo "<table border='1' align='center'>
	<tr>
	<th style=\"text-align:center\">Candidate</th>
	<th style=\"text-align:center\">Party</th>
	<th style=\"text-align:center\">Electoral Vote</th>
	<th style=\"text-align:center\">Popular Vote</th>
	<th style=\"text-align:center\">Vice President</th>
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
			echo "<td style=\"text-align:center\">" . "0 Results" . "</td>";
			echo "<td style=\"text-align:center\">" . "0 Results" . "</td>";
			echo "<td style=\"text-align:center\">" . "0 Results" . "</td>";
			echo "<td style=\"text-align:center\">" . "0 Results" . "</td>";
			echo "<td style=\"text-align:center\">" . "0 Results" . "</td>";
			echo "</tr>";
	}
	echo "</table>";
	
}

function poll_query($year) {
	// The echoed statements get returned to the html
	$user = 'root';
	$pass = 'root';
	$db = 'presidential_elections';

	$db = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect to database");

	$sql = "SELECT p.election_year, DATE_FORMAT(p.fc_date, '%M %D, %Y') as fc_date, p.fc_repvs, p.fc_demvs, p.fc_error, p.fc_absolute_error, p.last_survey_day, p.first_survey_day, p.release_day, p.sample, p.question, 
		p.survey_org, p.survey_sponser, p.intmethod, p.sample_desc, p.poll_type FROM polls p WHERE p.election_year=\"" . $db->real_escape_string($year) . "\" ORDER BY p.fc_date ASC";
	$result = $db->query($sql);

	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			
			// Display survey org or unavailable if data not available
			if ($row['survey_sponser'] != 'NULL') {
				echo "<h2>Survey Organization: " . $row['survey_org'] . "</h2>";
			} else {
				echo "<h2>Survey Organization: Unavailable</h2>";
			}
			
			// Dispaly Survey Sponser if exists
			if ($row['survey_sponser'] != 'NULL') {
				echo "<h4>Survey Sponsor: " . $row['survey_sponser'] . "</h2>";
			}
			
			// Display poll type
			if ($row['poll_type'] != 'NULL') {
				echo "<h4>Poll Type: " . $row['poll_type'] . "</h2>";
			}
			
			// Display interrogation method
			if ($row['intmethod'] != 'NULL') {
				echo "<h4>Interrogation Method: " . $row['intmethod'] . "</h2>";
			}
			
			// Display sample description
			if ($row['sample_desc'] != 'NULL') {
				echo "<h4>Sample Description: " . $row['sample_desc'] . "</h2>";
			}
			
			// Display question
			if ($row['question'] != 'NULL') {
				echo "<h4>Question: " . $row['question'] . "</h2>";
			}
			
			// Make the winner table
			echo "<table border='1' align='center'>
			<tr>
			<th style=\"text-align:center\">Year</th>
			<th style=\"text-align:center\">Forecast Date</th>
			<th style=\"text-align:center\">Sample Size</th>
			<th style=\"text-align:center\">Republican</th>
			<th style=\"text-align:center\">Democrat</th>
			<th style=\"text-align:center\">Forecast Error</th>
			</tr>";
			
			echo "<tr>";
			echo "<td style=\"text-align:center\">" . $row['election_year'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['fc_date'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['sample'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['fc_repvs'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['fc_demvs'] . "</td>";
			echo "<td style=\"text-align:center\">" . $row['fc_error'] . "</td>";
			echo "</tr>";
			echo "</table>";
			echo "<hr>";
		}
	} else {
		echo "<h3>No polling available for this year.</h3>";
	}
}

?>