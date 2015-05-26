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

function generatePassImage($pdo, $parkingPass, $vehicle, $font, $font2, $placardF, $mapF, $abqF, $igniteF) {

	try {


		// create a low resolution image of the proper pixel size
		$tempfile = tempnam("/tmp", "PASS");
		$image = imagecreatetruecolor(2400, 2600);
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

	// create placard image
	$placard = imagecreatefromjpeg($placardF);


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
	imagesetthickness($image, 10);

	// timeFormat
	$timeFormat = "M j, y g:i a";

	// create image text
	imagettftext($image, 65, 0.0, 100, 125, $black, $font2, "Start Date/Time: " . $parkingPass->getStartDateTime()->format($timeFormat));
	imagettftext($image, 65, 0.0, 100, 300, $black, $font2, "End Date/Time: " . $parkingPass->getEndDateTime()->format($timeFormat));

	imagettftext($image, 50.0, 0.0, 100, 550, $black, $font, "Year: " . $vehicle->getVehicleYear());
	imagettftext($image, 50.0, 0.0, 100, 650, $black, $font, "Make: " . $vehicle->getVehicleMake());
	imagettftext($image, 50.0, 0.0, 100, 750, $black, $font, "Model: " . $vehicle->getVehicleModel());
	imagettftext($image, 50.0, 0.0, 100, 850, $black, $font, "Color: " . $vehicle->getVehicleColor());

	imagettftext($image, 65, 0.0, 100, 1125, $black, $font2, "State/Lic. Plate #: " . $vehicle->getVehiclePlateState() . " - " . $vehicle->getVehiclePlateNumber());
	imagettftext($image, 65, 0.0, 100, 1300, $black, $font2, "Location: " . $location->getLocationDescription());

	imagettftext($placard, 70, 0, 260, 1130, $black, $font2, "0" . $parkingSpot->getPlacardNumber());

	imagettftext($image, 25, 0.0, 150, 1460, $red, $font, "LEGAL NOTICE: Duplication or manufacturing of a parking permit is a crime. Handwritten changes will VOID an temporary parking pass.
Vehicles displaying such permits will be cited. Attempts to fraudulently obtain parking privileges at CNM may result in disciplinary action.");

	imagettftext($image, 30, 0, 1800, 1800, $black, $font2, "Parking Lot Address:
					1st and Copper
					(Near Central Ave.
					and 2nd St.)");

	//create dashed line
	$style = array($black, $black, $white, $white);
	imagesetstyle($image, $style);
	imageline($image, 0, 1550, 2400, 1550, IMG_COLOR_STYLED);
	imagettftext($image, 20.0, 0.0, 1100, 1590, $black, $font, "Fold Here");

	// create border
	imagerectangle($image, 10, 10, 2390, 1400, $green);

	// create map image
	$map = imagecreatefromjpeg($mapF);
	$abq = imagecreatefromjpeg($abqF);
	$ignite = imagecreatefromjpeg($igniteF);

	//add transparency to logo
	imagealphablending($placard, true);

	// Get dimensions
	$imageWidth=imagesx($image);
	$imageHeight=imagesy($image);

	// get placard dimensions
	$placardWidth=imagesx($placard);
	$placardHeight=imagesy($placard);

	// get map dimensions
	$mapWidth=imagesx($map);
	$mapHeight=imagesy($map);

	// get abq logo dimensions
	$abqWidth=imagesx($abq);
	$abqHeight=imagesy($abq);

	// get ignite logo dimensions
//	$igniteWidth=imagesx($ignite);
//	$igniteHeight=imagesx($ignite);

	// place the placard image
	imagecopy(
	// parking image (destination)
		$image,
		// abq logo (source)
		$placard,
		// place logo within source boundary
		$imageWidth / 1.45, $imageHeight / 50,
		// source x and y
		0, 0,
		// width and height of the area of the logo to copy
		$placardWidth, $placardHeight);

	// place map image
	imagecopy(
	// parking image (destination)
		$image,
		// abq logo (source)
		$map,
		// place logo within source boundary
		$imageWidth / 3.4, $imageHeight / 1.5,
		// source x and y
		0, 0,
		// width and height of the area of the logo to copy
		$mapWidth, $mapHeight);

	// place abq logo
	imagecopy(
	// parking image (destination)
		$image,
		// abq logo (source)
		$abq,
		// place logo within source boundary
		$imageWidth / 2.85, $imageHeight / 6.15,
		// source x and y
		0, 0,
		// width and height of the area of the logo to copy
		$abqWidth, $abqHeight);

	// place ignite logo
//	imagecopy(
//	$image,
//	// abq logo (source)
//	$ignite,
//	// place logo within source boundary
//	$imageWidth / 1.189, $imageHeight / 2.1,
//	// source x and y
//	0, 0,
//	// width and height of the area of the logo to copy
//	$igniteWidth, $igniteHeight);


	// output image
	ob_start();
	imagejpeg($image);
	$jpegData = ob_get_contents();
	ob_end_clean();

	// free up memory
	imagedestroy($image);

	//return image
	return ($jpegData);
}
?>