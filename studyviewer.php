<?php

ini_set("memory_limit",-1);

require 'dbstuff.php';
class viewerInfo {}    
$viewerInfo = new viewerInfo;
$viewerInfo->study = new viewerInfo;
$viewerInfo->link_info = new viewerInfo;
$viewerInfo->study->stacks = array();

if (isset($_GET["study"])) {
    $pgconnect = pg_connect($pgconnectstr) or die('could not connect: '.pg_last-error());
    $studyviewerinfo .= !empty($_GET['study'])? (" where studies.id =".$_GET['study']):" where studies.id = -1";
    $studyinforesult = pg_query($studyviewerinfo) or die('query failed'.pg_last_error());
    if (pg_num_rows($studyinforesult)) {
        $argstudyid = $_GET['study'];
        $studyResult = array();
        while ($object = pg_fetch_object($studyinforesult)) {
            $studyResult[] = $object;
        }     
        
        $viewerInfo->study->_id = $argstudyid;
        $viewerInfo->study->pname = $studyResult[0]->full_name;
        $viewerInfo->study->session = '';
        $viewerInfo->study->descr = '';
        $viewerInfo->study->bodypart = '';
        $viewerInfo->study->institution = '';
        $viewerInfo->study->ref_phy = '';
        $viewerInfo->study->_id = $studyResult[0]->id;
        $viewerInfo->study->_uid = $studyResult[0]->study_uid;
        $viewerInfo->study->_guid = $studyResult[0]->study_guid;
        $viewerInfo->study->_accession_no = $studyResult[0]->accession_no;
        $viewerInfo->study->_account_no = $studyResult[0]->account_no;
        $viewerInfo->study->_stat = $studyResult[0]->stat_level;
        $viewerInfo->study->_status = $studyResult[0]->study_status;
        $viewerInfo->study->_modality = $studyResult[0]->modalities_in_study;
        $viewerInfo->study->_p_name = $studyResult[0]->full_name;
        $viewerInfo->study->_p_gender = $studyResult[0]->gender;
        $viewerInfo->study->_p_id = $studyResult[0]->patient_id;
        $viewerInfo->study->_s_date = $studyResult[0]->study_dt;
        $viewerInfo->study->_age = $studyResult[0]->patient_age;
        $viewerInfo->link_info = $studyResult[0]->viewer_stack_link_info;

        foreach ($studyResult[0] as $key => $value) $viewerInfo->study->{$key} = $value;
        foreach (json_decode($studyResult[0]->study_details) as $key => $value) $viewerInfo->study->{$key} = $value;
        foreach (json_decode($studyResult[0]->patient_details) as $key => $value) $viewerInfo->study->{$key} = $value;
        
        $stackinforesult = pg_query(sprintf($stackviewerinfo, $argstudyid, $argstudyid)) or die('query failed'.pg_last_error());        
        if (pg_num_rows($stackinforesult)) {
            $stackResult = array();
            while ($stackobject = pg_fetch_object($stackinforesult)) {
                $stackInfo = new viewerInfo;
                foreach (json_decode($stackobject->stack_props) as $key => $value) $stackInfo->{$key} = $value;
                $stackInfo->images = array();
                $stackInfo->_id = $stackobject->id;
                $instanceinforesult = pg_query(sprintf($instanceviewerinfo, $stackobject->id)) or die('query failed'.pg_last_error());
                if (pg_num_rows($instanceinforesult)) {
                    while ($instanceobject = pg_fetch_object($instanceinforesult)) {
                        $instanceInfo = new viewerInfo;
                        if ($instanceobject) {
                            foreach (json_decode($instanceobject->instance_info) as $key => $value) $instanceInfo->{$key} = $value;
                            $instanceattributes = json_decode($instanceobject->instance_attributes);
                            $instanceInfo->_f = base64_encode($instanceattributes->file_name? join('\\', array($instanceobject->root_directory,$instanceattributes->file_path,$instanceattributes->file_name)):$instanceobject->file_path);
                        }
                        $instanceInfo->_id = $instanceobject->id;
                        $stackInfo->images[] = $instanceInfo;
                    }
                }
                pg_free_result($instanceinforesult);
                $viewerInfo->study->stacks[] = $stackInfo;
            }
        }
        pg_free_result($stackinforesult);
    }
    pg_free_result($studyinforesult);
    pg_close($pgconnect);
}

$stack_count = count($viewerInfo->study->stacks);
if ($stack_count) {
    for($s=0;$s<$stack_count;$s++) {
        if (count($viewerInfo->study->stacks[$s]->images)) {
            $img_count = $viewerInfo->study->stacks[$s]->is_multi_frame? $viewerInfo->study->stacks[$s]->no_of_frames:$viewerInfo->study->stacks[$s]->no_of_images;
            echo '<img class=thumbnail imgindex=0 imgcount='.$img_count.' stackindex='.$s.' src="get_dcm_to_image.php?fn='.$viewerInfo->study->stacks[$s]->images[0]->_f.'&t=1" onerror="this.onerror=null;this.src=\'images/imageerror.png\';" /> ';
        }
    }
    echo "<br/>";
    echo "<canvas></canvas>";
    echo "<canvas></canvas>";
}
else echo "no stacks found";
echo '<br/><input type="button" class="scan" onclick="showDicoms('.$viewerInfo->study->_p_id.');" value="view dicoms" /></td>';
?>

<script>
//var study = <?php echo json_encode($viewerInfo) ?>;
function showDicoms(patient) {
    window.location.href="index.php?category=dicom&page=index&patient="+patient;
}

window.addEventListener('wheel', function(e) {
    if (e.target.className.indexOf('thumbnail') != -1) {
        if (e.deltaY < 0) {
            console.log('scrolling up');
        }
        if (e.deltaY > 0) {
            console.log('scrolling down');
        }            
    }
});
</script>