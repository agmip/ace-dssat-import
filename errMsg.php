<?php

	// clean session flg when get message back
	function getSessErrMsg($errFlg) {

		unset($_SESSION["errFlg"]);
		return getErrMsg($errFlg);
	}
	
	// return error message by erro flg code
	function getErrMsg($errFlg) {
		
		if (isset($_SESSION["errMsg"])) {
			$errMsg = $_SESSION["errMsg"];
		} else {
			$errMsg = "";
		}
		
		// Defination of Error Messages
		$defaultMsg = "Error Code Wrong, Please contact the Administrator!";
		$errMsgs = Array(
			"001" => "Email address or Password is incorrect, please try again.",
			"002" => "Update Faile, please try again later or continue to <a href='menu.php'>menu</a> page.",
			"003" => "Unknown format selected, please choose another one and try again.",
			"004" => "There is a necessary data not contained in the uploaded file.",
			"005" => "There is a experiment with no treatment be selected.",
			"DB" => $errMsg
		);
		
		// return message if code valid
		if (isset($errMsgs[$errFlg])) {
			return "[" . $errFlg . "] " . $errMsgs[$errFlg];
		} else {
			return "[" . $errFlg . "] " . $defaultMsg;
		}
		
	} 
?>