<html>

<head>
		<title>Tektonomastics</title>

		<?php	include "include/head.inc"; ?>

   <!-- <script src="/lib/jquery-1.4.2.js" type="text/javascript" ></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>-->
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>  

	<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>

	<!-- <script src="/script/jquery.validate.min.js" type="text/javascript"></script> -->

</head>


<body>
	<div id="body_container">
	
	<?php include "include/header.inc"; ?>
	<?php include "include/navbar.inc"; ?>
	
	<?php
	
	$name = $_POST['name'];
	//$name = mysql_real_escape_string($_POST['name']);
	$lat = $_POST['lat'];
	//$lat = mysql_real_escape_string($_POST['lat']);
	$lon = $_POST['lng'];
	//$lon = mysql_real_escape_string($_POST['lng']);
	
	print "<p>Thanks! We just need to collect a few details about " . $name . ", and then we're done.</p>\n";
	
	$bform = "<form action='/addnew.php' method='post' id='addform'>";
	
	//geog location details
	$bform .= "<input  type='hidden' name='lat' value='" . $lat . "' ></input>";
	$bform .= "<input  type='hidden' name='lng' value='" . $lon . "' ></input>";
	
	//stuff ppl can edit
	$bform .= "<input type='text' name='name' value='". $name . "' class='bigtext'></input>\n";
	
	print $bform; 
	?>
	
	<p>If you know the street # and address, please enter it. We can work out the neighborhood from the map location.</p>
	<input type='text' name='address' value='123 Something Street' onFocus="this.value='';" class='bigtext'></input>
	
	<p>If you've got photos of the building, you can upload them after the building is saved on the map.
	
	<h3>About you</h3>
	<p>To verify that you're a human and not a spamming computer, please enter your email. You'll get an email to verify your building submission. After that, we won't do anything else with your email address.</p>
	<input type='text' name='email' value='name@email.com' onFocus="this.value='';" class='bigtext'></input>
	
	<p>Optionally, give us a name or twitter so we can attribute this building to you.</p>
	<input type='text' name='contributor' value='your name' onFocus="this.value='';" class='bigtext'></input>
	<input type='text' name='twitter' value='@twitter' onFocus="this.value='';" class='bigtext'></input>
	<br><br>
	<input type='submit' value='Add this building' style='background: grey; color: pink; font-size: 1.2em'> or <a href="/map">Forget it, take me back.</a>
	</form>
	

	
	<?php	include "include/footer.inc"; ?>
	
</body>

</html>
