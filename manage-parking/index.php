<?php
$title = "Manage Parking";
$headerTitle = "Manage locations & parking spots";
require_once("../php/lib/header.php");
require_once("../php/classes/location.php");
require_once("../php/classes/parkingspot.php");


// require the encrypted configuration functions
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

try {
// now retrieve the configuration parameters
$configFile = "/etc/apache2/capstone-mysql/cnmparking.ini";
$configArray = readConfig($configFile);

// first, connect to mysqli
mysqli_report(MYSQLI_REPORT_STRICT);
$mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"], $configArray["database"]);

$objects = Location::getAllLocationsAndParkingSpots($mysqli);

?>

<table id="example" class="hover row-border">
	<thead>
	<th>Location</th>
		<th>Placard #</th>
</thead>
	<tbody>
	<?php

	foreach($objects as $object) {
		$locationId = $object["locationId"];
		$locationNote = $object["locationNote"];
		$locationDesc = $object["locationDescription"];
		$placard = $object["placardNumber"];
		$row = <<< EOF
		<tr><td></td><td>$placard</td><td>$locationDesc - $locationNote</td></tr>
EOF;
			echo $row;
		}
	echo "</tbody>";
	echo "</table>";
	$mysqli->close();

	} catch(Exception $exception) {
		echo "<td><tr class=\"alert alert-danger\" colspan=\"3\">Exception: " . $exception->getMessage() . "</td></tr>";
	}

	require_once("../php/lib/footer.php");
	?>
