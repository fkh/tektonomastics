<html>

<head>
		<title>Tektonomastics</title>

		<?php	include "include/head.inc"; ?>

   <!-- <script src="http://tektonomastics.org/lib/jquery-1.4.2.js" type="text/javascript" ></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>-->
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>  

	<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>


	<script src="http://tektonomastics.org/script/buildingmap.js" type="text/javascript"></script> 


<script type="text/javascript"> 
	 function loadPhotos(id) {
		$("#buildingimg").load("http://tektonomastics.org/getphotos.php?id="+id+"");
	 }
</script>

<script type="text/javascript">
$(function() {
		$("#accordion").accordion( {animated: false, autoHeight: false} );
	});
</script>


</head>


<body onload="loadMap()">
	<div id="body_container">
	
	<?php include "include/header.inc"; ?>
	<?php include "include/navbar.inc"; ?>
		
	<div id="map_canvas"></div>
	
	<div id=sidebar>
		
		<div id="accordion">
		    <h3><a href="#" onclick="return clearOverlays()">Browse map</a></h3>
		    <div>
			<!-- ><p><a href=# onclick="return mapZoom('all')">All</a> | Brooklyn | Queens | <a href=# onclick="return mapZoom('bx')">Bronx and N Manhattan</a></p> -->
			<div id='profile'>
			<div id='building-name'></div>
			<div id='building-address'>Click a building on the map to find out more.</div>
			</div>
			<div id="buildingimg"></div>
			</div>
		    <h3><a href="#">Search</a></h3>
		    <div><input id="address" type="textbox" value="New York City">
		    <input type="button" value="Find" onclick="codeAddress()"></div>
		    <h3><a href="#" onclick="return addNew(event)">Add a building</a></h3>
		    <div id='add-form'><p>Drag the red map marker to set the building's location. Switch to Map or Satellite zooms for accurate placement.</p>
			<form action="http://tektonomastics.org/addnew.php" enctype="multipart/form-data" method="post" name="addform">

			<p>Building name *<br>
			<input type="text" name="name" value="" id=""></input></p>

			<p>Notes<br>
			<textarea name="note" rows="4" cols="20"></textarea></p>

			<input  type="hidden" name="lat" value="" ></input>
			<input  type="hidden" name="lng" value="" ></input>

			<p># and street<br>
			<input type="text" name="address" value="" id=""></input></p>

			<b>About you</b>
			<p>Your email * <small>Please give a valid email address - we'll send you a verification email, simply click on the link in the email to publish your building.</small><br>
			<input type="text" name="contributor" value="" id=""></input></p>

			<p>Your twitter<br>
			<input type="text" name="twitter" value="" id=""></input></p>

			<input type="submit" value="Add this building">
			</form></div>
		</div>
		
	</div>
	<div class="push"></div>

	</div>

	<!-- footer -->
		<?php	include "include/footer.inc"; ?>
</body>

</html>