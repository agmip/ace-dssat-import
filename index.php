<?php
		session_start();
	if (!isset($_SESSION["user"])) {
		Header("Location:   login.php?" . SID);
	} else {
		Header("Location:   menu.php?" . SID);
	}
?>