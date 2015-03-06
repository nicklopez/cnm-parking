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
		<th>adminProfileId</th>
		<th>vehicleId</th>
		<th>parkingSpotId</th>
		<th>startDate</th>
		<th>endDate</th>
	</thead>
	<tbody>

<?php

$begin = new DateTime($_POST["startDate"]);
$end = new DateTime($_POST["endDate"]);


$parkingPass = ParkingPass::getParkingPassByStartDateTimeEndDateTimeRange($mysqli, $begin, $end);

foreach($parkingPass as $pass) {
	$adminProfileId = $pass->getAdminProfileId();
	$vehicleId = $pass->getVehicleId();
	$parkingSpotId = $pass->getParkingSpotId();
	$startDate = $pass->getStartDateTime()->format("m-d-Y");
	$endDate = $pass->getEndDateTime()->format("m-d-Y");
	$row = <<< EOF
		<tr><td>$adminProfileId</td><td>$vehicleId</td><td>$parkingSpotId</td><td>$startDate</td><td>$endDate</td></tr>
EOF;
	echo $row;
}
?>
</tbody>
</table>

<?php
$mysqli->close();

} catch(Exception $exception) {
	echo "<td><tr class=\"alert alert-danger\" colspan=\"3\">Exception: " . $exception->getMessage() . "</td></tr>";
}

require_once("../lib/footer.php"); ?>