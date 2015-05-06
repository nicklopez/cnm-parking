<?php
$title = "Parking Pass";
$headerTitle = "Done!";
require_once("/home/cnmparki/etc/mysql/encrypted-config.php");
require_once("../../img/parking-pass-gd.php");
require_once("../lib/header.php");
require_once("../classes/invite.php");
require_once("../classes/visitor.php");
require_once("../classes/vehicle.php");
require_once("../classes/adminprofile.php");
require_once("../classes/location.php");
require_once("../classes/parkingpass.php");
require_once("../classes/parkingspot.php");

// require PEAR::Mail <http://pear.php.net/package/Mail> to send mail
require_once("Mail.php");
require_once("Mail/mime.php");

session_start();

try {

	//set up connection
	$configArray = readConfig("/home/cnmparki/etc/mysql/cnmparking.ini");

	// Connect to mySQL
	$host = $configArray["hostname"];
	$db = $configArray["database"];
	$dsn = "mysql:host=$host;dbname=$db";
	$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
	$pdo = new PDO($dsn, $configArray["username"], $configArray["password"], $options);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

	if(isset($_POST["selectVehicle"]) && $_POST['selectVehicle'] != 0) {
		$vehicleId = $_POST["selectVehicle"];

		// create and insert parking pass
		$parkingPass = new ParkingPass(null, $_POST["adminProfileId"], $_POST["inviteId"], $_POST["parkingSpotId"], $vehicleId, $_POST["departureDate"], null, $_POST["arrivalDate"], null);
		$parkingPass->insert($pdo);

		// query the vehicle details for the parking pass printout
		$vehicle = Vehicle::getVehicleByVehicleId($pdo, $vehicleId);
	} else {
		// create and insert vehicle
		$vehicle = new Vehicle(null, $_POST["visitorId"], $_POST["vehicleColor"], $_POST["vehicleMake"], $_POST["vehicleModel"], $_POST["vehiclePlateNumber"], $_POST["vehiclePlateState"], $_POST["vehicleYear"]);
		$vehicle->insert($pdo);

		// create and insert parking pass
		$parkingPass = new ParkingPass(null, $_POST["adminProfileId"], $_POST["inviteId"], $_POST["parkingSpotId"], $vehicle->getVehicleId(), $_POST["departureDate"], null, $_POST["arrivalDate"], null);
		$parkingPass->insert($pdo);
	}

	// generate the email
	$to = $_POST["visitorEmail"];
	$from = "noreply@cnm.edu";

	// build headers
	$headers = array();
	$headers["To"] = $to;
	$headers["From"] = $from;
	$headers["Reply-To"] = $from;
	$headers["Subject"] = $_POST["visitorFirstName"] . " " . $_POST["visitorLastName"] . ", CNM Parking Pass";

	$message = <<< EOF
<html>
	<body>
		<h1>CNM Parking Pass</h1>
		<hr />
		<p>Welcome to CNM! Print out this temporary parking permit. It must be fully displayed on the dashboard of your vehicle. This permit is valid for any non-restricted parking space.
		Permit is not valid for meters, loading zones, fire lanes, or any other restricted spaces including spaces marked "Parking by Special Permit" sign.</p>
	</body>
</html>
EOF;

	// create the parking pass
	$imageData = generatePassImage($pdo, $parkingPass, $vehicle, "../../img/fonts/Helvetica.ttf", "../../img/fonts/Helvetica-Bold.ttf", "../../img/placard2s.jpg", "../../img/map.jpg");
	$plaintext = strip_tags($message);
	$mimeOptions = array("head_charset" => "UTF-8", "html_charset" => "UTF-8", "text_charset" => "UTF-8", "eol" => "\n");
	$mimeMessage = new Mail_mime($mimeOptions);
	$mimeMessage->setTXTBody($plaintext);
	$mimeMessage->setHTMLBody($message);
	$mimeMessage->addAttachment($imageData, "image/jpeg", "parking-pass-" . $parkingPass->getStartDateTime()->format("mdY") .".jpg", false, "base64", "inline");

	// send the email
	error_reporting(E_ALL & ~(E_STRICT | E_NOTICE | E_DEPRECATED));
	$body = $mimeMessage->get();
	$headers = $mimeMessage->headers($headers);
	$mailer =& Mail::factory("sendmail");
	$status = $mailer->send($to, $headers, $body);
	if(PEAR::isError($status) === true) {
		echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh snap!</strong> Unable to send mail message:" . $status->getMessage() . "</div>";
	} else {
		//echo "<div class=\"alert alert-success\" role=\"alert\"><strong>Sign up successful!</strong> Please check your email for your parking pass.</div>";
		header("location: ../../personal-vehicle/success-redirect.php");
	}


}	catch(Exception $exception) {
	echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh snap!</strong> " . $exception->getMessage() . "</div>";
}

?>
