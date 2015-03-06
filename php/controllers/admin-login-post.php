<?php
// start session
session_start();

// require necessary files
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once("../classes/admin.php");
require_once("../classes/adminprofile.php");

// assign the session
$_SESSION["login user"] = $_POST["adminEmail"];

?>
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
	<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
	<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/additional-methods.min.js"></script>

	<!-- Latest compiled and minified Bootstrap JavaScript, all compiled plugins included -->
	<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
	<script src="../../js/admin-login.js"></script>

<?php


try {

	// create a new salt and hash
	$salt = bin2hex(openssl_random_pseudo_bytes(32));

	// connect to database
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/cnmparking.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

	// query admin email and hash compare
	$admin = Admin::getAdminByAdminEmail($mysqli, $_POST["adminEmail"]);
	if(count($admin) !== 0) {
		$hash = hash_pbkdf2("sha512", $_POST["password"], $salt, 2048, 128);
		if($hash === $admin->getPassHash());

		// assign session to logged in admin id
		$adminProfile = AdminProfile::getAdminProfileByAdminId($mysqli, $admin->getAdminId());

		$_SESSION["adminProfile"] = array(
			'id' => $adminProfile->getAdminProfileId()
		);

		// session assignment to correct profile id
		$adminProfileId = $adminProfile->getAdminProfileId();
		echo '<button id="'.$adminProfileId.'" class="btn btn-default portalButton">Go To Portal</button>';

	} else {
		throw (new mysqli_sql_exception(" incorrect email or password. Try again."));
	}


	// verify the form values have been submitted
	if(@isset($_POST["adminEmail"]) === false || @isset($_POST["password"])=== false) {
		throw (new InvalidArgumentException("form values not complete. verify the form and try again."));
	}
	echo "<p>Click to Continue</p>";
		}
	catch	(Exception $exception) {
		echo "<p class=\"alert alert-danger\">" . $exception->getMessage() . "</p>";
	}

