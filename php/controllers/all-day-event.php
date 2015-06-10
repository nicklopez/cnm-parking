<?php
require_once("/home/cnmparki/etc/mysql/encrypted-config.php");
require_once("../classes/parkingpass.php");

try {

	$configArray = readConfig("/home/cnmparki/etc/mysql/cnmparking.ini");
	// Connect to mySQL
	$host = $configArray["hostname"];
	$db = $configArray["database"];
	$dsn = "mysql:host=$host;dbname=$db";
	$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
	$pdo = new PDO($dsn, $configArray["username"], $configArray["password"], $options);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$placardCount = ParkingPass::getPhysicalPlacardCountByDateRange($pdo, $_GET["start"] . " 00:00:00", $_GET["end"] . " 23:59:59");

	echo json_encode($placardCount);

} catch (PDOException $Exception) {
	echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh Snap:</strong>" . $exception->getMessage() . "</div>";
}
?>