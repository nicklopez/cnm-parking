<?php
/**
 * grabs an array of all Locations [locationId, locationDescription]
 *
 * prints out html code for use in a drop-down list
 */

// require the location class
require_once("../php/classes/location.php");

// require the mysqli config files
require_once("/home/cnmparki/etc/mysql/encrypted-config.php");

// define PDO connection info and connect
$configFile = "/home/cnmparki/etc/mysql/cnmparking.ini";
$configArray = readConfig($configFile);

// Connect to mySQL
$host = $configArray["hostname"];
$db = $configArray["database"];
$dsn = "mysql:host=$host;dbname=$db";
$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
$pdo = new PDO($dsn, $configArray["username"], $configArray["password"], $options);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

// get array of locations from class method, then echo out each row in an <object> tag
$locations = Location::getListOfLocations($pdo);
foreach($locations as $location) {
	echo "<option value='$location[0]'>$location[1]</option>";
}
?>