<?php
require 'dbstuff.php';
$pgconnect = pg_connect($pgconnectstr) or die('could not connect: '.pg_last-error());
$studyfilter .= !empty($_GET['patient'])? (" where patients.id =".$_GET['patient']):" limit 50";
$result = pg_query($studyfilter) or die('query failed'.pg_last_error());
if (!pg_num_rows($result)) echo "found no study...";
else {
echo '<table>';
while($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
echo '<tr ondblclick="showViewer('.$line["study_id"].');">';
echo '<td><img class="scan" onclick="showViewer('.$line["study_id"].');" /></td>';
foreach ($line as $col_value) {
echo '<td>'.$col_value.'</td>';
}
echo '</tr>';
}
echo '</table>';
}

pg_free_result($result);
pg_close($pgconnect);
?>

<script>
function showViewer(study) {
window.location.href="index.php?category=viewer&page=index&study="+study;
}
</script>