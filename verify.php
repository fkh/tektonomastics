<?php
		
		include 'connect.php';
		include 'locking.php';
		
		if ($_GET['key']) {
			
			$building = releaseRecord($_GET['key']);
			
			if ($building > 0) {
			
			$dbconnection = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error.');
			mysql_select_db($dbname, $dbconnection);
			
			$query = "SELECT sortname FROM building WHERE id = '" . $building ."';";
			
			$db = mysql_query($query);

			while ($row = mysql_fetch_array($db, MYSQL_BOTH)) {
			
				$confirmation = "Thanks! Your building is now included in the inventory." ;
				$confirmation .= "See it <a href='http://tektonomastics.org/name/" ;
				$confirmation .= $row['sortname'] ;
				$confirmation .= "'>here</a>." ;
			
			}
			
			print $confirmation;

			} else {

				echo "Something went wrong. Sorry. Please <a href='http://tektonomastics.org/contact.php'>contact us</a>.";
			}
		
			
		} else {
			
			echo "Oops, looks like something went wrong...";
			
		}

?>