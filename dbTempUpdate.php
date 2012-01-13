<?php
	include("dbConnect.php");
	
	function getTempData() {
		
		// Create connection with DB
		$con = connectDB();
		
		$dbConnectInput["dc_sql"] = "SELECT * FROM temp_file WHERE user_id = '" . $_SESSION["user"] . "' AND status <> 0";
		
		$dbConnectInput["dc_sql_type"] = "select";
		$dbConnectInput["dc_process_name"] = "";
		$dbConnectOutput = excuteSql($con, $dbConnectInput);
		
		mysql_close($con);
		return $dbConnectOutput;
	}
	
	function deleteTempData($inputId) {
		
		// Create connection with DB
		$con = connectDB();
		
		$dbConnectInput["dc_sql"] = "UPDATE temp_file SET status = 0 WHERE user_id = '" . $_SESSION["user"] . "' AND input_id = " . $inputId;
		
		$dbConnectInput["dc_sql_type"] = "update";
		$dbConnectInput["dc_process_name"] = "";
		$dbConnectOutput = excuteSql($con, $dbConnectInput);
		
		mysql_close($con);
		return $dbConnectOutput;
	}
	
	function insertTempFile($fileFormat) {
		
		// Create connection with DB
		$con = connectDB();
		
		$dbConnectInput["dc_sql"] = "INSERT INTO temp_file (`user_id`, `update_date`, `file_format`) VALUES ('" . $_SESSION["user"] ."', now(), '" . $fileFormat . "')";
		
		$dbConnectInput["dc_sql_type"] = "insert";
		$dbConnectInput["dc_process_name"] = "";
		$dbConnectOutput = excuteSql($con, $dbConnectInput);
		$input_id = mysql_insert_id();
		
		mysql_close($con);
		return $input_id;
	}
	
	function updateTempFileExpName($exname) {
		
		// Create connection with DB
		$con = connectDB();
		
		$dbConnectInput["dc_sql"] = "UPDATE temp_file SET exname = '" . $exname . "' WHERE input_id = '" . $_SESSION["input_id"] . "'";
		
		$dbConnectInput["dc_sql_type"] = "update";
		$dbConnectInput["dc_process_name"] = "";
		$dbConnectOutput = excuteSql($con, $dbConnectInput);
		
		mysql_close($con);
		return $dbConnectOutput;
	}
	
	function updateTempFile($jasonStr, $fileType) {
		
		// Create connection with DB
		$con = connectDB();
		if ($fileType == "X") {
			$dbConnectInput["dc_sql"] = "DELETE FROM temp_file_detail WHERE input_id = '" . $_SESSION["input_id"] . "'";;
			
			$dbConnectInput["dc_sql_type"] = "delete";
			$dbConnectInput["dc_process_name"] = "";
			$dbConnectOutput = excuteSql($con, $dbConnectInput);
		}
		
		$dbConnectInput["dc_sql"] = "INSERT INTO temp_file_detail VALUES ('" . $_SESSION["input_id"] . "', '" . $fileType . "', '" . mysql_real_escape_string($jasonStr) . "') ON DUPLICATE KEY UPDATE file_content = '" . mysql_real_escape_string($jasonStr) . "'";	
		$dbConnectInput["dc_sql_type"] = "insert";
		$dbConnectInput["dc_process_name"] = "";
		$dbConnectOutput = excuteSql($con, $dbConnectInput);
		
		if ($fileType == "X") {
			$dbConnectInput["dc_sql"] = "UPDATE temp_file SET step_no = 2 WHERE input_id = '" . $_SESSION["input_id"] . "'";
		} else if ($fileType == "S") {
			$dbConnectInput["dc_sql"] = "UPDATE temp_file SET step_no = 3 WHERE input_id = '" . $_SESSION["input_id"] . "' AND step_no <= 2";
		} else if ($fileType == "W") {
			$dbConnectInput["dc_sql"] = "UPDATE temp_file SET step_no = 4 WHERE input_id = '" . $_SESSION["input_id"] . "' AND step_no <= 3";
		} else if ($fileType == "O") {
			$dbConnectInput["dc_sql"] = "UPDATE temp_file SET step_no = 5 WHERE input_id = '" . $_SESSION["input_id"] . "' AND step_no <= 4";
		} else if ($fileType == "U") {
			$dbConnectInput["dc_sql"] = "UPDATE temp_file SET step_no = 6 WHERE input_id = '" . $_SESSION["input_id"] . "' AND step_no <= 5";
		} else {
			$dbConnectInput["dc_sql"] = "UPDATE temp_file SET step_no = 1 WHERE input_id = '" . $_SESSION["input_id"] . "'";
		}
		$dbConnectInput["dc_sql_type"] = "update";
		$dbConnectInput["dc_process_name"] = "";
		$dbConnectOutput = excuteSql($con, $dbConnectInput);
		
		mysql_close($con);
		return $dbConnectOutput;
	}
	
	function saveTempFile() {
		
		// Create connection with DB
		$con = connectDB();
		
		$dbConnectInput["dc_sql"] = "UPDATE temp_file SET status = 2 WHERE input_id = '" . $_SESSION["input_id"] . "'";	
		$dbConnectInput["dc_sql_type"] = "insert";
		$dbConnectInput["dc_process_name"] = "";
		$dbConnectOutput = excuteSql($con, $dbConnectInput);
		
		mysql_close($con);
		return $dbConnectOutput;
	}
	
	function readTempFile($inputId, $fileType) {
		
		// Create connection with DB
		$con = connectDB();
		
		$dbConnectInput["dc_sql"] = "SELECT file_content FROM temp_file_detail tfd WHERE tfd.input_id = '" . $inputId . "' AND tfd.file_type = '" . $fileType . "' AND tfd.input_id IN (SELECT tf.input_id FROM temp_file tf WHERE user_id = '" . $_SESSION["user"] . "')";
		
		$dbConnectInput["dc_sql_type"] = "select";
		$dbConnectInput["dc_process_name"] = "";
		$dbConnectOutput = excuteSql($con, $dbConnectInput);
		
		mysql_close($con);
		
		if ($dbConnectOutput["dc_result"] == 0) {
			return "";
		} else {
			return $dbConnectOutput["dc_result"][0]["file_content"];
		}
	}
	
	function readTempFileJson($fileType, $rtnFlg) {
		
		if (isset($_GET["inputId"])) {
			$jsonStr = readTempFile($_GET["inputId"], $fileType);
		} else if (isset($_SESSION["input_id"])) {
			$jsonStr = readTempFile($_SESSION["input_id"], $fileType);
		} else {
			$jsonStr = "";
		}
		
		if ($jsonStr == "" ) {
			if ($rtnFlg) {
				clearDssatSession();
				header("Location:   inputFiles00.php?" . SID);
				exit();
			} else {
				return "";
			}
		} else {
			if (isset($_GET["inputId"])) {
				$_SESSION["input_id"] = $_GET["inputId"];
			}
			return json_decode($jsonStr, true);
		}
	}
	
	function checkUploadStatus() {
		
		$con = connectDB();
		
		$dbConnectInput["dc_sql"] = "SELECT file_type, file_content FROM temp_file_detail WHERE input_id = '" . $_SESSION["input_id"] . "'";
		
		$dbConnectInput["dc_sql_type"] = "select";
		$dbConnectInput["dc_process_name"] = "";
		$dbConnectOutput = excuteSql($con, $dbConnectInput);
		
		mysql_close($con);
		
		$ret = array();
		if ($dbConnectOutput["dc_result"] != 0) {
			for ($i = 0; $i < count($dbConnectOutput["dc_result"]); $i++) {
				$files = json_decode($dbConnectOutput["dc_result"][$i]["file_content"], true);
				$ret[$dbConnectOutput["dc_result"][$i]["file_type"]] = array();
				for ($j = 0; $j < count($files); $j++) {
					if (isset($files[$j]["file_name"])) {
						$ret[$dbConnectOutput["dc_result"][$i]["file_type"]][$j] = $files[$j]["file_name"];
					} else {
						$ret[$dbConnectOutput["dc_result"][$i]["file_type"]][$j] = "";
					}
				}
			}
		}
		
		return $ret;
	}
	
	function checkSoilAvailable($ret, &$checkRet) {
		
		$flg = true;
		$fields = $ret["fields"];
		$checkRet = array();
		
		for($i=1; $i <= count($fields); $i++) {
			
			if (!checkSoilAvailableById($fields[$i]["slno"])) {
				$checkRet[$i - 1] = false;
				$flg = false;
			} else {
				$checkRet[$i - 1] = true;
			}	
		}
		
		return $flg;
	}
	
	function checkSoilAvailableById($sid) {
		
		$con = connectDB();
		$sql = "SELECT sid FROM soil_profiles WHERE soil_id = ";
		$dbConnectInput["dc_sql"] = $sql . "'" . $sid . "'";
		$dbConnectInput["dc_sql_type"] = "select";
		$dbConnectInput["dc_process_name"] = "";
		$dbConnectOutput = excuteSql($con, $dbConnectInput);
		
		if ($dbConnectOutput["dc_result"] === 0) {
			return false;
		} else {
			return true;
		}
	}
	
	function checkWthAvailable($ret, &$checkRet) {

		$checkRet = array();
		
		// check date used in each treatment
		for ($i = 1; $i <= count($ret["treatments"]); $i++) {
		
			$fl = $ret["treatments"][$i]["lnfld"];

			if ($fl != 0) {
				
				$wid = substr($ret["fields"][$fl]["wsta"], 0, min(strlen($ret["fields"][$fl]["wsta"]), 4));
				
				// Set a result set for each wsta(inclued in field)
				if (!array_key_exists($wid, $checkRet)) {
					$checkRet[$wid]["start"] = 116000;
					$checkRet[$wid]["end"] = 0;
					$checkRet[$wid]["wid"] = $wid;
				}
				
				// Set date range in the result set
				// If any date is earlier than simulation start date, then do sth.
				setDate($ret["simulation"], "yrsim", $checkRet[$wid]["start"], $checkRet[$wid]["end"], $ret["treatments"][$i]["lnsim"]);
				setDate($ret["soil"], "sadat", $checkRet[$wid]["start"], $checkRet[$wid]["end"], $ret["treatments"][$i]["lnsa"]);
				setDate($ret["initial"], "idayic", $checkRet[$wid]["start"], $checkRet[$wid]["end"], $ret["treatments"][$i]["lnic"]);
				setDate($ret["planting"], "yrplt", $checkRet[$wid]["start"], $checkRet[$wid]["end"], $ret["treatments"][$i]["lnplt"]);
				setDate($ret["planting"], "iemrg", $checkRet[$wid]["start"], $checkRet[$wid]["end"], $ret["treatments"][$i]["lnplt"]);
				setDate2($ret["irrigation_events"], "idlapl", $checkRet[$wid]["start"], $checkRet[$wid]["end"]);
				setDate($ret["fertilizers"], "fday", $checkRet[$wid]["start"], $checkRet[$wid]["end"], $ret["treatments"][$i]["lnfer"]);
				setDate($ret["residues"], "resday", $checkRet[$wid]["start"], $checkRet[$wid]["end"], $ret["treatments"][$i]["lnres"]);
				setDate($ret["chemical"], "cdate", $checkRet[$wid]["start"], $checkRet[$wid]["end"], $ret["treatments"][$i]["lnche"]);
				setDate($ret["tillage"], "tdate", $checkRet[$wid]["start"], $checkRet[$wid]["end"], $ret["treatments"][$i]["lntil"]);
				setDate($ret["environment"], "wmdate", $checkRet[$wid]["start"], $checkRet[$wid]["end"], $ret["treatments"][$i]["lnenv"]);
				setDate($ret["harvest"], "hdate", $checkRet[$wid]["start"], $checkRet[$wid]["end"], $ret["treatments"][$i]["lnhar"]);
				setDate($ret["simulation"], "pwdinf", $checkRet[$wid]["start"], $checkRet[$wid]["end"], $ret["treatments"][$i]["lnsim"]);
				setDate($ret["simulation"], "pwdinl", $checkRet[$wid]["start"], $checkRet[$wid]["end"], $ret["treatments"][$i]["lnsim"]);
				
			}
		}

		$con = connectDB();
		$sql = "SELECT count(wd.wid) AS wid_cnt FROM weather_daily wd WHERE ";
		$sql2 = " AND wid in (SELECT ws.wid FROM weather_sources ws WHERE wsta_id = ";
		$flg = true;
		
		foreach ($checkRet as &$wth) {
			
			$sYear = substr($wth["start"], 0, -3);
			if ($sYear == "") $sYear = "00";
			else if (strlen($sYear) == 1) $sYear = "0".$sYear;
			$sDay = substr($wth["start"], -3);
			$eYear = substr($wth["end"], 0, -3);
			if ($eYear == "") $eYear = "00";
			else if (strlen($eYear) == 1) $eYear = "0".$eYear;
			$eDay = substr($wth["end"], -3);
			
			$dbConnectInput["dc_sql"] = $sql . " wtyr BETWEEN " . $sYear . " AND " . $eYear . " OR (wtyr = " . $sYear . " AND wtday >= " . $sDay . " ) OR (wtyr = " . $eYear . " AND wtday <= " . $eDay . " )" . $sql2 . "'" . $wth["wid"] ."')";
			
			$dbConnectInput["dc_sql_type"] = "select";
			$dbConnectInput["dc_process_name"] = "";
			$dbConnectOutput = excuteSql($con, $dbConnectInput);
			
			// Not available in DB
			if ($dbConnectOutput["dc_result"][0]["wid_cnt"] == 0) {
				$wth["dbStatus"] = -1;
				$flg = false;
				
			// fully available in DB
			} else if (countDays($sYear, $sDay, $eYear, $eDay) <= $dbConnectOutput["dc_result"][0]["wid_cnt"]) {
				$wth["dbStatus"] = 1;
			
			// partly available in DB
			} else {
				$wth["dbStatus"] = 0;
				$flg = false;
			}
		}
		return $flg;
	}
	
	function setDate($arr, $key, &$startDate, &$endDate, $i) {
		if ($i != 0 && !checkInvalidValue($arr[$i][$key])) {
			//echo "start [" . $startDate . "]\tend [" . $endDate . "]\tvalue [" . $key . "][" . $arr[$i][$key]. "]<br/>";
			if (compareDate($startDate, $arr[$i][$key])) {
				$startDate = $arr[$i][$key];
			} else if (compareDate($arr[$i][$key], $endDate)) {
				$endDate = $arr[$i][$key];
			}
		}
	}
	
	function compareDate($v1, $v2) {
		if ($v1 < 16000) $v1 = $v1 + 100000; // TODO
		if ($v2 < 16000) $v2 = $v2 + 100000; // TODO
		return $v1 > $v2;
	}
	
	
	function setDate2($arr, $key, &$startDate, &$endDate) {
		
		for ($i = 1; $i <= count($arr); $i++) {
			setDate($arr, $key, $startDate, $endDate, $i);
		}
	}
	
	function countDays($sYear, $sDay, $eYear, $eDay) {
		$ret = 0;
		if ($sYear < 16) $sYear += 100; // TODO
		if ($eYear < 16) $eYear += 100; // TODO
		$ret = ($eYear - $sYear) * 365 - ($sDay - 1) + $eDay + floor(($eYear - $sYear) / 4);
		if ($sYear % 4 ==0 && $sDay <= 60) $ret++;
		if ($eYear % 4 ==0 && $eDay >= 60) $ret++;
		return $ret;
	}
	
	function clearDssatSession() {
		unset($_SESSION["input_id"]);
		unset($_SESSION["dssat_steps"]);
	}
?>