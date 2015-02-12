<?php
require_once("personal-vehicle.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once("../classes/visitor.php");
require_once("../classes/vehicle.php");

// verify the form values have been submitted
if(@isset($_POST["visitorFirstName"]) === false || @isset($_POST["visitorLastName"]) === false || @isset($_POST["visitorEmail"]) === false || @isset($_POST["visitorPhone"]) === false || @isset($_POST["vehicleMake"]) === false || @isset($_POST["vehicleModel"]) === false || @isset($_POST["vehicleYear"]) === false || @isset($_POST["vehicleColor"]) === false || @isset($_POST["vehiclePlateNumber"]) === false || @isset($_POST["vehiclePlateState"]) === false || @isset($_POST["vehiclePlateYear"]) === false) {
	echo "<p class=\"alert alert-danger\">form values not complete. verify the form and try again.</p>";
}

try {
	//
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/cnmparking.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

	$visitor = new Visitor(null, $_POST["visitorEmail"], $_POST["visitorFirstName"], $_POST["visitorLastName"], $_POST["visitorPhone"]);
	$visitor->insert($mysqli);

	$vehicle = new Vehicle(null, $visitor->getVisitorId(), $_POST["vehicleColor"], $_POST["vehicleMake"], $_POST["vehicleModel"], $_POST["vehiclePlateNumber"], $_POST["vehiclePlateState"], $_POST["vehicleYear"]);
	$vehicle->insert($mysqli);

if(@isset($_POST["visitorFirstName"]) === false || @isset($_POST["visitorLastName"]) === false || @isset($_POST["visitorEmail"]) === false || @isset($_POST["visitorPhone"]) === false || @isset($_POST["vehicleMake"]) === false || @isset($_POST["vehicleModel"]) === false || @isset($_POST["vehicleYear"]) === false || @isset($_POST["vehicleColor"]) === false || @isset($_POST["vehiclePlateNumber"]) === false || @isset($_POST["vehiclePlateState"]) === false || @isset($_POST["vehiclePlateYear"]) === false) {
	echo "<p class=\"alert alert-danger\">form values not complete. verify the form and try again.</p>";
}

	echo "<p class=\"alert alert-success\">Visitor vehicle info(id = " . $visitor->getVisitorId() . ") added!</p>";
} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}
?>
