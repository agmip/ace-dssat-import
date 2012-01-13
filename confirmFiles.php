<?php include("parts_checkSession.php"); ?>
<?php $p_pageNum=5;  $p_page="confirm"; include("parts_checkTabsStatus.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Language" content="en-US" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/frame.css" />
<script src="js/function.js" type="text/javascript"></script>
<title>Confirm Files</title>
<script src="js/function.js" type="text/javascript">
</script>
</head>

<body>
<div id="container">
	<?php include("parts_header.php"); ?>
	<?php include("errMsg.php"); ?>
	<div id="content">
		<?php include("parts_title.php"); ?>
		<?php include("parts_inputTabs.php"); ?>
		<form id="form1" method="post" action="checkSaveFiles.php">
			<?php include("parts_explain.php"); ?>
			<?php
				if (isset($_SESSION["errFlg"])) {
					$errMsg = getSessErrMsg($_SESSION["errFlg"]);
					echo "<div id='errMsg'>" . $errMsg . "</div>";
				}
			?>
			<?php
				include("function.php"); 
				include("dbTempUpdate.php");
			
				// Read Temporary Files From DB temp table
				$files = readTempFileJson("X", true);
				$fileTypes = checkUploadStatus();
			?>
			<table id="dataArea" align="center">
				<tr id="titleRow">
					<td>Experiment ID</td>
					<td>Treatment Num</td>
					<td>Treatment Name</td>
					<td>Select?</td>
				</tr>
				<?php
					$jsCheck = "";
					for($i = 0; $i < count($files); $i++) {
						$exp = $files[$i];
						$trs = $exp["treatments"];
						$expId = $exp["exp.details:"]["exname"];
						$isFstLine = true;
						
						foreach ($trs as $tr) {
							echo "<tr  id='inputRow'>";
							if ($isFstLine) {
								echo "<td rowspan='". count($trs) ."'>" . $expId . "</td>";
								$isFstLine = false;
							}
							
							$trNum = $tr["trtno"];
							$trName = $tr["titlet"];
							$checkBoxId = $expId . "_" . $trNum;
							$jsCheck = $jsCheck . $checkBoxId . ",";
							
							echo "<td class='center'>" . $trNum . "</td>";
							echo "<td>" . $trName . "</td>";
							echo "<td class='center' style='width: 81px'><input id='" . $checkBoxId . "' name='checkX_" . $expId . "[]' type='checkbox' checked='checked' value='" . $trNum . "' /></td>";
							echo "</tr>";
						}
						
						echo "<input name='exp_id[]' type='hidden' checked='checked' value='" . $expId . "' />";
					}
				?>
			</table>
			<br/>
			
			<table id="dataArea" align="center">
				<tr id="titleRow">
					<td style="width: 135px">Soil File ID</td>
					<td style="width: 148px">Avaibility</td>
					<td>Additional Info</td>
					<td style="width: 81px">Update?</td>
				</tr>
				<?php
					$filesS = readTempFileJson("S", false);
					if ($filesS != "") {
						
						for($i = 0; $i < count($filesS); $i++) {
							
							$retS = $filesS[$i];
							for ($j = 1; $j <= count($retS["upload"]); $j++) {
								
								if ($retS["upload"][$j] == "1") {
									
									$sid = $retS["site"][$j]["pedon"];
									if (checkSoilAvailableById($sid)) {
										$dbStatus =  "Available In DB";
									} else {
										$dbStatus =  "Unavailable In DB";
									}
									$sInfo = "";
									if (trim($retS["site"][$j]["ssite"]) != "") $sInfo .= $retS["site"][$j]["ssite"] . ", ";
									if (trim($retS["site"][$j]["scount"]) != "") $sInfo .= $retS["site"][$j]["scount"] . ", ";
									if (trim($retS["site"][$j]["slat"]) != "") $sInfo .= $retS["site"][$j]["slat"] . ", ";
									if (trim($retS["site"][$j]["slong"]) != "") $sInfo .= $retS["site"][$j]["slong"] . ", ";
									if (trim($retS["site"][$j]["tacon"]) != "") $sInfo .= $retS["site"][$j]["tacon"] . ", ";
									if ($sInfo != "" ) $sInfo = substr($sInfo, 0, -2);
									
									if (isset($fileTypes["S"])) {
										
										if ($sInfo != "") $sInfo .= "\r\n<br/>";
										$sInfo .= "Read From <strong>";
										$sInfo .= $fileTypes["S"][$i];
										$sInfo .= "</strong><br/>\r\n";
									}
									
									if ($sInfo == "" ) $sInfo = "--";
									
									echo "<tr id='inputRow'>\r\n";
									echo "	<td>" . $sid . "</td>\r\n";
									echo "	<td>" . $dbStatus . "</td>\r\n";
									echo "	<td>" . $sInfo . "</td>\r\n";
									echo "	<td class='center' style='width: 81px'>\r\n";
									echo "		<input name='checkS_" . $expId . "[]' type='checkbox' value='" . $sid . "' checked='checked' /></td>\r\n";
									echo "</tr>\r\n";
								} else {
									// TODO if no data will be uploaded
								}
							} // for $retS
						} // for $filesS
						
					} else {
						echo "<tr id='inputRow'><td colspan='4' class='center'>There is no new data uploaded.</td></tr>\r\n";
//						echo "<tr id='inputRow'>\r\n";
//						echo "	<td>--</td>\r\n";
//						echo "	<td>--</td>\r\n";
//						echo "	<td>--</td>\r\n";
//						echo "	<td class='center' style='width: 81px'>\r\n";
//						echo "		<input type='checkbox' value='1' disabled='disabled' /></td>\r\n";
//						echo "</tr>\r\n";
					}
				?>
			</table>
			<br/>
			<table id="dataArea" align="center">
				<tr id="titleRow">
					<td style="width: 135px">Weather File ID</td>
					<td style="width: 148px">Avaibility</td>
					<td>Additional Info</td>
					<td style="width: 81px">Update?</td>
				</tr>
				<?php
					$filesW = readTempFileJson("W", false);
					if ($filesW != "") {
						$checkRet = array();
						checkWthAvailable($files[0], $checkRet); // TODO will be revised when multiple XFile upload allowed
						
						for($i = 0; $i < count($filesW); $i++) {
							
							$retW = $filesW[$i];
							$wid = $retW["inste"] . $retW["sitee"];
							
							$sYear = substr($retW["daily"][1]["yrdoyw"], 0, -3);
							if ($sYear == "") $sYear = "00";
							else if (strlen($sYear) == 1) $sYear = "0".$sYear;
							$sDay = substr($retW["daily"][1]["yrdoyw"], -3);
							$eYear = substr($retW["daily"][count($retW["daily"])]["yrdoyw"], 0, -3);
							if ($eYear == "") $eYear = "00";
							else if (strlen($eYear) == 1) $eYear = "0".$eYear;
							$eDay = substr($retW["daily"][count($retW["daily"])]["yrdoyw"], -3);
							
							if ($checkRet[$wid]["dbStatus"] == "1") {
								$dbStatus =  "Fully Available In DB";
							} else if ($checkRet[$wid]["dbStatus"] == "-1") {
								$dbStatus =  "Unavailable In DB";
							} else {
								$dbStatus =  "Partly Available In DB";
							}
							
							if (isset($fileTypes["W"])) {
								$wInfo = "\r\n<br/>Read From <strong>";
//								for($k=0; $k<count($fileTypes["W"]); $k++)  $wInfo .= $fileTypes["W"][$k] . "<br/>";
//								if ($wInfo != "" ) $wInfo = substr($wInfo, 0, -5); 
								$wInfo .= $fileTypes["W"][$i];
								$wInfo .= "</strong><br/>\r\n";
							} else {
								$wInfo = "";
							}
							
							echo "	<tr id='inputRow'>\r\n";
							echo "		<td>" . $wid . "</td>\r\n";
							echo "		<td>" . $dbStatus . "</td>\r\n";
							echo "		<td>From <input id='FromY' name='sYear[]' type='text' maxlength='2' size='2' value='" . $sYear . "' disabled='disabled'/>-<input id='FromD' name='sDay[]' type='text' maxlength='3' size='3' value='" . $sDay . "' disabled='disabled' /> To <input id='ToY' name='eYear[]' type='text' maxlength='2' size='2' value='" . $eYear . "' disabled='disabled' />-<input id='ToD' name='eDay[]' type='text' maxlength='3' size='3' value='" . $eDay . "' disabled='disabled' />" . $wInfo . "</td>\r\n";
							echo "		<td class='center' style='width: 81px'>\r\n";
							echo "			<input name='checkW_" . $expId . "[]' type='checkbox' value='" . $wid . "' checked='checked' /></td>\r\n";
							echo "	</tr>\r\n";
						}
					} else {
						echo "<tr id='inputRow'><td colspan='4' class='center'>There is no new data uploaded.</td></tr>\r\n";
//						echo "	<tr id='inputRow'>\r\n";
//						echo "		<td>--</td>\r\n";
//						echo "		<td>--</td>\r\n";
//						echo "		<td>--</td>\r\n";
//						echo "		<td class='center' style='width: 81px'>\r\n";
//						echo "			<input type='checkbox' value='1' disabled='disabled' /></td>\r\n";
//						echo "	</tr>\r\n";
					}
				?>
			</table>
			<br/>
			<table id="dataArea" align="center">
				<tr id="titleRow">
					<td style="width: 135px">Observation File ID</td>
					<td>Additional Info</td>
					<td style="width: 81px">Update?</td>
				</tr>
				<?php
					$filesO = readTempFileJson("O", false);
					if ($filesO != "") {
						
						if (isset($filesO[0]["file_name"])) {
							$oidT = $expId;
							$statusT = " checked='checked'";
						} else {
							$oidT = "--";
							$statusT = " disabled='disabled'";
						}
						if (isset($filesO[1]["file_name"])) {
							$oidA = $expId;
							$statusA = " checked='checked'";
						} else {
							$oidA = "--";
							$statusA = " disabled='disabled'";
						}
						
						if (isset($fileTypes["O"])) {
							if ($fileTypes["O"][0] != "") {
								$tInfo = ", Read From <strong>" . $fileTypes["O"][0] . "</strong><br/>";
							} else {
								$tInfo = ", Unavailable ";
							}
							if ($fileTypes["O"][1] != "") {
								$aInfo = ", Read From <strong>" . $fileTypes["O"][1] . "</strong><br/>";
							} else {
								$aInfo = ", Unavailable ";
							}
						} else {
							$wInfo = "";
						}
				?>
					<tr id="inputRow">
						<td><?php echo $oidT; ?></td>
						<td>TFile<?php echo $tInfo; ?></td>
						<td class="center" style="width: 81px">
							<input name="checkT_<?php echo $expId; ?>" type="checkbox" value="<?php echo $oidT; ?>" <?php echo $statusT; ?> />
						</td>
					</tr>
					<tr id="inputRow">
						<td><?php echo $oidA; ?></td>
						<td>AFile<?php echo $aInfo; ?></td>
						<td class="center" style="width: 81px">
							<input name="checkA_<?php echo $expId; ?>" type="checkbox" value="<?php echo $oidA; ?>" <?php echo $statusA; ?> />
						</td>
					</tr>
				<?php
					} else {
						echo "<tr id='inputRow'><td colspan='4' class='center'>There is no new data uploaded.</td></tr>\r\n";
					}
				?>
			</table>
			<input name='files' type='hidden' value='' />
			<div id="subBtns">
				<input name="Button1" type="button" value="Back" onclick="goBack()" />&nbsp;&nbsp;
				<input id="Submit" type="button" value="Next" disabled="disabled" />&nbsp;&nbsp;
				<input id="save" name="save" type="Submit" value="Save" onclick="checkChkbox('<?php echo $jsCheck; ?>')" />&nbsp;&nbsp;
			</div>
		</form>
	</div>
	<?php include("parts_footer.php"); ?>
</div>
</body>

</html>
