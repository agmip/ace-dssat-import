<?php
	include("dbConnect.php");
	
	// return all records of experiments
	function getDataListAll() {
		
		// Create connection with DB
		$con = connectDB();
		
		$dbConnectInput["dc_sql"] = "SELECT exp.exp_id, exp.exname, tr.trno, tr.tr_name FROM experimental_descrips as exp, treatments as tr WHERE exp.exp_id = tr.exp_id ORDER BY exp.exp_id DESC, tr.trno";

		$dbConnectInput["dc_sql_type"] = "select";
		$dbConnectInput["dc_process_name"] = "";
		$dbConnectOutput = excuteSql($con, $dbConnectInput);
		//echo "[".$dbConnectInput["dc_sql"]."]<br />";
		//echo "Result: [".$dbConnectOutput["dc_result"]."]<br />";
		
		mysql_close($con);
		return $dbConnectOutput;
	}
	
	// return the record of experiments by exp_id
	function getDataListById($expId) {
		
		// Create connection with DB
		$con = connectDB();
		
		$dbConnectInput["dc_sql"] = "SELECT exp.exp_id, exp.exname, tr.trno, tr.tr_name FROM experimental_descrips as exp, treatments as tr WHERE exp.exp_id = '" . $expId . "' AND exp.exp_id = tr.exp_id ORDER BY exp.exp_id DESC, tr.trno";

		$dbConnectInput["dc_sql_type"] = "select";
		$dbConnectInput["dc_process_name"] = "";
		$dbConnectOutput = excuteSql($con, $dbConnectInput);
		//echo "[".$dbConnectInput["dc_sql"]."]<br />";
		//echo "Result: [".$dbConnectOutput["dc_result"]."]<br />";
		
		mysql_close($con);
		return $dbConnectOutput;
	}
	
	// get X-file's name by pointed exp_id
	function getXFileName($exp_id) {
		
		// Create connection with DB
		$con = connectDB();
		
		$dbConnectInput["dc_sql_type"] = "select";
		$dbConnectInput["dc_process_name"] = "";
		
		// get the X-file name
		$dbConnectInput["dc_sql"] = "SELECT exname FROM experimental_descrips WHERE exp_id = '" . $exp_id . "'";
		$dbConnectOutput = excuteSql($con, $dbConnectInput);
		
		// check result if there is one and only one record match the exp_id
		if($dbConnectOutput["dc_result_num"] === 1) {
			$fileName = $dbConnectOutput["dc_result"][0]["exname"];
			$fileNameExt = substr($fileName, -2) . "X";
			$fileName = substr($fileName, 0, strlen($fileName)-2);
		} else {
			return "";
		}
		
		mysql_close($con);
		return $fileName . "." . $fileNameExt;
	}
	
	// create X-file by pointed exp_id
	function writeFile($exp_id, $trnos) {
		
		// Create connection with DB
		$con = connectDB();
		
		// Write Header of the X-file
		writeHeader($con, $exp_id);

		// Write Treatment section of the X-file
		writeTreatment($con, $exp_id, $trnos);

		// Write CULTIVARS section of the X-file
		writeCultivars($con, $exp_id);

		// Write FIELDS section of the X-file
		writeFields($con, $exp_id);

		// Write SOIL ANALYSIS section of the X-file
		writeSoil($con, $exp_id);
		
		// Write INITIAL CONDITIONS section of the X-file
		writeInintial($con, $exp_id);
		
		// Write PLANTING DETAILS section of the X-file
		writePlanting($con, $exp_id);

		// Write IRRIGATION AND WATER MANAGEMENT section of the X-file
		writeIrrigation($con, $exp_id);

		// Write FERTILIZERS (INORGANIC) section of the X-file
		writeFertilizers($con, $exp_id);

		// Write RESIDUES AND ORGANIC FERTILIZER section of the X-file
		writeResidues($con, $exp_id);

		// Write CHEMICAL APPLICATIONS section of the X-file
		writeChemical($con, $exp_id);
		
		// Write TILLAGE AND ROTATIONS section of the X-file
		writeTillage($con, $exp_id);

		// Write ENVIRONMENT MODIFICATIONS section of the X-file
		writeEnvMdf($con, $exp_id);

		// Write HARVEST DETAILS section of the X-file
		writeHarvest($con, $exp_id);
		
		// Write Simulation section of the X-file
		writeSimulation("default");
		
		mysql_close($con);
	}
	
	// Write Header of the X-file
	function writeHeader($con, $exp_id) {
		
		$dbConnectInput["dc_sql_type"] = "select";
		$dbConnectInput["dc_process_name"] = "";
		
		// Write EXP.DETAILS and GENERALS section
		$dbConnectInput["dc_sql"] = "SELECT * FROM experimental_descrips WHERE exp_id = '" . $exp_id . "'";
		$dbConnectOutput = excuteSql($con, $dbConnectInput);
		$ret = $dbConnectOutput["dc_result"];
		
		// format the output data
		$exname = formatStr(" %10s", $ret[0]["exname"]);
		$local_name = " " . str_pad($ret[0]["local_name"], 60);
		$people = " " . str_pad($ret[0]["people"], 75);
		$address = " " . str_pad($ret[0]["address"], 75);
		$site = " " . str_pad($ret[0]["site"], 75);
		$notes = $ret[0]["notes"];
		$play = formatStr(" %5s", $ret[0]["play"]);

		// output EXP.DETAILS and GENERALS section
		echo "*EXP.DETAILS:$exname$local_name\r\n\r\n";
		echo "*GENERAL\r\n@PEOPLE\r\n$people\r\n@ADDRESS\r\n$address\r\n@SITE\r\n$site\r\n";
		
		// Write Plot Infor sub-section
		$dbConnectInput["dc_sql"] = "SELECT * FROM plot_info WHERE exp_id = '" . $exp_id . "'";
		$dbConnectOutput = excuteSql($con, $dbConnectInput);
		$ret = $dbConnectOutput["dc_result"];

		// Check if there is plot info data
		if ($dbConnectOutput["dc_result_num"] >= 1) {
			
			// format plot info data
			$parea = formatStr(" %6.1f", $ret[0]["parea"]);
			$prno = formatStr(" %5d", $ret[0]["prno"]);
			$plen = formatStr(" %5.1f", $ret[0]["plen"]);
			$pldr = formatStr(" %5d", $ret[0]["pldr"]);
			$plsp = formatStr(" %5d", $ret[0]["plsp"]);
			$pltha = formatStr(" %5.1f", $ret[0]["pltha"]);
			$hrno = formatStr(" %5d", $ret[0]["hrno"]);
			$hlen = formatStr(" %5.1f", $ret[0]["hlen"]);
			$plthm = " " . str_pad($ret[0]["plthm"], 15);

			// output plot info section
			echo "@ PAREA  PRNO  PLEN  PLDR  PLSP  PLAY HAREA  HRNO  HLEN  HARM.........\r\n";
			echo $parea . $prno . $plen . $pldr . $plsp . $play . $pltha . $hrno . $hlen . $plthm . "\r\n";

		}
		
		// Write notes // TODO: UTF-7 code needed
		if (trim($notes) != "") {
			echo "@NOTES\r\n";
			echo $notes;
		}
//		while (trim($notes) != "") {
//			$subNote = substr($notes, 0, 75);
//			$notes = substr($notes, 75);
//			echo " " . $subNote . "\r\n";
//		}
		
		echo "\r\n";
	}
	
	// Write TREATMENTS section of the X-file
	function writeTreatment($con, $exp_id, $trnos) {
		
		$dbConnectInput["dc_sql_type"] = "select";
		$dbConnectInput["dc_process_name"] = "";
		
		// Write TREATMENTS section
		$dbConnectInput["dc_sql"] = "SELECT * FROM treatments WHERE exp_id = '" . $exp_id . "'";
		$dbConnectOutput = excuteSql($con, $dbConnectInput);
		$ret = $dbConnectOutput["dc_result"];

		// write title
		if ($dbConnectOutput["dc_result_num"] > 0) {
			echo "*TREATMENTS                        -------------FACTOR LEVELS------------\r\n";
			echo "@N R O C TNAME.................... CU FL SA IC MP MI MF MR MC MT ME MH SM\r\n";
		}
		
		// write content
		for ($i = 0; $i < $dbConnectOutput["dc_result_num"]; $i++) {
			
			// check if this treatment is checked by user when download
			$key = array_search($ret[$i]["trno"], $trnos);
			if($key !== false) {
				
				// format the output data
				$trno = formatStr("%2d", $ret[$i]["trno"]);
				$sq = formatStr(" %1d", $ret[$i]["sq"]);
				$op = formatStr(" %1d", $ret[$i]["op"]);
				$co = formatStr(" %1d", $ret[$i]["co"]);
				$tr_name = " " . str_pad($ret[$i]["tr_name"], 25);
				$ge = formatStr(" %2d", $ret[$i]["ge"]);
				$fl = formatStr(" %2d", $ret[$i]["fl"]);
				$sa = formatStr(" %2d", $ret[$i]["sa"]);
				$ic = formatStr(" %2d", $ret[$i]["ic"]);
				$pl = formatStr(" %2d", $ret[$i]["pl"]);
				$ir = formatStr(" %2d", $ret[$i]["ir"]);
				$fe = formatStr(" %2d", $ret[$i]["fe"]);
				$om = formatStr(" %2d", $ret[$i]["om"]);
				$ch = formatStr(" %2d", $ret[$i]["ch"]);
				$ti = formatStr(" %2d", $ret[$i]["ti"]);
				$em = formatStr(" %2d", $ret[$i]["em"]);
				$ha = formatStr(" %2d", $ret[$i]["ha"]);
				$sm = formatStr(" %2d", $ret[$i]["sm"]);
	
				echo $trno . $sq . $op . $co . $tr_name . $ge . $fl . $sa . $ic . $pl . $ir . $fe . $om . $ch . $ti . $em . $ha . $sm . "\r\n";
			}
		}
		
		echo "\r\n";
	}
	
	// Write CULTIVARS section of the X-file
	function writeCultivars($con, $exp_id) {
		
		$dbConnectInput["dc_sql_type"] = "select";
		$dbConnectInput["dc_process_name"] = "";
		
		// Write CULTIVARS section
		$dbConnectInput["dc_sql"] = "SELECT * FROM genotypes WHERE exp_id = '" . $exp_id . "'";
		$dbConnectOutput = excuteSql($con, $dbConnectInput);
		$ret = $dbConnectOutput["dc_result"];

		// write title
		if ($dbConnectOutput["dc_result_num"] > 0) {
			echo "*CULTIVARS\r\n";
			echo "@C CR INGENO CNAME\r\n";
		}

		// write content
		for ($i = 0; $i < $dbConnectOutput["dc_result_num"]; $i++) {
			
			// format the output data
			$ge = formatStr("%2d", $ret[$i]["ge"]);
			$cr = formatStr(" %2s", $ret[$i]["cr"]);
			$cul_id = formatStr(" %6s", $ret[$i]["cul_id"]);
			$cul_name = " " . str_pad($ret[$i]["cul_name"], 16);

			echo $ge . $cr . $cul_id . $cul_name . "\r\n";
		}
		
		echo "\r\n";
	}
	
	// Write FIELDS section of the X-file
	function writeFields($con, $exp_id) {
		
		$dbConnectInput["dc_sql_type"] = "select";
		$dbConnectInput["dc_process_name"] = "";
		
		// Write FIELDS section
		$dbConnectInput["dc_sql"] = "SELECT * FROM fields WHERE exp_id = '" . $exp_id . "'";
		$dbConnectOutput = excuteSql($con, $dbConnectInput);
		$ret = $dbConnectOutput["dc_result"];

		// write title part 1
		if ($dbConnectOutput["dc_result_num"] > 0) {
			echo "*FIELDS\r\n";
			echo "@L ID_FIELD WSTA....  FLSA  FLOB  FLDT  FLDD  FLDS  FLST SLTX  SLDP  ID_SOIL    FLNAME\r\n";
		}

		// write content
		for ($i = 0; $i < $dbConnectOutput["dc_result_num"]; $i++) {
			
			// format the output data
			$fl = formatStr("%2d", $ret[$i]["fl"]);
			$id_field = formatStr(" %8s", $ret[$i]["id_field"]);
			$wsta_id = " " . str_pad($ret[$i]["wsta_id"], 8);
			$flsl = formatStr(" %5s", $ret[$i]["flsl"]);
			$flob = formatStr(" %5.0f", $ret[$i]["flob"]);
			$fl_drntype = formatStr(" %5s", $ret[$i]["fl_drntype"]);
			$fldrd = formatStr(" %5.0f", $ret[$i]["fldrd"]);
			$fldrs = formatStr(" %5.0f", $ret[$i]["fldrs"]);
			$flst = formatStr(" %5s", $ret[$i]["flst"]);
			$sltx = " " . str_pad($ret[$i]["sltx"], 5);
			$sldp = formatStr(" %5.0f", $ret[$i]["sldp"]);
			$soil_id = formatStr(" %10s", $ret[$i]["soil_id"]);
			$fl_name = " " . $ret[$i]["fl_name"];

			echo $fl . $id_field . $wsta_id . $flsl . $flob . $fl_drntype . $fldrd . $fldrs . $flst . $sltx . $sldp . $soil_id . $fl_name . "\r\n";
		}
		
		// write title part 2
		if ($dbConnectOutput["dc_result_num"] > 0) {
			echo "@L ...........XCRD ...........YCRD .....ELEV .............AREA .SLEN .FLWR .SLAS FLHST FHDUR\r\n";
		}

		// write content
		for ($i = 0; $i < $dbConnectOutput["dc_result_num"]; $i++) {
			
			// format the output data
			$fl = formatStr("%2d", $ret[$i]["fl"]);
			$fl_lat = formatStr(" %15.2f", $ret[$i]["fl_lat"]);
			$fl_long = formatStr(" %15.2f", $ret[$i]["fl_long"]);
			$flele = formatStr(" %9s", $ret[$i]["flele"]);
			$farea = formatStr(" %17s", $ret[$i]["farea"]);
			$slen = formatStr(" %5s", "-99"); // TODO need formulation to calculate in the furture using
			$fllwr = formatStr(" %5s", $ret[$i]["fllwr"]);
			$flsla = formatStr(" %5s", $ret[$i]["flsla"]);
			$flhst = formatStr(" %5s", $ret[$i]["flhst"]);
			$fhdur = formatStr(" %5s", $ret[$i]["fhdur"]);

			echo $fl . $fl_lat . $fl_long . $flele . $farea . $slen . $fllwr . $flsla . $flhst . $fhdur . "\r\n";
		}
		
		echo "\r\n";
	}
	
	// Write SOIL ANALYSIS section of the X-file
	function writeSoil($con, $exp_id) {
		
		$dbConnectInput["dc_sql_type"] = "select";
		$dbConnectInput["dc_process_name"] = "";
		
		// Write SOIL ANALYSIS section
		$dbConnectInput["dc_sql"] = "SELECT * FROM soil_analyses_levels WHERE exp_id = '" . $exp_id . "'";
		$dbConnectOutput = excuteSql($con, $dbConnectInput);
		$ret = $dbConnectOutput["dc_result"];
		$num = $dbConnectOutput["dc_result_num"];

		// write title
		if ($num > 0) {
			echo "*SOIL ANALYSIS\r\n";
		}

		// write content
		for ($i = 0; $i < $num; $i++) {
			
			// format the output data
			$sa = formatStr("%2d", $ret[$i]["sa"]);
			$sadat = changeDate2Days(" %05d", $ret[$i]["sadat"]);
			$samhb = formatStr(" %5s", $ret[$i]["samhb"]);
			$sampx = formatStr(" %5s", $ret[$i]["sampx"]);
			$samke = formatStr(" %5s", $ret[$i]["samke"]);
			$sa_name = "  " . $ret[$i]["sa_name"];

			// write level section
			echo "@A SADAT  SMHB  SMPX  SMKE  SANAME\r\n";
			echo $sa . $sadat . $samhb . $sampx . $samke . $sa_name . "\r\n";
			
			// write events section
			$dbConnectInput["dc_sql"] = "SELECT * FROM soil_analyses_events WHERE exp_id = '" . $exp_id . "' AND sa = '". $sa . "'";
			$dbConnectOutput = excuteSql($con, $dbConnectInput);
			$ret2 = $dbConnectOutput["dc_result"];
			
			echo "@A  SABL  SADM  SAOC  SANI SAPHW SAPHB  SAPX  SAKE  SASC\r\n";
			
			for ($j = 0; $j < $dbConnectOutput["dc_result_num"]; $j++) {
			
				// format the output data
				$sa = formatStr("%2d", $ret2[$j]["sa"]);
				$sabl = formatStr(" %5.0f", $ret2[$j]["sabl"]);
				$sabdm = formatStr(" %5.1f", $ret2[$j]["sabdm"]);
				$saoc = formatStr(" %5.2f", $ret2[$j]["saoc"]);
				$sani = formatStr(" %5.2f", $ret2[$j]["sani"]);
				$saphw = formatStr(" %5.1f", $ret2[$j]["saphw"]);
				$saphb = formatStr(" %5.1f", $ret2[$j]["saphb"]);
				$sapx = formatStr(" %5.1f", $ret2[$j]["sapx"]);
				$sake = formatStr(" %5.1f", $ret2[$j]["sake"]);
				$sasc = formatStr(" %5.2f", $ret2[$j]["sasc"]);

				echo $sa . $sabl . $sabdm . $saoc . $sani . $saphw . $saphb . $sapx . $sake . $sasc . "\r\n";
			}
		}
		echo "\r\n";
	}
	
	// Write INITIAL CONDITIONS section of the X-file
	function writeInintial($con, $exp_id) {
		
		$dbConnectInput["dc_sql_type"] = "select";
		$dbConnectInput["dc_process_name"] = "";

		// Write INITIAL CONDITIONS section
		$dbConnectInput["dc_sql"] = "SELECT * FROM initial_condition_levels WHERE exp_id = '" . $exp_id . "'";
		$dbConnectOutput = excuteSql($con, $dbConnectInput);
		$ret = $dbConnectOutput["dc_result"];
		$num = $dbConnectOutput["dc_result_num"];

		// write title
		if ($num > 0) {
			echo "*INITIAL CONDITIONS\r\n";
		}

		// write content
		for ($i = 0; $i < $num; $i++) {
			
			// format the output data
			$ic = formatStr("%2d", $ret[$i]["ic"]);
			$icpcr = formatStr(" %5s", $ret[$i]["icpcr"]);
			$icdat = changeDate2Days(" %05d", $ret[$i]["icdat"]);
			$icrt = formatStr(" %5.0f", $ret[$i]["icrt"]);
			$icnd = formatStr(" %5.0f", $ret[$i]["icnd"]);
			$icrzno = formatStr(" %5.2f", $ret[$i]["icrzno"]);
			$icrze = formatStr(" %5.2f", $ret[$i]["icrze"]);
			$icwt = formatStr(" %5.1f", $ret[$i]["icwt"]);
			$icrag = formatStr(" %5s", $ret[$i]["icrag"]);
			$icrn = formatStr(" %5.2f", $ret[$i]["icrn"]);
			$icrp = formatStr(" %5.2f", $ret[$i]["icrp"]);
			$icrip = formatStr(" %5s", $ret[$i]["icrip"]);
			$icrdp = formatStr(" %5s", $ret[$i]["icrdp"]);
			$ic_name = " " . $ret[$i]["ic_name"];
			
			// write level section
			echo "@C   PCR ICDAT  ICRT  ICND  ICRN  ICRE  ICWD ICRES ICREN ICREP ICRIP ICRID ICNAME\r\n";
			echo $ic . $icpcr . $icdat . $icrt . $icnd . $icrzno . $icrze . $icwt . $icrag . $icrn . $icrp . $icrip . $icrdp . $ic_name . "\r\n";
			
			// write events section
			$dbConnectInput["dc_sql"] = "SELECT * FROM initial_condition_events WHERE exp_id = '" . $exp_id . "' AND ic = '". $ic . "'";
			$dbConnectOutput = excuteSql($con, $dbConnectInput);
			$ret2 = $dbConnectOutput["dc_result"];
			
			echo "@C  ICBL  SH2O  SNH4  SNO3\r\n";
			
			for ($j = 0; $j < $dbConnectOutput["dc_result_num"]; $j++) {
			
				// format the output data
				$icbl = formatStr(" %5.0f", $ret2[$j]["icbl"]);
				$ich2o = formatStr(" %5.3f", $ret2[$j]["ich2o"]);
				$icnh4 = formatStr(" %5s", $ret2[$j]["icnh4"]);
				$icno3 = formatStr(" %5.1f", $ret2[$j]["icno3"]);

				echo $ic . $icbl . $ich2o . $icnh4 . $icno3 . "\r\n";
			}
		}
		echo "\r\n";
	}
	
	// Write PLANTING DETAILS section of the X-file
	function writePlanting($con, $exp_id) {
		//		$dbConnectInput["dc_sql"] = "SELECT * FROM plantings WHERE exp_id = '" . $exp_id . "'";
//		$dbConnectOutput[""] = excuteSql($con, $dbConnectInput);
		
		$dbConnectInput["dc_sql_type"] = "select";
		$dbConnectInput["dc_process_name"] = "";

		// Write HARVEST DETAILS section
		$dbConnectInput["dc_sql"] = "SELECT * FROM plantings WHERE exp_id = '" . $exp_id . "'";
		$dbConnectOutput = excuteSql($con, $dbConnectInput);
		$ret = $dbConnectOutput["dc_result"];
		$num = $dbConnectOutput["dc_result_num"];

		// write title
		if ($num > 0) {
			echo "*PLANTING DETAILS\r\n";
			echo "@P PDATE EDATE  PPOP  PPOE  PLME  PLDS  PLRS  PLRD  PLDP  PLWT  PAGE  PENV  PLPH  SPRL                        PLNAME\r\n";
		}

		// write content
		for ($i = 0; $i < $num; $i++) {
			
			// format the output data
			$pl = formatStr("%2d", $ret[$i]["pl"]);
			$pdate = changeDate2Days(" %05d", $ret[$i]["pdate"]);
			$pldae = changeDate2Days(" %05d", $ret[$i]["pldae"]);
			$plpop = formatStr(" %5.1f", $ret[$i]["plpop"]);
			$plpoe = formatStr(" %5.1f", $ret[$i]["plpoe"]);
			$plme = formatStr("     %1s", $ret[$i]["plme"]);
			$plds = formatStr("     %1s", $ret[$i]["plds"]);
			$plrs = formatStr(" %5.0f", $ret[$i]["plrs"]);
			$plrd = formatStr(" %5.0f", $ret[$i]["plrd"]);
			$pldp = formatStr(" %5.1f", $ret[$i]["pldp"]);
			$plmwt = formatStr(" %5.0f", $ret[$i]["plmwt"]);
			$page = formatStr(" %5.0f", $ret[$i]["page"]);
			$penv = formatStr(" %5.1f", $ret[$i]["penv"]);
			$plph = formatStr(" %5.1f", $ret[$i]["plph"]);
			$plspl = formatStr(" %5.1f", $ret[$i]["plspl"]);
			$pl_name = "                        " . $ret[$i]["pl_name"];
			
			echo $pl . $pdate . $pldae . $plpop . $plpoe . $plme . $plds . $plrs . $plrd . $pldp . $plmwt . $page . $penv . $plph . $plspl . $pl_name . "\r\n";
		}
		echo "\r\n";
	}
	
	// Write IRRIGATION AND WATER MANAGEMENT section of the X-file
	function writeIrrigation($con, $exp_id) {
		
		$dbConnectInput["dc_sql_type"] = "select";
		$dbConnectInput["dc_process_name"] = "";

		// Write IRRIGATION AND WATER MANAGEMENT section
		$dbConnectInput["dc_sql"] = "SELECT * FROM irrigation_levels WHERE exp_id = '" . $exp_id . "'";
		$dbConnectOutput = excuteSql($con, $dbConnectInput);
		$ret = $dbConnectOutput["dc_result"];
		$num = $dbConnectOutput["dc_result_num"];

		// write title
		if ($num > 0) {
			echo "*IRRIGATION AND WATER MANAGEMENT\r\n";
		}

		// write content
		for ($i = 0; $i < $num; $i++) {
			
			// format the output data
			$ir = formatStr("%2d", $ret[$i]["ir"]);
			$ireff = formatStr(" %5.2f", $ret[$i]["ireff"]);
			$irmdp = formatStr(" %5.0f", $ret[$i]["irmdp"]);
			$irthr = formatStr(" %5.0f", $ret[$i]["irthr"]);
			$irept = formatStr(" %5.0f", $ret[$i]["irept"]);
			$irstg = formatStr(" %5s", $ret[$i]["irstg"]);
			$iame = formatStr(" %5s", $ret[$i]["iame"]);
			$iamt = formatStr(" %5.0f", $ret[$i]["iamt"]);
			$ir_name = " " . $ret[$i]["ir_name"];
			
			// write level section
			echo "@I  EFIR  IDEP  ITHR  IEPT  IOFF  IAME  IAMT IRNAME\r\n";
			echo $ir . $ireff . $irmdp . $irthr . $irept . $irstg . $iame . $iamt . $ir_name . "\r\n";
			
			// write events section
			$dbConnectInput["dc_sql"] = "SELECT * FROM irrigation_events WHERE exp_id = '" . $exp_id . "' AND ir = '". $ir . "'";
			$dbConnectOutput = excuteSql($con, $dbConnectInput);
			$ret2 = $dbConnectOutput["dc_result"];
			
			echo "@I IDATE  IROP IRVAL\r\n";
			
			for ($j = 0; $j < $dbConnectOutput["dc_result_num"]; $j++) {
			
				// format the output data
				$idate = changeDate2Days(" %05d", $ret2[$j]["idate"]);
				$irop = formatStr(" %5s", $ret2[$j]["irop"]);
				$irval = formatStr(" %5.0f", $ret2[$j]["irval"]);

				echo $ir . $idate . $irop . $irval . "\r\n";
			}
		}
		echo "\r\n";
	}
	
	// Write FERTILIZERS (INORGANIC) section of the X-file
	function writeFertilizers($con, $exp_id) {
		
		$dbConnectInput["dc_sql_type"] = "select";
		$dbConnectInput["dc_process_name"] = "";

		// Write FERTILIZERS (INORGANIC) section
		$dbConnectInput["dc_sql"] = "SELECT * FROM fertilizer_levels as lev, fertilizer_events as eve WHERE lev.exp_id = '" . $exp_id . "' AND lev.exp_id = eve.exp_id AND  lev.fe = eve.fe ORDER BY lev.fe";
		$dbConnectOutput = excuteSql($con, $dbConnectInput);
		$ret = $dbConnectOutput["dc_result"];
		$num = $dbConnectOutput["dc_result_num"];

		// write title
		if ($num > 0) {
			echo "*FERTILIZERS (INORGANIC)\r\n";
			echo "@F FDATE  FMCD  FACD  FDEP  FAMN  FAMP  FAMK  FAMC  FAMO  FOCD FERNAME\r\n";
		}

		// write content
		for ($i = 0; $i < $num; $i++) {
			
			// format the output data
			$fe = formatStr("%2d", $ret[$i]["fe"]);
			$fdate = changeDate2Days(" %05d", $ret[$i]["fdate"]);
			$fecd = formatStr(" %5s", $ret[$i]["fecd"]);
			$feacd = formatStr(" %5s", $ret[$i]["feacd"]);
			$fedep = formatStr(" %5.0f", $ret[$i]["fedep"]);
			$feamn = formatStr(" %5.0f", $ret[$i]["feamn"]);
			$feamp = formatStr(" %5.0f", $ret[$i]["feamp"]);
			$feamk = formatStr(" %5.0f", $ret[$i]["feamk"]);
			$feamc = formatStr(" %5.0f", $ret[$i]["feamc"]);
			$feamo = formatStr(" %5.0f", $ret[$i]["feamo"]);
			$feocd = formatStr(" %5s", $ret[$i]["feocd"]);
			$fe_name = " " . $ret[$i]["fe_name"];
			
			echo $fe . $fdate . $fecd . $feacd . $fedep . $feamn . $feamp . $feamk . $feamc . $feamo . $feocd . $fe_name . "\r\n";
		}
		echo "\r\n";
	}
	
	// Write RESIDUES AND ORGANIC FERTILIZER section of the X-file
	function writeResidues($con, $exp_id) {
		
		$dbConnectInput["dc_sql_type"] = "select";
		$dbConnectInput["dc_process_name"] = "";

		// Write RESIDUES AND ORGANIC FERTILIZER section
		$dbConnectInput["dc_sql"] = "SELECT * FROM organic_material_levels as lev, organic_material_events as eve WHERE lev.exp_id = '" . $exp_id . "' AND lev.exp_id = eve.exp_id AND  lev.om = eve.om ORDER BY lev.om";
		$dbConnectOutput = excuteSql($con, $dbConnectInput);
		$ret = $dbConnectOutput["dc_result"];
		$num = $dbConnectOutput["dc_result_num"];

		// write title
		if ($num > 0) {
			echo "*RESIDUES AND ORGANIC FERTILIZER\r\n";
			echo "@R RDATE  RCOD  RAMT  RESN  RESP  RESK  RINP  RDEP  RMET RENAME\r\n";
		}

		// write content
		for ($i = 0; $i < $num; $i++) {
			
			// format the output data
			$om = formatStr("%2d", $ret[$i]["om"]);
			$omdat = changeDate2Days(" %05d", $ret[$i]["omdat"]);
			$omcd = formatStr(" %5s", $ret[$i]["omcd"]);
			$omamt = formatStr(" %5.0f", $ret[$i]["omamt"]);
			$omnpct = formatStr(" %5.2f", $ret[$i]["omnpct"]);
			$omppct = formatStr(" %5.2f", $ret[$i]["omppct"]);
			$omkpct = formatStr(" %5.2f", $ret[$i]["omkpct"]);
			$ominp = formatStr(" %5.0f", $ret[$i]["ominp"]);
			$omdep = formatStr(" %5.0f", $ret[$i]["omdep"]);
			$omacd = formatStr(" %5.0f", $ret[$i]["omacd"]);
			$om_name = " " . $ret[$i]["om_name"];
			
			echo $om . $omdat . $omcd . $omamt . $omnpct . $omppct . $omkpct . $ominp . $omdep . $omacd . $om_name . "\r\n";
		}
		echo "\r\n";
	}
	
	// Write CHEMICAL APPLICATIONS section of the X-file
	function writeChemical($con, $exp_id) {
		
		$dbConnectInput["dc_sql_type"] = "select";
		$dbConnectInput["dc_process_name"] = "";

		// Write CHEMICAL APPLICATIONS section
		$dbConnectInput["dc_sql"] = "SELECT * FROM chemical_levels as lev, chemical_events as eve WHERE lev.exp_id = '" . $exp_id . "' AND lev.exp_id = eve.exp_id AND  lev.ch = eve.ch ORDER BY lev.ch";
		$dbConnectOutput = excuteSql($con, $dbConnectInput);
		$ret = $dbConnectOutput["dc_result"];
		$num = $dbConnectOutput["dc_result_num"];

		// write title
		if ($num > 0) {
			echo "*CHEMICAL APPLICATIONS\r\n";
			echo "@C CDATE CHCOD CHAMT  CHME CHDEP   CHT..CHNAME\r\n";
		}

		// write content
		for ($i = 0; $i < $num; $i++) {
			
			// format the output data
			$ch = formatStr("%2d", $ret[$i]["ch"]);
			$cdate = changeDate2Days(" %05d", $ret[$i]["cdate"]);
			$chcd = formatStr(" %5s", $ret[$i]["chcd"]);
			$chamt = formatStr(" %5.2f", $ret[$i]["chamt"]);
			$chacd = formatStr(" %5s", $ret[$i]["chacd"]);
			$chdep = formatStr(" %5s", $ret[$i]["chdep"]);
			$ch_targets = formatStr(" %5s", $ret[$i]["ch_targets"]);
			$ch_name = "  " . $ret[$i]["ch_name"];
			
			echo $ch . $cdate . $chcd . $chamt . $chacd . $chdep . $ch_targets . $ch_name . "\r\n";
		}
		echo "\r\n";
	}
	
	// Write TILLAGE AND ROTATIONS section of the X-file
	function writeTillage($con, $exp_id) {
		
		$dbConnectInput["dc_sql_type"] = "select";
		$dbConnectInput["dc_process_name"] = "";

		// Write TILLAGE AND ROTATIONS section
		$dbConnectInput["dc_sql"] = "SELECT * FROM tillage_levels as lev, tillage_events as eve WHERE lev.exp_id = '" . $exp_id . "' AND lev.exp_id = eve.exp_id AND  lev.ti = eve.ti ORDER BY lev.ti";
		$dbConnectOutput = excuteSql($con, $dbConnectInput);
		$ret = $dbConnectOutput["dc_result"];
		$num = $dbConnectOutput["dc_result_num"];

		// write title
		if ($num > 0) {
			echo "*TILLAGE AND ROTATIONS\r\n";
			echo "@T TDATE TIMPL  TDEP TNAME\r\n";
		}

		// write content
		for ($i = 0; $i < $num; $i++) {
			
			// format the output data
			$ti = formatStr("%2d", $ret[$i]["ti"]);
			$tdate = changeDate2Days(" %05d", $ret[$i]["tdate"]);
			$tiimp = formatStr(" %5s", $ret[$i]["tiimp"]);
			$tidep = formatStr(" %5.0f", $ret[$i]["tidep"]);
			$ti_name = " " . $ret[$i]["ti_name"];
			
			echo $ti . $tdate . $tiimp . $tidep . $ti_name . "\r\n";
		}
		echo "\r\n";
	}
	
	// Write ENVIRONMENT MODIFICATIONS section of the X-file
	function writeEnvMdf($con, $exp_id) {
		
		$dbConnectInput["dc_sql_type"] = "select";
		$dbConnectInput["dc_process_name"] = "";

		// Write ENVIRONMENT MODIFICATIONS section
		$dbConnectInput["dc_sql"] = "SELECT * FROM environ_modif_levels as lev, environ_modif_events as eve WHERE lev.exp_id = '" . $exp_id . "' AND lev.exp_id = eve.exp_id AND  lev.em = eve.em ORDER BY lev.em";
		$dbConnectOutput = excuteSql($con, $dbConnectInput);
		$ret = $dbConnectOutput["dc_result"];
		$num = $dbConnectOutput["dc_result_num"];

		// write title
		if ($num > 0) {
			echo "*ENVIRONMENT MODIFICATIONS\r\n";
			echo "@E ODATE EDAY  ERAD  EMAX  EMIN  ERAIN ECO2  EDEW  EWIND ENVNAME\r\n";
		}

		// write content
		for ($i = 0; $i < $num; $i++) {
			
			// format the output data
			$em = formatStr("%2d", $ret[$i]["em"]);
			$emday = changeDate2Days(" %05d", $ret[$i]["emday"]);
			$ecdyl = formatStr(" %1s", $ret[$i]["ecdyl"]);
			$emdyl = formatStr("%4.1f", $ret[$i]["emdyl"]);
			$ecrad = formatStr(" %1s", $ret[$i]["ecrad"]);
			$emrad = formatStr("%4.1f", $ret[$i]["emrad"]);
			$ecmax = formatStr(" %1s", $ret[$i]["ecmax"]);
			$emmax = formatStr("%4.1f", $ret[$i]["emmax"]);
			$ecmin = formatStr(" %1s", $ret[$i]["ecmin"]);
			$emmin = formatStr("%4.1f", $ret[$i]["emmin"]);
			$ecrai = formatStr(" %1s", $ret[$i]["ecrai"]);
			$emrai = formatStr("%4.1f", $ret[$i]["emrai"]);
			$ecco2 = formatStr(" %1s", $ret[$i]["ecco2"]);
			$emco2 = formatStr("%4.0f", $ret[$i]["emco2"]);
			$ecdew = formatStr(" %1s", $ret[$i]["ecdew"]);
			$emdew = formatStr("%4.1f", $ret[$i]["emdew"]);
			$ecwnd = formatStr(" %1s", $ret[$i]["ecwnd"]);
			$emwnd = formatStr("%4.1f", $ret[$i]["emwnd"]);
			$em_name = " " . $ret[$i]["em_name"];
			
			echo $em . $emday . $ecdyl . $emdyl . $ecrad . $emrad . $ecmax . $emmax . $ecmin . $emmin . $ecrai . $emrai . $ecco2 . $emco2 . $ecdew . $emdew . $ecwnd . $emwnd . $em_name . "\r\n";
		}
		echo "\r\n";
	}
	
	// Write HARVEST DETAILS section of the X-file
	function writeHarvest($con, $exp_id) {
		
		$dbConnectInput["dc_sql_type"] = "select";
		$dbConnectInput["dc_process_name"] = "";

		// Write HARVEST DETAILS section
		$dbConnectInput["dc_sql"] = "SELECT * FROM harvest_levels as lev, harvest_events as eve WHERE lev.exp_id = '" . $exp_id . "' AND lev.exp_id = eve.exp_id AND  lev.ha = eve.ha ORDER BY lev.ha";
		$dbConnectOutput = excuteSql($con, $dbConnectInput);
		$ret = $dbConnectOutput["dc_result"];
		$num = $dbConnectOutput["dc_result_num"];

		// write title
		if ($num > 0) {
			echo "*HARVEST DETAILS\r\n";
			echo "@H HDATE  HSTG  HCOM HSIZE   HPC  HBPC HNAME\r\n";
		}

		// write content
		for ($i = 0; $i < $num; $i++) {
			
			// format the output data
			$ha = formatStr("%2d", $ret[$i]["ha"]);
			$haday = changeDate2Days(" %05d", $ret[$i]["haday"]);
			$hastg = formatStr(" %5s", $ret[$i]["hastg"]);
			$hacom = formatStr(" %5s", $ret[$i]["hacom"]);
			$hasiz = formatStr(" %5s", $ret[$i]["hasiz"]);
			$hapc = formatStr(" %5.0f", $ret[$i]["hapc"]);
			$habpc = formatStr(" %5.0f", $ret[$i]["habpc"]);
			$ha_name = " " . $ret[$i]["ha_name"];
			
			echo $ha . $haday . $hastg . $hacom . $hasiz . $hapc . $habpc . $ha_name . "\r\n";
		}
		echo "\r\n";
	}
	
	// Write Simulation section of the X-file
	function writeSimulation($type) {
		
		if ($type == "default")  {
			readfile("template\Simulate.def","r");
		}
	}
	
	// Change date format to days format
	function changeDate2Days($format, $dateStr) {
		if ($dateStr != 0 && $dateStr != "00000000") {
			$year = substr($dateStr,0,4);
			$month = substr($dateStr,5,2);
			$day = substr($dateStr,8,2);
			$days = idate("z", mktime(0,0,0,$month,$day,$year)) + 1;
			$dayStr = substr($dateStr,2,2) . sprintf("%03d", $days);
		} else {
			$dayStr = "-99";
		}
		return formatStr($format, $dayStr);
	}
	
	// Format output string
	function formatStr($format, $str) {
		// check if is not unuse value; if so, change to text format output
		if ($str != -99 && $str != "-99") {
			return sprintf($format, $str);
		} else {
			
			// if float
			if (stripos($format, ".") >= 2) {
				$format = substr($format, 0, strlen($format) - 3) . "s"; // TODO: if furture use more than 10 bit decimal, there should be revised
			} else {
				$format = substr($format, 0, strlen($format) - 1) . "s";
			}
			return sprintf(str_ireplace("%0", "%", $format), $str);
		}
	}
?>