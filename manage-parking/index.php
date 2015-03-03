<?php
$pageTitle = "Manage Parking";
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
	$locations = $objects["location"];
	$parkingSpots = $objects["parkingSpot"];

	var_dump($locations);
		//var_dump($parkingSpot);


} catch(Exception $exception) {
	echo "<td><tr class=\"alert alert-danger\" colspan=\"3\">Exception: " . $exception->getMessage() . "</td></tr>";
}

?>

<header>
	<h1>Manage locations & parking spots</h1>
</header>


<?php
require_once("../php/lib/footer.php");
?>
