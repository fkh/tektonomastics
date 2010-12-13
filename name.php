<html>

<head>
	<title>Tektonomastics</title>

	<?php include "include/head.inc"; ?>
	
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>  
	

	<script type="text/javascript">
	$(document).ready(function() {

		$('div.photo-form').hide();

		 $('a.add_photo').click(function() {
		    $('div.photo-add').hide();
		    $('div.photo-form').fadeIn("slow");
			return false;
		  });

		 $('#cancel_new').click(function() {
		    $('#photo-form').hide('blind');
			$('#photo-add').show();
		    return false;
		  });

		});
		

	</script>
	  
	</head>

		<body>

	<div id='body_container'>
	<?php include "include/header.inc"; ?>
	<?php include "include/navbar.inc"; ?>

	<div id='inventory-canvas'>


<?php

		include 'connect.php';
		include 'include/photos.inc';
		
		//whatever the passed name is, get it
		
		$building = urldecode($_GET['name']) ;
				
			if (!($_GET['name'])) {

				echo "Oops. Something went wrong.";

			}

		// echo "<h2>" . $building . "</h2>";
		
		//array of db field names
		//$dbnames = Array("id", "lat", "lon", "address",  "boro", "zip", "city", "photo", "name", "note",   "contributor", "twitter", "timestamp");
		
		//ok, now get a database connection
		$dbconnection = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error.');
		mysql_select_db($dbname, $dbconnection);
		
		//safe name
		$safename = addslashes($building);
		
		//echo $safename . " ";
				
		$query = "SELECT id, name, sortname, address, boro, contributor, twitter, zip, lat, lon, timestamp FROM building WHERE sortname = '" . $safename ."' AND rowlock = 0 ORDER BY sortname ASC;";
		
		//echo $query;
	
		$db = mysql_query($query);
		
		$numbuildings = mysql_num_rows($db);
		
		if ($numbuildings == 0 ) {

			echo "Oops. Something went wrong. Can't find a building called " . $safename . ".";

		}
		
		if ($numbuildings > 1 ) {
			echo "<p>There are " . $numbuildings . " buildings called " . $building . "! </p>";
		} 
		
		//we might have more than one building with the same name
		while ($row = mysql_fetch_array($db, MYSQL_BOTH)) {								
			
			echo "<h2>" . stripslashes($row['name']) . "</h2>" ;
					
			//address block
			if ($row['address'] <> "") { 
				
			echo "<p>" . $row['address'] ;
			
			// if boro
			if ($row['boro'] <> "") { echo ", " . $row['boro'] ; } 
			
			// if zip
			if ($row['zip']<> "") { echo ", " . $row['zip'] ; } 
			
			echo ".</p>"; 
			
			} else {	
				echo "<p><emp>No address listed</emp></p>" ;
			}
			
			echo "<p><a href='/map/" . $row['id'] . "'>View on map</a>.</p>";
			
			
			$buildingid = $row['id'];
			
			// add photos
			$photoBlock = "<div class='photo-add'><a href='#' class='add_photo'>Add a photo.</a><br><br></div><div class='photo-form'>";		
				$photoBlock .= "<div id=photo-form-header>Upload a photo of this building</div><br>";
				$photoBlock .= "<div id=photo-form-body>";
				$photoBlock .= "<form action='/addphoto.php' enctype='multipart/form-data' method='post' name='addphoto'>";
				$photoBlock .= "<input type='file' size='15' name='uploadFile'><input  type='hidden' name='id' value='" . $buildingid . "'></input><p>Your name (so we can credit you on Flickr!)</p><p><input type='text' name='contributor' value='' id=''></input></p><input type='submit' value='Upload'>";
				$photoBlock .= "</form></div></div>" ;
				
			$photos = getPhotos($buildingid); 

			$img_block = "";
			
			//prepare the photo block
			foreach ((array)$photos[photos] as $photo) {	
				$img_block .= "<p><img src='" . $photo . "'></p>\n";
			}
			
			foreach ((array)$photos[comments][flickr] as $flickr) {	
				$imgid = $flickr;
			}
			
			//work out how long the array is
			
			$com_count = count((array)$photos[comments][author]);
			if ($com_count > 0) {
			$comment_block = "<h4>Comments</h4>";
			//write out comment block
			for ($com_i = 0; $com_i < $com_count; $com_i++){
				
				$commentdate = $photos[comments][comdate][$com_i];
				
				$comment_block .= "<p>" . $photos[comments][comcon][$com_i];
				
				$comment_block .= "<br> -- " . $photos[comments][author][$com_i] . ", "; 
				
				$comment_block .= date( "F", $commentdate ) . " " . date( "Y", $commentdate ) . "</p>";
				
			}
			}
						
			if ($imgid) {
			$comment_block .= "<p><a href='http://www.flickr.com/photos/tektonomastics/" . $imgid . "'>Add a comment</a> via Flickr (it'll show up here!).</p>"; 
			}
			
			//print the images
			print $comment_block;
			print $photoBlock;
			print $img_block;
			
			//small static map
			$lat = $row['lat'];
			$lon = $row['lon'];			
			print "<a href='/map/". $row['id'] ."'><img src='http://maps.google.com/maps/api/staticmap?center=" . $lat . "," . $lon . "&zoom=17&size=700x200&markers=color:blue|" .  $lat . "," . $lon . "&sensor=false' /></a>";
			
			// credit
			$contrib_credit = "<p>Building contributed by ";
			
			//date details
			$contrib_date = date( "F", $row['timestamp'] ) . " " . date( "Y", $row['timestamp'] );		
			
			//if contributor
			$contrib = $row['contributor'];
			$twitter = $row['twitter'];
				
			if ( $twitter && $twitter <> "twitter") {
				
				$contrib_credit .= "<a href='http://twitter.com/" . $twitter . "'>@" . $twitter . "</a>";
			
			} else {
				if  ( $contrib && $contrib <> "your name" ) {
					$contrib_credit .= $contrib ;
				} else {
					$contrib_credit = "Anonymous contribution";
				}
			}

			$contrib_credit .= ", " . $contrib_date . ".</p>"; 
			
			print $contrib_credit;
				
			}
			
			//footer section of page
									
			//get previous building			
			$query = "SELECT sortname FROM building WHERE sortname < '" . $safename ."'   ORDER BY sortname DESC LIMIT 1;";
			$db = mysql_query($query);
			while ($row = mysql_fetch_array($db, MYSQL_BOTH)) {
				$prevbuild = stripslashes($row[0]);
			}

			//get next building
			$query = "SELECT sortname FROM building WHERE sortname > '" . $safename ."'   ORDER BY sortname ASC LIMIT 1;";
			$db = mysql_query($query);
			while ($row = mysql_fetch_array($db, MYSQL_BOTH)) {
				$nextbuild = stripslashes($row[0]); 	
			}
			
			//prev url
			$prevurl = "/name/" . urlencode(($prevbuild)) ;
			$nexturl = "/name/" . urlencode(($nextbuild)) ;
			
			echo "<p> Previous: <a href='" . $prevurl . "'>" . $prevbuild . "</a> | Next: <a href='" . $nexturl . "'>"   . $nextbuild . "</a></p>" ;			

		//tidy up the mysql connection
		mysql_close($dbconnection);
			
	?>
			
		</div>
		
		<div id='sidebar'>
		</div>
		<div class="push"></div>
		
		</div>
		<!-- footer -->
		<?php include "include/footer.inc"; ?>
</body>
	
</html>