<html>

<head>
		<title>Tektonomastics</title>

		<?php include "include/head.inc"; ?>

   <!-- <script src="/lib/jquery-1.4.2.js" type="text/javascript" ></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>-->
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>  

	<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>

	<script src="/script/buildingmap.js" type="text/javascript"></script> 


<script type="text/javascript"> 
	 function loadPhotos(id) {
		$("#buildingimg").load("/getphotos.php?id="+id+"");
	 }
</script>

<script type="text/javascript">
$(document).ready(function() {
	
	$('#addbox').hide();
	
	 $('#add_new').click(function() {
		$('#map_search').fadeOut();
		$('#new_building').fadeOut();
	    $('#addbox').fadeIn("slow");
	  });
	
	 $('#cancel_new').click(function() {
		$('#map_search').fadeIn();
		$('#new_building').fadeIn();
	    $('#addbox').hide('blind');
		clearOverlays();
	    return false;
	  });
	
	});
	
</script>


</head>



<body onload="loadMap(<?php if (isset($_GET['id'])) { echo $_GET['id']; } ?>)">
	<div id="body_container">
	
	<?php include "include/header.inc"; ?>
	<?php include "include/navbar.inc"; ?>
	
	<div id="map_search">
		<input id="address" type="textbox" value="Search for an address...">
	    <input type="button" value="Find" onclick="codeAddress()">
	</div>
	
	<div id="new_building"><a href="#" id="add_new" onclick="return addNew(event)">Add a building</a></div>
	<div id="addbox" name="msg">
	
	<div id="add-info">
	<h3>Step #1</h3><p><strong>Locate the building</strong></p><p>We dropped a red marker onto the map. Drag it to set the building's location. You might want to zoom in, for accurate placement.</p>
		
		</div>
		
		<div id="add-form">
			<h3>Step #2</h3><p><strong>Name the building</strong></p>
			<form action="/new/" enctype="multipart/form-data" method="post" name="addform">
 			<p><input type="text" name="name" value="building name" class="bigtext" onFocus="this.value='';"></input></p>
			<input  type="hidden" name="lat" value="" ></input>
			<input  type="hidden" name="lng" value="" ></input>
			<input type="submit" value="Next step" style="background: grey; color: pink; font-size: 1.2em"> or <a href=# id="cancel_new">Cancel</a>
			</form>
		</div>
		<div class="clear"></div>
	</div>
	
	<div id='building_info'>
		<div id='profile'>
		<div id='building-name'></div>
		<div id='building-address'></div>
		</div>

		<div id="buildingimg"></div>
	
	</div>
	
	<div id="map_canvas">
	</div>
 <!-- 	<div id="pano" style="position:absolute; left:410px; top: 8px; width: 400px; height: 300px;"></div> -->

		<?php	include "include/footer.inc"; ?>
</body>

</html>