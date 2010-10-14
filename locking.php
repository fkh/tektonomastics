<?php

	function lockRecord($id, $email, $name) {
				
		include 'connect.php';
		include 'sendEmail.php';
				
		$hashedId = makeHash($id);
		
		$verifylink = "http://tektonomastics.org/verify.php?key=" . $hashedId ;
			
		//update the db to lock that record 
		
		$dbconnection = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error.');
		mysql_select_db($dbname, $dbconnection);
		
		
		//stick the hash and id into the lock table -- no, not using a lock table.
		$updatequery = "UPDATE building set rowlock = 1 where id = " . $id . ";" ;
		
		// echo $updatequery;
		
		if (!mysql_query($updatequery,$dbconnection))
		  {
		  die('Error: ' . mysql_error());
		  }
		
		//compose the email
		$subject = "Verify your submission of '" . $name . "' to tektonomastics.org";
		
		$message = "Thanks for adding a building to tektonomastics.org!<br><br>";
		$message .= "The map pin for '" . $name . "' is currently hidden from view. To make it visible, please click this link: <br><br>";
		$message .= "<a href=" . $verifylink . ">" . $verifylink . "</a> <br><br>Visiting the link convinces us you are human, not a spam-generating computer.<br><br>";
		$message .= "Thanks!";
		
		//prep headers
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		//email to us
		$devsubject = "New building! " . $name ;
		$devmessage = "Submitted by: " . $email . ". Database id: " . $id ;
		
		//trac mail
		$tracurl = "http://trac.tektonomastics.org/newticket?summary=" . urlencode($name) . "&description=Review%20" . urlencode($name) . "&component=buildings&type=task&priority=minor";
		
		$devmessage .= "<br><br><a href='" . $tracurl . "&owner=haru&cc=fkh'>Add task for Haru</a>" ;
		$devmessage .= "<br><br><a href='" . $tracurl . "&owner=fkh&cc=haru'>Add task for Frank</a>" ;
		
		$devmessage .= "<br><br>" . $message;
		
		//send it, if we can
			smtp($email, $subject, $message, $headers);
			smtp("tektonomastics@gmail.com", $devsubject, $devmessage, $headers);
		
		return 1; // 
		
	}
	
	
	function releaseRecord($hash) {
		
		include 'connect.php';
		
		//extract the id from the hash
		$longid = substr($hash,strlen($hash)-4,4);
		$id = $longid + 0;
		
		//recreate the hash
		$comparison = makeHash($id);
		
		if ($hash == $comparison) {
			
			// we're ok to unlock the record
			$dbconnection = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error.');
			mysql_select_db($dbname, $dbconnection);
			
			$updatequery = "UPDATE building set rowlock = 0 where id = " . $id . ";" ;
			
			if (!mysql_query($updatequery,$dbconnection))
			  {
			  die('Error: ' . mysql_error());
			  }
		
				return $id ;
		} else {
			
			return 0 ;
		}
		
	}
	
	
	function makeHash($id) {
		// take the id and make our hash
		$salt = 3.14 * $id ;
		$salt .= "flack";
		
		$hash = md5($salt) . str_pad($id, 4, "0", STR_PAD_LEFT);
		
		return $hash;
	}
?>