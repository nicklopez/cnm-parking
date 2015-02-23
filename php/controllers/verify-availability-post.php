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



/**
 * connect to mySQL
 */
mysqli_report(MYSQLI_REPORT_STRICT);
$config = readConfig("/etc/apache2/capstone-mysql/cnmparking.ini");
$mysqli = new mysqli($config["hostname"], $config["username"], $config["password"], $config["database"]);

/**
 * verify availability via class get method
 * use $arrival and $departure as $sunrise and $sunset
 */


dateTimeVerifyAvailabilityInputDeparture