<html>

<head>
	<title>Tektonomastics</title>

	<?php include "include/head.inc"; ?>
	  
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
			
			$photos = getPhotos($row['id']); 
			
			foreach ($photos as $photo) {
				
				echo "<img src='" . $photo . "'><br><br>\n";
			}
			
			// credit
			$contrib_credit = "<p> Contributed by ";
			
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