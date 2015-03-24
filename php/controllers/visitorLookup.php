<?php
require_once("/home/cnmparki/etc/mysql/encrypted-config.php");
require_once("../classes/visitor.php");

try {
	// create a Visitor (if required) and Invite object and insert them into mySQL
	$configArray = readConfig("/home/cnmparki/etc/mysql/cnmparking.ini");
	// Connect to mySQL
	$host = $configArray["hostname"];
	$db = $configArray["database"];
	$dsn = "mysql:host=$host;dbname=$db";
	$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
	$pdo = new PDO($dsn, $configArray["username"], $configArray["password"], $options);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

	$visitor = Visitor::getVisitorByVisitorEmail($pdo, $_POST["emailAddress"]);
	if(count($visitor) === 0) {
		return;
	}

} catch (mysqli_sql_exception $Exception) {
	echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Visitor not found.</strong>" . $exception->getMessage() . "</div>";
}

$result = $visitor->getVisitorFirstName() . "," . $visitor->getVisitorLastName() . "," . $visitor->getVisitorPhone() . "," . $visitor->getVisitorId();

print_r($result);

?>