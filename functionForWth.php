<?php
	
	// Judge the content type of the line
	function judgeContentType($line, $flg) {
		
		if (strpos($line, "*") === 0) {
			
			if (strpos(strtolower($line), "weather") === 1) {
				$flg[0] = "weather";
			} else {
				$flg[0] = "site";
			}
			$flg[1] = "";
			$flg[2] = "data";
			 
			
		} else if (strpos($line, "@") === 0) {
			
			$flg[0] = "site";
			$flg[1] = strtolower(trim(substr($line, 1)));
			$flg[2] = "";
			
		} else if (strpos($line, "!") === 0) {
			
			$flg[2] = "comment";
			
		} else if (trim($line) !== "") {
			
			$flg[2] = "data";
			
		} else if ($flg[2] !== "blank") {
			
			$flg[1] = "";
			$flg[2] = "blank";
			
		} else {
			
			$flg[0] = "";
			$flg[1] = "";
			$flg[2] = "blank";
		}

		return $flg;
	}
	
	// split one line content into array
	function getSpliterResult($flg, $line, $ret) {
		
		//Read Weather Info
		if ($flg[0] == "weather" && $flg[2] == "data") {
			
			$line = str_ireplace("*soils:", "", $line);
			$ret["address"] = str_ireplace("*weather data : ", "", $line);
			
		// read Site Info
		} else if ($flg[0] == "site") {
			
			if (strpos($flg[1], "insi ") === 0 && $flg[2] == "data") {
				
				sscanf($line, " %2s%2s %8f %8f %5f %5f %5f %5f %5f", $ret["inste"], $ret["sitee"], $ret["xlat"], $ret["xlong"], $ret["elev"], $ret["tav"], $ret["tamp"], $ret["refht"], $ret["wndht"]);
					
			} else if (strpos($flg[1], "date ") === 0 && $flg[2] == "data") {
				
				$tmp = createWthSubArray(); // For the situation that some files don't contain all the field
				sscanf($line, "%5d %5f %5f %5f %5f %5f %5f %5f", $tmp["yrdoyw"], $tmp["srad"], $tmp["tmax"], $tmp["tmin"], $tmp["rain"], $tmp["tdew"], $tmp["windsp"], $tmp["par"]);
				$ret["daily"] = addArray($ret["daily"], $tmp, "");
				
			} else {
			}
		} else {
		}
		
		return $ret;
	}
	
	// Add the input value into the array where the position is located by id
	function addArray($arr, $value, $id) {

		// if id is not pointed out, then add into the end of array
		if ($id == "") {
			if (isset($arr)) {
				$arr[count($arr)+1] = $value;
			} else {
				$arr[1] = $value;
			}
			
		// if id is pointed out, then add into the located position
		} else {
			$arr[$id] = $value;
		}
		return $arr;
	}
	
	// create exp data array
	function createWthArray() {
		
		$ret["address"] = "";
		$ret["inste"] = "";
		$ret["sitee"] = "";
		$ret["xlat"] = "";
		$ret["xlong"] = "";
		$ret["elev"] = "";
		$ret["tav"] = "";
		$ret["tamp"] = "";
		$ret["refht"] = "";
		$ret["wndht"] = "";
		$ret["daily"] = array();
		
		return $ret;
	}
	
	// create exp data array
	function createWthSubArray() {
		
		$ret["yrdoyw"] = "";
		$ret["srad"] = "";
		$ret["tmax"] = "";
		$ret["tmin"] = "";
		$ret["rain"] = "";
		$ret["tdew"] = "";
		$ret["windsp"] = "";
		$ret["par"] = "";
		
		return $ret;
	}
	
	function checkInvalidValue($value) {
		if ($value == "" ||  $value == "-99" ||  $value == -99) {
			return true;
		} else {
			return false;
		}
	}
	
	function printWthArray($arr) {
		
		$keys = array_keys($arr);
		foreach ($keys as $key) {
			
			if (gettype($arr[$key]) == "array") {
				
				// print title
				$subArr = $arr[$key][1];
				$subKeys = array_keys($subArr);
				echo "<table><tr>";
				foreach ($subKeys as $subKey) {
						echo "<td>" . $subKey . "</td>";
				}
				echo "</tr>";
				
				// print daily data
				for ($i = 1; $i<= count($arr[$key]); $i++) {
					echo "<tr>";
					foreach ($subKeys as $subKey) {
						echo "<td>" . $subArr[$subKey] . "</td>";
					}
					echo "</tr>";
				}
				echo "</table>";
				
			} else {
				echo "[". $key . "]......[" . $arr[$key] ."]<br />";
			}
		}
		
	}

?>