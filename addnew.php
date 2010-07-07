<?php
		
		include 'connect.php';
		include 'locking.php';


		//array of db field names
		$dbnames = Array("id", "lat", "lon", "address",  "boro", "zip", "city", "photo", "name",  "sortname", "note",   "contributor", "twitter", "timestamp", "rowlock");
		
		//deal with the image file first
		//if (isset($_POST['upload']) && ($_FILES['uploadFile']['size'] > 0) ) { 
		if ($_FILES['uploadFile']['size'] > 0 ) { 
			$oldname = $_FILES['uploadFile']['name'];
			$filetype = end(explode('.',$oldname)); 
			$newname = md5(rand().$oldname).".".$filetype;

			//echo $newname;
			move_uploaded_file ($_FILES['uploadFile'] ['tmp_name'], "images/".$newname);

			//echo $_FILES['uploadFile'] ['name'];
			//echo $_FILES['uploadFile'] ['tmp_name'];
			//echo $_FILES['uploadFile'] ['error'];
			//echo $_FILES['uploadFile'] ['size'];

		}

		//ok, now get a database connection
		$dbconnection = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error.');
		mysql_select_db($dbname, $dbconnection);

		if (isset($_POST['name'])) { //then we have something to add to the database
			
		//get the answers safe for the db
		$timestamp = time();
		
		//make a sortname, to deal with "The"
		$name = mysql_real_escape_string($_POST['name']);
		
		if (substr($name, 0, 4) == "The ") {
			$sortname = mb_substr($name, 4);
		} else {
			$sortname = $name;
		}
				
		//make remaining answers safe
		$note = mysql_real_escape_string($_POST['note']);
		$lat = mysql_real_escape_string($_POST['lat']);
		$lon = mysql_real_escape_string($_POST['lng']);
		$address = mysql_real_escape_string($_POST['address']);
		$boro = mysql_real_escape_string($_POST['boro']);
		$zip = mysql_real_escape_string($_POST['zip']);
		$city = mysql_real_escape_string($_POST['city']);
		$contributor = mysql_real_escape_string($_POST['contributor']);
		$twitter = mysql_real_escape_string($_POST['twitter']);
		$photo = $newname;

		//header
	//	$postheader = 'lat=' . $lat . "&lng=" . $lon ;
	//	header($postheader);


		if ($contributor == 'tek') {
			$rowlock = 0;
		} else {
			$rowlock = 1;
		}

		//put the responses into the database
		$insertquery = "INSERT INTO building (name, sortname, note, lat, lon, address, boro, zip, city, contributor, twitter, photo, timestamp, rowlock) VALUES (";
		$insertquery .= "'" . $name . "', ";
		$insertquery .= "'" . $sortname . "', ";
		$insertquery .= "'" . $note . "', ";
		$insertquery .= "'" . $lat . "', ";
		$insertquery .= "'" . $lon . "', ";
		$insertquery .= "'" . $address . "', ";
		$insertquery .= "'" . $boro . "', ";
		$insertquery .= "'" . $zip . "', ";
		$insertquery .= "'" . $city . "', ";
		$insertquery .= "'" . $contributor . "', ";
		$insertquery .= "'" . $twitter . "', ";
		$insertquery .= "'" . $photo . "', ";
		$insertquery .= "'" . $timestamp . "', ";
		$insertquery .= "'" . $rowlock . "'";
		$insertquery .= ");";

		if (!mysql_query($insertquery,$dbconnection))
		  {
		  die('Error: ' . mysql_error());
		  }

		}
		
		//fire off the email
		if ($rowlock) {

			$dbquery = "SELECT max(id) FROM building WHERE name = '" . $name . "' ; "; 
				
			$result = mysql_query($dbquery);
			$row = mysql_fetch_array($result);			
			$id = $row[0] ; 
					
			lockRecord($id, $contributor, $name);
			
			echo "Check your email - we just send you a link, please click it to verify the building submission.<br><br> <a href='http://tektonomastics.org/map/'>Back to the map</a>.";
			
		} else {
			
			header('Location: http://tektonomastics.org/map/'); //fixme!
			
		}
		
		//tidy up the mysql connection
		mysql_close($dbconnection);
?>