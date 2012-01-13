<?php
	session_start();
	if (!isset($_SESSION["user"])) {
		Header("Location:   login.php?" . SID);
		exit();
	}
?>