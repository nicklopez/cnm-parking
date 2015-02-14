<?php
// start a PHP session for CSRF protection
session_start();

// require CSRF protection
require_once("../lib/csrf.php");

// require encrypted configuration files
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

// require the mySQL enabled Visitor class
require_once("../classes/visitor.php");

// require the mySQL enabled Invite class
require_once("../classes/invite.php");

// require PEAR::Mail <http://pear.php.net/package/Mail> to send mail
require_once("Mail.php");

try {
	// verify the form was submitted OK
	if (@isset($_POST["emailAddress"]) === false || @isset($_POST["firstName"]) === false ||
		@isset($_POST["lastName"]) === false || @isset($_POST["phone"]) === false) {
		throw(new RuntimeException("form variables incomplete or missing"));
	}

	// verify the CSRF tokens
	if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
		throw(new RuntimeException("CSRF tokens incorrect or missing. Make sure cookies are enabled."));
	}

	// create a new salt or token
	$activation = bin2hex(openssl_random_pseudo_bytes(16));

	// create a Visitor and Invite object and insert them into mySQL
	$config = readConfig("/etc/apache2/capstone-mysql/cnmparking.ini");
	$mysqli = new mysqli($config["hostname"], $config["username"], $config["password"], $config["database"]);
	$visitor = new Visitor(null, $_POST["emailAddress"], $_POST["firstName"], $_POST["lastName"], $_POST["phone"]);
	$visitor->insert($mysqli);
	$invite = new Invite(null, null, $activation, null, null, null, $visitor->getVisitorId());
	$invite->insert($mysqli);

	// email the CNM admin and inform them of the request
	$to = $visitor->getVisitorEmail();
	$from = "noreply@cnm.edu";

	// build headers
	$headers = array();
	$headers["To"] = $to;
	$headers["From"] = $from;
	$headers["Reply-To"] = $from;
	$headers["Subject"] = "Parking Pass Invite Request - ".$visitor->getVisitorFirstName() . " " . $visitor->getVisitorLastName();
	$headers["MIME-Version"] = "1.0";
	$headers["Content-Type"] = "text/html; charset=UTF-8";

	// build message
	$pageName = end(explode("/", $_SERVER["PHP_SELF"]));
	$url = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"];
	$url = str_replace($pageName, "personal-vehicle.php", $url);
	$url = "$url?activation=$activation";
	$message = <<< EOF
<html>
	<body>
		<h1>CNM STEMulus Center Parking</h1>
		<hr />
		<p>Please visit the following URL to send the invite: <a href="$url">$url</a>.</p>
	</body>
</html>
EOF;

	// send the email
	error_reporting(E_ALL & ~(E_STRICT|E_NOTICE|E_DEPRECATED));
	$mailer =& Mail::factory("sendmail");
	$status = $mailer->send($to, $headers, $message);
	if(PEAR::isError($status) === true)
	{
		echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh snap!</strong> your request did not reach us:" . $status->getMessage() . "</div>";
	}
	else
	{
		echo "<div class=\"alert alert-success\" role=\"alert\"><strong>Sign up successful!</strong> You will receive an email with additional info soon.</div>";
	}

} catch(Exception $exception) {
	echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh snap!</strong> Unable to request a parking pass: " . $exception->getMessage() . "</div>";
}
?>