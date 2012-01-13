<?php
	include("parts_checkSession.php");
	include("dbTempUpdate.php");
	
	// Define all supported format
	$formats["1"] = "DSSAT";
	
	
	$fileFormat = $_POST["FileFormat"];
	
	// check if format is valid
	if (!array_key_exists($fileFormat, $formats)) {
		
		// Set error msg set into session
		$_SESSION["errFlg"] = "003";
		Header("Location:   inputFiles00.php?" . SID );
		exit();
		
	} else {
		
		$input_id = insertTempFile($formats[$fileFormat]);
		$_SESSION["input_id"] = $input_id;
		
		if ($formats[$fileFormat] === "DSSAT") {
			
			// setup input status in session for DSSAT
			$_SESSION["dssat_steps"] = 1;
			Header("Location:   inputFiles01.php?" . SID);
			exit();
		}	
	}
?>