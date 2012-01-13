<?php
		if (isset($_SESSION["dssat_steps"])) {
			if ($_SESSION["dssat_steps"] < $p_pageNum) {
				$_SESSION["dssat_steps"]++;
			}
		} else {
			$_SESSION["dssat_steps"] = $p_pageNum;
		}
?>