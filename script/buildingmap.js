	var map;
	
	var infoWindow = new google.maps.InfoWindow( { 
		size: new google.maps.Size(150,150)
	  });
	
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
		
function loadMap(id) {
	
	//we need this for addresses
	geocoder = new google.maps.Geocoder();
	bounds = new google.maps.LatLngBounds(); 
	
	var latlng = new google.maps.LatLng(40.67, -73.96);
	var zoomLevel = 14;
	
	//get the lat/lon, if we have a single point

	if (id > 0) {

		zoomLevel = 17
		
		$.ajax({
				type: "GET",
				url: "/markers.php?id=" + id,
				dataType: "xml",
				async: false,
				success: function(xml) {
					$(xml).find('marker').each(function(){
						latlng = new google.maps.LatLng($(this).attr('lat'), $(this).attr('lon'));
					});
				}
			});
	}
      
	var myOptions = {
    zoom: zoomLevel,
    center: latlng,
    mapTypeId: google.maps.MapTypeId.HYBRID,
	mapTypeControlOptions: {
	        style: google.maps.MapTypeControlStyle.DROPDOWN_MENU,
	        mapTypeIds: [google.maps.MapTypeId.ROADMAP,
	                    google.maps.MapTypeId.SATELLITE,
	                    google.maps.MapTypeId.HYBRID,
	                    google.maps.MapTypeId.TERRAIN]
	      },
	
  };

 
	map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	
	google.maps.event.addListener(map, 'click', function() {
        infowindow.close();
    });
  
	//set center point values for the form
	newLat = map.getCenter().lat();
	newLng = map.getCenter().lng();
	document.addform.lat.value = newLat;
	document.addform.lng.value = newLng;
		
	getPoints(0);
	if (id > 0) {
		getPoints(id);
	}
	
}


	//for adding a new pushpin, for a new building
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
	
	
	function clearOverlays() { //tidy up the map and info pane
	  if (highlightList) {
	    for (i in highlightList) {
	      highlightList[i].setMap(null);
	    }
	  }

	}
	
	function bindInfoWindow(marker, map, infoWindow, html) {
	  google.maps.event.addListener(marker, 'click', function() {
	    infoWindow.setContent(html);
	    infoWindow.open(map, marker);
	  });
	}
	
	//makes map pins
	function addMarker(loclat, loclon, html, id) {
		
		if (id > 0) {
			var icon = customIcons['active'];
			var zInd = 999;
		} else {
			var icon = customIcons['small'];
			var zInd = 0;
	    }
	
		var location = new google.maps.LatLng(loclat,loclon);
		
		marker = new google.maps.Marker({
		    position: location,
		    map: map,
			icon: icon.icon,
			shadow: icon.shadow,
			zIndex: zInd
		  });
		
		bindInfoWindow(marker, map, infoWindow, html);
		
		if (id > 0 ) {
			map.panTo(location);
			map.setZoom(17)
		}
	
	}
	
	//fetches xml from markers.php, to set up map pins
	function getPoints(id) {
		
		markersSource = "/markers.php";
			
		if (id > 0) {	
			markersSource = markersSource + "?id=" + id ;
		}
		
		$.ajax({
				type: "GET",
				url: markersSource,
				dataType: "xml",
				success: function(xml) {
					$(xml).find('marker').each(function(){
						linktext = "<a href='/name/" + $(this).attr('sortname') + "'>";
						infoHTML = "<b>" + $(this).attr('name') + "</b><br>" + $(this).attr('address') + "<br>" + linktext + "View details and photos in inventory</a>";
						addMarker($(this).attr('lat'), $(this).attr('lon'), infoHTML, id);
								
					});
				}
			});
		

		
	}
	
