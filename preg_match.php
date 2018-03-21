<?php
$arg_file_name = !empty($_GET["f"])? $_GET["f"]:"abskwlfd.dcm";
$file_name = $arg_file_name;
if (preg_match("/(\.(dcm|jpg|jpeg|gif|png))$/i", $arg_file_name))
    $file_name = preg_replace("/(\.(dcm|jpg|jpeg|gif|png))$/i", "", $arg_file_name);
print "$arg_file_name: $file_name";
?>