#!/usr/bin/php
<?PHP
#
# Creates a jpeg and jpeg thumbnail of a DICOM file 
#

ini_set('memory_limit', '-1');
require_once('class_dicom.php');

$file = (!empty($_GET['fn'])? base64_decode($_GET['fn']) : '');
//$file = (isset($argv[1]) ? $argv[1] : '');
$is_thumbnail = !empty($_GET['t'])? $_GET['t'] : 0;

if(!$file) {
  print sys_get_temp_dir();
  print "<pre> USAGE: ./dcm_to_jpg.php <FILE>\n";
  exit;
}

if(!file_exists($file)) {
  print "$file: does not exist\n";
  exit;
}

$filenamesplits = explode('\\', $file);
$chk_file = sys_get_temp_dir() . '\\' . get_valid_file_name($file) . ($is_thumbnail? "_tn":"") . '.jpg';  
$d = new dicom_convert;

if (!file_exists($chk_file)) {  
    $d->file = $file;
    if ($is_thumbnail) $d->dcm_to_tn();
    else $d->dcm_to_jpg();
}

if (file_exists($chk_file)) {
  header('Content-Type: image/jpeg');
  readfile($chk_file);
}
else {
  print "$chk_file: something went wrong<br />";
  print "$d->out: hapened<br />";
  print "$d->convert_cmd: hapened<br />";
  exit;
}
/*else {
  //$im = imagecreatefromstring($d->out);
  $im = imagecreate(256, 256);
  $bg = imagecolorallocate($im, 0, 0, 0);
  $textcolor = imagecolorallocate($im, 255, 255, 255);
  imagestring($im, 10, 6, 8, $d->out, $textcolor);
  header('Content-type: image/png');
  imagepng($im);
  imagedestroy($im);
}*/

?>