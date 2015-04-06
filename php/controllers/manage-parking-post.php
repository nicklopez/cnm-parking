<?php
// start a PHP session for CSRF protection
session_start();

// require encrypted configuration files
require_once("/home/cnmparki/etc/mysql/encrypted-config.php");

// require the mySQL enabled ParkingSpot and Location class
require_once("../classes/location.php");
require_once("../classes/parkingspot.php");

try {

	$locationName = $_POST["locationName"];
	$locationDescription = $_POST["locationDescription"];
	$locationSpotStart = $_POST["locationSpotStart"];
	$locationSpotEnd = $_POST["locationSpotEnd"];
	$placardNumber = $_POST["start"];
	$end = $_POST["end"];
	$locationId = $_POST["modalLocationId"];

	// create a Visitor (if required) and Invite object and insert them into mySQL
	$configArray = readConfig("/home/cnmparki/etc/mysql/cnmparking.ini");

	// Connect to mySQL
	$host = $configArray["hostname"];
	$db = $configArray["database"];
	$dsn = "mysql:host=$host;dbname=$db";
	$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
	$pdo = new PDO($dsn, $configArray["username"], $configArray["password"], $options);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

	if((!empty($locationName) && !empty($locationDescription) && !empty($locationSpotStart) && !empty($locationSpotEnd))) {
		if($locationSpotStart > $locationSpotEnd) {
			echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Invalid placard # range.</strong></div>";
			exit;
		}

		$newLocation = new Location(null, 90.00, $locationName, $locationDescription, 180.00);
		$newLocation->insert($pdo);

		for ($locationSpotStart; $locationSpotStart<=$locationSpotEnd; $locationSpotStart++) {
			$parkingSpots = new ParkingSpot(null, $newLocation->getLocationId(), $locationSpotStart);
			$parkingSpots->insert($pdo);
		}
		echo "<div class=\"alert alert-success\" role=\"alert\"><strong>Location and parking spot(s) have been added</strong></div>";

	} elseif($placardNumber > $end) {
		echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Invalid placard # range.</strong></div>";
		exit;
	} else {
		for($placardNumber; $placardNumber <= $end; $placardNumber++) {
			$parkingSpots = new ParkingSpot(null, $locationId, $placardNumber);
			$parkingSpots->insert($pdo);
		}
		echo "<div class=\"alert alert-success\" role=\"alert\"><strong>Parking spot(s) have been added</strong></div>";
	}
} catch(Exception $exception) {
	echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh snap!</strong> Unable to add: " . $exception->getMessage() . "</div>";
}