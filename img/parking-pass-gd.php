<?php
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once("../php/classes/vehicle.php");
require_once("../php/classes/parkingpass.php");
require_once("../php/classes/parkingspot.php");
session_start();

//if(@isset($_GET[""]))
/**
 * sets the JPEG file to the specified resolution
 *
 * @param $jpg string path to a jpeg file
 * @param $dpi int dots per inch to change the image to
 * @throws OutOfRangeException if DPI is not positive
 * @throws UnexpectedValueException on file I/O errors
 * @see http://develobert.blogspot.com/2008/11/php-jpegjpg-dpi-function.html
 * @see http://bytes.com/topic/php/answers/5948-dpi-php-gd
 **/
function setDpi($jpg, $dpi)
{
	// handle degenerate cases
	$dpi = filter_var($dpi, FILTER_VALIDATE_INT);
	if($dpi === false || $dpi <= 0) {
		throw(new OutOfRangeException("DPI must be positive"));
	}

	// open temporary files
	$fr = fopen($jpg, "rb");
	$fw = fopen("$jpg.temp", "wb");
	if($fr === false || $fw === false) {
		throw(new UnexpectedValueException("unable to open temporary JPEG files"));
	}


	// convert the DPI into hexadecimal bytes
	$upperByte = intval(floor($dpi / 0xff));
	$lowerByte = $dpi & 0xff;

	// write the resolution to the JPEG
	stream_set_write_buffer($fw, 0);
	fwrite($fw, fread($fr, 13) . chr(1) . chr($upperByte) . chr($lowerByte) . chr($upperByte) . chr($lowerByte));

	// copy the image data over
	fseek($fr, 18);
	stream_copy_to_stream($fr, $fw);

	// close files
	fclose($fr);
	fclose($fw);

	// delete the old resolution and move the new file in its place
	unlink($jpg);
	rename("$jpg.temp", $jpg);
}
try {
	$parkingPassId = 369;
	// create a low resolution image of the proper pixel size
	$tempfile = tempnam("/tmp", "PASS");
	$image = imagecreatetruecolor(2400, 1500);
	imagejpeg($image, $tempfile, 90);
	imagedestroy($image);

	// convert the resolution to 300 dpi and reopen the image
	setDpi($tempfile, 300);
	$image = imagecreatefromjpeg($tempfile);
	unlink($tempfile);

	$arrivalDate = $_SESSION["arrivalDate"];
	$departureDate = $_SESSION["departureDate"];

	// get the pass id from $_GET
	//$parkingPassId = $parkingPass->getParkingPassId();

	// use the parkingPassId to get a ParkingPass object from mySQL
	$parkingPass = ParkingPass::getParkingPassByParkingPassId($mysqli, $parkingPassId);
	$parkingSpot = ParkingSpot::getParkingSpotByParkingSpotId($mysqli, $parkingPass->getParkingSpotId());
	$vehicle = Vehicle::getVehicleByVehicleId($mysqli, $parkingPass->getVehicleId());


	//set up connection
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/cnmparking.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);
	$parkingPass = ParkingPass::getParkingPassByParkingPassId($mysqli, $parkingPassId);
} catch(Exception $exception) {
	echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh snap!</strong> " . $exception->getMessage() . "</div>";
	exit;
}

//create colors
$black = imagecolorallocate($image, 0, 0, 0);
$white = imagecolorallocate($image, 255, 255, 255);
$yellow = imagecolorallocate($image,255, 255, 0);
$blue = imagecolorallocate($image, 0, 0, 205);
$red = imagecolorallocate($image,225, 0,0);
$pink = imagecolorallocate($image, 225, 192, 203);

// fill background color
imagefill($image, 0, 0, $white);

// line thickness
imagesetthickness($image, 105);

// timeFormat
$timeFormat = "M j, y g:i a";

// font Helvetica
$font = "./fonts/Helvetica.ttf";

// create image text
imagettftext($image, 75, 0.0, 350, 90, $black, $font, "CNM STEMulus Temporary Parking Pass");

imagettftext($image, 50, 0.0, 450, 365, $black, $font, "Start Date/Time: " . $parkingPass->getStartDateTime()->format($timeFormat) );

imagettftext($image, 50.0, 0.0, 450, 525, $black, $font, "End Date/Time: " . $parkingPass->getEndDateTime()->format($timeFormat));

imagettftext($image, 50.0, 0.0, 450, 700, $black, $font, "License Plate #: " . $vehicle->getVehiclePlateNumber());

imagettftext($image, 50.0, 0.0, 450, 875, $black, $font, "Location: " . $location->getLocationDescription());

imagettftext($image, 50.0, 0.0, 450, 1050, $black, $font, "Placard #: " . $parkingSpot->getPlacardNumber());

imagettftext($image, 25, 0.0, 150, 1300, $red, "./fonts/Helvetica.ttf", "LEGAL NOTICE: Duplication or manufacturing of a parking permit is a crime. Handwritten changes will VOID an temporary parking pass.
Vehicles displaying such permits will be cited. Attempts to fraudulently obtain parking privileges at CNM may result in disciplinary action.");



// set content type header as jpeg
header("Content-type: image/jpeg");
// header("Content-disposition: attachment; filename=\"parking-pass.jpg\"");

// test drawing a black line
imageline($image, 0, 200, 2500, 200, $yellow);
imageline($image, 0, 1200, 2500, 1200, $blue);

// output image
imagejpeg($image);

// free up memory
imagedestroy($image);
?>