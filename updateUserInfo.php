<?php
	session_start();
	include("dbUser.php");
	
	if (!isset($_SESSION["user"])) {
		Header("Location:   login.php?" . SID);
		exit();
	}

	$userId = $_SESSION["user"];
	$userEmail = $_POST["email"];
	$userLastName = $_POST["last_name"];
	$userFirstName = $_POST["first_name"];
	$password = $_POST["password"];
	$passwordCfm = $_POST["passwordCfm"];
	if ($password !== $passwordCfm) {
		$_SESSION["errFlg"] = "002";
		Header("Location:   userInfo.php?" . SID);
		exit();
	}
	//$dbConnectOutput = updateUserInfo($userId, $userEmail, $userLastName, $userFirstName, str_ireplace("'", "\'", str_ireplace("\\", "\\\\", convert_uuencode($password))));
	$dbConnectOutput = updateUserInfo($userId, $userEmail, $userLastName, $userFirstName, $password);
	if ($dbConnectOutput["dc_result"] == false) {
		$_SESSION["errFlg"] = "002";
		//$_SESSION["errFlg"] = "DB";//debug;
		//$_SESSION["errMsg"] = $dbConnectOutput["dc_errMsg"];//debug;
		Header("Location:   userInfo.php?" . SID);
		exit();
	} else {
		$_SESSION["user_last_name"] = $userLastName;
		$_SESSION["user_first_name"] = $userFirstName;
		Header("Location:   menu.php?" . SID);
		exit();
	}
?>