<?php
		
		require_once 'connect.php';
		include 'include/phpflickr-3.1.1/phpFlickr.php';
		
		// set up flickr authentication
		$f = new phpFlickr($flickrkey, $flickrsecret);
		$f->setToken($flickrtoken);
		
		//get a database connection
		$dbconnection = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error.');
		mysql_select_db($dbname, $dbconnection);
		
		//first, check for a valid id
		if ($_GET['id'] > 0 ) {
			
		$building = $_GET['id'];
		
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
		
		//var to hold the output list of image names
		$photosHtml = "";
		
		//then, loop through the flickr ids and look up the image links
		while ($row = mysql_fetch_array($db, MYSQL_BOTH)) {
		
			$flickrdata = $f->photos_getSizes($row['flickrImage']);
			
			$photosHtml .= "<img src='" . $flickrdata[0][source] . "' id=thumbnail>";
			
		} //end while


		
		} else {
			if ($_GET['recent'] == 1 ) { //get the eight most recent pics
			
				
			if ( (isset($_GET['pics'])) && ($_GET['pics'] > 0) ) {
				$picQty = $_GET['pics'] ; 
				} else {
				$picQty = 7;	
			}
			
					$getdb = "SELECT f.flickrimage as fi, b.sortname as bn, b.name as bname, f.timestamp as ts FROM flickr as f, building as b where f.buildingId = b.id group by b.sortname order by f.timestamp desc limit " .   $picQty . ";";

					if (!mysql_query($getdb,$dbconnection))
					  {
					  	die('Error: ' . mysql_error());
						exit;
					  }

					$db = mysql_query($getdb);
					
					$photosHtml = "";
					$flickrdata = array();
					
					while ($row = mysql_fetch_array($db, MYSQL_BOTH)) {
						$flickrdata = $f->photos_getSizes($row['fi']);
						
						$photosHtml .= "<a href='/name/" . $row['bn'] . "'><img name='thumb' alt='" . $row['bname'] . "' src='" . $flickrdata[0][source] . "' id=thumbnail></a>\n";
						
						

					} //end while

			}
	
		}//end of 'if' for having a db id. 
		
	
		echo $photosHtml;
		
?>
