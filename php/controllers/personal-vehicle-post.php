<?php
$title = "Processing...";
$headerTitle = "Processing...";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
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
//$file = './files/example.zip';
//$mime->addAttachment($file,'application/octet-stream');

session_start();

try {

	//set up connection
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/cnmparking.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);

	// create and insert vehicle
	$vehicle = new Vehicle(null, $_POST["visitorId"], $_POST["vehicleColor"], $_POST["vehicleMake"], $_POST["vehicleModel"], $_POST["vehiclePlateNumber"], $_POST["vehiclePlateState"], $_POST["vehicleYear"]);
	$vehicle->insert($mysqli);

	// create and insert parking pass
	$parkingPass = new ParkingPass(null, $_POST["adminProfileId"], $_POST["parkingSpotId"], $vehicle->getVehicleId(), $_POST["departureDate"], "2015-02-10 08:00:00", $_POST["arrivalDate"], null);
	$parkingPass->insert($mysqli);

	if(@isset($_POST["vehicleMake"]) === false || @isset($_POST["vehicleModel"]) === false || @isset($_POST["vehicleYear"]) === false || @isset($_POST["vehicleColor"]) === false ||
		@isset($_POST["vehiclePlateNumber"]) === false || @isset($_POST["vehiclePlateState"]) === false || @isset($_POST["arrivalDate"]) === false || @isset($_POST["departureDate"]) === false
	) {
		throw(new mysqli_sql_exception("form values not complete. verify the form and try again."));
		}


	$_SESSION["arrivalDate"] = $_POST["arrivalDate"];
	$_SESSION["departureDate"] = $_POST["departureDate"];


	// email the visitor a URL with token
	$objects = Invite::getInviteByActivation($mysqli, $_POST["activation"]);
	$visitor = $objects["visitor"];
	$to = $visitor->getVisitorEmail();
	$from = "noreply@cnm.edu";

	// build headers
	$headers = array();
	$headers["To"] = $to;
	$headers["From"] = $from;
	$headers["Reply-To"] = $from;
	$headers["Subject"] = $visitor->getVisitorFirstName() . " " . $visitor->getVisitorLastName() . ", CNM Temporary Parking Pass";
//	$headers["MIME-Version"] = "1.0";
//	$headers["Content-Type"] = "text/html; charset=UTF-8";

	// build message
	// $pageName = end(explode("/", $_SERVER["i"], 5));
//	$url = "https://" . $_SERVER["SERVER_NAME"] . "/img/parking-pass-gd.php";
////	$url = str_replace($pageName, "parking-pass-gd.php", $url);
//	$url = "$url?parkingPassId=$parkingPassId" . $parkingPass->getParkingPassId();
	$message = <<< EOF
<html>
	<body>
		<h1>Temporary CNM Parking Pass</h1>
		<hr />
		<p>Welcome to CNM! Print out this E-Permit. It must be fully displayed on the dashboard of your vehicle. This permit is valid for any non-restricted parking space.
		Permit is not valid for meters, loading zones, fire lanes, or any other restricted spaces including spaces marked Parking by Special Permit sign.</p>
	</body>
</html>
EOF;

	$imageData = generatePassImage($mysqli, $parkingPass, $vehicle, "../../img/fonts/Helvetica.ttf");
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
		echo "<div class=\"alert alert-success\" role=\"alert\"><strong>Sign up successful!</strong> Please check your for your temporary parking pass.</div>";
	}


}	catch(Exception $exception) {
	echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh snap!</strong> " . $exception->getMessage() . "</div>";
}

?>
