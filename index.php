<!DOCTYPE html>
<html lang="en-US">
<head><title>PHP 5 include and require</title>
<meta charset="utf-8">
<link rel="stylesheet" href="style.css" />
</head>
<body>
<div class='header-div'>
<?php require 'header.php'; ?>
</div>
<div class='content-div'>
<?php
$category = !empty($_GET['category'])? $_GET['category']:"";
switch ($category) {
case 'patient': require 'patient.php'; break;
case 'study': require 'study.php'; break;
case 'dicom': require 'dicom.php'; break;
case 'viewer': require 'studyviewer.php'; break;
default: require 'home.php'; break;
}
?>
</div>
<div class='footer-div'>
<?php require 'footer.php'; ?>
</div>
</body>
</html>