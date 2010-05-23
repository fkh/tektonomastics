<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>untitled</title>
	<meta name="generator" content="TextMate http://macromates.com/">
	<meta name="author" content="frank">
	<!-- Date: 2010-04-04 -->
</head>
<body>

		<?php

		include 'connect.php';

		//ok, now get a database connection
		$dbconnection = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error.');
		mysql_select_db($dbname, $dbconnection);

		//array of db field names
		$dbnames = Array("id", "lat", "lon", "address",  "boro", "zip", "city", "photo", "name", "note",   "contributor", "twitter", "timestamp");
	

		//quick check to see what's in the database
		$getdb = "SELECT * FROM building;";

		if (!mysql_query($getdb,$dbconnection))
		  {
		  	die('Error: ' . mysql_error());
			exit;
		  }

		$db = mysql_query($getdb);

		while ($row = mysql_fetch_array($db, MYSQL_BOTH)) {
			echo "<ul>";
			
			for ($i = 0 ; $i <= 13; $i++) {
					
				echo "<li>" . $dbnames[$i] . ": " . $row[$i] . "</li>" ;
				
			}
			
			echo "<li> <img width=100px src='images/" . $row["photo"] . "'></li>" ;
			
			echo "</ul>\n<hr>";
			
		}

		mysql_free_result($db);

		//tidy up the mysql connection
		mysql_close($dbconnection);
		?>

</body>
</html>
