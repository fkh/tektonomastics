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
				
		$query = "SELECT id, name, sortname, address, boro, contributor, twitter, zip, lat, lon, timestamp FROM building WHERE sortname	 = '" . $safename ."'   ORDER BY sortname ASC;";
		
		//echo $query;
	
		$db = mysql_query($query);
		
		$numbuildings = mysql_num_rows($db);
		
		if ($numbuildings == 0 ) {

			echo "Oops. Something went wrong. Can't find a building called " . $safename . ".";

		}
		
		if ($numbuildings > 1 ) {
			echo "<p>There are " . $numbuildings . " buildings called " . $building . "! </p>";
		} 
		
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
			
			$buildingid = $row['id'];
			
			// add photos
			$photoBlock = "<div class='photo-add'><a href='#' class='add_photo'>Add a photo</a><br></div><div class='photo-form'>";		
				$photoBlock .= "<div id=photo-form-header>Upload a photo of this building</div><br>";
				$photoBlock .= "<div id=photo-form-body>";
				$photoBlock .= "<form action='/addphoto.php' enctype='multipart/form-data' method='post' name='addphoto'>";
				$photoBlock .= "<input type='file' size='15' name='uploadFile'><input  type='hidden' name='id' value='" . $buildingid . "'></input><p>Your name (so we can credit you on Flickr!)</p><p><input type='text' name='contributor' value='' id=''></input></p><input type='submit' value='Upload'>";
				$photoBlock .= "</form></div></div>" ;
				
				print $photoBlock;
				
				
			
			$photos = getPhotos($buildingid); 
			
			foreach ($photos as $photo) {
				
				echo "<p><img src='" . $photo . "'></p>\n";
			}
			
			// credit
			$contrib_credit = "<p>Building contributed by ";
			
			//date details
			$contrib_date = date( "F", $row['timestamp'] ) . " " . date( "Y", $row['timestamp'] );		
			
			//if contributor
			$contrib = $row['contributor'];
			$twitter = $row['twitter'];
				
			if ( $twitter ) {
				
				$contrib_credit .= "<a href='http://twitter.com/" . $twitter . "'>@" . $twitter . "</a>";
			
			} else {
				if  ( $contrib ) {
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