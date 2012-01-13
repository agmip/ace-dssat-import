<?php
	
	// Judge the content type of the line
	function judgeContentType($line, $flg) {
		
		if (strpos($line, "*") === 0) {
			
			 sscanf($line, "*%s %*s", $flg[0]);
			 $flg[0] = strtolower(trim($flg[0]));
			 $flg[1] = "";
			 $flg[2] = "";
			
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
		
		//Read Exp Detail
		if ($flg[0] == "exp.details:" && $flg[2] == "") {
			sscanf($line, "%*13s %10s %60[^\^]", $ret[$flg[0]]["exname"], $ret[$flg[0]]["local_name"]);
			$ret[$flg[0]]["local_name"] = rtrim($ret[$flg[0]]["local_name"]);
			
		// read General
		} else if ($flg[0] == "general") {
			
			if ($flg[1] == "people" && $flg[2] == "data") {
				$ret[$flg[0]]["people"] = trim($line);
			} else if ($flg[1] == "address" && $flg[2] == "data") {
				$ret[$flg[0]]["address"] = trim($line);
			} else if (($flg[1] == "site" or $flg[0] == "sites") && $flg[2] == "data") {
				$ret[$flg[0]]["site"] = trim($line);
			} else if (strpos($flg[1], "parea") === 0 && $flg[2] == "data") {
				sscanf($line, "%6f %5d %5f %5d %5d %5s %5f %5d %5f %15[^\^]",
					$tmp["parea"],
					$tmp["prno"],
					$tmp["plen"],
					$tmp["pldr"],
					$tmp["plsp"],
					$tmp["play"],
					$tmp["harea"],
					$tmp["hrno"],
					$tmp["hlen"],
					$tmp["harm"]);
					$tmp["harm"] = rtrim($tmp["harm"]);
				$ret[$flg[0]]["polt_info"] = $tmp;
			} else if ($flg[1] == "notes" && $flg[2] == "data") {
				$ret[$flg[0]]["notes"] = addArray($ret[$flg[0]]["notes"], " ". trim($line)."\r\n", "");
			} else {
			}
		
		// read TREATMENTS
		} else if ($flg[0] == "treatments") {
			
			if ($flg[2] == "data") {
				
				sscanf($line, "%2d %1d %1d %1d %25[^\^] %2d %2d %2d %2d %2d %2d %2d %2d %2d %2d %2d %2d %2d",
					$tmp["trtno"],
					$tmp["rotno"],
					$tmp["rotopt"],
					$tmp["crpno"],
					$tmp["titlet"],
					$tmp["lncu"],
					$tmp["lnfld"],
					$tmp["lnsa"],
					$tmp["lnic"],
					$tmp["lnplt"],
					$tmp["lnir"],
					$tmp["lnfer"],
					$tmp["lnres"],
					$tmp["lnche"],
					$tmp["lntil"],
					$tmp["lnenv"],
					$tmp["lnhar"],
					$tmp["lnsim"]);
				$ret[$flg[0]] = addArray($ret[$flg[0]], $tmp, "");
				
			} else {
			}
			
		// read CULTIVARS	
		} else if ($flg[0] == "cultivars") {
				
			if ($flg[2] == "data") {
				sscanf($line, "%2d %2s %6s %16[^\^]",
					$tmp["lncu"],
					$tmp["cg"],
					$tmp["varno"],
					$tmp["cname"]);
					$tmp["cname"] = rtrim($tmp["cname"]);
				$ret[$flg[0]] = addArray($ret[$flg[0]], $tmp, "");
				
			} else {
			}
			
		// read FIELDS
		} else if ($flg[0] == "fields") {
				
			if (strpos($flg[1], "l id_") === 0 && $flg[2] == "data") {
				sscanf($line, "%2d %8s %8s %5s %5f %5s %5f %5f %5s %5s %5f %10s %[^\^]", $tmp["lnfld"], $tmp["fldnam"], $tmp["wsta"], $tmp["slope"], $tmp["flob"], $tmp["dfdrn"], $tmp["fldd"], $tmp["sfdrn"], $tmp["flst"], $tmp["sltx"], $tmp["sldp"], $tmp["slno"], $tmp["flname"]);
				$tmp["flname"] = rtrim($tmp["flname"]);
				$ret[$flg[0]] = addArray($ret[$flg[0]], $tmp, $tmp["lnfld"]);
				
			} else if (strpos($flg[1], "l ...") === 0 && $flg[2] == "data") {
				sscanf($line, "%2d %15f %15f %9f %17f %5f %5f %5f %5s %5f", $tmp["lnfld"], $tmp["xcrd"], $tmp["ycrd"], $tmp["elev"], $tmp["area"], $tmp["slen"], $tmp["flwr"], $tmp["slas"], $tmp["flhst"], $tmp["fhdur"]);
				$ret[$flg[0]] = mergeArray($ret[$flg[0]], $tmp, $tmp["lnfld"]);
				
			} else {
			}
			
		// SOIL ANALYSIS
		} else if ($flg[0] == "soil") {
			
			if (strpos($flg[1], "a sadat") === 0 && $flg[2] == "data") {
				sscanf($line, "%2d %5d %5s %5s %5s %[^\^]", $tmp["lnsa"], $tmp["sadat"], $tmp["smhb"], $tmp["smpx"], $tmp["smke"], $tmp["saname"]);
				$tmp["saname"] = rtrim($tmp["saname"]);
				$ret[$flg[0]] = addArray($ret[$flg[0]], $tmp, "");
				
			} else if (strpos($flg[1], "a  sabl") === 0 && $flg[2] == "data") {
				sscanf($line, "%2d %5f %5f %5f %5f %5f %5f %5f %5f %5f", $tmp["lnsa"], $tmp["sabl"], $tmp["sadm"], $tmp["saoc"], $tmp["sani"], $tmp["sahw"], $tmp["sahb"], $tmp["sapx"], $tmp["sake"], $tmp["sasc"]);
				$ret[$flg[0]."_events"] = addArray($ret[$flg[0]."_events"], $tmp, "");
				
			} else {
			}
			
		// INITIAL CONDITIONS
		} else if ($flg[0] == "initial") {
				
			if (strpos($flg[1], "c   pcr") === 0 && $flg[2] == "data") {
				sscanf($line, "%2d %5s %5d %5f %5f %5f %5f %5f %5f %5f %5f %5f %5f %[^\^]", $tmp["lnic"], $tmp["prcrop"], $tmp["idayic"], $tmp["wresr"], $tmp["wresnd"], $tmp["efinoc"], $tmp["efnfix"], $tmp["icwd"], $tmp["icres"], $tmp["icren"], $tmp["icrep"], $tmp["icrip"], $tmp["icrid"], $tmp["icname"]);
				$tmp["icname"] = rtrim($tmp["icname"]);
				$ret[$flg[0]] = addArray($ret[$flg[0]], $tmp, "");
				
			} else if (strpos($flg[1], "c  icbl") === 0 && $flg[2] == "data") {
				sscanf($line, "%2d %5f %5f %5f %5f",
					$tmp["lnic"],
					$tmp["icbl"],
					$tmp["sh20"],
					$tmp["snh4"],
					$tmp["sno3"]);
				$ret[$flg[0]."_events"] = addArray($ret[$flg[0]."_events"], $tmp, "");
				
			} else {
			}
			
		// read PLANTING DETAILS
		} else if ($flg[0] == "planting") {
				
			if ($flg[2] == "data") {
				sscanf($line, "%2d %5d %5d %5f %5f %1s %1s %5f %5f %5f %5f %5f %5f %5f %5f %[^\^]", $tmp["lnplt"], $tmp["yrplt"], $tmp["iemrg"], $tmp["plants"], $tmp["pltpop"], $tmp["plme"], $tmp["plds"], $tmp["rowspc"], $tmp["azir"], $tmp["sdepth"], $tmp["sdwtpl"], $tmp["sdage"], $tmp["atemp"], $tmp["plph"], $tmp["sprl"], $tmp["plname"]);
				$tmp["plname"] = rtrim($tmp["plname"]);
				$ret[$flg[0]] = addArray($ret[$flg[0]], $tmp, "");
				
			} else {
			}
			
		// read IRRIGATION AND WATER MANAGEMENT
		} else if ($flg[0] == "irrigation") {
				
			if (strpos($flg[1], "i  efir") === 0 && $flg[2] == "data") {
				sscanf($line, "%2d %5f %5f %5f %5f %5s %5s %5f %[^\^]", $tmp["lnir"], $tmp["effirx"], $tmp["dsoilx"], $tmp["thetcx"], $tmp["ieptx"], $tmp["ioffx"], $tmp["iamex"], $tmp["airamx"], $tmp["irname"]);
				$tmp["irname"] = rtrim($tmp["irname"]);
				$ret[$flg[0]] = addArray($ret[$flg[0]], $tmp, "");
				
			} else if (strpos($flg[1], "i idate") === 0 && $flg[2] == "data") {
				sscanf($line, "%2d %5d %5s %5f",
					$tmp["lnir"],
					$tmp["idlapl"],
					$tmp["irrcod"],
					$tmp["amt"]);
				$ret[$flg[0]."_events"] = addArray($ret[$flg[0]."_events"], $tmp, "");
				
			} else {
			}
			
		// read FERTILIZERS (INORGANIC)
		} else if ($flg[0] == "fertilizers") {
				
			if ($flg[2] == "data") {
				sscanf($line, "%2d %5d %5s %5s %5f %5f %5f %5f %5f %5f %5s %[^\^]", $tmp["lnfert"], $tmp["fday"], $tmp["iftype"], $tmp["fercod"], $tmp["dfert"], $tmp["anfer"], $tmp["apfer"], $tmp["akfer"], $tmp["acfer"], $tmp["aofer"], $tmp["focod"], $tmp["fername"]);
				$tmp["fername"] = rtrim($tmp["fername"]);
				$ret[$flg[0]] = addArray($ret[$flg[0]], $tmp, "");
				
			} else {
			}
			
		// read RESIDUES AND OTHER ORGANIC MATERIALS
		} else if ($flg[0] == "residues") {
				
			if ($flg[2] == "data") {
				sscanf($line, "%2d %5d %5s %5f %5f %5f %5f %5f %5f %5f %[^\^]", $tmp["lnres"], $tmp["resday"], $tmp["rescod"], $tmp["residue"], $tmp["resn"], $tmp["resp"], $tmp["resk"], $tmp["rinp"], $tmp["depres"], $tmp["rmet"], $tmp["rename"]);
				$tmp["rename"] = rtrim($tmp["rename"]);
				$ret[$flg[0]] = addArray($ret[$flg[0]], $tmp, "");
				
			} else {
			}
			
		// read CHEMICAL APPLICATIONS
		} else if ($flg[0] == "chemical") {
				
			if ($flg[2] == "data") {
				sscanf($line, "%2d %5d %5s %5f %5s %5s %5s %[^\^]", $tmp["lnche"], $tmp["cdate"], $tmp["chcod"], $tmp["chamt"], $tmp["chmet"], $tmp["chdep"], $tmp["cht"], $tmp["chname"]);
				$tmp["chname"] = rtrim($tmp["chname"]);
				$ret[$flg[0]] = addArray($ret[$flg[0]], $tmp, "");
				
			} else {
			}
			
		// read TILLAGE
		} else if ($flg[0] == "tillage") {
				
			if ($flg[2] == "data") {
				sscanf($line, "%2d %5d %5s %5f %[^\^]", $tmp["tl"], $tmp["tdate"], $tmp["timpl"], $tmp["tdep"], $tmp["tname"]);
				$tmp["tname"] = rtrim($tmp["tname"]);
				$ret[$flg[0]] = addArray($ret[$flg[0]], $tmp, "");
				
			} else {
			}
			
		// read ENVIRONMENT MODIFICATIONS
		} else if ($flg[0] == "environment") {
				
			if ($flg[2] == "data") {
				sscanf($line, "%2d %5d %1s%4f %1s%4f %1s%4f %1s%4f %1s%4f %1s%4f %1s%4f %1s%4f %[^\^]", $tmp["lnenv"], $tmp["wmdate"], $tmp["dayfac"], $tmp["dayadj"], $tmp["radfac"], $tmp["radadj"], $tmp["txfac"], $tmp["txadj"], $tmp["tmfac"], $tmp["tmadj"], $tmp["prcfac"], $tmp["prcadj"], $tmp["co2fac"], $tmp["co2adj"], $tmp["dptfac"], $tmp["dptadj"], $tmp["wndfac"], $tmp["wndadj"], $tmp["envname"]);
				$tmp["envname"] = rtrim($tmp["envname"]);
				$ret[$flg[0]] = addArray($ret[$flg[0]], $tmp, "");
				
			} else {
			}
			
		// read HARVEST DETAILS
		} else if ($flg[0] == "harvest") {
				
			if ($flg[2] == "data") {
				sscanf($line, "%2d %5d %5s %5s %5s %5f %5f %[^\^]", $tmp["lnhar"], $tmp["hdate"], $tmp["hstg"], $tmp["hcom"], $tmp["hsiz"], $tmp["hpc"], $tmp["hbpc"], $tmp["hname"]);
				$tmp["hname"] = rtrim($tmp["hname"]);
				$ret[$flg[0]] = addArray($ret[$flg[0]], $tmp, "");
				
			} else {
			}
			
		// read SIMULATION CONTROLS
		} else if ($flg[0] == "simulation") {
			
			// general
			if (strpos($flg[1], "n general") === 0 && $flg[2] == "data") {
				sscanf($line, "%2d %11s %2d %2d %1s %5d %5d %25[^\^] %[^\^]", $tmp["lnsim"], $tmp["titcom"], $tmp["nyrs"], $tmp["nrepsq"], $tmp["isimi"], $tmp["yrsim"], $tmp["rseed"], $tmp["titsim"], $tmp["model"]);
				$tmp["model"] = rtrim($tmp["model"]);
				$ret[$flg[0]] = addArray($ret[$flg[0]], $tmp, $tmp["lnsim"]);
			
			// options
			} else if (strpos($flg[1], "n options") === 0 && $flg[2] == "data") {
				sscanf($line, "%2d %11s %1s %1s %1s %1s %1s %1s %1s %1s %1s", $tmp["lnsim"], $tmp["titopt"], $tmp["iswwat"], $tmp["iswnit"], $tmp["iswsym"], $tmp["iswpho"], $tmp["iswpot"], $tmp["iswdis"], $tmp["chem"], $tmp["till"], $tmp["co2"]);
				$ret[$flg[0]] = mergeArray($ret[$flg[0]], $tmp, $tmp["lnsim"]);
			
			// methods
			} else if (strpos($flg[1], "n methods") === 0 && $flg[2] == "data") {
				sscanf($line, "%2d %11s %1s %1s %1s %1s %1s %1s %1s %1s %1s %1s %1s", $tmp["lnsim"], $tmp["titmet"], $tmp["mewth"], $tmp["mesic"], $tmp["meli"], $tmp["meevp"], $tmp["meinf"], $tmp["mepho"], $tmp["hydro"], $tmp["nswit"], $tmp["mesom"], $tmp["mesev"], $tmp["mesol"]);
				$ret[$flg[0]] = mergeArray($ret[$flg[0]], $tmp, $tmp["lnsim"]);
			
			// management
			} else if (strpos($flg[1], "n management") === 0 && $flg[2] == "data") {
				sscanf($line, "%2d %11s %1s %1s %1s %1s %1s",
					$tmp["lnsim"],
					$tmp["titmat"],
					$tmp["iplti"],
					$tmp["iirri"],
					$tmp["iferi"],
					$tmp["iresi"],
					$tmp["ihari"]);
				$ret[$flg[0]] = mergeArray($ret[$flg[0]], $tmp, $tmp["lnsim"]);
								
			// outputs
			} else if (strpos($flg[1], "n outputs") === 0 && $flg[2] == "data") {
				sscanf($line, "%2d %11s %1s %1s %1s %2d %1s %1s %1s %1s %1s %1s %1s %1s %1s", $tmp["lnsim"], $tmp["titout"], $tmp["iox"], $tmp["ideto"], $tmp["idets"], $tmp["frop"], $tmp["idetg"], $tmp["idetc"], $tmp["idetw"], $tmp["idetn"], $tmp["idetp"], $tmp["idetd"], $tmp["idetl"], $tmp["chout"], $tmp["opout"]);
				$ret[$flg[0]] = mergeArray($ret[$flg[0]], $tmp, $tmp["lnsim"]);
													
			// planting
			} else if (strpos($flg[1], "n planting") === 0 && $flg[2] == "data") {
				sscanf($line, "%2d %11s %5d %5d %5f %5f %5f %5f %5f",
					$tmp["lnsim"],
					$tmp["titpla"],
					$tmp["pwdinf"],
					$tmp["pwdinl"],
					$tmp["swpltl"],
					$tmp["swplth"],
					$tmp["swpltd"],
					$tmp["ptx"],
					$tmp["pttn"]);
				$ret[$flg[0]] = mergeArray($ret[$flg[0]], $tmp, $tmp["lnsim"]);
													
			// Irrigation and Water Management
			} else if (strpos($flg[1], "n irrigation") === 0 && $flg[2] == "data") {
				sscanf($line, "%2d %11s %5f %5f %5f %5s %5s %5f %5f",
					$tmp["lnsim"],
					$tmp["titirr"],
					$tmp["dsoil"],
					$tmp["thetac"],
					$tmp["iept"],
					$tmp["ioff"],
					$tmp["iame"],
					$tmp["airamt"],
					$tmp["effirr"]);
				$ret[$flg[0]] = mergeArray($ret[$flg[0]], $tmp, $tmp["lnsim"]);
													
			// Nitrogen Fertilization
			} else if (strpos($flg[1], "n nitrogen") === 0 && $flg[2] == "data") {
				sscanf($line, "%2d %11s %5f %5f %5f %5s %5s",
					$tmp["lnsim"],
					$tmp["titnit"],
					$tmp["dsoiln"],
					$tmp["soilnc"],
					$tmp["soilnx"],
					$tmp["ncode"],
					$tmp["nend"]);
				$ret[$flg[0]] = mergeArray($ret[$flg[0]], $tmp, $tmp["lnsim"]);
													
			// Residues
			} else if (strpos($flg[1], "n residues") === 0 && $flg[2] == "data") {
				sscanf($line, "%2d %11s %5f %5d %5f",
					$tmp["lnsim"],
					$tmp["titres"],
					$tmp["rip"],
					$tmp["nresdl"],
					$tmp["dresmg"]);
				$ret[$flg[0]] = mergeArray($ret[$flg[0]], $tmp, $tmp["lnsim"]);
													
			// Harvests
			} else if (strpos($flg[1], "n harvest") === 0 && $flg[2] == "data") {
				sscanf($line, "%2d %11s %5d %5d %5f %5f",
					$tmp["lnsim"],
					$tmp["tithar"],
					$tmp["hdlay"],
					$tmp["hlate"],
					$tmp["hpp"],
					$tmp["hrp"]);
				$ret[$flg[0]] = mergeArray($ret[$flg[0]], $tmp, $tmp["lnsim"]);
				
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
	
	// merge the 2nd array into the 1st array where  the position is located by id
	function mergeArray($arr, $arr2, $id) {
		
		if (isset($arr) && isset($arr2)) {
			$arr[$id] = array_merge($arr[$id], $arr2);
		} else {
			$arr[$id] = $arr2;
		}
			
		return $arr;
	}
	
	// create exp data array
	function createExpArray() {
		
		$ret["exp.details:"] = array("exname"=>"", "local_name"=>"");
		$ret["general"] = array("people"=>"", "address"=>"", "site"=>"", "polt_info"=>array("parea"=>"", "prno"=>"", "plen"=>"", "pldr"=>"", "plsp"=>"", "play"=>"", "harea"=>"", "hrno"=>"", "hlen"=>"", "harm"=>""), "notes"=>array());
		$ret["treatments"] = array();
		$ret["cultivars"] = array();
		$ret["fields"] = array();
		$ret["soil"] = array();
		$ret["soil_events"] = array();
		$ret["initial"] = array();
		$ret["initial_events"] = array();
		$ret["planting"] = array();
		$ret["irrigation"] = array();
		$ret["irrigation_events"] = array();
		$ret["fertilizers"] = array();
		$ret["residues"] = array();
		$ret["chemical"] = array();
		$ret["tillage"] = array();
		$ret["environment"] = array();
		$ret["harvest"] = array();
		$ret["simulation"] = array();
		
		return $ret;
	}
	
	// TODO: This will be deleted.
	function splitStrToArray($split, $string) {
		$token = strtok($string, $split);
		$cnt = 1;

		while ($token !== false) {
			$strs[$cnt] = $token;
			$token = strtok($split);
			$cnt++;
		}
		
		return $strs;
	}
	
	// check if there is exp id in the file. If not, add the id from file name
	function checkExpId($ret, $fileName) {
		
		if (!array_key_exists("exname", $ret["exp.details:"])) {
			$expId = substr($fileName,0,8) . substr($fileName,-2,2);
			$ret["exp.details:"]["name"] = $expId;
		}
		return $ret;
	}
	
	// check if there is coordinate data in the fields section, if not, read it from weather file
	function checkCoordinate($ret) {
		
		for ($i = 1; $i <= sizeof($ret["fields"]); $i++) {
			
			if (checkInvalidValue($ret["fields"][$i]["xcrd"]) || checkInvalidValue($ret["fields"][$i]["ycrd"]) || ($ret["fields"][$i]["xcrd"] == 0 && $ret["fields"][$i]["ycrd"] == 0)) {
				
				$wid = $ret["fields"][$i]["wsta"];
				if (!checkInvalidValue($wid)) {
					
					if (strlen($wid) <= 4) {
						$wid = $wid . substr($ret["simulation"][1]["yrsim"], 0, 2);
						$n = 0;
						$str = "01";
						while (!file_exists("template/Weather/" . $wid . $str . ".WTH") && $n <= 99) {
							$n++;
							$str = sprintf("%02d", $n);
						}
						$wid = $wid . $str;
					}
					
					if (file_exists("template/Weather/" . $wid . ".WTH")) {
						$wFile = fopen("template/Weather/" . $wid . ".WTH","r");// or exit("Fail to load coordinate Info. Invalid Weather File defined in Fields section!");
					
						$type = "";
						while(!feof($wFile) && $type != "end") {
							$line = fgets($wFile);
							if (strpos($line, "@ INSI") === 0) {
								$type = "title";
							} else if ($type == "title") {
								sscanf($line, "%s %f %f %s", $tmp1, $ret["fields"][$i]["xcrd"], $ret["fields"][$i]["ycrd"], $tmp2);
								$type = "end";
							}
						}
						fclose($wFile);
						
					} else {
						$ret["fields"][$i]["xcrd"] = "-99";
						$ret["fields"][$i]["ycrd"] = "-99";
					}
				}
			}
		}
		return $ret;
	}
	
	function checkInvalidValue($value) {
		if ($value == "" ||  $value == "-99" ||  $value == -99) {
			return true;
		} else {
			return false;
		}
	}
	
	function printArray($arr, $key, $lev) {
		
		$subKeys = array_keys($arr);

		foreach ($subKeys as $subKey) {
			
			if (gettype($arr[$subKey]) == "array") {
				for ($i = 0; $i < $lev; $i++) {
					echo "->";
				}
				echo "[" . $key . "][".$subKey."]<br />";
				printArray($arr[$subKey], $subKey, $lev+1);
			} else {
				for ($i = 0; $i < $lev; $i++) {
					echo "->";
				}
				echo "[". $subKey . "][" . $arr[$subKey] ."]<br />";
//				echo "<tr>";
//				for ($i = 0; $i < $lev; $i++) {
//					echo "<td></td>";
//				}
//				echo "<td>" . $key . "</td><td>" . $arr[$subKey] . "</td></tr>";
			}
		}
	}

?>