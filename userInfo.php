<?php include("parts_checkSession.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Language" content="en-US" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/frame.css" />
<title>User Info</title>
</head>

<body>
<div id="container">
	<?php include("parts_header.php"); ?>
	<?php include("errMsg.php"); ?>
	<?php include("dbUser.php"); ?>
	<div id="content">
		<?php
			if (isset($_SESSION["errFlg"])) {
				$errMsg = getSessErrMsg($_SESSION["errFlg"]);
				echo "<div id='errMsg'>" . $errMsg . "</div>";
			}
			$dbConnectOutput = getUserInfo($_SESSION["user"]);
			if ($dbConnectOutput["dc_result_num"] === 1) {
				$userEmail = $dbConnectOutput["dc_result"][0]["email"];
				$userLastName = $dbConnectOutput["dc_result"][0]["last_name"];
				$userFirstName = $dbConnectOutput["dc_result"][0]["first_name"];
			} else {
				$userEmail = "";
				$userLastName = "";
				$userFirstName = "";
			}
		?>
		<div><form id="form1" method="post" action="updateUserInfo.php">
			<table align="center">
				<tr>
					<td style="width: 99px; height: 45px;">Email</td>
					<td style="height: 45px; width: 350px;"><input type="text" name="email" value="<?php echo $userEmail; ?>"/></td>
				</tr>
				<tr>
					<td style="width: 99px; height: 45px;">Last Name</td>
					<td style="height: 45px; width: 350px;"><input type="text" name="last_name" value="<?php echo $userLastName; ?>"/></td>
				</tr>
				<tr>
					<td style="width: 99px; height: 45px;">First Name</td>
					<td style="height: 45px; width: 350px;"><input type="text" name="first_name" value="<?php echo $userFirstName; ?>"/></td>
				</tr>
				<tr>
					<td style="width: 99px; height: 45px;">New Password</td>
					<td style="height: 45px; width: 350px;">
					<input type="password" name="password" /></td>
				</tr>
				<tr>
					<td style="width: 99px; height: 45px;">Confirm<br />New Password</td>
					<td style="height: 45px; width: 350px;">
					<input type="password" name="passwordCfm" /></td>
				</tr>
				<tr>
					<td style="width: 99px; height: 45px;"></td>
					<td style="height: 45px; width: 350px;"><input type="submit" value="Submit" /></td>
				</tr>
			</table>
		</form></div>
	</div>
	<?php include("parts_footer.php"); ?>
</div>
</body>

</html>