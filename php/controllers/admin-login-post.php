<?php
// start session
session_start();

$title = "CNM Admin Portal";
$headerTitle = "CNM Admin Portal";

// require necessary files
require_once("../lib/header.php");
require_once("/home/cnmparki/etc/mysql/encrypted-config.php");
require_once("../classes/admin.php");
require_once("../classes/adminprofile.php");


// assign the session
$_SESSION["login user"] = $_POST["adminEmail"];

try {
	// connect to database
	$configArray = readConfig("/home/cnmparki/etc/mysql/cnmparking.ini");

	$host = $configArray["hostname"];
	$db = $configArray["database"];
	$dsn = "mysql:host=$host;dbname=$db";
	$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
	$pdo = new PDO($dsn, $configArray["username"], $configArray["password"], $options);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

	if(@isset($_POST["adminEmail"]) === true && @isset($_POST["password"]) === true) {
		// query admin email and hash compare
		$admin = Admin::getAdminByAdminEmail($pdo, $_POST["adminEmail"]);
	} else {
		throw (new InvalidArgumentException("form values not complete. verify the form and try again."));
	}
	if(count($admin) === 0) {
		throw (new PDOException(" incorrect email or password. Try again."));
	}
	$hash = hash_pbkdf2("sha512", $_POST["password"], $admin->getSalt(), 2048, 128);
	if($hash === $admin->getPassHash()) {
		// assign session to logged in admin id
		$adminProfile = AdminProfile::getAdminProfileByAdminId($pdo, $admin->getAdminId());
		$_SESSION["adminFirstName"] = $adminProfile->getAdminFirstName();
		$_SESSION["adminProfileId"] = $adminProfile->getAdminProfileId();

		// session assignment to correct profile id
		$adminProfileId = $adminProfile->getAdminProfileId();

		// redirect to initial destination
		$link = "http://" . $_SERVER["HTTP_HOST"] . $_SESSION["url"];

		if($_SESSION["url"] != null) {
			header("location: " . $link);
		} else {
			header("location:../test-portal/test-portal.php");

		}
//		echo '<button id="' . $adminProfileId . '" class="btn btn-primary portalButton">Go To Portal</button>';
//		echo "<p>Click to Continue</p>";

	} else {
		throw (new PDOException(" incorrect email or password. Try again."));
	}
} catch	(Exception $exception) {
	echo "<p class=\"alert alert-danger\">" . $exception->getMessage() . "</p>";
}

require_once("../lib/footer.php");
?>

