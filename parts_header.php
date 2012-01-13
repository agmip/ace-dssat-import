	<div id="header">
		<table><tr>
			<td id="logo">
				<img alt="logo" src="img/agmip_logo2.png"/>
			</td>
			<td id="user" align="right">
				<p>Hello, <?php echo $_SESSION["user_last_name"];?> <?php echo $_SESSION["user_first_name"];?></p>
				<a href="logout.php">Logout</a>
			</td>
		</tr></table>
		<hr />
	</div>