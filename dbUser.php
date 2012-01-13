<?php
	
	include("dbConnect.php");
	
	// check if there is record match the input email and passowrd, return the record if valid
	function checkLogin($email, $password) {
	
		// Create connection with DB
		$con = connectDB();
		
		//$dbConnectInput["dc_sql"] = "SELECT * FROM users WHERE email = '" . $email . "' AND password='" . convert_uuencode($password) . "'";
		$dbConnectInput["dc_sql"] = "SELECT * FROM users WHERE email = '" . $email . "' AND password='" . $password . "'";
		
		$dbConnectInput["dc_sql_type"] = "select";
		$dbConnectInput["dc_process_name"] = "";
		$dbConnectOutput = excuteSql($con, $dbConnectInput);
		
		mysql_close($con);
		return $dbConnectOutput;
	}
	
	// check if there is record match the input user_id, return the record if valid
	function getUserInfo($userId) {
	
		// Create connection with DB
		$con = connectDB();
		
		$dbConnectInput["dc_sql"] = "SELECT * FROM users WHERE user_id = '" . $userId . "'";
	
		$dbConnectInput["dc_sql_type"] = "select";
		$dbConnectInput["dc_process_name"] = "";
		$dbConnectOutput = excuteSql($con, $dbConnectInput);
		
		mysql_close($con);
		return $dbConnectOutput;
	}
	
	function updateUserInfo($userId, $email, $lastName, $firstName, $password) {
		
		// Create connection with DB
		$con = connectDB();
		
		if ($password === "") {
			$passStr = "";
		} else {
			$passStr = ", password = '" . $password . "'";
		}
		
		$dbConnectInput["dc_sql"] = "UPDATE users SET first_name='" . $firstName . "', last_name = '" . $lastName . "', email = '" . $email . "'" . $passStr . " WHERE user_id = '" . $userId . "'";
	
		$dbConnectInput["dc_sql_type"] = "update";
		$dbConnectInput["dc_process_name"] = "";
		$dbConnectOutput = excuteSql($con, $dbConnectInput);
		
		mysql_close($con);
		return $dbConnectOutput;
	}
		
		
?>