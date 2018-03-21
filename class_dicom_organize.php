<?php

require_once('class_dicom.php');

define('STORAGE', 'X:/sorted');

get_directory("X:/unsorted");

function process_file($file) {

  if(!is_dcm($file)) {
    print("Not a DICOM file: $file\n");
    unlink($file);
    return(0);
  }

  $d = new dicom_tag;
  $d->file = $file;
  $d->load_tags();

  $name = $d->get_tag('0010', '0010');
  $id = $d->get_tag('0010', '0020');
  $modality = $d->get_tag('0008', '0060');
  $appt_date = $d->get_tag('0008', '0020');
  $sop_id = $d->get_tag('0002', '0003');

  $year = date('Y', strtotime($appt_date));
  $month = date('m', strtotime($appt_date));
  $day = date('d', strtotime($appt_date));

  $storage = STORAGE . "/$year";
  if(!file_exists($storage)) {
    mkdir($storage);
  }
  $storage = $storage . "/$month";
  if(!file_exists($storage)) {
    mkdir($storage);
  }
  $storage = $storage . "/$day";
  if(!file_exists($storage)) {
    mkdir($storage);
  }

  $name = str_replace('^', '_', $name);
  $arr_replace = array('^', "'", '"', '`', '/', '\\', '?', ':', ';');
  foreach($arr_replace as $replace) {
    $name = str_replace($replace, '', $name);
    $id = str_replace($replace, '', $id);
  }

  $storage = $storage . "/$name" . "_$id";
  if(!file_exists($storage)) {
    mkdir($storage);
  }

  $new_file = $modality . "_" . $sop_id . ".dcm";

  if(file_exists("$storage/$new_file")) {
    $new_file = $modality . "_" . $sop_id . "_" . rand(1, 1000) . ".dcm";
  }

//  print "$storage/$new_file\n";

  if(!rename($file, "$storage/$new_file")) {
    print "Failed $file -> $storage/$new_file";
    exit;
  }
  print ".";

//  print "$name - $storage\n";
  //exit;
}

function is_dir_empty($dir) {
  if (!is_readable($dir)) return NULL;
  return (count(scandir($dir)) == 2);
}

function get_directory($dir, $level = 0) {
  $ignore = array( 'cgi-bin', '.', '..' );
  $dh = @opendir($dir);
  while( false !== ( $file = readdir($dh))){
    if( !in_array( $file, $ignore ) ){
      if(is_dir("$dir/$file")) {
        echo "\n$file\n";
        get_directory("$dir/$file", ($level+1));
      }
      else {
        //echo "$spaces $file\n";
        process_file("$dir/$file");
      }
    }
  }

  closedir( $dh );

  if(is_dir_empty($dir) && $dir != "X:/unsorted") {
    //print "\n-= Removing $dir =-\n";
    rmdir($dir);

  }

}

?>