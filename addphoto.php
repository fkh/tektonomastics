<?php

		include 'connect.php';
		include 'include/phpFlickr.php';
		
		
		// set up flickr authentication
		$f = new phpFlickr('247d8333f05337cfc918849ff141b0c6', 'b449e6d4f9bb6d30', false);
		$f->setToken('72157623802048061-6ef5b17ea0b52483');
		
		if (isset($_POST['id'])) { //then we have something to add to the database
			
		//	echo "found id ";
			
		//get the answers safe for the db
		$timestamp = time();
		$name = mysql_real_escape_string($_POST['id']);
		$contributor = mysql_real_escape_string($_POST['contributor']);
			
		//upload the file, ready for flickr
		if ($_FILES['uploadFile']['size'] > 0 ) { 
		//	echo "have file";
			$oldname = $_FILES['uploadFile']['name'];
			$filetype = end(explode('.',$oldname)); 
			$newname = md5(rand().$oldname).".".$filetype;
			
			move_uploaded_file ($_FILES['uploadFile'] ['tmp_name'], "images/".$newname);
		//	echo "moved ";

		}
				
		//get a database connection
		$dbconnection = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error.');
		mysql_select_db($dbname, $dbconnection);
		
		$query = "SELECT NAME, sortname, lat, lon FROM building WHERE ID = ". $name . ";";

		$db = mysql_query($query);

		while ($row = mysql_fetch_array($db, MYSQL_BOTH)) {								
		
			$buildingname = $row[0] ; 
			$lat = $row['lat'];
			$lon = $row['lon'];
			$sortname =  $row['sortname'];
			
		}
		
		//upload vars
		//$photo_url = "http://tektonomastics.org/images/" . $newname;
		$photo_url = "images/" . $newname;
		
		if (!$buildingname) {
			$title = "(building name not given)";
		} else {
			$title = $buildingname;
		}
		
		$url_name = str_replace(" ", "_", $buildingname) ;
		
		
		$description = $buildingname . " is listed in <a href='http://tektonomastics.org'>Tektonomastics</a>, the building names project.\n\n <a href='http://blog.tektonomastics.org'>More about the project</a>. \n<a href='http://tektonomastics.org/map/'>Add a building!</a> \n <a href='http://tektonomastics.org/name/" . $url_name . "'>See all buildings called " . $buildingname . "</a>." ; //FIXME
		$tags = "tektonomastics";
		
		//echo $photo_url;
		
		//now upload the image to flickr
		
		$flickrid = $f->sync_upload($photo_url, $title, $description, $tags);
	//	$flickrid = $f->sync_upload("images/" . $newname);		

		if ($flickrid == 0) {
			$flickrerror = $f->getErrorMsg();
			$flickrcode = $f->getErrorCode();
			
			echo "<html><head><title>Error!</title></head><body id='addphoto'>Oops! Something went wrong. It looks like your image didn't upload. <a href='http://tektonomastics.org/contact'>Please let us know about this problem</a>  by sending us the following info:<br><br>Technically speaking, the error was: <em>#" . $flickrcode . " - " . $flickrerror . "</em>. <br><br></body></html>";
		} else {
		//put the responses into the database
		$insertquery = "INSERT INTO flickr (buildingId, flickrImage, timestamp, user) VALUES (";
		$insertquery .= "'" . $name . "', ";
		$insertquery .= "'" . $flickrid . "', ";
		$insertquery .= "'" . $timestamp . "', ";
		$insertquery .= "'" . $contributor . "'";
		$insertquery .= ");";
	
	
		if (!mysql_query($insertquery,$dbconnection))
		  {
		  die('Error: ' . mysql_error());
		  } else {
		//	echo "\ninserted ok\n";
		}		
			
		//add geo info to the pic
		
		$geo = $f->photos_geo_setLocation($flickrid, $lat, $lon);
		$geo = $f->photos_setTags($flickrid, "tektonomastics building");
				
		//tidy up the mysql connection
	//	mysql_close($dbconnection);
		
		//header
		
		header('Location: http://tektonomastics.org/name/' . $sortname);
		
		}
		}
?>