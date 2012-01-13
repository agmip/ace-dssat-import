<?php include("parts_checkSession.php"); ?>
<?php $p_pageNum=2; $p_page="input02"; include("parts_checkTabsStatus.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Language" content="en-US" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/frame.css" />
<script src="js/function.js" type="text/javascript"></script>
<title>Input File 02 Soil</title>
</head>

<body>
<div id="container">
	<?php include("parts_header.php"); ?>
	<?php include("errMsg.php"); ?>
	<div id="content">
		<?php include("parts_title.php"); ?>
		<?php include("parts_inputTabs.php"); ?>
		<form id="form1" method="post" action="checkInputSFiles.php" enctype="multipart/form-data">
			<?php include("parts_explain.php"); ?>
			<?php
				if (isset($_SESSION["errFlg"])) {
					$errMsg = getSessErrMsg($_SESSION["errFlg"]);
					echo "<div id='errMsg'>" . $errMsg . "</div>";
				}
			?>
			<table id="inputArea" align="center">
				<tr id="titleRow">
					<td style="width: 90px">Soil ID</td>
					<td style="width: 160px">Availability</td>
					<td style="width: 400px">Additional Info</td>
					<td style="width: 50px">Update</td>
				</tr>
				<?php
					$content_status = $_POST["content_status"];
					$content_id = $_POST["content_id"];
					$content_xcrd = $_POST["content_xcrd"];
					$content_ycrd = $_POST["content_ycrd"];
					$ids = "";
					for ($i = 0; $i < count($content_id); $i++) {
						
						$ids = $ids . $content_id[$i] . ",";
						
						if ($content_status[$i] == "1") {
							$dbStatus =  "Available In DB";
							$checked = " onclick=\"changeActiveStatus('" . $content_id[$i] . "');\"";
							$disabled = " disabled='disabled' ";
							if (isset($_POST["upload_file"]) && $_POST["upload_file"][$i] != "") {
								$checked .= " checked = 'checked' ";
								$disabled = "";
							}
						} else {
							$dbStatus =  "Unavailable In DB";
							$checked = " disabled='disabled' checked = 'checked' ";
							$disabled = "";
						}
						
						echo "<tr id='inputRow'>\r\n";
						echo "	<td rowspan='2'>" . $content_id[$i] . "</td>\r\n";
						echo "	<td>" . $dbStatus . "</td>\r\n";
						echo "	<td>XCRD " . $content_xcrd[$i] . ", YCRD " . $content_ycrd[$i] . "</td>\r\n";
						echo "	<td class='center'>\r\n";
						echo "		<input id='check_" . $content_id[$i] . "' type='checkbox' name='checkBox[]'" . $checked . " value='1' />\r\n";
						echo "		<input id='FileId_" . $content_id[$i] . "' type='hidden' name='FileId[]' value='" . $content_id[$i] . "'" . $disabled . "/>\r\n";
						echo "	</td>\r\n";
						echo "</tr>\r\n";
						echo "<tr id='inputRow'><td colspan='3'>\r\n";
						if (isset($_POST["upload_file"]) && $_POST["upload_file"][$i] != "") {
							echo "Read From <strong>" . $_POST["upload_file"][$i] . "</strong> OR<br/>\r\n";
						}
						echo "<input id='FilePath_" . $content_id[$i] . "' name='FilePath[]' type='file' size='60'  onchange=\"changeToUploadById('" .  $content_id[$i] . "');\" " . $disabled . "/></td></tr>\r\n";
						echo "<input type='hidden' name='sid[]' value='" . $content_id[$i] . "' />\r\n";
						$AllInDBFlg = true;
						if ((isset($_POST["upload_file"]) && $_POST["upload_file"][$i] != "") || $content_status[$i] == "1") {
							echo "<input type='hidden' id='upload_file_" .  $content_id[$i] . "' name='upload_file_id[]' value='1' />\r\n";
						} else {
							$AllInDBFlg = false;
							echo "<input type='hidden' id='upload_file_" .  $content_id[$i] . "' name='upload_file_id[]' value='0' />\r\n";
						}
					}
					
					// If temporary or formal table contain this id
					if ($AllInDBFlg) {
						echo "<input type='hidden' id='upload_file' name='upload_file' value='1' />\r\n";
					} else {
						echo "<input type='hidden' id='upload_file' name='upload_file' value='0' />\r\n";
					}
					$ids = substr($ids, 0, -1);
				?>
				<input type="hidden" id="submitType" name="submitType" value="next" />	
			</table>
			<div id="subBtns">
				<input type="button" value="Back" onclick="goBack();" />&nbsp;&nbsp;
				<input id="Submit" type="button" value="Next" onclick="checkExdForSoilfile('next', '<?php echo $ids; ?>')" />&nbsp;&nbsp;
				<input id="Submit" type="button" value="Finish" onclick="checkExdForSoilfile('finish', '<?php echo $ids; ?>')" />&nbsp;&nbsp;
			</div>
		</form>
	</div>
	<?php include("parts_footer.php"); ?>
</div>
</body>

</html>