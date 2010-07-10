<?php 		
	
	//	returns a list of flickr image urls.
	
		include 'include/phpFlickr.php';
		
		function getPhotos($building)
		{
		
			include 'connect.php';
		
			// set up flickr authentication
			$f = new phpFlickr('247d8333f05337cfc918849ff141b0c6', 'b449e6d4f9bb6d30');
			$f->setToken('72157623802048061-6ef5b17ea0b52483');	
			
			//get a database connection
			$dbconnection = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error.');
			mysql_select_db($dbname, $dbconnection);

			//secondly, go to our database and get the list of flickr ids	
			$getdb = "SELECT * FROM flickr where buildingId = " . $building . ";";
			
			if (!mysql_query($getdb,$dbconnection))
			  {
			  	die('Error: ' . mysql_error());
				exit;
			  }

			$db = mysql_query($getdb);

			
			//for the flickr image size info
			$flickrdata = array();
			$photos = array();

			//var to hold the output list of image names
			$photosHtml = "";
			
			//then, loop through the flickr ids and look up the image links
			while ($row = mysql_fetch_array($db, MYSQL_BOTH)) {

				$flickrdata = $f->photos_getSizes($row['flickrImage']);

				// print_r($flickrdata);

			array_push($photos, $flickrdata[3][source]) ;

			}
			
			return $photos;
			
		}
		
?>