<?php
	include("parts_checkSession.php");
	include("dbTempUpdate.php");
	
	$act = $_GET["act"];
	$inputId = $_GET["inputId"];
	$stepNo = $_GET["stepNo"];
	
	if ($act == "edit") {
		$_SESSION["input_id"] = $inputId;
		$_SESSION["dssat_steps"] = $stepNo;
		if ($stepNo == 1) {
			header("Location:   inputFiles01.php?" . SID );
		} else if ($stepNo == 2) {
			header("Location:   checkInputXFiles.php?" . SID );
		} else if ($stepNo == 3) {
			header("Location:   checkInputSFiles.php?" . SID );
		} else if ($stepNo == 4) {
			header("Location:   checkInputWFiles.php?" . SID );
		} else if ($stepNo == 5) {
			header("Location:   checkInputOFiles.php?" . SID );
		} else if ($stepNo == 6) {
			header("Location:   checkInputOFiles.php?" . SID );
		} else {
			header("Location:   inputFiles00.php?" . SID );
		}
		
		exit();
	} else if ($act == "delete") {
		deleteTempData($inputId);
		
	} else {
	}
	
	header("Location:   listTemp.php?" . SID );
	exit();
	
?>