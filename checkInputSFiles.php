<?php
	include("parts_checkSession.php");
	include("function.php"); 
	include("dbTempUpdate.php");
	
	$filesS = array();
	$validDataFlg = true;
	
	// Save Soil data into DB temp tables
	if (isset($_POST["submitType"]) && $_POST["upload_file"] == "0") {
		
		if (isset($_FILES["FilePath"]["tmp_name"])) {
			$fileNumS = count($_FILES["FilePath"]["tmp_name"]);
		} else {
			$fileNumS = 0;
		}
		
		$filesS = readTempFileJson("S", false);
		if ($filesS != "") {
			$fileNumU = count($filesS);
		} else {
			$filesS = array();
			$fileNumU = count($_POST["upload_file_id"]);
		}
		
		for($i = 0, $k = 0; $i < $fileNumS && $k < $fileNumU; $i++) {
			$line = "";
			$flg[0] = "";
			$flg[1] = "";
			$flg[2] = "";
			$lineNo = 0;
			$retS = createSoilArray();
			//$filesS[$i] = $retS;
			$validDataFlgs[$i] = true;
			
			if ($_FILES["FilePath"]["tmp_name"][$i] != "") {
				$file = fopen($_FILES["FilePath"]["tmp_name"][$i],"r") or exit("Unable to open file!"); 	
				
				while(!feof($file)) {
					$lineNo++;
					$line = fgets($file);
					$flg = judgeContentTypeS($line, $flg); // explode,splitStrToArray
					//echo "[line".$lineNo."],[".$flg[0]."],[".$flg[1]."],[".$flg[2]."]<br>"; //debug
					$retS = getSpliterResultS($flg, $line, $retS);
				}
				fclose($file);
				
				// Check if the user input data is contain the necessary data
				$validDataFlgs[$i] = false;
				for ($j = 1; $j <= count($retS["site"]); $j++) {
					if ($retS["site"][$j]["pedon"] == $_POST["FileId"][$i]) {
						$validDataFlgs[$i] = true;
						$retS["upload"][$j] = 1;
					} else {
						$retS["upload"][$j] = 0;
					}
				}
				$retS["file_name"] = $_FILES["FilePath"]["name"][$i];
				
				while(isset($_POST["upload_file_id"][$k])) {
					if ($_POST["upload_file_id"][$k] == "1") {
						if (!isset($filesS[$k])) {
							$filesS[$k]["db_sid"] = $_POST["sid"][$k];
							$filesS[$k]["upload"] = array();
						}
						$k++;
					}	else {
						break;
					}
				}
				
				$filesS[$k] = $retS;
				$k++;
			}
		}
		
		// if there is data not fulfilled with requirement, then goback
		for($i = 0; $i < $fileNumS; $i++) {
			if (!$validDataFlgs[$i]) {
				header("Location:   checkInputXFiles.php?inputId=" . $_SESSION["input_id"] . "&" . SID );
				$_SESSION["errFlg"] = "004";
				$validDataFlg = false;
				exit();
			}
		}
		
		// Save temple Xfile data into temp table when data is OK
		if($validDataFlg) updateTempFile(json_encode($filesS), "S");
	}
	
	// Read Xfile and decide forward page
	if ($validDataFlg) {
		
		// Read XFile From DB temp table
		$files = readTempFileJson("X", true);
		
		$ret = $files[0]; // TODO will be changed when multiple file uploaded allowed
		
		// Set forward page
		if (isset($_POST["submitType"]) && $_POST["submitType"] == "finish") {
			
			// Get all type of  files which are already be uploaded previously
			$fileTypes = checkUploadStatus();
		
			// Check if weather data is avaliable in DB
			if (!array_key_exists("W", $fileTypes) && !checkWthAvailable($ret, $checkRet)) {
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
			checkWthAvailable($ret, $checkRet);
			
			if (!isset($_SESSION["dssat_steps"]) || $_SESSION["dssat_steps"] < 3) {
				$_SESSION["dssat_steps"] = 3;
			}
			$target = "inputFiles03.php";
		}
	} else {
		$target = "inputFiles00.php";
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
			if ($target == "inputFiles03.php") {
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
			} else if ($target == "confirmFiles.php") {
				
			}
		?>
	</form>
</body>	

</html>