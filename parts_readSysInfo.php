<?php
	
	if (!isset($_SESSION["sysInfo"])) {
		createSysInfoArray();
		$sysInfoFile = fopen("sysInfo.ini","r") or exit("Unable to open sysInfo.ini file!");
		while(!feof($sysInfoFile)) {
			$line = trim(fgets($sysInfoFile));
			if (strpos($line, "!") === 0) {
				// comment, ignore
			} else if (strpos($line, "[version]") === 0) {
				// version line
				$_SESSION["sysInfo"]["version"] = trim(substr($line, strpos($line, "=")+1));
			} else {
			}
		}
		fclose($sysInfoFile);
	}
	
	function createSysInfoArray() {
		$_SESSION["sysInfo"] = array("version" => "1.1");
	}

	function getVersion() {
		return $_SESSION["sysInfo"]["version"];
	}
	
?>