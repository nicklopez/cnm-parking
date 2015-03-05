<?php
// create image
$image = imagecreatetruecolor(768, 480);

//create colors
$black = imagecolorallocate($image, 0, 0, 0);
$white = imagecolorallocate($image, 255, 255, 255);

// fill background color
imagefill($image, 0, 0, $white);

// create image text
imagettftext($image, 16.0, 0.0, 16, 16, $black, "../fonts/Helvetica.ttf", "CNM STEMulus Temporary Parking Pass");

// set content type header as jpeg
header("Content-type: image/jpeg");

// test drawing a black line
imageline($image, 50, 50, 50, 50, $black);

// output image
imagejpeg($image);

// free up memory
imagedestroy($image);
?>

