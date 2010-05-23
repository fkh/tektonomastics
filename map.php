<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Buildings</title>
	<meta name="author" content="frank">
	<!-- Date: 2010-04-04 -->

	<LINK href="http://tektonomastics.org/style.css" rel="stylesheet" type="text/css"></LINK>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />

   <!-- <script src="http://tektonomastics.org/lib/jquery-1.4.2.js" type="text/javascript" ></script>-->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
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
	
	<div id="map_canvas"></div>
	
	<div id=sidebar>
		
		<div id="accordion">
		    <h3><a href="#">Browse map</a></h3>
		    <div>
			<div id='profile'></div>
			<div id="buildingimg"></div>
			</div>
		    <h3><a href="#">Search</a></h3>
		    <div><input id="address" type="textbox" value="New York City">
		    <input type="button" value="Find" onclick="codeAddress()"></div>
		    <h3><a href="#" onclick="return addNew(event)">Add a building</a></h3>
		    <div><p>Drag the red map marker to set the building's location. Switch to Map or Satellite zooms for accurate placement.</p>
			<form action="http://tektonomastics.org/addnew.php" enctype="multipart/form-data" method="post" name="addform">

			<p>Building name<br>
			<input type="text" name="name" value="" id=""></input></p>

			<p>Notes<br>
			<textarea name="note" rows="4" cols="20"></textarea></p>

			<input  type="hidden" name="lat" value="" ></input>
			<input  type="hidden" name="lng" value="" ></input>

			<p># and street<br>
			<input type="text" name="address" value="" id=""></input></p>

			<b>About you</b>
			<p>Your name<br>
			<input type="text" name="contributor" value="" id=""></input></p>

			<p>Your twitter<br>
			<input type="text" name="twitter" value="" id=""></input></p>

			<input type="submit" value="Add this building">
			</form></div>
		</div>
		
		
	

	
		</div>	
		

	  </div>

</body>
</html>