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
$image = imagecreatetruecolor(2400, 2600);
imagejpeg($image, $tempfile, 90);
imagedestroy($image);

// convert the resolution to 300 dpi and reopen the image
setDpi($tempfile, 300);
$image = imagecreatefromjpeg($tempfile);
unlink($tempfile);

// add the logo
$logo = imagecreatefromjpeg('placard4.jpg');
$logo2 = imagecreatefromjpeg('gmapd.jpg');
$logo3 = imagecreatefromjpeg('ignite.jpg');
$logo4 = imagecreatefromjpeg('abq3.jpg');

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
imagettftext($image, 65, 0.0, 100, 300, $black, $font2, "End Date/Time: Mar 25, '15 10:00 am");

imagettftext($image, 50.0, 0.0, 100, 550, $black, $font, "Year: 2009" );
imagettftext($image, 50.0, 0.0, 100, 650, $black, $font, "Make: Toyota" );
imagettftext($image, 50.0, 0.0, 100, 750, $black, $font, "Model: Tacoma" );
imagettftext($image, 50.0, 0.0, 100, 850, $black, $font, "Color: Red" );


imagettftext($image, 65, 0.0, 100, 1125, $black, $font2, "State/Lic. Plate #: NM - 000999" );
imagettftext($image, 65, 0.0, 100, 1300, $black, $font2, "Location: 1st and Copper (CNM)");

imagettftext($logo, 70, 0, 260, 1130, $black, $font2, "0205");

imagettftext($image, 25, 0.0, 150, 1460, $red, $font, "LEGAL NOTICE: Duplication or manufacturing of a parking permit is a crime. Handwritten changes will VOID an temporary parking pass.
																													Vehicles displaying such permits will be cited.");

// text for parking lot address
imagettftext($image, 30, 0, 1800, 1800, $black, $font2, "Parking Lot Address:

					1st and Copper
					(Near Central Ave.
					and 2nd St.)");


$w   = imagecolorallocate($image, 255, 255, 255);
//$style = array($black, $black, $black, $black, $black, $w, $w, $w, $w, $w);
//imagesetstyle($image, $style);
//imageline($image, 0, 1400, 2400, 1400, IMG_COLOR_STYLED);

$style = array($black, $black, $w, $w);
imagesetstyle($image, $style);
imageline($image, 0, 1550, 2400, 1550, IMG_COLOR_STYLED);
imagettftext($image, 20.0, 0.0, 1100, 1590, $black, $font2, "Fold Here");

imagelayereffect($logo, IMG_EFFECT_OVERLAY);

imagerectangle($image, 10, 10, 2390, 1400, $green);

//imagedashedline($image, 0, 1600, 2400, 1500, $red);


// Output and free from memory
header('Content-Type: image/jpeg');


//add transparency to logo
imagealphablending($logo, true);

// Get dimensions
$imageWidth=imagesx($image);
$imageHeight=imagesy($image);

$logoWidth=imagesx($logo);
$logoHeight=imagesy($logo);

$logoWidth2=imagesx($logo2);
$logoHeight2=imagesy($logo2);

$logoWidth3=imagesx($logo3);
$logoHeight3=imagesy($logo3);

$logoWidth4=imagesx($logo4);
$logoHeight4=imagesy($logo4);



// Paste the logo
imagecopy(
// parking image (destination)
	$image,
	// abq logo (source=)
	$logo,
	// place logo within source boundary
	$imageWidth / 1.45, $imageHeight / 50,
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
	$imageWidth / 3.4, $imageHeight / 1.5,
	0, 0,
	// width and height of the area of the logo to copy
	$logoWidth2, $logoHeight2);

imagecopy(
// parking image (destination)
	$image,
	// abq logo (source)
	$logo3,
	// place logo within source boundary
	$imageWidth / 1.1869, $imageHeight / 1.935,
	// source x and y
	0, 0,
	// width and height of the area of the logo to copy
	$logoWidth3, $logoHeight3);

imagecopy(
// parking image (destination)
	$image,
	// abq logo (source)
	$logo4,
	// place logo within source boundary
	$imageWidth / 2.85, $imageHeight / 6.15,
	// source x and y
	0, 0,
	// width and height of the area of the logo to copy
	$logoWidth4, $logoHeight4);

imagejpeg($image);
//imagejpeg($logo);
imagedestroy($image, "parkingPlacard.jpeg");
//imagedestroy($logo);
?>