<?php

// require encrypted configuration files
require_once("/home/cnmparki/etc/mysql/encrypted-config.php");

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

	// create a new salt or token
	$activation = bin2hex(openssl_random_pseudo_bytes(16));

	// create a Visitor (if required) and Invite object and insert them into mySQL
	$configArray = readConfig("/home/cnmparki/etc/mysql/cnmparking.ini");

	// Connect to mySQL
	$host = $configArray["hostname"];
	$db = $configArray["database"];
	$dsn = "mysql:host=$host;dbname=$db";
	$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
	$pdo = new PDO($dsn, $configArray["username"], $configArray["password"], $options);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

	// query mySQL to see if visitor exists
	$visitor = Visitor::getVisitorByVisitorEmail($pdo, $_POST["emailAddress"]);
	if(count($visitor) === 0) {
		$newVisitor = new Visitor(null, $_POST["emailAddress"], $_POST["firstName"], $_POST["lastName"], $_POST["phone"]);
		$newVisitor->insert($pdo);
		$invite = new Invite(null, null, $activation, null, null, null, $newVisitor->getVisitorId());
		$invite->insert($pdo);
	} else {
		$invite = new Invite(null, null, $activation, null, null, null, $visitor->getVisitorId());
		$invite->insert($pdo);
	}

	// email the CNM admin and inform them of the request
	if(count($visitor) === 0) {
		$to = $newVisitor->getVisitorEmail();
	} else {
		$to = $visitor->getVisitorEmail();
	}
	$from = "noreply@cnm.edu";

	// build headers
	$headers = array();
	$headers["To"] = $to;
	$headers["From"] = $from;
	$headers["Reply-To"] = $from;

	// use and if statement to fetch the available visitor values
	if(count($visitor) === 0) {
		$headers["Subject"] = "Parking Pass Invite Request - " . $newVisitor->getVisitorFirstName() . " " . $newVisitor->getVisitorLastName();
	} else {
		$headers["Subject"] = "Parking Pass Invite Request - " . $visitor->getVisitorFirstName() . " " . $visitor->getVisitorLastName();
	}
	$headers["MIME-Version"] = "1.0";
	$headers["Content-Type"] = "text/html; charset=UTF-8";

	// build message
	$pageName = end(explode("/", $_SERVER["PHP_SELF"],4));
	$url = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"];
	$url = str_replace($pageName, "send-invite", $url);
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
		echo "<div class=\"alert alert-success\" role=\"alert\"><strong>Request sent successfully!</strong> You will receive an email with additional details shortly.</div>";
	}

} catch(Exception $exception) {
	echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh snap!</strong> Unable to request a parking pass: " . $exception->getMessage() . "</div>";
}
?>