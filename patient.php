<?php
require 'dbstuff.php';
$pgconnect = pg_connect($pgconnectstr) or die('could not connect: '.pg_last-error());
$limit = !empty($_GET['limit'])? $_GET['limit']:50;
$patientfilter .= (" limit ".$limit);
$result = pg_query($patientfilter) or die('query failed'.pg_last_error());
if (!pg_num_rows($result)) echo "found no patients...";
else {
    function getmyname($line) {
        if ($line->ideogram_name != NULL) return $line->ideogram_name;
        else if ($line->phonogram_name != NULL) return $line->phonogram_name;
        else if ($line->full_name != NULL) return $line->full_name;
        else return '';
    }
echo '<table>';
while($line = pg_fetch_object($result)) {
echo '<tr ondblclick="showStudies('.$line->patient_id.');">';
echo '<td><img class="scan" onclick="showStudies('.$line->patient_id.');" /></td>';
echo '<td>'.$line->account_no.'</td>';
echo '<td>'.getmyname($line).'</td>';
echo '<td>'.$line->birth_date.'</td>';
echo '</tr>';
}
echo '</table>';
}

pg_free_result($result);
pg_close($pgconnect);
?>
<script>
function showStudies(patient) {
//window.location.href="index.php?category=study&page=index&patient="+tr.getAttribute('id');
window.location.href="index.php?category=study&page=index&patient="+patient;
}
</script>