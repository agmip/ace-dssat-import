<?php include("parts_checkSession.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Language" content="en-US" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/frame.css" />
<title>Input Files</title>
<script src="js/function.js" type="text/javascript">
</script>
<style type="text/css">
.style2 {
				font-size: small;
}
</style>
</head>

<body>
<div id="container">
	<?php include("parts_header.php"); ?>
	<div id="content">
		<form id="form1" method="post" action="readFiles.php" enctype="multipart/form-data">
			<table align="center"  >
				<tr><td colspan="2" class="style2" >Choose file for experiments that you want to add to the Agmip experiment database.</td></tr>
				<tr><td colspan="2" class="style2" >Currently, only DSSAT-format experiment file are enabled. Other crop model formats will be added as data translator are developed.</td></tr>
				<tr><td colspan="2"></td></tr>
				<tr>
					<td style="height: 50px">File Format:</td>
					<td><select id="FileFormat" name="FileFormat" style="width: 100Px;">
						<option value="1">DSSAT</option>
					</select></td>
				</tr>
				<tr>
					<td rowspan="2" valign="top">File path:</td>
					<td style="height: 50px"><input id="FilePath" name="FilePath" type="file" size="60" /></td>
				</tr>
				<tr>
					<td style="height: 50px"><div><input id="FilesPath" type="text" size="60" /></div>
					<input id="IsReadMultiFiles" name="IsReadMultiFiles" type="checkbox" /> Read Multi-files<br /></td>
				</tr>
				<tr>
					<td style="height: 50px"></td>
					<td align="right"><input type="button" value="Back to Menu" onclick="window.location.href='index.php'"/> <input id="Submit" type="button" value="Read" onclick="checkExdForExpfile()" /></td>
				</tr>
			</table>
		</form>
	</div>
	<?php include("parts_footer.php"); ?>
</div>
</body>

</html>
