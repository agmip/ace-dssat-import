<?php include("parts_checkSession.php"); ?>
<?php $p_pageNum=1; $p_page="input01"; include("parts_checkTabsStatus.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Language" content="en-US" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/frame.css" />
<script src="js/function.js" type="text/javascript"></script>
<title>Input Files step01</title>
</head>

<body>
<div id="container">
	<?php include("parts_header.php"); ?>
	<div id="content">
		<?php include("parts_title.php"); ?>
		<?php include("parts_inputTabs.php"); ?>
		<form id="form1" method="post" action="checkInputXFiles.php" enctype="multipart/form-data">
			<?php include("parts_explain.php"); ?>
			<table id="inputArea" align="center">
				<tr>
					<td id="titleCol">File Format:</td>
					<td id="inputCol">DSSAT</td>
				</tr>
				<?php
					include("dbTempUpdate.php");
					$fileTypes = checkUploadStatus();
				?>
				<tr>
					<td id="titleCol" rowspan="2">File path:</td>
					<td id="inputCol">
						<?php if (isset($fileTypes["X"])) { ?>
							Read From <strong><?php for($i=0; $i<count($fileTypes["X"]); $i++) echo $fileTypes["X"][$i] . ", "; ?></strong> OR<br/> 
						<?php } ?>
						<input id="FilePath" name="FilePath" type="file" size="60" onchange="changeToUpload();" />
					</td>
				</tr>
				<tr>
					<td id="inputCol"><div><input id="FilesPath" type="text" size="60" disabled="disabled" /></div>
					<input id="IsReadMultiFiles" name="IsReadMultiFiles" type="checkbox" disabled="disabled" /> 
					Read Multi-files<br /></td>
				</tr>
				<input type="hidden" id="submitType" name="submitType" value="next" />
				<?php
					if (isset($fileTypes["X"])) { //TODO
						echo "<input type='hidden' id='upload_file' name='upload_file' value='1' />\r\n";
					} else {
						echo "<input type='hidden' id='upload_file' name='upload_file' value='0' />\r\n";
					}
				?>
			</table>
			<div id="subBtns">
				<input type="button" value="Back" onclick="goBack();" />&nbsp;&nbsp;
				<input id="Submit" type="button" value="Next" onclick="checkExdForExpfile('next')" />&nbsp;&nbsp;
				<input id="Submit" type="button" value="Finish" onclick="checkExdForExpfile('finish')" />&nbsp;&nbsp;
			</div>
		</form>
	</div>
	<?php include("parts_footer.php"); ?>
</div>
</body>

</html>
