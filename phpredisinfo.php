<?php
require 'dbstuff.php';
echo base64_encode(!empty($_GET['p'])?$_GET['p']:"");
echo base64_decode(!empty($_GET['f'])?$_GET['f']:"QzpcS29uaWNhTWlub2x0YVxESUNPTVNcMjAxN1wxMlwxNlwxOVwxLjIuMzkyLjIwMDAzNi45MTA3Ljk5OTkuMTAwMC4yMDE3MTEyMjE3NTE1NDc2XDEuMi4yNzYuMC43MjMwMDEwLjMuMS40LjI0MzMzMjk0MS4xMDEyLjE1MTEzNDA3MTUuMTg=");

$arglimit = isset($_GET['c'])? $_GET['c']:'50';
$argfilter = isset($_GET['p'])? (" where patients.id =".$_GET['p']):"";
echo sprintf($dicomstudyfilter,$argfilter,$arglimit);
?>