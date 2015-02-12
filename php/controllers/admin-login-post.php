<?php

require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once("../classes/admin.php");
require_once("../classes/adminprofile.php");
session_start();

// verify the form values have been submitted
if(@isset($_POST["adminEmail"]) === false || @isset($_POST["password"])) {
	echo "<p class=\"alert alert-danger\">form values not complete. verify the form and try again.</p>";
}
try {
	//
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/cnmparking.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);
	$admin = new Admin(null, "12345678123456781234567812345678", $_POST["adminEmail"], "12345678123456781234567812345678123456781234567812345678123456781234567812345678123456781234567812345678123456781234567812345678", "1234567812345678123456781234567812345678123456781234567812345678");
	$admin->insert($mysqli);
	$adminProfile = new AdminProfile(null, $this->adminId->getAdminId(), $_POST["adminFirstName"], $_POST["adminLastName"]);
	$adminProfile->insert($mysqli);

	echo "<p class=\"alert alert-success\">Admin(id = " . $admin->getAdminId() . ") logged on!</p>";
} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
}
?>