<?php
	include("parts_checkSession.php");
	include("function.php"); 
	include("dbTempUpdate.php");
	
	// user final selection
	$selection = array();
	
	$expIds = $_POST["exp_id"];
	
	// Get user final selection for the update files
	for ($i = 0; $i < count($expIds); $i++) {
		// Treatment
		if (isset($_POST["checkX_" . $expIds[$i]])) {
			$selection[$expIds[$i]]["treatment"] = $_POST["checkX_" . $expIds[$i]];
		} else {
			header("Location:   confirmFiles.php?" . SID );
			$_SESSION["errFlg"] = "005";
			exit();
		}
		
		
		// Soil
		if (isset($_POST["checkS_" . $expIds[$i]])) {
			$selection[$expIds[$i]]["soil"] = $_POST["checkS_" . $expIds[$i]];
		} else {
			$selection[$expIds[$i]]["soil"] = array();
		}
		
		// Weather
		if (isset($_POST["checkW_" . $expIds[$i]])) {
			$selection[$expIds[$i]]["weather"] = $_POST["checkW_" . $expIds[$i]];
		} else {
			$selection[$expIds[$i]]["weather"] = array();
		}
		
		// Observed data TFile
		if (isset($_POST["checkT_" . $expIds[$i]])) {
			$selection[$expIds[$i]]["observed"][0] = $_POST["checkT_" . $expIds[$i]];
		} else {
			$selection[$expIds[$i]]["observed"] = "";
		}
		
		// Observed data AFile
		if (isset($_POST["checkA_" . $expIds[$i]])) {
			$selection[$expIds[$i]]["observed"][1] = $_POST["checkA_" . $expIds[$i]];
		} else {
			$selection[$expIds[$i]]["observed"] = "";
		}
	}
	
	// Update temp tables
	updateTempFile(json_encode($selection), "U");
	saveTempFile();
	clearDssatSession();
	header("Location:   saveFiles.php?" . SID );
	exit();
?>