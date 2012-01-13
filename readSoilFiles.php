<?php include("parts_checkSession.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Language" content="en-US" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/frame.css" />
<title>Read Soil Files</title>
<script src="js/function.js" type="text/javascript">
</script>
<style type="text/css">
.style1 {
	border: 1px solid #000000;
	background-color: #C0C0C0;
}
.style2 {
	border: 2px solid #000000;
}
.style3 {
	border: 1px solid #000000;
}
.style4 {
	border: 1px solid #000000;
	text-align: center;
}
.style5 {
	text-align: center;
}
</style>
</head>

<body>
<div id="container">
	<?php include("parts_header.php"); ?>
	<?php include("functionForSoil.php"); ?>
	<?php
		$fileNum = 1;
		$files = array();
		
		for($i = 0; $i < $fileNum; $i++) {
			$line = "";
			$flg[0] = "";
			$flg[1] = "";
			$flg[2] = "";
			$lineNo = 0;
			$ret = createSoilArray();
			
			$file = fopen($_FILES["FilePath"]["tmp_name"],"r") or exit("Unable to open file!");
			
			while(!feof($file)) {
				$lineNo++;
				$line = fgets($file);
				$flg = judgeContentType($line, $flg); // explode,splitStrToArray
				//echo "[line".$lineNo."],[".$flg[0]."],[".$flg[1]."],[".$flg[2]."]<br>"; //debug
				$ret = getSpliterResult($flg, $line, $ret);
			}
			fclose($file);
			
			//$ret = checkExpId($ret, $_FILES["FilePath"]["name"]); // TODO if there must be a soil id, then add this method to do that
			
			$files[$i] = $ret;
			printSoilArray($ret); //debug
			//print_r($ret); //debug
		}

	?>
	<div id="content">
		<form id="form1" method="post" action="saveFiles.php">
			<table class="style2" style="width: 600px" align="center">
				<!--tr>
					<td class="style1">Experiment ID</td>
					<td class="style1">Treatment Num</td>
					<td class="style1">Treatment Name</td>
					<td class="style1" style="width: 81px">Select?</td>
				</tr-->
				<?php
					
					echo "<input name='files' type='hidden' value='" . json_encode($files) . "' />";
				?>
			</table>
			<br />
			<p class="style5">
				<span><input name="Button1" type="button" value="Back" onclick="goBack()"/></span>&nbsp;
				<span><input id="save" name="save" type="button" value="Save" onclick="checkChkbox('<?php echo $jsCheck; ?>')" /></span></form>
			</p>
		</form>
	</div>
	<?php include("parts_footer.php"); ?>
</div>
</body>
</html>