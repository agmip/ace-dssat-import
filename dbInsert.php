<?php
	include("dbConnect.php");

	//insert the new data into DB
	function insertDB($files, $post) {
		
		// Create connection with DB
		$con = connectDB();
		
		// loop all exp files
		for ($i = 0; $i < count($files); $i++) {
			
			$ret = $files[$i];
			$expName = $ret["exp.details:"]["exname"];
			
			if (array_key_exists($expName, $post)) {
				
				// insert Exp Deatals data
				$result[$expName]["errFlg"] = false;
				$dbConnectOutput = insertExpDetail($con, $ret);
				$result[$expName]["exp.details:"] = $dbConnectOutput;
				if ($dbConnectOutput["dc_result"]) {
					$expId = $dbConnectOutput["dc_expId"];
				} else {
					$result[$expName]["errFlg"] = true;
					break;
					//die("The new data is fail to save, please check they are already saved by other people.");
				}
				 
				// insert included PlotInfo data
				$dbConnectOutput = insertPlotInfoData($con, $ret, $expId);
				$result[$expName]["polt_info"] = $dbConnectOutput;
				if($dbConnectOutput["dc_result"] === false) $result[$expName]["errFlg"] = true;
				
				// insert included cultivars data
				$dbConnectOutput = insertSectionData($con, $ret, $expId, "cultivars");
				$result[$expName]["cultivars"] = $dbConnectOutput;
				if($dbConnectOutput["dc_result"] === false) $result[$expName]["errFlg"] = true;
				
				// insert included field data
				$dbConnectOutput = insertSectionData($con, $ret, $expId, "fields");
				$result[$expName]["fields"] = $dbConnectOutput;
				if($dbConnectOutput["dc_result"] === false) $result[$expName]["errFlg"] = true;
				
				// insert included soil data
				$dbConnectOutput = insertSectionData($con, $ret, $expId, "soil");
				$result[$expName]["soil"] = $dbConnectOutput;
				if($dbConnectOutput["dc_result"] === false) $result[$expName]["errFlg"] = true;
				
				// insert included soil events data
				$dbConnectOutput = insertSectionData($con, $ret, $expId, "soil_events");
				$result[$expName]["soil_events"] = $dbConnectOutput;
				if($dbConnectOutput["dc_result"] === false) $result[$expName]["errFlg"] = true;
				
				// insert included initial condition data
				$dbConnectOutput = insertSectionData($con, $ret, $expId, "initial");
				$result[$expName]["initial"] = $dbConnectOutput;
				if($dbConnectOutput["dc_result"] === false) $result[$expName]["errFlg"] = true;
				
				// insert included initial condition levels data
				$dbConnectOutput = insertSectionData($con, $ret, $expId, "initial_events");
				$result[$expName]["initial_events"] = $dbConnectOutput;
				if($dbConnectOutput["dc_result"] === false) $result[$expName]["errFlg"] = true;
				
				// insert included planting data
				$dbConnectOutput = insertSectionData($con, $ret, $expId, "planting");
				$result[$expName]["planting"] = $dbConnectOutput;
				if($dbConnectOutput["dc_result"] === false) $result[$expName]["errFlg"] = true;
				
				// insert included irrigation data
				$dbConnectOutput = insertSectionData($con, $ret, $expId, "irrigation");
				$result[$expName]["irrigation"] = $dbConnectOutput;
				if($dbConnectOutput["dc_result"] === false) $result[$expName]["errFlg"] = true;
				
				// insert included irrigation events data
				$dbConnectOutput = insertSectionData($con, $ret, $expId, "irrigation_events");
				$result[$expName]["irrigation_events"] = $dbConnectOutput;
				if($dbConnectOutput["dc_result"] === false) $result[$expName]["errFlg"] = true;
				
				// insert included fertilizers data
				$dbConnectOutput = insertSectionData($con, $ret, $expId, "fertilizers");
				$result[$expName]["fertilizers"] = $dbConnectOutput;
				if($dbConnectOutput["dc_result"] === false) $result[$expName]["errFlg"] = true;
				
				// insert included residues organic material data
				$dbConnectOutput = insertSectionData($con, $ret, $expId, "residues");
				$result[$expName]["residues"] = $dbConnectOutput;
				if($dbConnectOutput["dc_result"] === false) $result[$expName]["errFlg"] = true;
				
				// insert included chemical data
				$dbConnectOutput = insertSectionData($con, $ret, $expId, "chemical");
				$result[$expName]["chemical"] = $dbConnectOutput;
				if($dbConnectOutput["dc_result"] === false) $result[$expName]["errFlg"] = true;
				
				// insert included tillage data
				$dbConnectOutput = insertSectionData($con, $ret, $expId, "tillage");
				$result[$expName]["tillage"] = $dbConnectOutput;
				if($dbConnectOutput["dc_result"] === false) $result[$expName]["errFlg"] = true;
				
				// insert included environment modification data
				$dbConnectOutput = insertSectionData($con, $ret, $expId, "environment");
				$result[$expName]["environment"] = $dbConnectOutput;
				if($dbConnectOutput["dc_result"] === false) $result[$expName]["errFlg"] = true;
				
				// insert included harvest data
				$dbConnectOutput = insertSectionData($con, $ret, $expId, "harvest");
				$result[$expName]["harvest"] = $dbConnectOutput;
				if($dbConnectOutput["dc_result"] === false) $result[$expName]["errFlg"] = true;
				
				// insert simulation controll data
				
				
				// insert treatment data
				for ($j = 1; $j <= count($ret["treatments"]); $j++) {
					
					// only insert checked treatment data
					$trno = $ret["treatments"][$j]["trtno"];
					$key = array_search($trno, $post[$expName]);
					if($key !== false) {
						
						// insert treatment data
						$dbConnectOutput = insertTreatment($con, $ret, $j, $expId);
						$result[$expName]["treatment"] = $dbConnectOutput;
						if($dbConnectOutput["dc_result"] === false) $result[$expName]["errFlg"] = true;
					}
				}
			}

		}
		mysql_close($con);
		return $result;
	}
	
	// insert new exp detail data and return the new exp_id
	function insertExpDetail($con, $ret) {
		$DBParas = getDBParas();
		$dc_db = $DBParas["db"];
		
		$notes = "";
		for ($j = 1; $j <= count($ret["general"]["notes"]); $j++) {
			$notes = $notes.$ret["general"]["notes"][$j]."\r\n"; // TODO "\r\n" is not fulfilled with json format
		}
		
		$dbConnectInput["dc_sql"] = "INSERT INTO `". $dc_db ."`.`experimental_descrips` (`exname`, `local_name`, `people`, `address`, `site`, `play`, `notes`) VALUES ('".$ret["exp.details:"]["exname"]."', '".$ret["exp.details:"]["local_name"]."', '".$ret["general"]["people"]."', '".$ret["general"]["address"]."', '".$ret["general"]["site"]."', '".$ret["general"]["polt_info"]["play"]."', '". $notes ."')";

		$dbConnectInput["dc_sql_type"] = "insert";
		$dbConnectInput["dc_process_name"] = "";
		$dbConnectOutput = excuteSql($con, $dbConnectInput);
		//echo "[".$dbConnectInput["dc_sql"]."]<br />";
		//echo "Result: [".$dbConnectOutput["dc_result"]."]<br />";
		$expId = mysql_insert_id();
		//echo "ID [".$expId."]<br />";
		$dbConnectOutput["dc_expId"] = $expId;
		return $dbConnectOutput;
	}
	
	// insert new exp detail data and return the new exp_id
	function insertPlotInfoData($con, $ret, $expId) {
		
		$DBParas = getDBParas();
		$dc_db = $DBParas["db"];
		
		if ($ret["general"]["polt_info"]["parea"] != "" ||
			$ret["general"]["polt_info"]["prno"] != "" ||
			$ret["general"]["polt_info"]["plen"] != "" ||
			$ret["general"]["polt_info"]["pldr"] != "" ||
			$ret["general"]["polt_info"]["plsp"] != "" ||
			$ret["general"]["polt_info"]["play"] != "" ||
			$ret["general"]["polt_info"]["harea"] != "" ||
			$ret["general"]["polt_info"]["hrno"] != "" ||
			$ret["general"]["polt_info"]["hlen"] != "" ||
			$ret["general"]["polt_info"]["harm"] != "") {

			$dbConnectInput["dc_sql"] = "INSERT INTO `". $dc_db ."`.`plot_info` (`exp_id`, `parea`, `prno`, `plen`, `pldr`, `plsp`, `pltha`, `hrno`, `hlen`, `plthm`) VALUES ('". $expId ."', '".$ret["general"]["polt_info"]["parea"]."', '".$ret["general"]["polt_info"]["prno"]."', '".$ret["general"]["polt_info"]["plen"]."', '".$ret["general"]["polt_info"]["pldr"]."', '".$ret["general"]["polt_info"]["plsp"]."', '".$ret["general"]["polt_info"]["harea"]."', '".$ret["general"]["polt_info"]["hrno"]."', '".$ret["general"]["polt_info"]["hlen"]."', '".$ret["general"]["polt_info"]["harm"]."')";
			$dbConnectInput["dc_sql_type"] = "insert";
			$dbConnectInput["dc_process_name"] = "";
			$dbConnectOutput = excuteSql($con, $dbConnectInput);
			//echo "[".$dbConnectInput["dc_sql"]."]<br />";
			//echo "Result: [".$dbConnectOutput["dc_result"]."]<br />";
		} else {
			$dbConnectOutput["dc_result"] = 0;
			$dbConnectOutput["dc_sql"] = "";
			$dbConnectOutput["dc_errMsg"] = "There is no plot info data be included.";
		}
		return $dbConnectOutput;
	}
	
	// insert treatment data
	function insertTreatment($con, $ret, $i, $expId) {
		$DBParas = getDBParas();
		$dc_db = $DBParas["db"];
		
		$ge = $ret["treatments"][$i]["lncu"];
		$fl = $ret["treatments"][$i]["lnfld"];
		$sa = $ret["treatments"][$i]["lnsa"];
		$ic = $ret["treatments"][$i]["lnic"];
		$pl = $ret["treatments"][$i]["lnplt"];
		$ir = $ret["treatments"][$i]["lnir"];
		$fe = $ret["treatments"][$i]["lnfer"];
		$om = $ret["treatments"][$i]["lnres"];
		$ch = $ret["treatments"][$i]["lnche"];
		$ti = $ret["treatments"][$i]["lntil"];
		$em = $ret["treatments"][$i]["lnenv"];
		$ha = $ret["treatments"][$i]["lnhar"];
		$sm = $ret["treatments"][$i]["lnsim"];
		$keys = "`exp_id`, `trno`, `sq`, `op`, `co`, `tr_name`";
		$values = "'". $expId ."', '".$ret["treatments"][$i]["trtno"]."', '".$ret["treatments"][$i]["rotno"]."', '".$ret["treatments"][$i]["rotopt"]."', '".$ret["treatments"][$i]["crpno"]."', '".$ret["treatments"][$i]["titlet"]."'";
		
		// Check level_id of each section to find if it is set by 0 (not include related section data)
		if($ge != 0) {
			$keys = $keys.", `ge`";
			$values = $values. ", '". $ge . "'";
		}
		if($fl != 0) {
			$keys = $keys.", `fl`";
			$values = $values. ", '". $fl . "'";
		}
		if($sa != 0) {
			$keys = $keys.", `sa`";
			$values = $values. ", '". $sa . "'";
		}
		if($ic != 0) {
			$keys = $keys.", `ic`";
			$values = $values. ", '". $ic . "'";
		}
		if($pl != 0) {
			$keys = $keys.", `pl`";
			$values = $values. ", '". $pl . "'";
		}
		if($ir != 0) {
			$keys = $keys.", `ir`";
			$values = $values. ", '". $ir . "'";
		}
		if($fe != 0) {
			$keys = $keys.", `fe`";
			$values = $values. ", '". $fe . "'";
		}
		if($om != 0) {
			$keys = $keys.", `om`";
			$values = $values. ", '". $om . "'";
		}
		if($ch != 0) {
			$keys = $keys.", `ch`";
			$values = $values. ", '". $ch . "'";
		}
		if($ti != 0) {
			$keys = $keys.", `ti`";
			$values = $values. ", '". $ti . "'";
		}
		if($em != 0) {
			$keys = $keys.", `em`";
			$values = $values. ", '". $em . "'";
		}
		if($ha != 0) {
			$keys = $keys.", `ha`";
			$values = $values. ", '". $ha . "'";
		}
		if($sm != 0) {
			$keys = $keys.", `sm`";
			$values = $values. ", '". $sm . "'";
		}
		
		$dbConnectInput["dc_sql"] = "INSERT INTO `". $dc_db ."`.`treatments` (" . $keys . ") VALUES (" . $values . ")";
		$dbConnectInput["dc_sql_type"] = "insert";
		$dbConnectInput["dc_process_name"] = "";
		$dbConnectOutput = excuteSql($con, $dbConnectInput);
		//echo "[".$dbConnectInput["dc_sql"]."]<br />";
		//echo "Result: [".$dbConnectOutput["dc_result"]."]<br />";
		return $dbConnectOutput;
	}
	
	// insert section data except treatment and Exp details
	function insertSectionData($con, $ret, $expId, $tableName) {
		$DBParas = getDBParas();
		$dc_db = $DBParas["db"];
		
		$dbConnectOutput["dc_result"] = 0;
		$dbConnectOutput["dc_errmsg"] = "There is no ". $tableName ." data be included.";
		$preSecId = 0;
		$cnt = 1;
		if (count($ret[$tableName]) < 1) {
			$result["dc_result"] = 0;
			$result["dc_sql"] = "";
		}
		
		for($i = 1; $i <= count($ret[$tableName]);$i++) {
			
			if ($tableName == "cultivars") {
				
				$dbConnectInput["dc_sql"] = "INSERT INTO `". $dc_db ."`.`genotypes` (`exp_id`, `ge`, `cr`, `cul_id`, `cul_name`) VALUES ('". $expId ."', '".$ret["cultivars"][$i]["lncu"]."', '".$ret["cultivars"][$i]["cg"]."', '".$ret["cultivars"][$i]["varno"]."', '".$ret["cultivars"][$i]["cname"]."')";
				
			} else if ($tableName == "fields") {
				
				$dbConnectInput["dc_sql"] = "INSERT INTO `". $dc_db ."`.`fields` (`exp_id`, `fl`, `id_field`, `wsta_id`, `flsl`, `flob`, `fl_drntype`, `fldrd`, `fldrs`, `flst`, `sltx`, `sldp`, `soil_id`, `fl_name`, `fl_lat`, `fl_long`, `flele`, `farea`, `fllwr`, `flsla`, `flhst`, `fhdur`) VALUES ('". $expId ."', '".$ret["fields"][$i]["lnfld"]."', '".$ret["fields"][$i]["fldnam"]."', '".$ret["fields"][$i]["wsta"]."', '".$ret["fields"][$i]["slope"]."', '".$ret["fields"][$i]["flob"]."', '".$ret["fields"][$i]["dfdrn"]."', '".$ret["fields"][$i]["fldd"]."', '".$ret["fields"][$i]["sfdrn"]."', '".$ret["fields"][$i]["flst"]."', '".$ret["fields"][$i]["sltx"]."', '".$ret["fields"][$i]["sldp"]."', '".$ret["fields"][$i]["slno"]."', '".$ret["fields"][$i]["flname"]."', '".$ret["fields"][$i]["xcrd"]."', '".$ret["fields"][$i]["ycrd"]."', '".$ret["fields"][$i]["elev"]."', '".$ret["fields"][$i]["area"]."', '".$ret["fields"][$i]["flwr"]."', '".$ret["fields"][$i]["slas"]."', '".$ret["fields"][$i]["flhst"]."', '".$ret["fields"][$i]["fhdur"]."')";
				
			} else if ($tableName == "soil") {
				
				$sadat = changeDays2Date($ret["soil"][$i]["sadat"]);
				$dbConnectInput["dc_sql"] = "INSERT INTO `". $dc_db ."`.`soil_analyses_levels` (`exp_id`, `sa`, `sadat`, `samhb`, `sampx`, `samke`, `sa_name`) VALUES ('". $expId ."', '".$ret["soil"][$i]["lnsa"]."', '".$sadat."', '".$ret["soil"][$i]["smhb"]."', '".$ret["soil"][$i]["smpx"]."', '".$ret["soil"][$i]["smke"]."', '".$ret["soil"][$i]["saname"]."')";
				
			} else if ($tableName == "soil_events") {
				
				$sa_appl_no = $i - 1;
				$dbConnectInput["dc_sql"] = "INSERT INTO `". $dc_db ."`.`soil_analyses_events` (`exp_id`, `sa_appl_no`, `sa`, `sabl`, `sabdm`, `saoc`, `sani`, `saphw`, `saphb`, `sapx`, `sake`, `sasc`) VALUES ('". $expId ."', '". $sa_appl_no ."', '".$ret["soil_events"][$i]["lnsa"]."', '".$ret["soil_events"][$i]["sabl"]."', '".$ret["soil_events"][$i]["sadm"]."', '".$ret["soil_events"][$i]["saoc"]."', '".$ret["soil_events"][$i]["sani"]."', '".$ret["soil_events"][$i]["sahw"]."', '".$ret["soil_events"][$i]["sahb"]."', '".$ret["soil_events"][$i]["sapx"]."', '".$ret["soil_events"][$i]["sake"]."', '".$ret["soil_events"][$i]["sasc"]."')";
				
			} else if ($tableName == "initial") {
				
				$icdat = changeDays2Date($ret["initial"][$i]["idayic"]);
				$dbConnectInput["dc_sql"] = "INSERT INTO `". $dc_db ."`.`initial_condition_levels` (`exp_id`, `ic`, `icpcr`, `icdat`, `icrt`, `icnd`, `icrzno`, `icrze`, `icwt`, `icrag`, `icrn`, `icrp`, `icrip`, `icrdp`, `ic_name`) VALUES ('". $expId ."', '".$ret["initial"][$i]["lnic"]."', '".$ret["initial"][$i]["prcrop"]."', '".$icdat."', '".$ret["initial"][$i]["wresr"]."', '".$ret["initial"][$i]["wresnd"]."', '".$ret["initial"][$i]["efinoc"]."', '".$ret["initial"][$i]["efnfix"]."', '".$ret["initial"][$i]["icwd"]."', '".$ret["initial"][$i]["icres"]."', '".$ret["initial"][$i]["icren"]."', '".$ret["initial"][$i]["icrep"]."', '".$ret["initial"][$i]["icrip"]."', '".$ret["initial"][$i]["icrid"]."', '".$ret["initial"][$i]["icname"]."')";
				
			} else if ($tableName == "initial_events") {
				
				$ic_layer = $i - 1;
				$dbConnectInput["dc_sql"] = "INSERT INTO `". $dc_db ."`.`initial_condition_events` (`exp_id`, `ic_layer`, `ic`, `icbl`, `ich2o`, `icnh4`, `icno3`) VALUES ('". $expId ."', '". $ic_layer ."', '".$ret["initial_events"][$i]["lnic"]."', '".$ret["initial_events"][$i]["icbl"]."', '".$ret["initial_events"][$i]["sh20"]."', '".$ret["initial_events"][$i]["snh4"]."', '".$ret["initial_events"][$i]["sno3"]."')";
				
			} else if ($tableName == "planting") {
				
				$pdate = changeDays2Date($ret["planting"][$i]["yrplt"]);
				$pldae = changeDays2Date($ret["planting"][$i]["iemrg"]);
				$dbConnectInput["dc_sql"] = "INSERT INTO `". $dc_db ."`.`plantings` (`exp_id`, `pl`, `pdate`, `pldae`, `plpop`, `plpoe`, `plme`, `plds`, `plrs`, `plrd`, `pldp`, `plmwt`, `page`, `penv`, `plph`, `plspl`, `pl_name`) VALUES ('". $expId ."', '".$ret["planting"][$i]["lnplt"]."', '".$pdate."', '".$pldae."', '".$ret["planting"][$i]["plants"]."', '".$ret["planting"][$i]["pltpop"]."', '".$ret["planting"][$i]["plme"]."', '".$ret["planting"][$i]["plds"]."', '".$ret["planting"][$i]["rowspc"]."', '".$ret["planting"][$i]["azir"]."', '".$ret["planting"][$i]["sdepth"]."', '".$ret["planting"][$i]["sdwtpl"]."', '".$ret["planting"][$i]["sdage"]."', '".$ret["planting"][$i]["atemp"]."', '".$ret["planting"][$i]["plph"]."', '".$ret["planting"][$i]["sprl"]."', '".$ret["planting"][$i]["plname"]."')";
				
			} else if ($tableName == "irrigation") {
				
				$dbConnectInput["dc_sql"] = "INSERT INTO `". $dc_db ."`.`irrigation_levels` (`exp_id`, `ir`, `ireff`, `irmdp`, `irthr`, `irept`, `irstg`, `iame`, `iamt`, `ir_name`) VALUES ('". $expId ."', '".$ret["irrigation"][$i]["lnir"]."', '".$ret["irrigation"][$i]["effirx"]."', '".$ret["irrigation"][$i]["dsoilx"]."', '".$ret["irrigation"][$i]["thetcx"]."', '".$ret["irrigation"][$i]["ieptx"]."', '".$ret["irrigation"][$i]["ioffx"]."', '".$ret["irrigation"][$i]["iamex"]."', '".$ret["irrigation"][$i]["airamx"]."', '".$ret["irrigation"][$i]["irname"]."')";
				
			} else if ($tableName == "irrigation_events") {
				
				$idate = changeDays2Date($ret["irrigation_events"][$i]["idlapl"]);
				$ir_app_no = $i - 1;
				$dbConnectInput["dc_sql"] = "INSERT INTO `". $dc_db ."`.`irrigation_events` (`exp_id`, `ir_app_no`, `ir`, `idate`, `irop`, `irval`) VALUES ('". $expId ."', '". $ir_app_no ."', '".$ret["irrigation_events"][$i]["lnir"]."', '".$idate."', '".$ret["irrigation_events"][$i]["irrcod"]."', '".$ret["irrigation_events"][$i]["amt"]."')";
				
			} else if ($tableName == "fertilizers") {
				
				if ($ret["fertilizers"][$i]["lnfert"] != $preSecId) {
					$dbConnectInput["dc_sql"] = "INSERT INTO `". $dc_db ."`.`fertilizer_levels` (`exp_id`, `fe`, `fe_name`) VALUES ('". $expId ."', '".$ret["fertilizers"][$i]["lnfert"]."', '".$ret["fertilizers"][$i]["fername"]."')";
					$dbConnectInput["dc_sql_type"] = "insert";
					$dbConnectInput["dc_process_name"] = "";
					$dbConnectOutput = excuteSql($con, $dbConnectInput);
					$preSecId = $ret["fertilizers"][$i]["lnfert"];
					//echo "[".$dbConnectInput["dc_sql"]."]<br />";
					//echo "Result: [".$dbConnectOutput["dc_result"]."]<br />";
					$result["dc_sql"][$cnt] = $dbConnectOutput["dc_sql"];
					$result["dc_result"][$cnt] = $dbConnectOutput["dc_result"];
					$cnt++;
				}
				$fdate = changeDays2Date($ret["fertilizers"][$i]["fday"]);
				$fe = $preSecId;
				$fe_appl_no = $i - 1;
				$dbConnectInput["dc_sql"] = "INSERT INTO `". $dc_db ."`.`fertilizer_events` (`exp_id`, `fe`, `fe_appl_no`, `fdate`, `fecd`, `feacd`, `fedep`, `feamn`, `feamp`, `feamk`, `feamc`, `feamo`, `feocd`) VALUES ('". $expId ."', '". $fe ."', '". $fe_appl_no ."', '".$fdate."', '".$ret["fertilizers"][$i]["iftype"]."', '".$ret["fertilizers"][$i]["fercod"]."', '".$ret["fertilizers"][$i]["dfert"]."', '".$ret["fertilizers"][$i]["anfer"]."', '".$ret["fertilizers"][$i]["apfer"]."', '".$ret["fertilizers"][$i]["akfer"]."', '".$ret["fertilizers"][$i]["acfer"]."', '".$ret["fertilizers"][$i]["aofer"]."', '".$ret["fertilizers"][$i]["focod"]."')";

			} else if ($tableName == "residues") {
				
				if ($ret["residues"][$i]["lnres"] != $preSecId) {
					$dbConnectInput["dc_sql"] = "INSERT INTO `". $dc_db ."`.`organic_material_levels` (`exp_id`, `om`, `om_name`) VALUES ('". $expId ."', '".$ret["residues"][$i]["lnres"]."', '".$ret["residues"][$i]["rename"]."')";
					$dbConnectInput["dc_sql_type"] = "insert";
					$dbConnectInput["dc_process_name"] = "";
					$dbConnectOutput = excuteSql($con, $dbConnectInput);
					$preSecId = $ret["residues"][$i]["lnres"];
					//echo "[".$dbConnectInput["dc_sql"]."]<br />";
					//echo "Result: [".$dbConnectOutput["dc_result"]."]<br />";
					$result["dc_sql"][$cnt] = $dbConnectOutput["dc_sql"];
					$result["dc_result"][$cnt] = $dbConnectOutput["dc_result"];
					$cnt++;
				}
				$omdat = changeDays2Date($ret["residues"][$i]["resday"]);
				$om = $preSecId;
				$om_ops_no = $i - 1;
				$dbConnectInput["dc_sql"] = "INSERT INTO `". $dc_db ."`.`organic_material_events` (`exp_id`, `om`, `om_ops_no`, `omdat`, `omcd`, `omamt`, `omnpct`, `omppct`, `omkpct`, `ominp`, `omdep`, `omacd`) VALUES ('". $expId ."', '". $om ."', '". $om_ops_no ."', '".$omdat."', '".$ret["residues"][$i]["rescod"]."', '".$ret["residues"][$i]["residue"]."', '".$ret["residues"][$i]["resn"]."', '".$ret["residues"][$i]["resp"]."', '".$ret["residues"][$i]["resk"]."', '".$ret["residues"][$i]["rinp"]."', '".$ret["residues"][$i]["depres"]."', '".$ret["residues"][$i]["rmet"]."')";
				
			} else if ($tableName == "chemical") {
				
				if ($ret["chemical"][$i]["lnche"] != $preSecId) {
					$dbConnectInput["dc_sql"] = "INSERT INTO `". $dc_db ."`.`chemical_levels` (`exp_id`, `ch`, `ch_name`) VALUES ('". $expId ."', '".$ret["chemical"][$i]["lnche"]."', '".$ret["chemical"][$i]["chname"]."')";
					$dbConnectInput["dc_sql_type"] = "insert";
					$dbConnectInput["dc_process_name"] = "";
					$dbConnectOutput = excuteSql($con, $dbConnectInput);
					$preSecId = $ret["chemical"][$i]["lnche"];
					//echo "[".$dbConnectInput["dc_sql"]."]<br />";
					//echo "Result: [".$dbConnectOutput["dc_result"]."]<br />";
					$result["dc_sql"][$cnt] = $dbConnectOutput["dc_sql"];
					$result["dc_result"][$cnt] = $dbConnectOutput["dc_result"];
					$cnt++;
				}
				$cdate = changeDays2Date($ret[$tableName][$i]["cdate"]);
				$ch = $preSecId;
				$ch_appl_no = $i - 1;
				$dbConnectInput["dc_sql"] = "INSERT INTO `". $dc_db ."`.`chemical_events` (`exp_id`, `ch`, `ch_appl_no`, `cdate`, `chcd`, `chamt`, `chacd`, `chdep`, `ch_targets`) VALUES ('". $expId ."', '". $ch ."', '". $ch_appl_no ."', '".$cdate."', '".$ret["chemical"][$i]["chcod"]."', '".$ret["chemical"][$i]["chamt"]."', '".$ret["chemical"][$i]["chmet"]."', '".$ret["chemical"][$i]["chdep"]."', '".$ret["chemical"][$i]["cht"]."')";
				
			} else if ($tableName == "tillage") {
				
				if ($ret["tillage"][$i]["tl"] != $preSecId) {
					$dbConnectInput["dc_sql"] = "INSERT INTO `". $dc_db ."`.`tillage_levels` (`exp_id`, `ti`, `ti_name`) VALUES ('". $expId ."', '".$ret["tillage"][$i]["tl"]."', '".$ret["tillage"][$i]["tname"]."')";
					$dbConnectInput["dc_sql_type"] = "insert";
					$dbConnectInput["dc_process_name"] = "";
					$dbConnectOutput = excuteSql($con, $dbConnectInput);
					$preSecId = $ret["tillage"][$i]["tl"];
					//echo "[".$dbConnectInput["dc_sql"]."]<br />";
					//echo "Result: [".$dbConnectOutput["dc_result"]."]<br />";
					$result["dc_sql"][$cnt] = $dbConnectOutput["dc_sql"];
					$result["dc_result"][$cnt] = $dbConnectOutput["dc_result"];
					$cnt++;
				}
				$tdate = changeDays2Date($ret[$tableName][$i]["tdate"]);
				$ti = $preSecId;
				$ti_ops_no = $i - 1;
				$dbConnectInput["dc_sql"] = "INSERT INTO `". $dc_db ."`.`tillage_events` (`exp_id`, `ti`, `ti_ops_no`, `tdate`, `tiimp`, `tidep`) VALUES ('". $expId ."', '". $ti ."', '". $ti_ops_no ."', '".$tdate."', '".$ret["tillage"][$i]["timpl"]."', '".$ret["tillage"][$i]["tdep"]."')";
				
			} else if ($tableName == "environment") {
				
				if ($ret["environment"][$i]["lnenv"] != $preSecId) {
					$dbConnectInput["dc_sql"] = "INSERT INTO `". $dc_db ."`.`environ_modif_levels` (`exp_id`, `em`, `em_name`) VALUES ('". $expId ."', '".$ret["environment"][$i]["lnenv"]."', '".$ret["environment"][$i]["envname"]."')";
					$dbConnectInput["dc_sql_type"] = "insert";
					$dbConnectInput["dc_process_name"] = "";
					$dbConnectOutput = excuteSql($con, $dbConnectInput);
					$preSecId = $ret["environment"][$i]["lnenv"];
					//echo "[".$dbConnectInput["dc_sql"]."]<br />";
					//echo "Result: [".$dbConnectOutput["dc_result"]."]<br />";
					$result["dc_sql"][$cnt] = $dbConnectOutput["dc_sql"];
					$result["dc_result"][$cnt] = $dbConnectOutput["dc_result"];
					$cnt++;
				}
				$emday = changeDays2Date($ret[$tableName][$i]["wmdate"]);
				$em = $preSecId;
				$env_lev_no = $i - 1;
				$dbConnectInput["dc_sql"] = "INSERT INTO `". $dc_db ."`.`environ_modif_events` (`exp_id`, `em`, `env_lev_no`, `emday`, `ecdyl`, `emdyl`, `ecrad`, `emrad`, `ecmax`, `emmax`, `ecmin`, `emmin`, `ecrai`, `emrai`, `ecco2`, `emco2`, `ecdew`, `emdew`, `ecwnd`, `emwnd`) VALUES ('". $expId ."', '". $em ."', '". $env_lev_no ."', '".$emday."', '".$ret["environment"][$i]["dayfac"]."', '".$ret["environment"][$i]["dayadj"]."', '".$ret["environment"][$i]["radfac"]."', '".$ret["environment"][$i]["radadj"]."', '".$ret["environment"][$i]["txfac"]."', '".$ret["environment"][$i]["txadj"]."', '".$ret["environment"][$i]["tmfac"]."', '".$ret["environment"][$i]["tmadj"]."', '".$ret["environment"][$i]["prcfac"]."', '".$ret["environment"][$i]["prcadj"]."', '".$ret["environment"][$i]["co2fac"]."', '".$ret["environment"][$i]["co2adj"]."', '".$ret["environment"][$i]["dptfac"]."', '".$ret["environment"][$i]["dptadj"]."', '".$ret["environment"][$i]["wndfac"]."', '".$ret["environment"][$i]["wndadj"]."')";
				
			} else if ($tableName == "harvest") {
				
				if ($ret["harvest"][$i]["lnhar"] != $preSecId) {
					$dbConnectInput["dc_sql"] = "INSERT INTO `". $dc_db ."`.`harvest_levels` (`exp_id`, `ha`, `ha_name`) VALUES ('". $expId ."', '".$ret["harvest"][$i]["lnhar"]."', '".$ret["harvest"][$i]["hname"]."')";
					$dbConnectInput["dc_sql_type"] = "insert";
					$dbConnectInput["dc_process_name"] = "";
					$dbConnectOutput = excuteSql($con, $dbConnectInput);
					$preSecId = $ret["harvest"][$i]["lnhar"];
					//echo "[".$dbConnectInput["dc_sql"]."]<br />";
					//echo "Result: [".$dbConnectOutput["dc_result"]."]<br />";
					$result["dc_sql"][$cnt] = $dbConnectOutput["dc_sql"];
					$result["dc_result"][$cnt] = $dbConnectOutput["dc_result"];
					$cnt++;
				}
				$haday = changeDays2Date($ret[$tableName][$i]["hdate"]);
				$ha = $preSecId;
				$ha_ops_no = $i - 1;
				$dbConnectInput["dc_sql"] = "INSERT INTO `". $dc_db ."`.`harvest_events` (`exp_id`, `ha`, `ha_ops_no`, `haday`, `hastg`, `hacom`, `hasiz`, `hapc`, `habpc`) VALUES ('". $expId ."', '". $ha ."', '". $ha_ops_no ."', '".$haday."', '".$ret["harvest"][$i]["hstg"]."', '".$ret["harvest"][$i]["hcom"]."', '".$ret["harvest"][$i]["hsiz"]."', '".$ret["harvest"][$i]["hpc"]."', '".$ret["harvest"][$i]["hbpc"]."')";
				
			} else {
				return array("dc_result" => false);
			}
			
			$dbConnectInput["dc_sql_type"] = "insert";
			$dbConnectInput["dc_process_name"] = "";
			$dbConnectOutput = excuteSql($con, $dbConnectInput);
			//echo "[".$dbConnectInput["dc_sql"]."]<br />";
			//echo "Result: [".$dbConnectOutput["dc_result"]."]<br />";
			
			$result["dc_sql"][$cnt] = $dbConnectOutput["dc_sql"];
			$result["dc_result"][$cnt] = $dbConnectOutput["dc_result"];
			$cnt++;
		}
		
		return $result;
	}
	
	function changeDays2Date($dayStr) {
		if ($dayStr != -99 && $dayStr != "-99") {
			$year = floor($dayStr/1000);
			$day = $dayStr%1000;
			return date("Y-m-d",mktime(0,0,0,1,$day,$year));
		} else {
//			return "00000000";
			return "null";
		}
	}
?>