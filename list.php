<?php include("parts_checkSession.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Language" content="en-US" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/frame.css" />
<title>List</title>
<script src="js/function.js" type="text/javascript"></script>
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
.style7 {
				border: 1px solid #000000;
				text-align: center;
				font-size: x-small;
}
</style>
</head>

<body>
<div id="container">
	<?php include("parts_header.php"); ?>
	<?php include("dbSelect.php"); ?>
	<div id="content">
		<p class="style5">
			<span><input id="addNewFile" name="addNewFile" type="button" value="Add New File" onclick="goto('inputFiles.php')"/></span>
			<span><input id="backToMenu" name="backToMenu" type="button" value="Back To Menu" onclick="goto('menu.php')"/></span>
			<span><input id="GoToMap" name="GoToMap" type="button" value="Switch To Map" onclick="goto('listByMap.php')"/></span>
		</p>
		<table class="style2" style="width: 600px" align="center">
			<tr>
				<td class="style1">Experiment ID</td>
				<td class="style1">Treatment Num</td>
				<td class="style1">Treatment Name</td>
				<td class="style1" style="width: 81px">Select?</td>
				<td class="style1">Download</td>
			</tr>
			<?php 
				$dbConnectOutput = getDataListAll();
				$ret = $dbConnectOutput["dc_result"];
				$retNum = $dbConnectOutput["dc_result_num"];
				$exp_id = "";

				for ($i = 0; $i < $retNum; $i++) {
					
					if ($exp_id != $ret[$i]["exp_id"]) {
						$isFstLine = true;
						$exp_id = $ret[$i]["exp_id"];
						$exName = $ret[$i]["exname"];
						$cnt = 1;
						
						while ($i+$cnt < $retNum) {
							if ($exp_id == $ret[$i+$cnt]["exp_id"]) {
								$cnt++;
							} else {
								break;
							}
						}
						$lastLine = $i + $cnt - 1;
					} else {
						$isFstLine = false;
					}
					
					$trNum = $ret[$i]["trno"];
					$trName = $ret[$i]["tr_name"];
					$checkBoxId = $exp_id . "_" . $trNum;
					if ($isFstLine) {
						echo "<form id='form_" . $exp_id ."' method='post' action='getFiles.php'>";
						echo "<input name='exp_id' type='hidden' value='" . $exp_id . "'/>";
					}
					echo "<tr>";
					if ($isFstLine) {
						echo "<td rowspan='". $cnt ."' class='style3'><a href='expDetail.php?expId=" . $exp_id . "'>" . $exName . "</a></td>";
					}
					echo "<td class='style4'>" . $trNum . "</td>";
					echo "<td class='style3'>" . $trName . "</td>";
					echo "<td class='style4' style='width: 81px'><input id='" . $checkBoxId . "' name='" . $exp_id . "[]' type='checkbox' checked='checked' value='" . $trNum . "' /></td>";
					if ($isFstLine) {
						echo "<td rowspan='" . $cnt . "' class='style7'><input name='" . $exp_id . "_btn' type='submit' value='Download' /></td>";
					}
					echo "</tr>";
					if ($i === $lastLine) {
						echo "</form>";
					}
				}
			?>
		</table>
		<br />
	</div>
	<?php include("parts_footer.php"); ?>
</div>
</body>

</html>
