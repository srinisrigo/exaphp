<?php

ini_set("memory_limit",-1);

require 'dbstuff.php';
$pgconnect = pg_connect($pgconnectstr) or die('could not connect: '.pg_last-error());
$arglimit = isset($_GET['c'])? $_GET['c']:50;
$argfilter = isset($_GET['p'])? (" where patients.id =".$_GET['p']):"";
$result = pg_query(sprintf($dicomstudyfilter,$argfilter,$arglimit)) or die('query failed'.pg_last_error());
$myarray = array();
if (pg_num_rows($result))
while ($object = pg_fetch_object($result)) {
  $myarray[] = $object;
}
pg_free_result($result);
pg_close($pgconnect);
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Max-Age: 3628800');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
if(array_key_exists('callback', $_GET)){

    header('Content-Type: text/javascript; charset=utf8');

    $callback = $_GET['callback'];
    echo $callback.'('.json_encode($myarray).');';

}else{
    // normal JSON string
    header('Content-Type: application/json; charset=utf8');

    echo json_encode($myarray);
}
?>
