<?php

require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once("../classes/admin.php");
require_once("../classes/adminprofile.php");
session_start();

$_SESSION["login user"] = $_POST["adminEmail"];


try {
	//
	// verify the form values have been submitted
//	if(@isset($_POST["adminEmail"]) === false || @isset($_POST["password"])) {
//		throw (new InvalidArgumentException("<p class=\"alert alert-danger\">form values not complete. verify the form and try again.</p>"));
//	}

	// create a new salt and hash
	$salt = bin2hex(openssl_random_pseudo_bytes(32));


	// connect to database
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/cnmparking.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

	// query admin email and hash compare
	$admin = Admin::getAdminByAdminEmail($mysqli, $_POST["adminEmail"]);
	if (count($admin) !== 0) {
		$hash = hash_pbkdf2("sha512", $_POST["password"], $salt, 2048, 128);
		if($hash === $admin->getPassHash()) ;
		echo "Welcome Back!";
		}
	else	{
		echo "incorrect email or password.";
	}

	// assign session to logged in admin id
	$adminProfile = AdminProfile::getAdminProfileByAdminId($mysqli, $admin->getAdminId());

	$_SESSION["adminProfileId"] = $adminProfile->getAdminProfileId();

	print_r($_SESSION);

	// PLACEHOLDER TO REDIRECT TO ADMIN PORTAL


			echo "<p class=\"alert alert-success\">Admin(id = " . $admin->getAdminId() . ") logged on!</p>";
		}
	catch
		(Exception $exception) {
			echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";
		}

?>