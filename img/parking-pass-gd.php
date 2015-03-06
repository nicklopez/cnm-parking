<?php

// get the pass id from $_GET
$parkingPassId = 42;
 //$parkingPassId = $_GET["parkingPassId"];

// use the parkingPassId to get a ParkingPass object from mySQL

require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once("../php/classes/vehicle.php");
require_once("../php/classes/parkingpass.php");

try {
	//set up connection
	mysqli_report(MYSQLI_REPORT_STRICT);
	$configArray = readConfig("/etc/apache2/capstone-mysql/cnmparking.ini");
	$mysqli = new mysqli($configArray['hostname'], $configArray['username'], $configArray['password'], $configArray['database']);
	$parkingPass = ParkingPass::getParkingPassByParkingPassId($mysqli, $parkingPassId);
} catch(Exception $exception) {
	echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh snap!</strong> " . $exception->getMessage() . "</div>";
	exit;
}

// create image
$image = imagecreatetruecolor(768, 480);

//create colors
$black = imagecolorallocate($image, 0, 0, 0);
$white = imagecolorallocate($image, 255, 255, 255);
$yellow = imagecolorallocate($image,255, 255, 0);

// fill background color
imagefill($image, 0, 0, $white);

// create image text
imagettftext($image, 16.0, 0.0, 16, 16, $black, "./fonts/Helvetica.ttf", "CNM STEMulus Temporary Parking Pass" . $_GET["parkingPassId"]);

imagettftext($image, 16.0, 0.0, 150, 150, $black, "./fonts/Helvetica.ttf", "Start Date/Time:" );

imagesetthickness($image, 45);

// set content type header as jpeg
header("Content-type: image/jpeg");

// test drawing a black line
imageline($image, 0, 50, 480, 50, $yellow);

// output image
imagejpeg($image);

// free up memory
imagedestroy($image);
?>