<?php
// require_once("../../../lib/csrf.php");
require_once("../../admin-registration/index.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once("../classes/admin.php");
require_once("../classes/adminprofile.php");


// create new admin
try {
	if(@isset($_POST["adminFirstName"]) === false || @isset($_POST["adminLastName"]) === false || @isset($_POST["adminEmail"]) || @isset($_POST["password"])) {
		throw(new InvalidArgumentException("<p class=\"alert alert-danger\">form values not complete. verify the form and try again.</p>"));
	}
	// create a new salt and hash
	$salt = bin2hex(openssl_random_pseudo_bytes(32));
	$hash = hash_pbkdf2("sha512", $_POST["password"], $salt, 2048, 128);

	// create an admin and admin profile object and insert them into mySQL
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/cnmparking.ini");
	$activation = bin2hex(openssl_random_pseudo_bytes(16));
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

	$admin = new Admin(null, $activation, $_POST["adminEmail"], $_POST["hash"], $salt);
	$admin->insert($mysqli);

	$adminProfile = new AdminProfile(null, $admin->getAdminId(), $_POST["adminFirstName"], $_POST["adminLastName"]);
	$adminProfile->insert($mysqli);


	// email the user with an activation message
//	$to = $user->getEmail();
//	$from = "admin@cnm.edu";


		echo "<p class=\"alert alert-success\">Admin(id = " . $admin->getAdminId() . ") added!</p>";
	} catch(Exception $exception) {
		echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
	}

?>
