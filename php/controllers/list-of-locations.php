<?php
/**
 * grabs an array of all Locations [locationId, locationDescription]
 *
 * prints out html code for use in a drop-down list
 */

	// require the location class
	require_once("../php/classes/location.php");

	// require the mysqli config files
	require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

	// define mysqli connection info and connect
	$configFile = "/etc/apache2/capstone-mysql/cnmparking.ini";
	$configArray = readConfig($configFile);

	mysqli_report(MYSQLI_REPORT_STRICT);
	$mysqli = new mysqli($configArray["hostname"], $configArray["username"], $configArray["password"], $configArray["database"]);

	// get array of locations from class method, then echo out each row in an <object> tag
	$locations = Location::getListOfLocations($mysqli);
		foreach($locations as $location) {
			echo "<option value='$location[0]'>$location[1]</option>";
		}
?>