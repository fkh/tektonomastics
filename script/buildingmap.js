	var map;
	
	var infoWindow;
	
	var geocoder;
	
	var newLat;
	var newLng;
	
	var focal;
	
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
				url: "http://tektonomastics.org/markers.php?type=new",
				dataType: "xml",
				success: parsexml
				});
		});
		
	
		var markers;
		
  function loadMap() {
	

		
	//we need this for addresses
	geocoder = new google.maps.Geocoder();
	
	bounds = new google.maps.LatLngBounds(); 
        
	var latlng = new google.maps.LatLng(40.7, -73.9);
	var myOptions = {
      zoom: 16,
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

	downloadUrl("http://tektonomastics.org/markers.php", function(data) {
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
		
		var html = " ";
	 
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
