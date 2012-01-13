<?php
	session_start();
//	if (!isset($_SESSION["user"])) {
//		Header("Location:   login.php?" . SID);
//	}

	include("dbUser.php");
	
	$email = $_POST["email"];
	$password = $_POST["password"];
	$dbConnectOutput = checkLogin($email, $password);
	
	// check if the input data is valid
	if ($dbConnectOutput["dc_result_num"] === 1) {
		$lastName = $dbConnectOutput["dc_result"][0]["last_name"];
		$firstName = $dbConnectOutput["dc_result"][0]["first_name"];
		$userId = $dbConnectOutput["dc_result"][0]["user_id"];
	}
	
	// Check if the input info is same to the data in DB
	if (isset($lastName) && isset($firstName)) {
		
		// set user name into session
		$_SESSION["user"] = $userId;
		$_SESSION["user_last_name"] = $lastName;
		$_SESSION["user_first_name"] = $firstName;
		
	} else {
		
		// Login error msg set into session
		$_SESSION["errFlg"] = "001";
		Header("Location:   login.php?" . SID );
		exit();
	}
	
	// Check if the password is blank which means it needs user to fill the whole personal info
	if ($password === "" || $lastName === "") {
		
		// Go to userInfo page
		$_SESSION["user_last_name"] = $email;
		$_SESSION["user_first_name"] = "";
		Header("Location:   userInfo.php?" . SID);
		exit();
	} else {
		
		// ALL OK, go to menu page
		Header("Location:   menu.php?" . SID);
		exit();
	}

?>