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
 * TEST
 */
$test = ParkingPass::getParkingPassAvailability($mysqli, 1, "2015-02-10 00:00:00", "2015-02-10 23:59:59");

var_dump($test);