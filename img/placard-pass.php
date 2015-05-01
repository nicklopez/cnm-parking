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
$image = imagecreatetruecolor(2400, 2500);
imagejpeg($image, $tempfile, 90);
imagedestroy($image);

// convert the resolution to 300 dpi and reopen the image
setDpi($tempfile, 300);
$image = imagecreatefromjpeg($tempfile);
unlink($tempfile);

// fonts
$font = 'fonts/Helvetica.ttf';
$font2 = 'fonts/Helvetica-Bold.ttf';

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



imagettftext($image, 65, 0.0, 100, 125, $black, $font2, "Start Date/Time: Mar 25, '15 9:00 am");
imagettftext($image, 65, 0.0, 100, 275, $black, $font2, "End Date/Time: Mar 25, '15 10:00 am");

imagettftext($image, 50.0, 0.0, 100, 400, $black, $font, "Year: 2009" );
imagettftext($image, 50.0, 0.0, 100, 500, $black, $font, "Make: Toyota" );
imagettftext($image, 50.0, 0.0, 100, 600, $black, $font, "Model: Tacoma" );
imagettftext($image, 50.0, 0.0, 100, 700, $black, $font, "Color: Red" );
imagettftext($image, 50.0, 0.0, 100, 800, $black, $font, "Plate State: NM" );

imagettftext($image, 65, 0.0, 100, 950, $black, $font2, "License Plate #: NM - 000999" );
imagettftext($image, 65, 0.0, 100, 1100, $black, $font2, "Location: 1st and Copper (CNM)");

imagettftext($image, 55.0, 0.0, 1750, 1220, $black, $font2, "Placard #: 0284");

imagettftext($image, 25, 0.0, 150, 1300, $red, $font, "LEGAL NOTICE: Duplication or manufacturing of a parking permit is a crime. Handwritten changes will VOID an temporary parking pass.
																													Vehicles displaying such permits will be cited.");



$w   = imagecolorallocate($image, 255, 255, 255);
//$style = array($black, $black, $black, $black, $black, $w, $w, $w, $w, $w);
//imagesetstyle($image, $style);
//imageline($image, 0, 1400, 2400, 1400, IMG_COLOR_STYLED);

$style = array($black, $black, $w, $w);
imagesetstyle($image, $style);
imageline($image, 0, 1390, 2400, 1390, IMG_COLOR_STYLED);
imagettftext($image, 20.0, 0.0, 1100, 1430, $black, $font2, "Fold Here");


imagerectangle($image, 10, 10, 2390, 1250, $green);

//imagedashedline($image, 0, 1600, 2400, 1500, $red);


// Output and free from memory
header('Content-Type: image/jpeg');

// add the logo
$logo = imagecreatefromjpeg('placard2s.jpg');
$logo2 = imagecreatefromjpeg('map.jpg');

//add transparency to logo
imagealphablending($logo, true);

// Get dimensions
$imageWidth=imagesx($image);
$imageHeight=imagesy($image);

$logoWidth=imagesx($logo);
$logoHeight=imagesy($logo);

$logoWidth2=imagesx($logo2);
$logoHeight2=imagesy($logo2);



// Paste the logo
imagecopy(
// parking image (destination)
	$image,
	// abq logo (source)
	$logo,
	// place logo within source boundary
	$imageWidth / 1.41, $imageHeight / 50,
	// source x and y
	0, 0,
	// width and height of the area of the logo to copy
	$logoWidth, $logoHeight);

imagecopy(
// parking image (destination)
	$image,
	// abq logo (source)
	$logo2,
	// place logo within source boundary
	$imageWidth / 3.3, $imageHeight / 1.60,
	// source x and y
	0, 0,
	// width and height of the area of the logo to copy
	$logoWidth2, $logoHeight2);

imagejpeg($image);
imagedestroy($image, "test.jpeg");

?>