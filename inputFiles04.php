<?php include("parts_checkSession.php"); ?>
<?php $p_pageNum=4;  $p_page="input04"; include("parts_checkTabsStatus.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Language" content="en-US" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/frame.css" />
<script src="js/function.js" type="text/javascript"></script>
<title>Input Files 04 Obervation</title>
</head>

<body>
<div id="container">
	<?php include("parts_header.php"); ?>
	<?php include("errMsg.php"); ?>
	<div id="content">
		<?php include("parts_title.php"); ?>
		<?php include("parts_inputTabs.php"); ?>
		<form id="form1" method="post" action="checkInputOFiles.php" enctype="multipart/form-data">
			<?php include("parts_explain.php"); ?>
			<?php
				if (isset($_SESSION["errFlg"])) {
					$errMsg = getSessErrMsg($_SESSION["errFlg"]);
					echo "<div id='errMsg'>" . $errMsg . "</div>";
				}
			?>
			<table id="inputArea" align="center">
				<tr>
					<td id="titleCol">File Format:</td>
					<td id="inputCol">DSSAT</td>
				</tr>
				<tr>
					<td id="titleCol">TFile path:</td>
					<td id="inputCol">
						<?php
							$AllInDBFlg = true;
							if (isset($_POST["upload_file"]) && $_POST["upload_file"][0] != "") {
								echo "Read From <strong>". $_POST["upload_file"][0] . ", </strong> OR<br/>\r\n";
								echo "<input type='hidden' id='upload_file_T' name='upload_file_id[]' value='1' />\r\n";
							} else {
								$AllInDBFlg = false;
								echo "<input type='hidden' id='upload_file_T' name='upload_file_id[]' value='0' />\r\n";
							}
						?>
						<input id="FilePath_T" name="FilePath[]" type="file" size="60" onchange="changeToUploadById('T');" /></td>
				</tr>
				<tr>
					<td id="titleCol">AFile path:</td>
					<td id="inputCol">
						<?php
							if (isset($_POST["upload_file"]) && $_POST["upload_file"][1] != "") {
								echo "Read From <strong>". $_POST["upload_file"][1] . ", </strong> OR<br/>\r\n";
								echo "<input type='hidden' id='upload_file_A' name='upload_file_id[]' value='1' />\r\n";
							} else {
								$AllInDBFlg = false;
								echo "<input type='hidden' id='upload_file_A' name='upload_file_id[]' value='0' />\r\n";
							}
						?>
						<input id="FilePath_A" name="FilePath[]" type="file" size="60" onchange="changeToUpload('A');" /></td>
				</tr>
				<input id='FileId' type='hidden' name='FileId' value='<?php echo $_POST["content_id"]; ?>' />
				<input type="hidden" id="submitType" name="submitType" value="next" />
				<?php
				if ($AllInDBFlg) {
						echo "<input type='hidden' id='upload_file' name='upload_file' value='1'>";
					} else {
						echo "<input type='hidden' id='upload_file' name='upload_file' value='0'>";
					}
				?>
			</table>
			<div id="subBtns">
				<input type="button" value="Back" onclick="goBack();" />&nbsp;&nbsp;
				<input id="Submit" type="button" value="Next" onclick="checkExdForObvfile()" />&nbsp;&nbsp;
				<input id="Submit" type="button" value="Finish" onclick="checkExdForObvfile()" />&nbsp;&nbsp;
			</div>
		</form>
	</div>
	<?php include("parts_footer.php"); ?>
</div>
</body>

</html>
