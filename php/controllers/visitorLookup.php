<?php
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once("../classes/visitor.php");

try {
	// create a Visitor (if required) and Invite object and insert them into mySQL
	$config = readConfig("/etc/apache2/capstone-mysql/cnmparking.ini");
	$mysqli = new mysqli($config["hostname"], $config["username"], $config["password"], $config["database"]);

	$visitor = Visitor::getVisitorByVisitorEmail($mysqli, $_POST["emailAddress"]);
	if(count($visitor) === 0) {
		return;
	}

} catch (mysqli_sql_exception $Exception) {
	echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Visitor not found.</strong>" . $exception->getMessage() . "</div>";
}

$result = $visitor->getVisitorFirstName() . "," . $visitor->getVisitorLastName() . "," . $visitor->getVisitorPhone() . "," . $visitor->getVisitorId();

print_r($result);

?>