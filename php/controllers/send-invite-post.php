<?php
// start a PHP session for CSRF protection
session_start();

// require encrypted configuration files
require_once("/home/cnmparki/etc/mysql/encrypted-config.php");

// require the mySQL enabled Visitor class
require_once("../classes/visitor.php");

// require the mySQL enabled Invite class
require_once("../classes/invite.php");

// require PEAR::Mail <http://pear.php.net/package/Mail> to send mail
require_once("Mail.php");

try {
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
	$objects = Invite::getInviteByActivation($pdo, $_POST["token"]);
	$invite = $objects["invite"];
	$invite->setAdminProfileId($_SESSION["adminProfileId"]);
	$invite->setActionDateTime(new DateTime());

	if (isset($_POST["accept"])) {
		$invite->setApproved($_POST["accept"]);
	} else {
		$invite->setApproved($_POST["decline"]);
	}

	$invite->update($pdo);

	// email the visitor a URL with token
	$visitor = $objects["visitor"];
	$to = $visitor->getVisitorEmail();
	$from = "noreply@cnm.edu";

	// build headers
	$headers = array();
	$headers["To"] = $to;
	$headers["From"] = $from;
	$headers["Reply-To"] = $from;

	// use and if statement to fetch the available visitor values
	$headers["Subject"] = "Parking Pass Invite - ";
	$headers["MIME-Version"] = "1.0";
	$headers["Content-Type"] = "text/html; charset=UTF-8";

	// build message
	$activation = $invite->getActivation();
	$pageName = end(explode("/", $_SERVER["PHP_SELF"], 4));
	$url = "http://cnmparking.com/personal-vehicle";
	$url = "$url?activation=$activation";
	if(isset($_POST["accept"])) {
		$message = <<< EOF
	<html>
		<body>
			<h1>CNM STEMulus Center Parking</h1>
				<hr />
					<p>Please visit the following URL to register for a parking pass: <a href="$url">$url</a>.</p>
		</body>
	</html>
EOF;
	} else {
		$message = <<< EOF
	<html>
		<body>
			<h1>CNM STEMulus Center Parking</h1>
				<hr />
					<p>Your request could not be approved at this time.  Please contact CNM STEMulus center if you have any questions</p>
		</body>
	</html>
EOF;
	}

	// send the email
	error_reporting(E_ALL & ~(E_STRICT | E_NOTICE | E_DEPRECATED));
	$mailer =& Mail::factory("sendmail");
	$status = $mailer->send($to, $headers, $message);

	if(PEAR::isError($status) === true) {
		echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh snap!</strong> your request did not reach us:" . $status->getMessage() . "</div>";
	} else if(PEAR::isError($status) === false && isset($_POST["accept"])) {
		echo "<div class=\"alert alert-success\" role=\"alert\"><strong>Invite sent to " . $visitor->getVisitorFirstName(). " " . $visitor->getVisitorLastName() . " successfully!</strong></div>";
	} else {
		echo "<div class=\"alert alert-warning\" role=\"alert\"><strong>Decline message sent to " . $visitor->getVisitorFirstName(). " " . $visitor->getVisitorLastName() . " successfully!</strong></div>";
	}
} catch(Exception $exception) {
	echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh snap!</strong> Unable to request a parking pass: " . $exception->getMessage() . "</div>";
}

?>