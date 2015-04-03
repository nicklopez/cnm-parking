<?php
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
function setDpi($jpg, $dpi) {
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

function generatePassImage($pdo, $parkingPass, $vehicle, $font) {

	try {


		// create a low resolution image of the proper pixel size
		$tempfile = tempnam("/tmp", "PASS");
		$image = imagecreatetruecolor(2400, 1500);
		imagejpeg($image, $tempfile, 90);
		imagedestroy($image);

		// convert the resolution to 300 dpi and reopen the image
		setDpi($tempfile, 300);
		$image = imagecreatefromjpeg($tempfile);
		unlink($tempfile);

		//set up connection
		$parkingSpot = ParkingSpot::getParkingSpotByParkingSpotId($pdo, $parkingPass->getParkingSpotId());
		$location = Location::getLocationByLocationId($pdo, $parkingSpot->getLocationId());
	} catch
	(Exception $exception) {
		throw(new RuntimeException($exception->getMessage(), 0, $exception));
	}

//create colors
	$black = imagecolorallocate($image, 0, 0, 0);
	$white = imagecolorallocate($image, 255, 255, 255);
	$yellow = imagecolorallocate($image, 255, 255, 0);
	$blue = imagecolorallocate($image, 0, 0, 205);
	$red = imagecolorallocate($image, 225, 0, 0);
	$pink = imagecolorallocate($image, 225, 192, 203);
	$green = imagecolorallocate($image, 46, 139, 87);

// fill background color
	imagefill($image, 0, 0, $white);

// line thickness
	imagesetthickness($image, 105);

// timeFormat
	$timeFormat = "M j, y g:i a";

// create image text
	imagettftext($image, 75, 0.0, 350, 90, $black, $font, "CNM STEMulus Temporary Parking Pass");

	imagettftext($image, 50, 0.0, 450, 365, $black, $font, "Start Date/Time: " . $parkingPass->getStartDateTime()->format($timeFormat));

	imagettftext($image, 50.0, 0.0, 450, 525, $black, $font, "End Date/Time: " . $parkingPass->getEndDateTime()->format($timeFormat));

	imagettftext($image, 50.0, 0.0, 450, 700, $black, $font, "License Plate #: " . $vehicle->getVehiclePlateState() . " - " . $vehicle->getVehiclePlateNumber());

	imagettftext($image, 50.0, 0.0, 450, 875, $black, $font, "Location: " . $location->getLocationDescription());

	imagettftext($image, 50.0, 0.0, 450, 1050, $black, $font, "Placard #: " . $parkingSpot->getPlacardNumber());

	imagettftext($image, 25, 0.0, 150, 1300, $red, $font, "LEGAL NOTICE: Duplication or manufacturing of a parking permit is a crime. Handwritten changes will VOID an temporary parking pass.
Vehicles displaying such permits will be cited. Attempts to fraudulently obtain parking privileges at CNM may result in disciplinary action.");

// test drawing a black line
	imageline($image, 0, 200, 2500, 200, $yellow);
	imageline($image, 0, 1200, 2500, 1200, $blue);

// output image
	ob_start();
	imagejpeg($image);
	$jpegData = ob_get_contents();
	ob_end_clean();

// free up memory
	imagedestroy($image);

	return ($jpegData);
}
?>