<?php include("parts_checkSession.php"); ?>
<?php
	include("dbSelect.php");
	$exp_id = $_POST["exp_id"];
	$trnos = $_POST[$exp_id];
	$fileName = getXFileName($exp_id);
	header('Content-type:application/force-download');
	header('Content-Transfer-Encoding:Binary');
	header('Content-Disposition:attachment;filename=' . $fileName);
	//print_r($trnos);
	writeFile($exp_id, $trnos);
?>