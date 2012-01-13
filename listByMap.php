<?php include("parts_checkSession.php"); ?>
<?php include("dbMarkers.php"); ?>
<?php
	header('Cache-control: private, must-revalidate');
	if (isset($_POST["location"])) {
		$location = $_POST["location"];
	} else {
		$location = "";
	}
	if (isset($_POST["crop"])) {
		$crop = $_POST["crop"];
	} else {
		$crop = array();
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Language" content="en-US" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/frame.css" />
<title>List By Map</title>
<style type="text/css">
.style1 {
				font-size: small;
}
.style2 {
				text-align: center;
}
</style>
<script src="js/function.js" type="text/javascript"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
<script type="text/javascript">
	function initialize(initLatlng) {
		
		// initialize the map
		if (initLatlng == "") {
			initLatlng = new google.maps.LatLng(28, 280); // Florida
		}
		geocoder = new google.maps.Geocoder();
		var myOptions = {
			zoom: 4,
			center: initLatlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
                
		// setup the markup info
		<?php 
			if (isset($_POST["crop"])) {
				$dbConnectOutput = getMarkers($_POST["crop"]);
				writeMarkers($dbConnectOutput);
			} else {
				echo "var fields = [];";
			}
		?>
//		var fields = [
//			['BRPI0202MZ', -15.890542, 305.274856,141,'rice.png'],
//			['GAGR0201SB', -10.890542, 310.274856,139,'corn.png'],
//			['GAGR0201SB', -13.890542, 315.274856,138,'tomato.png']
//		];
		
		setMarkers(map, fields);
		
		// get the coordinate of Address
		var address = document.getElementById("location").value;
		if (geocoder && address != "") {
			geocoder.geocode( { 'address': address}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					map.setCenter(results[0].geometry.location);
//					var marker = new google.maps.Marker({
//						map: map,
//						position: results[0].geometry.location
//					});
//					var bounds = new google.maps.LatLngBounds();
//					bounds = bounds.extend(marker.getLatLng());
//					map.fitBounds(bounds);
				} else {
					alert("Geocode was not successful for the following reason: " + status);
				}
			});
		}
		

	}
	
	function setMarkers(map, locations) {
		
		var markers = new Array();
		
		for (var i = 0; i < locations.length; i++) {
			var field = locations[i];
			var myLatLng = new google.maps.LatLng(field[1], field[2]);
			var image = 'img/' + field[4];
			markers[i] = new google.maps.Marker({
					position: myLatLng,
					map: map,
					icon: image,
					title: field[0]});
			//google.maps.event.addListener(markers[i], 'click', function () {alert(field[3]);});//goto('expDetail.php?expId='+field[3]);
			if (field[1] != -99) {
				map.setCenter(myLatLng);
			}
		}
		<?php
			if (isset($_POST["crop"])) {
				writeAddListener($dbConnectOutput);
			}
		?>
	}
</script>
</head>

<body onload="initialize(new google.maps.LatLng(-15, -55))">
<div id="container">
	<?php include("parts_header.php"); ?>
	<div id="content">
		<table><tr>
			<td style="width: 760px">
				<h2>Crop Experiment Database</h2>
				<span class="style1">Select crop and location,<br/>
				Click icon in the map to view experiments in database.</span>
			</td>
			<td>
				<span><input id="addNewFile" name="addNewFile" type="button" value="Add New File" onclick="goto('inputFiles.php')" style="width: 140px"/></span>
				<span><input id="backToMenu" name="backToMenu" type="button" value="Back To Menu" onclick="goto('menu.php')" style="width: 140px"/></span>
				<span><input id="GoToList" name="GoToList" type="button" value="Switch To List" onclick="goto('list.php')" style="width: 140px"/></span>
			</td>
		</tr></table>
		<form id="form1" method="post" action="listByMap.php"><table style="width:100%">
			<tr>
				<td style="width: 100px" rowspan="2">Crop</td>
				<td style="width: 200px" rowspan="2">
				<select name="crop[]" style="width: 180px; height: 70px;" multiple="multiple">
					<option value="BH" <?php isSelect($crop, "BH"); ?>>Bahia</option>
					<option value="BA" <?php isSelect($crop, "BA"); ?>>Barley</option>
					<option value="BR" <?php isSelect($crop, "BR"); ?>>Brachiaria</option>
					<option value="CB" <?php isSelect($crop, "CB"); ?>>Cabbage</option>
					<option value="CS" <?php isSelect($crop, "CS"); ?>>Cassava</option>
					<option value="CH" <?php isSelect($crop, "CH"); ?>>Chickpea</option>
					<option value="CO" <?php isSelect($crop, "CO"); ?>>Cotton</option>
					<option value="CP" <?php isSelect($crop, "CP"); ?>>Cowpea</option>
					<option value="BN" <?php isSelect($crop, "BN"); ?>>Drybean</option>
					<option value="FB" <?php isSelect($crop, "FB"); ?>>FabaBean</option>
					<option value="FA" <?php isSelect($crop, "FA"); ?>>Fallow</option>
					<option value="GB" <?php isSelect($crop, "GB"); ?>>GreenBean</option>
					<option value="MZ" <?php isSelect($crop, "MZ"); ?>>Maize</option>
					<option value="ML" <?php isSelect($crop, "ML"); ?>>Millet</option>
					<option value="PN" <?php isSelect($crop, "PN"); ?>>Peanut</option>
					<option value="PR" <?php isSelect($crop, "PR"); ?>>Pepper</option>
					<option value="PI" <?php isSelect($crop, "PI"); ?>>PineApple</option>
					<option value="PT" <?php isSelect($crop, "PT"); ?>>Potato</option>
					<option value="RI" <?php isSelect($crop, "RI"); ?>>Rice</option>
					<option value="SG" <?php isSelect($crop, "SG"); ?>>Sorghum</option>
					<option value="SB" <?php isSelect($crop, "SB"); ?>>Soybean</option>
					<option value="SC" <?php isSelect($crop, "SC"); ?>>Sugarcane</option>
					<option value="SU" <?php isSelect($crop, "SU"); ?>>Sunflower</option>
					<option value="SW" <?php isSelect($crop, "SW"); ?>>SweetCorn</option>
					<option value="TN" <?php isSelect($crop, "TN"); ?>>Tanier</option>
					<option value="TR" <?php isSelect($crop, "TR"); ?>>Taro</option>
					<option value="TM" <?php isSelect($crop, "TM"); ?>>Tomato</option>
					<option value="VB" <?php isSelect($crop, "VB"); ?>>Velvetbean</option>
					<option value="WH" <?php isSelect($crop, "WH"); ?>>Wheat</option>
				</select></td>
				<td style="width: 400px">Search for location:</td>
				<td style="width: 200px"></td>
			</tr>
			<tr>
				<td><input id="location" name="location" type="text" style="width: 400px" value="<?php echo $location; ?>"/></td>
				<td><input id="search" name="search" type="submit" value="Search"/></td>
			</tr>
		</table></form>
		<div id="map_canvas" style="width:900px; height:500px"></div>
	</div>
	<?php include("parts_footer.php"); ?>
</div>
</body>

</html>