<?php
	include("parts_checkSession.php");
	include("function.php"); 
	include("dbTempUpdate.php");
	
	$filesO = array();
	$validDataFlg = true;
	$blankDataFlg = true;
	
	// Save Soil data into DB temp tables
	if (isset($_POST["submitType"]) && $_POST["upload_file"] == "0") {
		
		$fileNumO = count($_FILES["FilePath"]["tmp_name"]);
		
		$filesO = readTempFileJson("O", false);
		if ($filesO == "") {
			$filesO = array();
		}
		
		// Read Observed data File
		for($i = 0; $i < $fileNumO; $i++) {
			$line = "";
			$flg[0] = "";
			$flg[1] = "";
			$flg[2] = "";
			$lineNo = 0;
			$retO = createObvArray();
			$validDataFlgs[$i] = true;
			
			if ($_FILES["FilePath"]["tmp_name"][$i] != "") {
				$file = fopen($_FILES["FilePath"]["tmp_name"][$i],"r") or exit("Unable to open file!"); 	
			
			
				while(!feof($file)) {
					$lineNo++;
					$line = fgets($file);
					$flg = judgeContentTypeO($line, $flg); //TODO // explode,splitStrToArray
					//echo "[line".$lineNo."],[".$flg[0]."],[".$flg[1]."],[".$flg[2]."]<br>"; //debug
					$retO = getSpliterResultO($flg, $line, $retO); //TODO
				}
				fclose($file);

				// TODO Check if the user input data is contain the necessary data
				if ((substr($_FILES["FilePath"]["name"][$i], 0, -4) . substr($_FILES["FilePath"]["name"][$i], -3, -1)) == $_POST["FileId"]) {
					$validDataFlgs[$i] = true;
				} else {
					$validDataFlgs[$i] = false;
				}
				
				$retO["file_name"] = $_FILES["FilePath"]["name"][$i];
				$filesO[$i] = $retO;
				$blankDataFlg = false;
			} else {
				if ($_POST["upload_file_id"][$i] == "0") {
					$filesO[$i] = createObvArray();
				}
			}
		}
		
		// if there is data not fulfilled with requirement, then goback
		for($i = 0; $i < $fileNumO; $i++) {
			if (!$validDataFlgs[$i]) {
				header("Location:   checkInputWFiles.php?inputId=" . $_SESSION["input_id"] . "&" . SID );
				$_SESSION["errFlg"] = "004";
				$validDataFlg = false;
				exit();
			}
		}
		
		// Save temple Xfile data into temp table when data is OK
		if($validDataFlg && !$blankDataFlg) updateTempFile(json_encode($filesO), "O");
	}
	
	// Read Xfile and decide forward page
	if ($validDataFlg) {
			
		if (!isset($_SESSION["dssat_steps"]) || $_SESSION["dssat_steps"] < 5) {
			$_SESSION["dssat_steps"] = 5;
		}
		$target = "confirmFiles.php";
		
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
			if ($target == "confirmFiles.php") {
				
			}
		?>
	</form>
</body>	

</html>