<?php
/**
 * controller to verify availability of parking spot/time.placard
 *
 * @Author Kyle Dozier <kyle@kedlogic.com>
 */


/**
 * require the encrypted config functions
 */
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

require_once ("../classes/parkingpass.php");
require_once ("../classes/parkingspot.php");


var_dump($_POST);

/**
 * connect to mySQL
 */
mysqli_report(MYSQLI_REPORT_STRICT);
$config = readConfig("/etc/apache2/capstone-mysql/cnmparking.ini");
$mysqli = new mysqli($config["hostname"], $config["username"], $config["password"], $config["database"]);

/**
 * verify availability via ParkingPass get method
 * use $arrival and $departure as $sunrise and $sunset
 * html id/name 's = dateTimeVerifyAvailabilityInputArrival and dateTimeVerifyAvailabilityInputDeparture
 */

$location = filter_input(INPUT_POST, "intLocationInput", FILTER_VALIDATE_INT);
if(empty($searchInput) === true) {
	throw(new InvalidArgumentException("Input contains hostile code"));
}

$location = filter_input(INPUT_POST, "dateTimePickerArrival", FILTER_SANITIZE_STRING);
if(empty($searchInput) === true) {
	throw(new InvalidArgumentException("Input contains hostile code"));
}

$departure = filter_input(INPUT_POST, "dateTimePickerDeparture", FILTER_SANITIZE_STRING);
if(empty($searchInput) === true) {
	throw(new InvalidArgumentException("Input contains hostile code"));
}

$searchResults = ParkingPass::getParkingPassAvailability($mysqli, $location, $arrival, $departure);
if($searchResults !== null) {
	$searchResults = array($searchResults);
}

return($searchResults);
