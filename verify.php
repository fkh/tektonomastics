<?php
		
		include 'connect.php';
		include 'locking.php';
		
		if ($_GET['key']) {
			
			$building = releaseRecord($_GET['key']);
			
			if ($building > 0) {
				
			$query = "SELECT sortname FROM building WHERE id = '" . $building ."';";
			
			$db = mysql_query($query);

			while ($row = mysql_fetch_array($db, MYSQL_BOTH)) {
			
			$confirmation = "Thanks! Your building is now included in the inventory.";
			$confirmation .= "See it <a href='http://tektonomastics.org/map/";
			$confirmation .= $row['sortname'];
			$confirmation .= "'>here</a>.";
			
			}



			} else {

				echo "Something went wrong. Sorry. Please <a href='http://tektonomastics.org/contact.php'>contact us</a>.";
			}
		
			
		} else {
			
			echo "Oops, looks like something went wrong...";
			
		}

?>