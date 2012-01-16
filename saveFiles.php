<?php include("parts_checkSession.php"); ?>
<?php $p_page="save"; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Language" content="en-US" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/frame.css" />
<script src="js/function.js" type="text/javascript"></script>
<title>Save Files</title>
<script src="js/function.js" type="text/javascript">
</script>
</head>

<body>
<div id="container">
	<?php include("parts_header.php"); ?>
	<div id="content">
		<?php include("parts_title.php"); ?>
		<?php include("parts_inputTabs.php"); ?>
		<form>
			<?php include("parts_explain.php"); ?>
			<table id="resultArea" align="center">
				
			</table>
			<div id="linkBtns">
				<input type="button" value="Back to Menu" onclick="goto('menu.php')" />&nbsp;&nbsp;
				<input type="button" value="Go to List" onclick="goto('list.php')" />&nbsp;&nbsp;
				<input type="button" value="Go to Map" onclick="goto('listByMap.php')" />&nbsp;&nbsp;
				<input type="button" value="Back to Input Page" onclick="goto('inputFiles00.php')" />&nbsp;&nbsp;
			</div>
		</form>
	</div>
	<?php include("parts_footer.php"); ?>
</div>
</body>
</html>