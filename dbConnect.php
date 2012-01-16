<?php

	// Define DB connection parameters
	function getDBParas() {
		$dc_paras["host"]="localhost";
		$dc_paras["db"]="ageng_agmip";
		$dc_paras["user"]="root";
		$dc_paras["pass"]="";
		
		return  $dc_paras;
	}
	
	// Create DB connection
	function connectDB() {
		$DBParas = getDBParas();
		$dc_host = $DBParas["host"];
		$dc_db = $DBParas["db"];
		$dc_user = $DBParas["user"];
		$dc_pass = $DBParas["pass"];
		
		$con = mysql_connect($dc_host, $dc_user, $dc_pass);
		
		if (!$con) {
			die('Could not connect 01: ' . mysql_error());
		}
			
		return $con;
	}
	
	// run with single SQL statement
	function excuteSql($con, $dbConnectInput) {
		
		$DBParas = getDBParas();
		$dc_db = $DBParas["db"];
		
		if (!mysql_select_db($dc_db, $con)){
			die('Could not connect 02: ' . mysql_error());
		}

		$dc_sql = str_ireplace("'null'", "null", $dbConnectInput["dc_sql"]);
		$result = mysql_query($dc_sql);
		if ($dbConnectInput["dc_sql_type"] == "select") {
	
			$dbConnectOutput["dc_process_name"] = $dbConnectInput["dc_process_name"];
			$dbConnectOutput["dc_result_num"] = mysql_num_rows($result);
			
			if (mysql_num_rows($result) > 0) {
				$j = 0;
				while ($row = mysql_fetch_array($result)) {
					$dc_result[$j] = $row;
					$j++;
				}
				$dbConnectOutput["dc_result"] = $dc_result;
			} else {
				$dbConnectOutput["dc_result"] = 0;
			}

		} else {
			
			$dbConnectOutput["dc_result"] = $result;
			$dbConnectOutput["dc_sql"] = $dbConnectInput["dc_sql"];
			$dbConnectOutput["dc_errMsg"] = mysql_error();

		}
		
		return $dbConnectOutput;
	}
	
	// run with mulitple SQL statement
	function excuteSqls($con, $dbConnectInput) {
		
		$DBParas = getDBParas();
		$dc_db = $DBParas["db"];
		
		if (!mysql_select_db($dc_db, $con)){
			die('Could not connect 02: ' . mysql_error());
		}
		
		for ($i=0; $i <count($dbConnectInput); $i++) {
			
			$dc_sql = $dbConnectInput[$i]["dc_sql"];
			$result = mysql_query($dc_sql);
			if ($dbConnectInput[$i]["dc_sql_type"] == "select") {
		
				$dbConnectOutput[$i]["dc_process_name"] = $dbConnectInput[$i]["dc_process_name"];
				$dbConnectOutput[$i]["dc_result_num"] = mysql_num_rows($result);
				
				$j = 0;
				while ($row = mysql_fetch_array($result)) {
					$dc_result[$j] = $row;
					$j++;
				}
				$dbConnectOutput[$i]["dc_result"] = $dc_result;
	
			} else {
				
				$dbConnectOutput[$i]["dc_result"] = $result;
				$dbConnectOutput[$i]["dc_sql"] = $dbConnectInput[$i]["dc_sql"];
				$dbConnectOutput[$i]["dc_errMsg"] = mysql_error();
			
			}
		}
	}
?>
