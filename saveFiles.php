<?php include("parts_checkSession.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Language" content="en-US" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/frame.css" />
<script src="js/function.js" type="text/javascript">
</script>
<title>Save Files</title>
<style type="text/css">
.style1 {
				text-align: center;
}
.style2 {
				text-align: left;
				font-size: medium;
}
</style>
</head>
<body>
<div id="container">
	<?php include("parts_header.php"); ?>
	<?php include("function.php"); ?>
	<?php include("dbInsert.php"); ?>
	<div id="content">
		<p class="style2"><strong>The Process Results are as follows</strong></p>
		<table style="border: medium double #C0C0C0; width: 85%" align="center" border-collapse: collapse;">
		<?php
			$files = json_decode($_POST["files"], true);
			//printArray($files, "root", 0);
			
			$errFlg = false;
			//print_r($files);
			//echo "<br/>";
			$ret = insertDB($files, $_POST);
			$expNames = array_keys($ret);
			foreach ($expNames as $expName) {
				
				if ($ret[$expName]["errFlg"] === false) {
					echo "<tr bgcolor='#9BBB59'><td colspan='2'>" . $expName . "</td><td style='width: 68px' class='style1'>OK</td></tr>";
				} else {
					echo "<tr bgcolor='#00FF00'><td colspan='2'>" . $expName . "</td><td bgcolor='#CD7371' style='width: 68px' class='style1'>Failure</td></tr>";
				}
				
				$secNames = array_keys($ret[$expName]);
				$line = 0;
				foreach ($secNames as $secName) {
					if ($secName != "errFlg") {
						
						if($line%2 == 0) {
							$color = "#DEE7D1";
						} else {
							$color = "#EFF3EA";
						}
						
						if (gettype($ret[$expName][$secName]["dc_sql"]) == "array") {
							
							$cnt = count($ret[$expName][$secName]["dc_sql"]);
							for ($i = 1; $i <= $cnt; $i++) {

								if ($i == 1) {
									echo "<tr bgcolor='" . $color . "'><td rowspan='" . $cnt . "' valign='top'>" . $secName . "</td><td>".$ret[$expName][$secName]["dc_sql"][$i]."</td>";
								} else {
									echo "<tr bgcolor='" . $color . "'><td>".$ret[$expName][$secName]["dc_sql"][$i]."</td>";
								}

								if ($ret[$expName][$secName]["dc_result"][$i] === true) {
									echo "<td style='width: 68px' class='style1'>OK</td></tr>";
								} else if ($ret[$expName][$secName]["dc_result"][$i] === 0) {
									echo "<td style='width: 68px' class='style1'>No Data</td></tr>";
								} else {
									echo "<td bgcolor='#CD7371' style='width: 68px' class='style1'>Failure</td></tr>";
								}
							}
							
						} else {
							
							echo "<tr bgcolor='" . $color . "'><td valign='top'>" . $secName . "</td><td>".$ret[$expName][$secName]["dc_sql"]."</td>";
							if ($ret[$expName][$secName]["dc_result"] === true) {
								echo "<td style='width: 68px' class='style1'>OK</td></tr>";
							} else if ($ret[$expName][$secName]["dc_result"] === 0) {
								echo "<td style='width: 68px' class='style1'>No Data</td></tr>";
							} else {
								echo "<td bgcolor='#CD7371' style='width: 68px' class='style1'>Failure</td></tr>";
							}
						}
						$line++;
					}
				}
			}
		?>
		</table>
		<table align="center">
			<tr>
				<td><input type="button" value="Go to Menu" onclick="goto('menu.php')" /></td>
				<td><input type="button" value="Go to List" onclick="goto('list.php')" /></td>
				<td><input type="button" value="Go to Map" onclick="goto('listByMap.php')" /></td>
				<td><input type="button" value="Back to Input Page" onclick="goto('inputFiles.php')" /></td>
			</tr>
		</table>
	</div>
	<?php include("parts_footer.php"); ?>
</div>
</body>
</html>