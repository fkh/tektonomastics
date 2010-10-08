<?php
		
		include 'connect.php';
		include 'include/phpFlickr.php';
		
		// set up flickr authentication
		$f = new phpFlickr('247d8333f05337cfc918849ff141b0c6', 'b449e6d4f9bb6d30');
		$f->setToken('72157623802048061-6ef5b17ea0b52483');
		
		//get a database connection
		$dbconnection = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error.');
		mysql_select_db($dbname, $dbconnection);
		
		//first, check for a valid id
		if ($_GET['id'] > 0 ) {
			
		$building = $_GET['id'];
		
		// echo $building;

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

		//	print_r($flickrdata);
			
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
		
		
		
		//return a list of images
		

		
		//tidy up the mysql connection
	//	mysql_close($dbconnection);
?>