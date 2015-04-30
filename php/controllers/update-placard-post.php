<?php
require_once("/home/cnmparki/etc/mysql/encrypted-config.php");
require_once("../classes/placardassignment.php");

try {

	$configArray = readConfig("/home/cnmparki/etc/mysql/cnmparking.ini");
	// Connect to mySQL
	$host = $configArray["hostname"];
	$db = $configArray["database"];
	$dsn = "mysql:host=$host;dbname=$db";
	$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
	$pdo = new PDO($dsn, $configArray["username"], $configArray["password"], $options);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$assignPlacard = new PlacardAssignment($_POST["assignId"], $_POST["adminProfileId"], $_POST["availablePlacards"], $_POST["endDate"], $_POST["firstName"], "", $_POST["lastName"], $_POST["returnDate"], $_POST["startDate"]);
	$assignPlacard->update($pdo);
	echo "<div class=\"alert alert-success\" role=\"alert\"><strong>Placard Updated</strong></div>";

} catch (PDOException $Exception) {
	echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh Snap:</strong>" . $exception->getMessage() . "</div>";
}
?>