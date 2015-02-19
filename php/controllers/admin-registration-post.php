<?php
// require_once("../../../lib/csrf.php");
require_once("../../admin-registration/index.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once("../classes/admin.php");
require_once("../classes/adminprofile.php");

// require PEAR::Mail <http://pear.php.net/package/Mail> to send mail
require_once("Mail.php");


// create new admin
try {
	// verify the form was submitted
	if(@isset($_POST["adminFirstName"]) === false || @isset($_POST["adminLastName"]) === false || @isset($_POST["adminEmail"]) || @isset($_POST["password"])) {
		throw(new InvalidArgumentException("form values not complete. verify the form and try again."));
	}
	echo "<p class=\"alert alert-success\">Admin(id = " . $admin->getAdminId() . ") added!</p>";} catch(Exception $exception) {
	echo "<p class=\"alert alert-danger\">Exception: " . $exception->getMessage() . "</p>";


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

	// email the user with an activation message
	$to = $admin->getAdminEmail();
	$from = "noreply@cnm.edu";

	// build headers
	$headers = array();
	$headers["To"] = $to;
	$headers["From"] = $from;
	$headers["Reply-To"] = $from;
	$headers["Subject"] = $adminProfile->getAdminFirstName() . " " . $adminProfile->getAdminLastName() . ", Activate your CNM Parking Admin Account";
	$headers["MIME-Version"] = "1.0";
	$headers["Content-Type"] = "text/html; charset=UTF-8";

	// build message
	$pageName = end(explode("/", $_SERVER["PHP_SELF"]));
	$url = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"];
	$url = str_replace($pageName, "activate.php", $url);
	$url = "$url?activation=$activation";
	$message = <<< EOF

<html>
	<body>
		<h1>Welcome New CNM Parking Admin!</h1>
		<hr />
		<p>Thank you for registering for a CNM Parking Admin. Visit the following URL to the Admin Login Page <a href="$url">$url</a>.</p>
	</body>
</html>
EOF;

	// send the email
	error_reporting(E_ALL & ~(E_STRICT|E_NOTICE|E_DEPRECATED));
	$mailer =& Mail::factory("sendmail");
	$status = $mailer->send($to, $headers, $message);
	if(PEAR::isError($status) === true)
	{
		echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh snap!</strong> Unable to send mail message:" . $status->getMessage() . "</div>";
	}
	else
	{
		echo "<div class=\"alert alert-success\" role=\"alert\"><strong>Admin registration successful!</strong> Please check your Email to complete the signup process.</div>";
	}

} catch(Exception $exception) {
	echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh snap!</strong> Unable to sign up: " . $exception->getMessage() . "</div>";
}



?>
