<?php

		include 'connect.php';
		include 'include/phpFlickr.php';
		
		// set up flickr authentication
		$f = new phpFlickr('247d8333f05337cfc918849ff141b0c6', 'b449e6d4f9bb6d30');
		$f->setToken('72157623802048061-6ef5b17ea0b52483');
		
		if (isset($_POST['id'])) { //then we have something to add to the database
			
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

			//echo $newname;
			move_uploaded_file ($_FILES['uploadFile'] ['tmp_name'], "images/".$newname);

		}
				
		//get a database connection
		$dbconnection = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error.');
		mysql_select_db($dbname, $dbconnection);
		
		$query = "SELECT NAME FROM building WHERE ID = ". $name . ";";

		$db = mysql_query($query);

		while ($row = mysql_fetch_array($db, MYSQL_BOTH)) {								
		
			$buildingname = $row[0] ; 

		}
		
		//upload vars
		$photo_url = "images/" . $newname;
		$title = $buildingname;
		$description = ""; //FIXME
		$tags = "tektonomastics";
		
		//now upload the image to flickr
		$flickrid = $f->sync_upload($photo_url, $title, $description, $tags);
	//	$flickrid = $f->sync_upload("images/" . $newname);		

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
		  }
			
			
		}
		
		//tidy up the mysql connection
	//	mysql_close($dbconnection);
		
		//header
		header('Location: http://fkh.webfactional.com/index.php?id=' . $name);
?>