<?php
	
	// Judge the content type of the line
	function judgeContentType($line, $flg) {
		
		if (strpos($line, "*") === 0) {
			
			if (strpos(strtolower($line), "soils") === 1) {
				$flg[0] = "soils";
			} else {
				$flg[0] = "site";
			}
			$flg[1] = "";
			$flg[2] = "data";
			 
			
		} else if (strpos($line, "@") === 0) {
			
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
		
		//Read SOILS Info
		if ($flg[0] == "soils" && $flg[2] == "data") {
			
			$line = str_ireplace("*soils:", "", $line);
			$ret["address"] = str_ireplace("*soils :", "", $line);
			
		// read Site Info
		} else if ($flg[0] == "site") {
			
			// header info
			if ($flg[1] == "" && $flg[2] == "data") {
				
				// Create the sub array for the new soil
				$tmp = createSoilSubArray();

				// Get the info from line and save them into array
				sscanf($line, "%*1s%10s %11s %5s %5f %50[^\^]", $tmp["pedon"], $tmp["slsour"], $tmp["sltx"], $tmp["sldp"], $tmp["sldesc"]);
				$ret["site"] = addArray($ret["site"], $tmp, "");
				
			} // Site info
			else if (strpos($flg[1], "site ") === 0 && $flg[2] == "data") {
				
				// Get the current sub array
				$cnt = count($ret["site"]);
				$tmp = $ret["site"][$cnt];
				
				// Get the info from line and save them into array
				sscanf($line, " %11s %11s %8f %8f %50[^\^]", $tmp["ssite"], $tmp["scount"], $tmp["slat"], $tmp["slong"], $tmp["tacon"]);
				$ret["site"][$cnt] = $tmp;

			} // soil info
			else if (strpos($flg[1], "scom ") === 0 && $flg[2] == "data") {
				
				// Get the current sub array
				$cnt = count($ret["site"]);
				$tmp = $ret["site"][$cnt];
				
				// Get the info from line and save them into array
				sscanf($line, " %5s %5f %5f %5f %5f %5f %5f %5s %5s %5s", $tmp["scom"], $tmp["salb"], $tmp["u"], $tmp["swcon"], $tmp["cn2"], $tmp["slnf"], $tmp["slpf"], $tmp["smhb"], $tmp["smpx"], $tmp["smke"]);
				$ret["site"][$cnt] = $tmp;
				
			} // layer1 info
			else if (strpos($flg[1], "slb  slmh") === 0 && $flg[2] == "data") {
				
				// Get the current element's index in the array
				$cnt = count($ret["site"]);
				
				// Get the info from line and save them into array
				sscanf($line, " %5f %5s %5f %5f %5f %5f %5f %5f %5f %5f %5f %5f %5f %5f %5f %5f %5f", $tmp["zlyr"], $tmp["mh"], $tmp["ll"], $tmp["dul"], $tmp["sat"], $tmp["shf"], $tmp["swcn"], $tmp["bd"], $tmp["oc"], $tmp["clay"], $tmp["silt"], $tmp["stones"], $tmp["totn"], $tmp["ph"], $tmp["phkcl"], $tmp["cec"], $tmp["sadc"]);
				$ret["site"][$cnt]["layer1"] = addArray($ret["site"][$cnt]["layer1"], $tmp, "");
				
			} // layer2 info
			else if (strpos($flg[1], "slb  slpx ") === 0 && $flg[2] == "data") {
				
				// Get the current element's index in the array
				$cnt = count($ret["site"]);
				
				// Get the info from line and save them into array
				sscanf($line, " %5f %5f %5f %5f %5f %5f %5f %5f %5f %5f %5f %5f %5f %5f %5f %5f %5f", $tmp["zzlyr"], $tmp["extp"], $tmp["totp"], $tmp["orgp"], $tmp["caco"], $tmp["extal"], $tmp["extfe"], $tmp["extmn"], $tmp["totbas"], $tmp["pterma"], $tmp["ptermb"], $tmp["exk"], $tmp["exmg"], $tmp["exna"], $tmp["exts"], $tmp["slec"], $tmp["slca"]);
				$ret["site"][$cnt]["layer2"] = addArray($ret["site"][$cnt]["layer2"], $tmp, "");
				
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
	function createSoilArray() {
		
		$ret["address"] = "";
		$ret["site"] = array();
		
		return $ret;
	}
	
	// create exp data array
	function createSoilSubArray() {
		
		$ret["pedon"] = "";
		$ret["slsour"] = "";
		$ret["sltx"] = "";
		$ret["sldp"] = "";
		$ret["sldesc"] = "";
		$ret["ssite"] = "";
		$ret["scount"] = "";
		$ret["slat"] = "";
		$ret["slong"] = "";
		$ret["tacon"] = "";
		$ret["scom"] = "";
		$ret["salb"] = "";
		$ret["u"] = "";
		$ret["swcon"] = "";
		$ret["cn2"] = "";
		$ret["slnf"] = "";
		$ret["slpf"] = "";
		$ret["smhb"] = "";
		$ret["smpx"] = "";
		$ret["smke"] = "";
		$ret["layer1"] = array();
		$ret["layer2"] = array();
		
		return $ret;
	}
	
	function checkInvalidValue($value) {
		if ($value == "" ||  $value == "-99" ||  $value == -99) {
			return true;
		} else {
			return false;
		}
	}
	
	function printSoilArray($arr) {
		
		echo "[soils]......[" . $arr["address"] . "]<br/>";
		$site = $arr["site"];
		
		for ($i = 1; $i <=count($site) ;$i++) {

			echo "############## Site" . $i . " ##############<br/>";
			$subKeys = array_keys($site[$i]);
			
			foreach ($subKeys as $subKey) {
			
				if (gettype($site[$i][$subKey]) == "array") {
					
					for ($j = 1; $j<= count($site[$i][$subKey]); $j++) {
						$subArr = $site[$i][$subKey][$j];
						$subKeys2 = array_keys($subArr);
						echo "************* layer" . $j . " *************<br/>";
						foreach ($subKeys2 as $subKey2) {
							echo "[" . $subKey . "]-->[".$subKey2."]......[" . $subArr[$subKey2] . "]<br />";
						}
					}
					
				} else {
					echo "[". $subKey . "]......[" . $site[$i][$subKey] ."]<br />";
				}
			}
		}
		
	}

?>