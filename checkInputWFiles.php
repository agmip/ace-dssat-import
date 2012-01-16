<?php
	include("parts_checkSession.php");
	include("function.php"); 
	include("dbTempUpdate.php");
	
	$filesW = array();
	$validDataFlg = true;
	
	// Save Weather data into DB temp tables
	if (isset($_POST["submitType"]) && $_POST["upload_file"] == "0") {
		
		$filesW = readTempFileJson("W", false);
		if ($filesW != "") {
			$fileNumU = count($filesW);
		} else {
			$filesW = array();
			$fileNumU = count($_POST["upload_file_id"]);
		}
		
		for($j = 0, $k = 0; $j < count($_POST["wid"]) && $k < $fileNumU; $j++) {
			
			$wid = $_POST["wid"][$j];
			if (isset($_FILES["FilePath_" . $wid]["tmp_name"])) {
				$fileNumW = count($_FILES["FilePath_" . $wid]["tmp_name"]);
			} else {
				$fileNumW = 0;
			}
			
			$firstFileFlg = true;
			$validDataFlgs[$j] = true;
			
			while(isset($_POST["upload_file_id"][$k])) {
				if ($_POST["upload_file_id"][$k] == "1") {
					if (!isset($filesW[$wid][$k])) {
						$filesW[$k]["db_wid"] = $_POST["wid"][$k];
						$filesW[$k]["upload"] = array();
					}
					$k++;
				}	else {
					break;
				}
			}
			
			for($i = 0; $i < $fileNumW; $i++) {
				$line = "";
				$flg[0] = "";
				$flg[1] = "";
				$flg[2] = "";
				$lineNo = 0;
				$retW = createWthArray();
				//$filesW[$i] = $retW;
				
				if ($_FILES["FilePath_" . $wid]["tmp_name"][$i] != "") {
          $filename = $_FILES["FilePath_" . $wid]["name"][$i];
					$file = fopen($_FILES["FilePath_" . $wid]["tmp_name"][$i],"r") or exit("Unable to open file!"); 	
					
					while(!feof($file)) {
						$lineNo++;
						$line = fgets($file);
						$flg = judgeContentTypeW($line, $flg); // explode,splitStrToArray
						//echo "[line".$lineNo."],[".$flg[0]."],[".$flg[1]."],[".$flg[2]."]<br>"; //debug
						$retW = getSpliterResultW($flg, $line, $retW);
	//					if ($flg === false) {
	//						
	//					} else {
	//						
	//					} //TODO later revise to improve performance
					}
					fclose($file);
					
					// Check if the user input data is contain the necessary data
					if (($retW["inste"] . $retW["sitee"]) != $_POST["wid"][$j]) {
            // TEMP: chporter has authorized a fuzzy routine to check the file name, in case the inste+sitee is incorrect.
            $checkname = basename($filename);
            if( substr( $checkname, 0, 4 ) != $wid ) { 
              echo $checkname.'<br>';
              echo substr($checkname, 0, 4).'<br>';
              echo $wid.'<br>';
              exit();
              $validDataFlgs[$j] = false;
            } else {
              // Log this for the DSSAT Police (chporter)
              $errlog = fopen("./dssat.err", "a") or die ("Cannot open error file");
              fwrite($errlog, "ERROR IN FILE ".$checkname.": Expected: ".$wid." Received: ".$retW["inste"].$retW["sitee"]."\r\n"); 
              fclose($errlog);
              $retW["inste"] = substr( $checkname, 0, 2 );
              $retW["sitee"] = substr( $checkname, 2, 2 );
            }
					} else {
						// TODO check if there is enough days in the upload file
					}
					
					//$retW["file_name"] = $_FILES["FilePath_" . $wid]["name"][$i]; // TODO
					if (!isset($filesW[$k]) || ($_POST["upload_file_id"][$k] == "0" && $firstFileFlg)) {
						$filesW[$k] = array();
						$filesW[$k]["wid"] = $_POST["wid"][$j];
						$filesW[$k]["file_name"] = array();
						$filesW[$k]["start"] = $retW["daily"][1]["yrdoyw"];
						$filesW[$k]["end"] = $retW["daily"][count($retW["daily"])]["yrdoyw"];
						$firstFileFlg = false;
					}
					$num = count($filesW[$k]) - 4;
					$filesW[$k][$num] = $retW;
					$filesW[$k]["file_name"][$num] = $_FILES["FilePath_" . $wid]["name"][$i];
					if (compareDate($filesW[$k]["start"], $retW["daily"][1]["yrdoyw"]))
						$filesW[$k]["start"] = $retW["daily"][1]["yrdoyw"];
					if (compareDate($retW["daily"][count($retW["daily"])]["yrdoyw"], $filesW[$k]["end"]))
						$filesW[$k]["end"] = $retW["daily"][count($retW["daily"])]["yrdoyw"];
				}
			}
			
		}
		
		// if there is data not fulfilled with requirement, then goback
		for($i = 0; $i < count($_POST["wid"]); $i++) {
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
		
		// Set forward page
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
