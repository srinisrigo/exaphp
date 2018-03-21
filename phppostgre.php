<style>
* {
-moz-user-select: none;
-webkit-user-select: none;
-ms-user-select: none;
user-select: none;
margin: 0;
padding: 0;
}

html, body {
height: 100%;
width: 100%;
}

table {
border-collapse: collapse;
}

div.header-div, div.content-div, div.footer-div {
width: 100%;
}

div.header-div {
height: 10%;
background-color: #fca;
}

div.footer-div {
height: 3%;
background-color: #cfa;
}

div.content-div {
height: 87%;
overflow: auto;
}
</style>
<div class='header-div'></div>
<div class='content-div'>
<?php
$pgconnect = pg_connect("host=192.168.1.104 dbname=GIP_DEV user=postgres password=1q2w3e4r5t")
or die('could not connect: '.pg_last-error());
$result = pg_query('select * from patients limit 1') or die('query failed'.pg_last_error());
echo '<table>';
while($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
echo '<tr>';
foreach ($line as $col_value) {
echo '<td>'.$col_value.'</td>';
}
echo '</tr>';
}
echo '</table>';

pg_free_result($result);
pg_close($pgconnect);
?>
</div>
<div class='footer-div'></div>