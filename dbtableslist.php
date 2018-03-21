<?php
$pgconnectstr43 = "host=127.0.0.1 port=5432 dbname=kmmar09 user=postgres password=1q2w3e4r5t";
$pgconnectstr102 = "host=192.168.1.102 port=5434 dbname=assured2 user=postgres password=Emdsystems102";
$pgconnectstr105 = "host=192.168.1.105 port=5433 dbname=GIP_MERGE user=postgres password=1q2w3e4r5t";

$pglisttables = "SELECT tablename FROM pg_catalog.pg_tables";
$pglisttables43 = array();
$pglisttables102 = array();
$pgconnect = pg_connect($pgconnectstr43) or die('could not connect 43: '.pg_last-error());
$result = pg_query($pglisttables) or die('query failed'.pg_last_error());
if (!pg_num_rows($result)) echo "found no tables...";
else {
    while($line = pg_fetch_object($result)) {
        $pglisttables43[] = $line->tablename;
    }
}
pg_free_result($result);
pg_close($pgconnect);
$pgconnect = pg_connect($pgconnectstr105) or die('could not connect 102: '.pg_last-error());
$result = pg_query($pglisttables) or die('query failed'.pg_last_error());
if (!pg_num_rows($result)) echo "found no tables...";
else {
    while($line = pg_fetch_object($result)) {
        $pglisttables102[] = $line->tablename;
    }
}
pg_free_result($result);
pg_close($pgconnect);

$result=array_diff($pglisttables43,$pglisttables102);
sort($result);
echo "array_diff: ".count($result);
foreach($result as $key => $value)
echo "<br/>".$value;

$result=array_intersect($pglisttables43,$pglisttables102);
echo "<br/>array_intersect: ".count($result);

?>