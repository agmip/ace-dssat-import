		<?php
			// Define text
			$p_explain["default"][0] = "Choose file for experiments that you want to add to the Agmip experiment database.";
			$p_explain["default"][1] = "Currently, only DSSAT-format experiment file are enabled. Other crop model formats will be added as data translator are developed.";
			$p_explain["input00"][0] = "Select the model format for input data files.";
			$p_explain["input00"][1] = "Currently, only DSSAT-format experiment file are enabled. Other crop model formats will be added as data translator are developed.";
			$p_explain["input01"][0] = $p_explain["default"][0];
			$p_explain["input01"][1] = "If an experiment file is uploaded again, associated files that were previously uploaded will be deleted.";
			$p_explain["input02"][0] = "The following soil profiles are listed for your expreriment.";
			$p_explain["input02"][1] = "Please choose to use them by reading from the database or uploading a new profile.";
			$p_explain["input03"][0] = "The following weather profiles are listed for your expreriment.";
			$p_explain["input03"][1] = "If you want to upload a new file, please check the update checkbox, then select your file.";
			$p_explain["input04"][0] = "Choose A and T file for your experiment if available.(OPTIONAL)";
			$p_explain["confirm"][0] = "Please confirm if correct file are checked for uploading.";
			$p_explain["save"][0] = "The files you uploaded are saved and will be reviewed by administrator.";
			$p_explain["save"][1] = "If you want to change anything before review, please go to <a href='listTemp.php'>temporary data list</a>.";
			$p_explain["listTemp"][0] = "The experiment listed here are temporary data before being proved.";
			$p_explain["listTemp"][1] = "You can click delete button to delete the record if you no longer want it. Once you complete uploading, you will not be able to delete it.";
			$p_explain["listTemp"][2] = "You can click edit button to change the content. Once your files begin to be reviewed, it will no longer be changed again.";
			$p_explain["listTemp"][3] = "(*)status -[editing] means the records are never been saved; [pending] means the records are saved but have not been reviewed yet; [verifying] means the record are being reviewed now.";
			
			if (!isset($p_page) || !array_key_exists($p_page, $p_explain)) {
				$p_page_explain = "default";
			} else {
				$p_page_explain = $p_page;
			}
			
		?>
		<div id="explain">
			<ul>
				<?php
					for ($i = 0; $i<count($p_explain[$p_page_explain]); $i++) {
						echo "<li>" . $p_explain[$p_page_explain][$i] . "</li>\r\n";
					}
				?>
			</ul>
		</div>