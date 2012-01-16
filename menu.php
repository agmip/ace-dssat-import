<?php include("parts_checkSession.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Language" content="en-US" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/frame.css" />
<title>Menu</title>
<style type="text/css">
.style1 {
				font-size: xx-large;
}
.style2 {
				border-style: solid;
				border-width: thin;
}
</style>
<link rel="stylesheet" type="text/css" href="css/frame.css" />
</head>

<body>
<div id="container">
	<?php include("parts_header.php"); ?>
	<div id="content">
		<table class="style2" cellspacing="5" cellpadding="5" align="center" style="border: medium double #C0C0C0">
			<tr>
				<td colspan="3" class="style1" style="height: 69px; border-bottom-style: dotted; border-bottom-width: thin; border-bottom-color: #000000;"><strong>Menu</strong></td>
			</tr>
			
			<tr>
				<td style="width: 39px; height: 40px;"></td>
				<td colspan="2" style="height: 40px"><strong>View Data</strong> - Browse data in the AgMIP Crop Experiment database</td>
			</tr>
			<tr>
				<td style="width: 39px; height: 28px;"></td>
				<td style="width: 30px; height: 28px;"></td>
				<td style="height: 28px; width: 449px"><a href="list.php">List</a></td>
			</tr>
			<tr>
				<td style="width: 39px; height: 28px;"></td>
				<td style="width: 30px; height: 28px;"></td>
				<td style="height: 28px; width: 449px"><a href="listByMap.php">Google Map</a></td>
			</tr>
			<tr>
				<td style="width: 39px; height: 40px;"></td>
				<td colspan="2" style="height: 40px"><strong>Upload Your Data File</strong> - upload your file or continue your previous work</td>
			</tr>
			<tr>
				<td style="width: 39px; height: 28px;"></td>
				<td style="width: 30px; height: 28px;"></td>
				<td style="height: 28px; width: 449px"><a href="listTemp.php">Experimental Data</a></td>
			</tr>
			<tr>
				<td style="width: 39px; height: 28px;"></td>
				<td style="width: 30px; height: 28px;"></td>
				<td style="height: 28px; width: 449px"><a href="inputSoilFiles.php">Soil Data</a></td>
			</tr>
			<tr>
				<td style="width: 39px; height: 28px;"></td>
				<td style="width: 30px; height: 28px;"></td>
				<td style="height: 28px; width: 449px"><a href="inputWthFiles.php">Weather Data</a></td>
			</tr>
			<tr>
				<td style="width: 39px; height: 40px;"></td>
				<td colspan="2" style="height: 40px"><a href="#" onclick="alert('This page has not been completed.')"><strong>Query Data</strong></a></td>
			</tr>
		</table>
	</div>
	<?php include("parts_footer.php"); ?>
</div>
</body>

</html>