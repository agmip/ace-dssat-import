<?php include("parts_checkSession.php"); ?>
<?php $p_page="listTemp"; ?>
<?php
	include("dbTempUpdate.php");
	
	$output = getTempData(); // TODO
	
	if ($output["dc_result"] == 0) {
		header("Location:   inputFiles00.php?" . SID);
		exit();
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Language" content="en-US" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/frame.css" />
<script src="js/function.js" type="text/javascript"></script>
<title>List For Temporary Data</title>
</head>

<body>
<div id="container">
	<?php include("parts_header.php"); ?>
	<div id="content">
		<?php include("parts_title.php"); ?>
		<form id="form1" method="post" action="inputFiles00.php" enctype="multipart/form-data">
			<?php include("parts_explain.php"); ?>
			<div id="TopBtns">
				<input type="button" value="Menu" onclick="window.location.href='index.php'" />&nbsp;&nbsp;
				<input id="SubmitF" type="submit" value="Add New" />&nbsp;&nbsp;
			</div>
			<table id="dataArea" align="center">
				<tr id="titleRow" class="center">
					<td>No</td>
					<td>Exp ID</td>
					<td>Format</td>
					<td style="width: 130px">Update Date</td>
					<td>Status(*)</td>
					<td style="width: 140px">Action</td>
				</tr>
				<?php
					$tempData = $output["dc_result"];
					
					for ($i = 0; $i < count($tempData); $i++) {
						
						if ($tempData[$i]["status"] == 2) {
							$status = "Pending";
							$linkStatus1 = "";
							$linkStatus2 = " disabled=\"disabled\"";
							$linkText = "";
						} else if ($tempData[$i]["status"] == 3) {
							$status = "Verifying";
							$linkStatus1 = " disabled=\"disabled\"";
							$linkStatus2 = " disabled=\"disabled\"";
						} else {
							$status = "Editing";
							$linkStatus1 = "";
							$linkStatus2 = "";
						}
						
						if (trim($tempData[$i]["exname"]) == "") {
							$exname = "--";
						} else {
							$exname = $tempData[$i]["exname"];
						}
				?>
				<tr id="inputRow" class="center">
					<td><?php echo $i+1; ?></td>
					<td><?php echo $exname; ?></td>
					<td><?php echo $tempData[$i]["file_format"]; ?></td>
					<td><?php echo $tempData[$i]["update_date"]; ?></td>
					<td><?php echo $status; ?></td>
					<td>
						<input id="SubmitF" type="button" value="Edit" style="width: 60px"  onclick="window.location.href='checkContinueStep.php?act=edit&inputId=<?php echo $tempData[$i]["input_id"]; ?>&stepNo=<?php echo $tempData[$i]["step_no"]; ?>'" <?php echo $linkStatus1; ?>/>
						<input id="SubmitF" type="button" value="Delete" style="width: 60px" onclick="window.location.href='checkContinueStep.php?act=delete&inputId=<?php echo $tempData[$i]["input_id"]; ?>&stepNo=<?php echo $tempData[$i]["step_no"]; ?>'" <?php echo $linkStatus2; ?>/>
					</td>
				</tr>
				<?php
					}
				?>
			</table>
			<div id="linkBtns">
				<input type="button" value="Menu" onclick="window.location.href='index.php'" />&nbsp;&nbsp;
				<input id="Submit" type="submit" value="Add New" />&nbsp;&nbsp;
			</div>
		</form>
	</div>
	<?php include("parts_footer.php"); ?>
</div>
</body>

</html>