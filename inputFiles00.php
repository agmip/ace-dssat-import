<?php include("parts_checkSession.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Language" content="en-US" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/frame.css" />
<script src="js/function.js" type="text/javascript"></script>
<title>Input Files step00</title>
</head>

<body>
<div id="container">
	<?php include("parts_header.php"); ?>
	<?php include("errMsg.php"); ?>
	<div id="content">
		<?php $p_page="input00"; include("parts_title.php"); ?>
		<div id="tabs">
			<ul>
				<li>
					<span class="active">Step 0. Select Model</span>
				</li>
			</ul>
		</div>
		<form id="form1" method="post" action="checkFileFormat.php" enctype="multipart/form-data">
			<?php $p_page="input00"; include("parts_explain.php"); ?>
			<?php
				if (isset($_SESSION["errFlg"])) {
					$errMsg = getSessErrMsg($_SESSION["errFlg"]);
					echo "<div id='errMsg'>" . $errMsg . "</div>";
				}
			?>
			<table id="inputArea" align="center">
				<tr>
					<td id="titleCol">Model :</td>
					<td id="inputCol">
						<select id="FileFormat" name="FileFormat" style="width: 191px;">
							<option value="1">DSSAT</option>
						</select>
					</td>
				</tr>
			</table>
			<div id="subBtns">
				<input type="button" value="Back" onclick="goBack();" />&nbsp;&nbsp;
				<input id="SubmitN" type="submit" value="Next" onclick="" />&nbsp;&nbsp;
				<input id="SubmitF" type="button" value="Finish" disabled ="disabled" />&nbsp;&nbsp;
			</div>
		</form>
	</div>
	<?php include("parts_footer.php"); ?>
</div>
</body>

</html>
