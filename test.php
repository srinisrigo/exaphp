<?php
##DICOMS\2017\11\21\11\1.2.392.200036.9107.632.9999.20090909.090909.1\1.2.392.200036.910701.699.1.2009090909090901
  $filenamesplits = explode('\\', "DICOMS\\2017\\11\\21\\11\\1.2.392.200036.9107.632.9999.20090909.090909.1\\1.2.392.200036.910701.699.1.2009090909090901");
  var_dump($filenamesplits);
  $filenamestr = $filenamesplits[count($filenamesplits)-1];
  $filenamestr = preg_replace("/(\.(dcm|jpg|jpeg|gif|png))$/i", "", $filenamestr);
  echo $filenamestr;
?>