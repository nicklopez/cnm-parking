<?php
// require_once("../../../lib/csrf.php");
$headerTitle = "Success";
$title = "Success";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once("../classes/admin.php");
require_once("../classes/adminprofile.php");
require_once("../lib/header.php");

// require PEAR::Mail <http://pear.php.net/package/Mail> to send mail
require_once("Mail.php");


try {


// create a new salt and hash
		$salt = bin2hex(openssl_random_pseudo_bytes(32));
		$hash = hash_pbkdf2("sha512", $_POST["password"], $salt, 2048, 128);

		// create an activation
		$activation = bin2hex(openssl_random_pseudo_bytes(16));

		// create an admin and admin profile object and insert them into mySQL
		mysqli_report(MYSQLI_REPORT_STRICT);
		$configArray = readConfig("/etc/apache2/capstone-mysql/cnmparking.ini");
		$activation = bin2hex(openssl_random_pseudo_bytes(16));
		$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

		$admin = new Admin(null, $activation, $_POST["adminEmail"], $hash, $salt);
		$admin->insert($mysqli);

		$adminProfile = new AdminProfile(null, $admin->getAdminId(), $_POST["adminFirstName"], $_POST["adminLastName"]);
		$adminProfile->insert($mysqli);


		// verify the form values have been submitted
		if(@isset($_POST["adminFirstName"]) === false || @isset($_POST["adminLastName"]) === false || @isset($_POST["adminEmail"]) === false || @isset($_POST["password"]) === false)	{
			throw (new InvalidArgumentException("form values not complete. verify the form and try again."));
		}
} catch	(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Unable to complete request. Try again.</p>";
	}

try {
	$objects = Admin::getAdminByAdminEmail($mysqli, $_POST["adminEmail"]);
	// email the visitor a URL with token
	$admin = $objects->getAdminEmail();
	$to = $objects->getAdminEmail();
	$from = "noreply@cnm.edu";

	// build headers
	$headers = array();
	$headers["To"] = $to;
	$headers["From"] = $from;
	$headers["Reply-To"] = $from;
	$headers["Subject"] = $adminProfile->getAdminFirstName() . " " . $adminProfile->getAdminLastName() . ", Activate your CNM Parking Admin Login";
	$headers["MIME-Version"] = "1.0";
	$headers["Content-Type"] = "text/html; charset=UTF-8";

	// build message
	$pageName = end(explode("/", $_SERVER["PHP_SELF"], 4));
	$url = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"];
	$url = str_replace($pageName, "admin-login", $url);
	$message = <<< EOF
<html>
	<body>
		<h1>Congratulations on your Parking Admin Registration</h1>
		<hr />
		<p>Thank you for registering for an CNM Parking Admin. Visit the following URL to complete your registration process: <a href="$url">$url</a>.</p>
	</body>
</html>
EOF;

	// send the email
	error_reporting(E_ALL & ~(E_STRICT | E_NOTICE | E_DEPRECATED));
	$mailer =& Mail::factory("sendmail");
	$status = $mailer->send($to, $headers, $message);
	if(PEAR::isError($status) === true) {
		echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh snap!</strong> Unable to send mail message:" . $status->getMessage() . "</div>";
	} else {
		echo "<div class=\"alert alert-success\" role=\"alert\"><strong>Sign up successful!</strong> Please check your Email to complete the registration process.</div>";
	}
} catch(Exception $exception) {
	echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh snap!</strong> Unable to sign up: " . $exception->getMessage() . "</div>";
}

?>

