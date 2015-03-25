<?php
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


// create a low resolution image of the proper pixel size
$tempfile = tempnam("/tmp", "PASS");
$image = imagecreatetruecolor(2400, 1500);
imagejpeg($image, $tempfile, 90);
imagedestroy($image);

// convert the resolution to 300 dpi and reopen the image
setDpi($tempfile, 300);
$image = imagecreatefromjpeg($tempfile);
unlink($tempfile);


$font = 'fonts/Helvetica.ttf';

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
imagettftext($image, 75, 0.0, 145, 95, $black, $font, "City of Albuquerque Temporary Parking Pass");

imagettftext($image, 50, 0.0, 450, 365, $black, $font, "Start Date/Time: Mar 25, '15 9:00 am");
imagettftext($image, 50.0, 0.0, 450, 525, $black, $font, "End Date/Time: Mar 25, '15 10:00 am");

imagettftext($image, 50.0, 0.0, 450, 700, $black, $font, "License Plate #: NM - 000999" );
imagettftext($image, 50.0, 0.0, 450, 875, $black, $font, "Location: Downtown ABQ - Lot #5");

imagettftext($image, 50.0, 0.0, 450, 1050, $black, $font, "Placard #: 0284");

imagettftext($image, 25, 0.0, 150, 1300, $red, $font, "LEGAL NOTICE: Duplication or manufacturing of a parking permit is a crime. Handwritten changes will VOID an temporary parking pass.
																													Vehicles displaying such permits will be cited.");

imagerectangle($image, 50, 200, 2350, 1200, $green);

// Output and free from memory
header('Content-Type: image/jpeg');

// add the logo
$logo = imagecreatefromjpeg('abq.jpg');

//add transparency to logo
imagealphablending($logo, true);

// Get dimensions
$imageWidth=imagesx($image);
$imageHeight=imagesy($image);

$logoWidth=imagesx($logo);
$logoHeight=imagesy($logo);

// Paste the logo
imagecopy(
// parking image (destination)
	$image,

	// abq logo (source)
	$logo,

	// place logo within source boundary
	$imageWidth / 1.31 , $imageHeight / 2.1,

	// source x and y
	0, 0,

	// width and height of the area of the logo to copy
	$logoWidth, $logoHeight);

imagejpeg($image);
imagedestroy($image, "test.jpeg");

?>