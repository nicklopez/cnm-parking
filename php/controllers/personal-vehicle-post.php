<?php
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once("../lib/header.php");
require_once("../classes/visitor.php");
require_once("../classes/vehicle.php");
require_once("../classes/adminprofile.php");
require_once("../classes/parkingspot.php");
require_once("../classes/parkingpass.php");

// require PEAR::Mail <http://pear.php.net/package/Mail> to send mail
require_once("Mail.php");
//$file = './files/example.zip';
//$mime->addAttachment($file,'application/octet-stream');


try {

	//set up connection
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/cnmparking.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

//	// create and insert parking spot
//	$parkingSpot = new ParkingSpot(null, 1, 200);
//	$parkingSpot->insert($mysqli);

	// create and insert visitor
	$visitor = new Visitor(null, $_POST["visitorEmail"], $_POST["visitorFirstName"], $_POST["visitorLastName"], $_POST["visitorPhone"]);
	$visitor->insert($mysqli);

	// create and insert vehicle
	$vehicle = new Vehicle(null, $visitor->getVisitorId(), $_POST["vehicleColor"], $_POST["vehicleMake"], $_POST["vehicleModel"], $_POST["vehiclePlateNumber"], $_POST["vehiclePlateState"], $_POST["vehicleYear"]);
	$vehicle->insert($mysqli);

	// create and insert parking pass
	$parkingPass = new ParkingPass(null, 6, 1, $vehicle->getVehicleId(), $_POST["endDateTime"], "2015-02-10 08:00:00", $_POST["startDateTime"], null);
	$parkingPass->insert($mysqli);


if(@isset($_POST["visitorFirstName"]) === false || @isset($_POST["visitorLastName"]) === false || @isset($_POST["visitorEmail"]) === false || @isset($_POST["visitorPhone"]) === false || @isset($_POST["vehicleMake"]) === false || @isset($_POST["vehicleModel"]) === false || @isset($_POST["vehicleYear"]) === false ||
	@isset($_POST["vehicleColor"]) === false || @isset($_POST["vehiclePlateNumber"]) === false || @isset($_POST["vehiclePlateState"]) === false || @isset($_POST["startDateTime"]) === false || @isset($_POST["endDateTime"]) === false) {
	throw(new mysqli_sql_exception("form values not complete. verify the form and try again."));
	}
{
	echo "<div class=\"alert alert-success\" role=\"alert\"><strong>Sign up successful!</strong> Please check your for your temporary parking pass.</div>";
	}


//try {
//	// email the visitor a URL with token
//	$visitor = $objects["visitor"];
//	$to = $visitor->getVisitorEmail();
//	$from = "noreply@cnm.edu";
//
//	// build headers
//	$headers = array();
//	$headers["To"] = $to;
//	$headers["From"] = $from;
//	$headers["Reply-To"] = $from;
//	$headers["Subject"] = $visitor->getVisitorFirstName() . " " . $visitor->getVisitorLastName() . ", CNM Temporary Parking Pass";
//	$headers["MIME-Version"] = "1.0";
//	$headers["Content-Type"] = "text/html; charset=UTF-8";
//
//	// build message
//	$pageName = end(explode("/", $_SERVER["PHP_SELF"]));
//	$url = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"];
//	$url = str_replace($pageName, "activate.php", $url);
//	$url = "$url?activation=$activation";
//	$message = <<< EOF
//<html>
//	<body>
//		<h1>Congratulations on your Parking Admin Registration</h1>
//		<hr />
//		<p>Thank you for registering for an CNM Parking Admin. Visit the following URL to complete your registration process: <a href="$url">$url</a>.</p>
//	</body>
//</html>
//EOF;
//
//	// send the email
//	error_reporting(E_ALL & ~(E_STRICT | E_NOTICE | E_DEPRECATED));
//	$mailer =& Mail::factory("sendmail");
//	$status = $mailer->send($to, $headers, $message);
//	if(PEAR::isError($status) === true) {
//		echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh snap!</strong> Unable to send mail message:" . $status->getMessage() . "</div>";

	//else
	{
		echo "<div class=\"alert alert-success\" role=\"alert\"><strong>Sign up successful!</strong> Please check your for your temporary parking pass.</div>";
	}

} catch(Exception $exception) {
	echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh snap!</strong> " . $exception->getMessage() . "</div>";
}
?>
