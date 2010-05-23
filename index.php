<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Buildings</title>
	<meta name="author" content="frank">
	<!-- Date: 2010-04-04 -->

	<LINK href="style.css" rel="stylesheet" type="text/css"></LINK>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />

    <script type="text/javascript" src="lib/jquery-1.4.2.js"></script>

<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>

<script type="text/javascript">

	var map;
	
	var infoWindow;
	
	var geocoder;
	
	var newLat;
	var newLng;
	
	var focal;
	<?php
	if (isset($_GET[id])) {
		$id =  $_GET[id];
		echo "focal = " . $id . ";";
		}
	?>

	
	var customIcons = {
	      small: {
	        icon: 'http://labs.google.com/ridefinder/images/mm_20_blue.png',
	        shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
	      }
	    };
	
	var xml;
	
	//get the listing of map markers
	$(document).ready(function(){
		$.ajax({
		        type: "GET",
				url: "markers.php?type=new",
				dataType: "xml",
				success: parsexml
				});
		});

	
		var markers;
		
  function load() {
	

		
	//we need this for addresses
	geocoder = new google.maps.Geocoder();
	
	bounds = new google.maps.LatLngBounds(); 
	

    
	<?php
	
	$zoomlevel = 16;
	$lat = 40.7;
	$lng = -73.9;
	
	if (isset($_POST[newlat])) {
		$lat = $_POST[newlat];
		$lng = $_POST[newlng];

	$zoomlevel = 18;
	
	}	
	
	echo "var latlng = new google.maps.LatLng(" . $lat . ", " . $lng . ");";
	echo "var myOptions = {" ;
	echo "zoom: " . $zoomlevel . ",";
	
	?>
        
      center: latlng,
      mapTypeId: google.maps.MapTypeId.TERRAIN
    };
   
 	map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	
	//set center point values for the form
	newLat = map.getCenter().lat();
	newLng = map.getCenter().lng();
	document.addform.lat.value = newLat;
	document.addform.lng.value = newLng;

	 infoWindow = new google.maps.InfoWindow;

	downloadUrl("markers.php", function(data) {
	  var xml = parseXml(data);
	  markers = xml.documentElement.getElementsByTagName("marker");
	  var notFixed = true;
	  for (var i = 0; i < markers.length; i++) {
		var building = markers[i].getAttribute("building");
	    var name = markers[i].getAttribute("name");
	    var address = markers[i].getAttribute("address");
	    var point = new google.maps.LatLng(
	        parseFloat(markers[i].getAttribute("lat")),
	        parseFloat(markers[i].getAttribute("lon")));
		
		if (building == focal) {
			map.panTo(point);
			map.setZoom(19);
			map.setMapTypeId(google.maps.MapTypeId.HYBRID)
			notFixed = false;
			
		}
		
		bounds.extend(point); 
		
	    var html = "<b>" + name + "</b> <br/>" + address + "<br><br>" ;
	    var icon = customIcons['small'] || {};
	    var marker = new google.maps.Marker({
	      map: map,
	      position: point,
	      icon: icon.icon,
	      shadow: icon.shadow,
		  title: name
	    });
	    bindInfoWindow(marker, map, infoWindow, html, building);
	  }
	
	if (notFixed) {
	map.fitBounds(bounds);
	}
	
	});
		

	
  }

	
	function bindInfoWindow(marker, map, infoWindow, html, id) {
	  google.maps.event.addListener(marker, 'click', function() {
		
		//add photo form to the building data
		var formhtml;
		var outhtml;
		
		formhtml = "<p>Add a photo to this building</p><form action='addphoto.php' enctype='multipart/form-data' method='post' name='addphoto'><input type='file' name='uploadFile'><input  type='hidden' name='id' value='" + id + "'></input><p>Your name</p><p><input type='text' name='contributor' value='' id=''></input></p><input type='submit' value='Upload'></form>";

		outhtml = html + formhtml;
		//report out the building info
		document.getElementById("profile").innerHTML = outhtml;
		loadPhotos(id);
	//	map.panTo(marker.getPosition());
	  });
	}
	
	
	function downloadUrl(url,callback) {
	 var request = window.ActiveXObject ?
	     new ActiveXObject('Microsoft.XMLHTTP') :
	     new XMLHttpRequest;

	 request.onreadystatechange = function() {
	   if (request.readyState == 4) {
	     request.onreadystatechange = doNothing;
	     callback(request.responseText, request.status);
	   }
	 };

	 request.open('GET', url, true);
	 request.send(null);
	}
	
	function parseXml(str) {
      if (window.ActiveXObject) {
        var doc = new ActiveXObject('Microsoft.XMLDOM');
        doc.loadXML(str);
        return doc;
      } else if (window.DOMParser) {
        return (new DOMParser).parseFromString(str, 'text/xml');
      }
    }

    function doNothing() {}

	function addNew(e) {
		
		//change map to be a hybrid, better for point placement
	//	map.setTypeId(google.maps.MapTypeId.HYBRID);
		
		var centerPoint = new google.maps.LatLng();
		centerPoint = map.getCenter();
		
		newLat = centerPoint.lat();
		newLng = centerPoint.lng();
		document.addform.lat.value = newLat;
		document.addform.lng.value = newLng;
		
		var newPoint = new google.maps.Marker({
			position: centerPoint,
			map: map,
			draggable: true
		});
		
		google.maps.event.addListener(newPoint, 'dragend', function() {
			map.setCenter(newPoint.position);
			newLat = newPoint.position.lat();
			newLng = newPoint.position.lng();
			document.addform.lat.value = newLat;
			document.addform.lng.value = newLng;
		}
		);
		
		var html = "<table>" +
	                 "<tr><td>Building name*:</td> <td><input type='text' id='name'/> </td> </tr>" +
	                 "<tr><td># and street </td> <td><input type='text' id='address'/></td> </tr>" +
	                 "<tr><td>Notes</td> <td><input type='text' id='notes'/></td> </tr>" +
		             "<tr><td>Photo</td> <td> <input type='file' name='uploadFile'></td> </tr>" +
				     "<tr><td>Your name</td> <td><input type='text' id='contributor'/></td> </tr>" +
					 "<tr><td>Your twitter name</td> <td><input type='text' id='twitter'/></td> </tr>" +
	                 "<tr><td></td><td><input type='button' value='Add' onclick='saveData()'/></td></tr>";
	 
		bindInfoWindow(newPoint, map, infoWindow, html);
		
		return false;
	}
	
	
	function codeAddress() {
	    var address = document.getElementById("address").value;
	    if (geocoder) {
	      geocoder.geocode( { 'address': address}, function(results, status) {
	        if (status == google.maps.GeocoderStatus.OK) {
	          map.setCenter(results[0].geometry.location);
	 		  map.setZoom(18);
			//	addNew();
	        } else {
	          alert("Geocode was not successful for the following reason: " + status);
	        }
	      });
	    }
	  }
	
	
	
	function saveData() {
      var name = escape(document.getElementById("name").value);
      var address = escape(document.getElementById("address").value);
      var type = document.getElementById("type").value;
      var latlng = marker.getPosition();
 
      var url = "phpsqlinfo_addrow.php?name=" + name + "&address=" + address +
                "&type=" + type + "&lat=" + latlng.lat() + "&lng=" + latlng.lng();
      downloadUrl(url, function(data, responseCode) {
        if (responseCode == 200 && data.length <= 1) {
          infowindow.close();
          document.getElementById("message").innerHTML = "Location added.";
        }
      });
    }

	function buildingInfo (id, lat, lon, address, name, contributor) {
		
		this.id = id;
		this.lat = lat;
		this.lon = lon;
		this.address = address;
		this.name = name;
		this.contributor = contributor;
		
	}
	
	function parsexml(xml) {
		$(xml).find('building').each(function(){
		//	var title = $(this).find('name').text();
		//	$("#markers").append('<p>' + title + '</p>');
		});
	}
	
	
    
</script>

<script type="text/javascript"> 
	function loadPhotos(id) {
		$("#buildingimg").load("getphotos.php?id="+id+"");
	}
</script>

<script type="text/javascript" charset="utf-8">
	$(document).ready(function(){
   $("a.nav").toggle(function(){
   		$(".add").show('slow');
		$(".nav").hide('fast');
		$("#profile").hide();
		$("#buildingimg").hide();		
	},function(){
	    $(".add").hide('fast');
	});
 });
</script>

</head>

<body onload="load()">
	
	<!-- header/navigation -->
	
	<div id="navigation">
		<ul id="menuitems">
		<li><a href="about/">About</a></li>
		<li><a href="map/">Map</a></li>
		<li><a href="inventory/">Inventory</a></li>
		<li><a href="http://blog.tektonomastics.org">Blog</a></li>
		</ul>
	</div>
	

</body>
</html>