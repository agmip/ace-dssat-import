		<?php
			// Define the tab texts
			$p_contents = array("Step 1. XFile",
										"Step 2. Soil File",
										"Step 3. Weather File",
										"Step 4. Observation File",
										"Step 5. Confirm",
										"Step 6. Save");
			// Define the tab links
			$p_links = array ("inputFiles01.php",
										"checkInputXFiles.php",		// 02 Soil
										"checkInputSFiles.php",		// 03 Weather
										"checkInputWFiles.php",	// 04 Observation
										"checkInputOFiles.php",	// 05 Confirm
										"#");									// TODO 06 Save
			
			if (isset($_SESSION["dssat_steps"])) {
				
				for ($i=0; $i < count($p_contents); $i++) {
					if ($i <= $_SESSION["dssat_steps"] - 1 && $i != $p_pageNum - 1) {
						$p_tabs[$i] = "<a href='" . $p_links[$i] . "'><span class='active'>" . $p_contents[$i] . "</span></a>";
					} else if ($i > $_SESSION["dssat_steps"] - 1) {
						$p_tabs[$i] = "<span>" . $p_contents[$i] . "</span>";
					} else {
						$p_tabs[$i] = "<span class='active'>" . $p_contents[$i] . "</span>";
					}
				}
				
			} else if ($p_page == "save") {
				
				for ($i=0; $i < count($p_contents); $i++) {
					$p_tabs[$i] = "<span class='active'>" . $p_contents[$i] . "</span>";
				}
				
			} else {
				$p_tabs[0] = "";
			}
		?>
		<div id="tabs">
			<ul>
					<?php
						for ($i = 0; $i < count($p_tabs); $i++) {
							echo "<li>" . $p_tabs[$i] . "</li>\r\n";
						}
					?>
			</ul>
		</div>