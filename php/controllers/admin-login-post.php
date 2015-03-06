<?php
// start session
session_start();

$title = "Admin Portal";
$headerTitle = "Admin Portal";

// require necessary files
require_once("../lib/header.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once("../classes/admin.php");
require_once("../classes/adminprofile.php");


// assign the session
$_SESSION["login user"] = $_POST["adminEmail"];

try {
	// connect to database
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/cnmparking.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);


	if(@isset($_POST["adminEmail"]) === true && @isset($_POST["password"]) === true) {
		// query admin email and hash compare
		$admin = Admin::getAdminByAdminEmail($mysqli, $_POST["adminEmail"]);
		$hash = hash_pbkdf2("sha512", $_POST["password"], $admin->getSalt(), 2048, 128);

	} else {
		throw (new InvalidArgumentException("form values not complete. verify the form and try again."));
	}

	if($hash === $admin->getPassHash()) {
		// assign session to logged in admin id
		$adminProfile = AdminProfile::getAdminProfileByAdminId($mysqli, $admin->getAdminId());
		$_SESSION["adminProfile"] = array(
			'id' => $adminProfile->getAdminProfileId()
		);

		// session assignment to correct profile id
		$adminProfileId = $adminProfile->getAdminProfileId();
		echo '<button id="' . $adminProfileId . '" class="btn btn-primary portalButton">Go To Portal</button>';
		echo "<p>Click to Continue</p>";

	} else {
		throw (new mysqli_sql_exception(" incorrect email or password. Try again."));
	}
} catch	(Exception $exception) {
	echo "<p class=\"alert alert-danger\">" . $exception->getMessage() . "</p>";
}

require_once("../lib/footer.php");
?>