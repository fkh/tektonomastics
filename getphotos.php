<?php
		
		include 'connect.php';
		include 'include/phpFlickr.php';
		
		// set up flickr authentication
		$f = new phpFlickr('247d8333f05337cfc918849ff141b0c6', 'b449e6d4f9bb6d30');
		$f->setToken('72157623802048061-6ef5b17ea0b52483');
		
		//first, check for a valid id
		if ($_GET['id'] > 0 ) {
			
		$building = $_GET['id'];
		
		// echo $building;
		
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
		
		//var to hold the output list of image names
		$photosHtml = "";
		
		//then, loop through the flickr ids and look up the image links
		while ($row = mysql_fetch_array($db, MYSQL_BOTH)) {
		
			$flickrdata = $f->photos_getSizes($row['flickrImage']);

		//	print_r($flickrdata);
			
			$photosHtml .= "<img src='" . $flickrdata[0][source] . "' id=thumbnail>";
			
		} //end while

			$photosHtml .= "<div id=photo-form>";		
		
			$photosHtml .= "<div id=photo-form-header>Add a photo</div> ";
			
			$photosHtml .= "<div id=photo-form-body>";
			
			$photosHtml .= "<form action='/addphoto.php' enctype='multipart/form-data' method='post' name='addphoto'>";
			
			$photosHtml .= "<input type='file' size='15' name='uploadFile'><input  type='hidden' name='id' value='" . $building . "'></input><p>Your name</p><p><input type='text' name='contributor' value='' id=''></input></p><input type='submit' value='Upload'>";
			
			$photosHtml .= "</form></div></div>" ;
		
		} //end of 'if' for having a db id.
		
	
		echo $photosHtml;
		
		
		
		
		//return a list of images
		

		
		//tidy up the mysql connection
	//	mysql_close($dbconnection);
?>