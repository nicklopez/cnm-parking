<?php
$pageTitle = "Results";
require_once("../lib/header.php");

require_once("../classes/parkingpass.php");

// require the encrypted configuration functions
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

try {
// now retrieve the configuration parameters
	$configFile = "/etc/apache2/capstone-mysql/cnmparking.ini";
	$configArray = readConfig($configFile);

// first, connect to mysqli
	mysqli_report(MYSQLI_REPORT_STRICT);
	$mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"], $configArray["database"]);

	?>
	<header>
		<h1>Results</h1>
	</header>
	<table id="reports" class="hover row-border">
		<thead>
			<th>Location</th>
			<th>Visitor Name</th>
			<th>Vehicle Plate #</th>
			<th>Arrival</th>
			<th>Departure</th>
			<th>Approved By</th>
		</thead>
		<tbody>

			<?php

			$begin = new DateTime($_POST["startDate"]);
			$end = new DateTime($_POST["endDate"]);

			$parkingPass = ParkingPass::getVisitorParkingDataByDateRange($mysqli, $begin, $end);

			if (count($parkingPass) === 0) {
				return null;
			} else {
				foreach($parkingPass as $pass) {
					$locationDesc = $pass["locationDescription"] . " - " . $pass["locationNote"];
					$visitor = $pass["visitorFirstName"] . " " . $pass["visitorLastName"];
					$admin = $pass["adminFirstName"] . " " . $pass["adminLastName"];
					$plateNumber = $pass["vehiclePlateNumber"];
					$startDate = new DateTime($pass["startDateTime"]);
					$formattedStartDate = $startDate->format("m-d-Y H:i:s");
					$endDate = new DateTime($pass["endDateTime"]);
					$formattedEndDate = $endDate->format("m-d-Y H:i:s");
					$row = <<< EOF
				<tr><td>$locationDesc</td><td>$visitor</td><td>$plateNumber</td><td>$formattedStartDate</td>
				<td>$formattedEndDate</td><td>$admin</td></tr>
EOF;
					echo $row;
				}
			}

	echo "</tbody>";
	echo "</table>";
	$mysqli->close();

} catch(Exception $exception) {
	echo "<td><tr class=\"alert alert-danger\" colspan=\"3\">Exception: " . $exception->getMessage() . "</td></tr>";
}

require_once("../lib/footer.php"); ?>