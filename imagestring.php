<?php
// Create a 100*30 image
$im = imagecreate(100, 30);

// White background and blue text
$bg = imagecolorallocate($im, 255, 255, 255);
$textcolor = imagecolorallocate($im, 0, 0, 255);

// Write the string at the top left
imagestring($im, 10, 6, 8, 'EMD041003!', $textcolor);

// Output the image
header('Content-type: image/png');

imagepng($im);
imagedestroy($im);
?>