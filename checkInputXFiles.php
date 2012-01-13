<?php
	include("parts_checkSession.php");
	include("function.php"); 
	include("dbTempUpdate.php");
	
	$files = array();
	
	// Get file content from user upload file or DB temp tables
	if (!isset($_POST["submitType"])  || $_POST["upload_file"] != "0") {
		
		// Read XFile From DB temp table
		$files = readTempFileJson("X", true);
		
	} else {
		
		$fileNum = 1; //TODO For furthur multiple input file 
		
		for($i = 0; $i < $fileNum; $i++) {
			$line = "";
			$flg[0] = "";
			$flg[1] = "";
			$flg[2] = "";
			$lineNo = 0;
			$ret = createExpArray();
			
			$file = fopen($_FILES["FilePath"]["tmp_name"],"r") or exit("Unable to open file!"); 	
			
			while(!feof($file)) {
				$lineNo++;
				$line = fgets($file);
				$flg = judgeContentType($line, $flg); // explode,splitStrToArray
				//echo "[line".$lineNo."],[".$flg[0]."],[".$flg[1]."],[".$flg[2]."]<br>"; //debug
				$ret = getSpliterResult($flg, $line, $ret);
			}
			fclose($file);
			
			$ret = checkExpId($ret, $_FILES["FilePath"]["name"]);
			$ret = checkCoordinate($ret);
			
			$ret["file_name"] = $_FILES["FilePath"]["name"];
			$files[$i] = $ret;
		}
		
		// Save temple Xfile data into temp table
		updateTempFile(json_encode($files), "X");
		updateTempFileExpName($files[0]["exp.details:"]["exname"]);
		$_SESSION["dssat_steps"] = 2;
	}
	
	$ret = $files[0]; // TODO will be changed when multiple file uploaded allowed
	
	// Set forward page
	if (isset($_POST["submitType"]) && $_POST["submitType"] == "finish") {
		
		// Get all type of  files which are already be uploaded previously
		$fileTypes = checkUploadStatus();
		
		// Check if soil data is avaliable in DB
		if (!array_key_exists("S", $fileTypes) && !checkSoilAvailable($ret, $checkRet)) {
			if (!isset($_SESSION["dssat_steps"]) || $_SESSION["dssat_steps"] < 2) {
				$_SESSION["dssat_steps"] = 2;
			}
			$target = "inputFiles02.php";
	
		// Check if weather data is avaliable in DB
		} else if (!array_key_exists("W", $fileTypes) && !checkWthAvailable($ret, $checkRet)) {
			if (!isset($_SESSION["dssat_steps"]) || $_SESSION["dssat_steps"] < 3) {
				$_SESSION["dssat_steps"] = 3;
			}
			$target = "inputFiles03.php";
		} else {
		
			// All check ok, forward to confirm page
			if (!isset($_SESSION["dssat_steps"]) || $_SESSION["dssat_steps"] < 5) {
				$_SESSION["dssat_steps"] = 5;
			}
			$target = "confirmFiles.php";
		}
	} else {
		$fileTypes = checkUploadStatus();
		checkSoilAvailable($ret, $checkRet);
		
		if (!isset($_SESSION["dssat_steps"]) || $_SESSION["dssat_steps"] < 2) {
			$_SESSION["dssat_steps"] = 2;
		}
		$target = "inputFiles02.php";
	}
?>

<html>
<header>
<script language="javascript">
	function autoSubmit() {
		var form1 = document.getElementById("form1");
		form1.submit();
	}
	
</script>
</header>

<body onload="autoSubmit();">
	<form id="form1" method="post" action="<?php echo $target; ?>" enctype="multipart/form-data">
		<?php
			if ($target == "inputFiles02.php") {
				for ($i = 1; $i <= count($checkRet); $i++) {
					echo "<input type='hidden' id='content_status' name='content_status[]' value='" . $checkRet[$i - 1] . "' />\r\n";
					echo "<input type='hidden' id='content_id' name='content_id[]' value='" . $ret["fields"][$i]["slno"] . "' />\r\n";
					echo "<input type='hidden' id='content_id' name='content_xcrd[]' value='" . $ret["fields"][$i]["xcrd"] . "' />\r\n";
					echo "<input type='hidden' id='content_id' name='content_ycrd[]' value='" . $ret["fields"][$i]["ycrd"] . "' />\r\n";
				}
				
				if (array_key_exists("S", $fileTypes)) {
					for ($i = 0; $i< count($fileTypes["S"]); $i++) {
						echo "<input type='hidden' id='upload_file' name='upload_file[]' value='" . $fileTypes["S"][$i] . "' />\r\n";
					}
				}
			} else if ($target == "inputFiles03.php") {
				foreach ($checkRet as $wth) {
					echo "<input type='hidden' id='content_id' name='content_id[]' value='" . $wth["wid"] . "' />\r\n";
					echo "<input type='hidden' id='content_status' name='content_status[]' value='" . $wth["dbStatus"] . "' />\r\n";
					echo "<input type='hidden' id='content_syear' name='content_start[]' value='" . $wth["start"] . "' />\r\n";
					echo "<input type='hidden' id='content_status' name='content_end[]' value='" . $wth["end"] . "' />\r\n";
				}
				
				if (array_key_exists("W", $fileTypes)) {
					for ($i = 0; $i< count($fileTypes["W"]); $i++) {
						echo "<input type='hidden' id='upload_file' name='upload_file[]' value='" . $fileTypes["W"][$i] . "' />\r\n";
					}
				}
			} if ($target == "confirmFiles.php") {
				
			}
		?>
	</form>
</body>	

</html>