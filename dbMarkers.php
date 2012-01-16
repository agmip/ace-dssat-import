<?php
	include("dbConnect.php");
	
	// defination of icons showed in map
	function getIcon($type) {
		
		$defaultIcon = "rice.png"; //"icon_s.png";
		$icons = array (
			"FA" => "Fallow.png",
			"GB" => "GreenBean.png",
			"MZ" => "Maize.png",
			"ML" => "Millet.png",
			"PN" => "Peanut.png",
			"PR" => "Pepper.png",
			"PI" => "PineApple.png",
			"PT" => "Potato.png",
			"RI" => "Rice.png",
			"SG" => "Sorghum.png",
			"SB" => "Soybean.png",
			"SC" => "Sugarcane.png",
			"SU" => "Sunflower.png",
			"SW" => "SweetCorn.png",
			"TN" => "Tanier.png",
			"TR" => "Taro.png",
			"TM" => "Tomato.png",
			"VB" => "Velvetbean.png",
			"WH" => "Wheat.png"
		);
		
		if (array_key_exists($type, $icons)) {
			return $icon[$type];
		} else {
			return $defaultIcon;
		}
	}
	
	function getMarkers($crops) {
		// Create connection with DB
		$con = connectDB();
		
		if (sizeof($crops) !== 0) {
			$strCrops = " AND ( exp.exname LIKE '%" . $crops[0] . "'";
			for ($i = 0; $i < sizeof($crops); $i++) {
				$strCrops = $strCrops . " OR exp.exname LIKE '%" . $crops[$i] . "'";
			}
			$strCrops = $strCrops . " ) ";
		} else {
			$strCrops = " ";
		}
		
		$dbConnectInput["dc_sql"] = "SELECT exp.exp_id, exp.exname, fl.fl_lat, fl.fl_long FROM experimental_descrips as exp, fields as fl WHERE exp.exp_id = fl.exp_id" . $strCrops . "ORDER BY exp.exp_id DESC";

		$dbConnectInput["dc_sql_type"] = "select";
		$dbConnectInput["dc_process_name"] = "";
		$dbConnectOutput = excuteSql($con, $dbConnectInput);
		//echo "//[".$dbConnectInput["dc_sql"]."]<br />";
		//echo "//Result: [".$dbConnectOutput["dc_result"]."]<br />";
		
		mysql_close($con);
		return $dbConnectOutput;
	}
	
	function writeMarkers($dbConnectOutput) {
		
		echo "var fields = [";
		$num = $dbConnectOutput["dc_result_num"];
		if ($num >= 1) {
			$rets = $dbConnectOutput["dc_result"];
			for ($i =0; $i < $num - 1; $i++) {
				$icon = substr($rets[$i]["exname"], -2); // TODO waiting for all icon completed.
				echo "['" . $rets[$i]["exname"] . "', " . $rets[$i]["fl_lat"] . ", " . $rets[$i]["fl_long"] . ", " . $rets[$i]["exp_id"] . ", '" . getIcon("") . "'],";
			}
			
			$icon = substr($rets[$num - 1]["exname"], -2); // TODO waiting for all icon completed.
			echo "['" . $rets[$num - 1]["exname"] . "', " . $rets[$num - 1]["fl_lat"] . ", " . $rets[$num - 1]["fl_long"] . ", " . $rets[$num - 1]["exp_id"] . ", '" . getIcon("") . "']";
		}
		echo " ];";
		
	}
	
	function writeAddListener($dbConnectOutput) {
		
		if ($dbConnectOutput["dc_result_num"] >= 1) {
			$rets = $dbConnectOutput["dc_result"];		
			for ($i =0; $i <sizeof($rets); $i++) {
				echo "google.maps.event.addListener(markers[" . $i . "], 'click', function () {goto('expDetail.php?expId=" . $rets[$i]["exp_id"] . "');});\r\n";
			}
		}
	}
	
	function isSelect($crops, $crop) {
		
		if (in_array($crop, $crops) || sizeof($crops) === 0) {
			echo "selected=''";
		}
	}

?>