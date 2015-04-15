<?php
require_once("/home/cnmparki/etc/mysql/encrypted-config.php");
require_once("../classes/vehicle.php");

try {

	if(empty($_POST["visitorId"])) {
		exit;
	}

	$configArray = readConfig("/home/cnmparki/etc/mysql/cnmparking.ini");
	// Connect to mySQL
	$host = $configArray["hostname"];
	$db = $configArray["database"];
	$dsn = "mysql:host=$host;dbname=$db";
	$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
	$pdo = new PDO($dsn, $configArray["username"], $configArray["password"], $options);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$vehicles = Vehicle::getVehicleByVisitorId($pdo, $_POST["visitorId"]);

	foreach($vehicles as $vehicle) {
		$id = $vehicle["vehicleId"];
		$cars = $vehicle["vehicleYear"] . " " . $vehicle["vehicleMake"] . " " . $vehicle["vehicleModel"];
		$list[] = "<option value=$id>$cars</option>";
	}

	print_r($list);

} catch (PDOException $Exception) {
	echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Visitor not found.</strong>" . $exception->getMessage() . "</div>";
}
?>