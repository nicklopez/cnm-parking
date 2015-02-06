<?php
// Loads the functions for encrypted configuration
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

// first, require your class
require_once("../php/classes/parkingSpot.php");

$parkingSpot = new ParkingSpot(null, 17, "250");

// connect to mySQL and populate the database
try {
	// tel mysqli to throw exceptions
	mysqli_report(MYSQLI_REPORT_STRICT);

	// now connect to mySQL
	$config = readConfig("/etc/apache2/capstone-mysql/cnmparking.ini");
	$mysqli = new mysqli($config["hostname"], $config["username"], $config["password"], $config["database"]);

	// now insert into mySQL
	$parkingSpot->insert($mysqli);

	// now disconnect from mySQL
	$mysqli->close();

	// var_dump the result to affirm we got a real primary key
	var_dump($parkingSpot);
} catch(Exception $exception) {
	// echo the error message and location for now
	echo "Exception: " . $exception->getMessage() . "<br />";
	echo $exception->getFile() . ":" . $exception->getLine();
}
?>