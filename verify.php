<?php
		
		include 'connect.php';
		include 'locking.php';
		
		if ($_GET['key']) {
			
			
			if (releaseRecord($_GET['key']) == 1) {

				echo "Thanks! Your building is now visible on the map. See it <a href='http://tektonomastics.org/map/'>here</a>.";

			} else {

				echo "Something went wrong. Sorry. Please <a href='http://tektonomastics.org/contact.php'>contact us</a>.";
			}
		
			
		} else {
			
			echo "Oops, looks like something went wrong...";
			
		}

?>