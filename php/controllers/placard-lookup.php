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

	$availablePlacards = ParkingPass::getAvailablePlacards($pdo, $_POST["locationId"], $_POST["startDate"], $_POST["endDate"]);
	if (count($availablePlacards) === 0) {
		echo "<option value='0'>No placards available</option>";
	} else {
		$list[] = "<option value='0' selected> -- Select a Placard -- </option>";
		foreach($availablePlacards as $availablePlacard) {
			$id = $availablePlacard['parkingSpotId'];
			$placardNumber = $availablePlacard['placardNumber'];
			$list[] = "<option value='$id'>$placardNumber</option>";
		}

		print_r($list);
	}

} catch (PDOException $Exception) {
	echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh Snap:</strong>" . $exception->getMessage() . "</div>";
}
?>