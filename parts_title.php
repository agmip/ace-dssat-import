		<?php
			$p_titles["default"] = "DSSAT Title";
			$p_titles["input00"] = "Select Model";
			$p_titles["input01"] = "Select DSSAT Experiment Data";
			$p_titles["input02"] = "Select DSSAT Soil Data";
			$p_titles["input03"] = "Select DSSAT Weather Data";
			$p_titles["input04"] = "Select DSSAT Observed Data";
			$p_titles["confirm"] = "Confirm Upload Data";
			$p_titles["save"] = "Save Upload Data";
			$p_titles["listTemp"] = "Temporary Data List";
			
			if (!isset($p_page) || !array_key_exists($p_page, $p_titles)) {
				$p_page_title = "default";
			} else {
				$p_page_title = $p_page;
			}
		?>
		<div id="title">
			<span><?php echo $p_titles[$p_page_title]; ?></span>
		</div>
