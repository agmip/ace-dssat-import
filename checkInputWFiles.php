<?php
	include("parts_checkSession.php");
	include("function.php"); 
	include("dbTempUpdate.php");
	
	$filesW = array();
	$validDataFlg = true;
	
	// Save Soil data into DB temp tables
	if (isset($_POST["submitType"]) && $_POST["upload_file"] == "0") {
		
		if (isset($_FILES["FilePath"]["tmp_name"])) {
			$fileNumW = count($_FILES["FilePath"]["tmp_name"]);
		} else {
			$fileNumW = 0;
		}
		
		$filesW = readTempFileJson("W", false);
		if ($filesW != "") {
			$fileNumU = count($filesW);
		} else {
			$filesW = array();
			$fileNumU = count($_POST["upload_file_id"]);
		}
		
		for($i = 0, $k = 0; $i < $fileNumW && $k < $fileNumU; $i++) {
			$line = "";
			$flg[0] = "";
			$flg[1] = "";
			$flg[2] = "";
			$lineNo = 0;
			$retW = createWthArray();
			//$filesW[$i] = $retW;
			$validDataFlgs[$i] = true;
			
			if ($_FILES["FilePath"]["tmp_name"][$i] != "") {
			
				$file = fopen($_FILES["FilePath"]["tmp_name"][$i],"r") or exit("Unable to open file!"); 	
				
				while(!feof($file)) {
					$lineNo++;
					$line = fgets($file);
					$flg = judgeContentTypeW($line, $flg); // explode,splitStrToArray
					//echo "[line".$lineNo."],[".$flg[0]."],[".$flg[1]."],[".$flg[2]."]<br>"; //debug
					$retW = getSpliterResultW($flg, $line, $retW);
				}
				fclose($file);
				
				// Check if the user input data is contain the necessary data
				// TODO check if there is enough days in the upload file
				if (($retW["inste"] . $retW["sitee"]) == $_POST["FileId"][$i]) {
					$validDataFlgs[$i] = true;
				} else {
					$validDataFlgs[$i] = false;
				}
				
				$retW["file_name"] = $_FILES["FilePath"]["name"][$i]; // TODO
				
				while(isset($_POST["upload_file_id"][$k])) {
					if ($_POST["upload_file_id"][$k] == "1") {
						if (!isset($filesW[$k])) {
							$filesW[$k]["db_wid"] = $_POST["wid"][$k];
							$filesW[$k]["upload"] = array();
						}
						$k++;
					}	else {
						break;
					}
				}
				
				$filesW[$k] = $retW;
				$k++;
			}
		}
		
		// if there is data not fulfilled with requirement, then goback
		for($i = 0; $i < $fileNumW; $i++) {
			if (!$validDataFlgs[$i]) {
				header("Location:   checkInputSFiles.php?inputId=" . $_SESSION["input_id"] . "&" . SID );
				$_SESSION["errFlg"] = "004";
				$validDataFlg = false;
				exit();
			}
		}
		
		// Save temple Xfile data into temp table when data is OK
		if($validDataFlg) updateTempFile(json_encode($filesW), "W");
	}
	
	// Read Xfile and decide forward page
	if ($validDataFlg) {
		
		// Read XFile From DB temp table
		$files = readTempFileJson("X", true);
		
		$ret = $files[0]; // TODO will be changed when multiple file uploaded allowed
		
		// Set forward page
		if (isset($_POST["submitType"]) && $_POST["submitType"] == "finish") {
		
			// All check ok, forward to confirm page
			if (!isset($_SESSION["dssat_steps"]) || $_SESSION["dssat_steps"] < 5) {
				$_SESSION["dssat_steps"] = 5;
			}
			$target = "confirmFiles.php";
			
		} else {
			$fileTypes = checkUploadStatus();
			
			if (!isset($_SESSION["dssat_steps"]) || $_SESSION["dssat_steps"] < 4) {
				$_SESSION["dssat_steps"] = 4;
			}
			$target = "inputFiles04.php";
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
			if ($target == "inputFiles04.php") {
				echo "<input type='hidden' name='content_id' value='" . $ret["exp.details:"]["exname"] . "' />\r\n";
				
				if (array_key_exists("O", $fileTypes)) {
					for ($i = 0; $i< count($fileTypes["O"]); $i++) {
						echo "<input type='hidden' id='upload_file' name='upload_file[]' value='" . $fileTypes["O"][$i] . "' />\r\n";
					}
				}
			} else if ($target == "confirmFiles.php") {
				
			}
		?>
	</form>
</body>	

</html>