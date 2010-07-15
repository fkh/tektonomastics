	var map;
	
	var infoWindow;
	
	var geocoder;
	
	var newLat;
	var newLng;
	
	var focal;
	
	var bId = [];
	
	var markerList = [];
	var highlightList = [];
	
	var customIcons = {
	      small: {
	        icon: 'http://labs.google.com/ridefinder/images/mm_20_blue.png',
	        shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
	      },
		  active: {
		    icon: 'http://labs.google.com/ridefinder/images/mm_20_red.png',
	        shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'	
		}
	    };
	
	//array to hold the map markers and highlight markers
	var baseMarkers = [];
	var hiliteMarker = [];
	
	var xml;
	
	//get the listing of map markers
	$(document).ready(function(){
		$.ajax({
		        type: "GET",
				url: "/markers.php?type=new",
				dataType: "xml",
				success: parsexml
				});
		});
		
		var markers;
		
function loadMap() {
	
	//we need this for addresses
	geocoder = new google.maps.Geocoder();
	bounds = new google.maps.LatLngBounds(); 
      
	var latlng = new google.maps.LatLng(40.67, -73.96);
	var myOptions = {
    zoom: 14,
    center: latlng,
    mapTypeId: google.maps.MapTypeId.HYBRID,
	mapTypeControlOptions: {
	        style: google.maps.MapTypeControlStyle.DROPDOWN_MENU,
	        mapTypeIds: [google.maps.MapTypeId.ROADMAP,
	                    google.maps.MapTypeId.SATELLITE,
	                    google.maps.MapTypeId.HYBRID,
	                    google.maps.MapTypeId.TERRAIN]
	      },
	//streetViewControl: true
	
  };
 
	map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	
	//var panorama = new  google.maps.StreetViewPanorama(document.getElementById("pano"));
	//map.setStreetView(panorama);
    
	
	//set center point values for the form
	newLat = map.getCenter().lat();
	newLng = map.getCenter().lng();
	document.addform.lat.value = newLat;
	document.addform.lng.value = newLng;

	// infoWindow = new google.maps.InfoWindow;

	downloadUrl("/markers.php", function(data) {
	  var xml = parseXml(data);
	  markers = xml.documentElement.getElementsByTagName("marker");

	  var notFixed = true;
	  for (var i = 0; i < markers.length; i++) {
		
		var building = markers[i].getAttribute("building");
		
		bId[building] = i;
		
	    var name = markers[i].getAttribute("name");
	    var address = markers[i].getAttribute("address");
	    var point = new google.maps.LatLng(
		        parseFloat(markers[i].getAttribute("lat")),
		        parseFloat(markers[i].getAttribute("lon"))
			);
			
		bounds.extend(point); 
		addMarker(point, building);
		
	  }
	
	//map.fitBounds(bounds);
		
	});
		
	
}


	//make a new map marker with our default styling
	function addMarker(location, id) {
		
		var icon = customIcons['small'] || {};
	    
		marker = new google.maps.Marker({
		    position: location,
		    map: map,
			icon: icon.icon,
			shadow: icon.shadow
		  });
		
		google.maps.event.addListener(marker, 'click', function() {
			loadProfile(id);
			loadPhotos(id);
			idClicked(id);
		});
		
		baseMarkers[id] = location;
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
		
		//get rid of any overlays
		clearOverlays();
		$("#building-name").empty();
		$("#building-address").empty();
		$("#buildingimg").empty();
		
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
		
		highlightList.push(newPoint);
		
		google.maps.event.addListener(newPoint, 'dragend', function() {
			map.setCenter(newPoint.position);
			newLat = newPoint.position.lat();
			newLng = newPoint.position.lng();
			document.addform.lat.value = newLat;
			document.addform.lng.value = newLng;
		}
		);
		
		var html = " ";
	 
		// bindInfoWindow(newPoint, map, infoWindow, html);
		
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
	
	function loadProfile(id) {
		
		//fetch the database info for this bulding
				
		$("#building-name").html(markers[bId[id]].getAttribute("name"));
		$("#building-address").html(markers[bId[id]].getAttribute("address"));
		$("#buildingimg").html("<div id='info'>Loading pics...</div>");


	}
	
	function addHiliteMarker(id) {
	//	var redHighlight = google.maps.MarkerImage({icon: 'http://labs.google.com/ridefinder/images/mm_20_red.png'});
	//	baseMarkers[id].setImage(redHighlight);
	}
	

	function idClicked(id) {
		clearOverlays();
		
		$("#accordion").accordion("activate", 0); //open first panel
		
		var icon = customIcons['active'] || {} ;
		
		marker = new google.maps.Marker({
	    position: baseMarkers[id],
		icon: icon.icon,
	    map: map
	  });
	  
	//	alert(baseMarkers[id]);
		highlightList.push(marker);
	}
	
	
	function clearOverlays() { //tidy up the map and info pane
	  if (highlightList) {
	    for (i in highlightList) {
	      highlightList[i].setMap(null);
	    }
	  }

	}
	
	function mapZoom(place) {
		
		switch(place) {
			case 'all':
				map.fitBounds(bounds);
				break;
			case 'bx':
				map.panTo(new google.maps.LatLng(40.83, -73.93));
				map.setZoom(13);
				break;
		}
	}
