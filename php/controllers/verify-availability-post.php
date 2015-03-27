<?php
/**
 * controller to verify availability of parking spot/time.placard
 *
 * @Author Kyle Dozier <kyle@kedlogic.com>
 */

/**
 * require the encrypted config functions
 */
require_once("/home/cnmparki/etc/mysql/encrypted-config.php");

require_once ("../classes/parkingpass.php");
require_once ("../classes/parkingspot.php");

/**
 * connect to mySQL
 */
$configArray = readConfig("/home/cnmparki/etc/mysql/cnmparking.ini");
$host = $configArray["hostname"];
$db = $configArray["database"];
$dsn = "mysql:host=$host;dbname=$db";
$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
$pdo = new PDO($dsn, $configArray["username"], $configArray["password"], $options);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

/**
 * verify availability via ParkingPass get method
 * use $arrival and $departure as $sunrise and $sunset
 * return single parkingSpotId as
 *
 * @throw
 */
$location = filter_input(INPUT_POST, "selectListLocation", FILTER_VALIDATE_INT);
if(empty($location) === true) {
	throw(new InvalidArgumentException("Input contains hostile code"));
}

$arrival = filter_input(INPUT_POST, "dateTimePickerArrival", FILTER_SANITIZE_STRING);
if(empty($arrival) === true) {
	throw(new InvalidArgumentException("Input contains hostile code"));
} else {
	$arrival[strlen($arrival)-1] = '0';
	$arrival[strlen($arrival)-2] = '0';

}

$departure = filter_input(INPUT_POST, "dateTimePickerDeparture", FILTER_SANITIZE_STRING);
if(empty($departure) === true) {
	throw(new InvalidArgumentException("Input contains hostile code"));
} else {
	$departure[strlen($departure)-1] = '0';
	$departure[strlen($departure)-2] = '0';

}

$availableSpot = ParkingPass::getParkingPassAvailability($pdo, $location, $arrival, $departure);
if($availableSpot !== false) {

	$isAvailable = "Yes! There are currently available parking spots for that time window" . "," . $availableSpot["parkingSpotId"];
} else {
	$isAvailable = "No parking spots are available during the given time window" . "," . $availableSpot["parkingSpotId"];
}

echo $isAvailable;