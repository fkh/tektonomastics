<?php 		
	
	//	returns a list of flickr image urls.
	
		include 'include/phpFlickr.php';
		
		function getPhotos($building) {
		
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
			$comauth = array();	
			$comdate = array();
			$comcontent = array();		
			$com = array();			

			//var to hold the output list of image names
			$photosHtml = "";
			
			//then, loop through the flickr ids and look up the image links
			while ($row = mysql_fetch_array($db, MYSQL_BOTH)) {

				$flickrpic = $row['flickrImage'];
				$flickrdata = $f->photos_getSizes($flickrpic);

				array_push($photos, $flickrdata[3][source]);
	
				$flickrdata = $f->photos_comments_getList($flickrpic);
							
				foreach ($flickrdata as $photo)  {
					foreach ($photo as $commenttree) {
						foreach  ($commenttree as $comment){
							
							array_push($comauth, $comment[authorname]);
							array_push($comdate, $comment[datecreate]);							
							array_push($comcontent, $comment[_content]);
						

						}

					}
				}
			
				$comments = array('author'=>$comauth, 'comdate'=>$comdate, 'comcon'=>$comcontent, 'flickr'=>$flickrpic);

			}
			
			return array('photos'=>$photos, 'comments'=>$comments);
			
		}
		
?>