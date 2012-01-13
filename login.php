<?php
	session_start();
	if (isset($_SESSION["user"]) && $_SESSION["user"] !== "") {
		Header("Location:   menu.php?" . SID);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Language" content="en-US" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/frame.css" />
<title>Login</title>
<style type="text/css">
.style1 {
				text-align: right;
}
</style>
</head>

<body>
<div id="container">
	<?php include("parts_header2.php"); ?>
	<?php include("errMsg.php"); ?>
	<div id="content">
		<?php
			if (isset($_SESSION["errFlg"])) {
				$errMsg = getSessErrMsg($_SESSION["errFlg"]);
				echo "<div id='errMsg'>" . $errMsg . "</div>";
			}
		?>
		<div><form id="form1" method="post" action="checkUserInfo.php">
			<table align="center">
				<tr>
					<td style="width: 99px; height: 60px;">Email</td>
					<td style="height: 60px; width: 350px;"><input type="text" name="email" /></td>
				</tr>
				<tr>
					<td style="width: 99px; height: 60px;">Password</td>
					<td style="height: 60px; width: 350px;"><input type="password" name="password" /></td>
				</tr>
				<tr>
					<td style="width: 99px; height: 60px;"></td>
					<td class="style1" style="height: 60px; width: 350px;"><input type="submit" value="Login" /></td>
				</tr>
			</table>
		</form></div>
	</div>
	<?php include("parts_footer.php"); ?>
</div>
</body>

</html>