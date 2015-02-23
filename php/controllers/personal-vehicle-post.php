<?php
require_once("index.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once("../classes/visitor.php");
require_once("../classes/vehicle.php");
require_once("../classes/adminprofile.php");
require_once("../classes/parkingspot.php");
require_once("../classes/parkingpass.php");


try {
	//set up connection
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/cnmparking.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

	// create and insert admin profile
	$adminProfile = new AdminProfile(null, $admin->getAdminId(), $adminProfile->getAdminFirstName(), $adminProfile->getAdminLastName());
	$adminProfile->insert($mysqli);

	// create and insert parking spot
	$parkingSpot = new ParkingSpot(null, $location->getLocationId(), $parkingSpot->getPlacardNumber());
	$parkingSpot->insert($mysqli);

	// create and insert visitor
	$visitor = new Visitor(null, $_POST["visitorEmail"], $_POST["visitorFirstName"], $_POST["visitorLastName"], $_POST["visitorPhone"]);
	$visitor->insert($mysqli);

	// create and insert vehicle
	$vehicle = new Vehicle(null, $visitor->getVisitorId(), $_POST["vehicleColor"], $_POST["vehicleMake"], $_POST["vehicleModel"], $_POST["vehiclePlateNumber"], $_POST["vehiclePlateState"], $_POST["vehicleYear"]);
	$vehicle->insert($mysqli);

	// create and insert parking pass
	$parkingPass = new ParkingPass(null, $adminProfile->getAdminProfileId(), $parkingSpot->getParkingSpotId(), $vehicle->getVehicleId(), $_POST["EndDateTime"], $parkingPass->getIssuedDateTime(), $_POST["startDateTime"], $parkingPass->getUuId());
	$parkingPass->insert($mysqli);

if(@isset($_POST["visitorFirstName"]) === false || @isset($_POST["visitorLastName"]) === false || @isset($_POST["visitorEmail"]) === false || @isset($_POST["visitorPhone"]) === false || @isset($_POST["vehicleMake"]) === false || @isset($_POST["vehicleModel"]) === false || @isset($_POST["vehicleYear"]) === false ||
	@isset($_POST["vehicleColor"]) === false || @isset($_POST["vehiclePlateNumber"]) === false || @isset($_POST["vehiclePlateState"]) === false) {
	trow(new mysqli_sql_exception("form values not complete. verify the form and try again."));
}

	echo "<p class=\"alert alert-success\">Visitor(id = " . $visitor->getVisitorId() . ") added!</p>";
} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}
?>
