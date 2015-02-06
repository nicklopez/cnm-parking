<?php
// Loads the functions for encrypted configuration
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

// first, require your class
require_once("parkingPass.php");

$parkingPass = new ParkingPass(NULL, 72, 1, 2, "2015-02-05 12:00:00", "2015-02-05 10:00:00", "2015-02-05 12:59:59");

// connect to mySQL and populate the database
try {
	// tel mysqli to throw exceptions
	mysqli_report(MYSQLI_REPORT_STRICT);

	// now connect to mySQL
	$config = readConfig("/etc/apache2/capstone-mysql/cnmparking.ini");
	$mysqli = new mysqli($config["hostname"], $config["username"], $config["password"], $config["database"]);

	// now insert into mySQL
	$parkingPass->insert($mysqli);

	// now disconnect from mySQL
	$mysqli->close();

	// var_dump the result to affirm we got a real primary key
	var_dump($parkingPass);
} catch(Exception $exception) {
	// echo the error message and location for now
	echo "Exception: " . $exception->getMessage() . "<br />";
	echo $exception->getFile() . ":" . $exception->getLine();
}
?>