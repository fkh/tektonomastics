<html>

<head>
	<title>Tektonomastics</title>
	
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>  
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>  
	

	
	<script type="text/javascript"> 
		function loadPhotos() {
			$.get('/getphotos.php?recent=1', function(data){
				$('#recent-pics').html(data);
							});
		}
	</script>
	
	
	<?php include "include/head.inc"; ?>
	  
	</head>

		<body onload="loadPhotos();">
			
		<div id='body_container'>
			
	<?php 
	
	//layout
	include "include/header.inc";		
	include "include/navbar.inc"; 
	
	//database details
	include "connect.php";
	
		//array of db field names
		$dbnames = Array("id", "lat", "lon", "address",  "boro", "zip", "city", "photo", "name", "note",   "contributor", "twitter", "timestamp");
		
		//ok, now get a database connection
		/* debug
		echo "\n<br>db host: " . $dbhost;
		echo "\n<br>db name: " . $dbname;
		echo "\n<br>db connection: " . $dbconnection;
		*/

		$dbconnection = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error: ' . mysql_error() );
		mysql_select_db($dbname, $dbconnection);
		
		$query = "SELECT id, NAME, SORTNAME, count(*) as quantity FROM building WHERE rowlock = 0 GROUP BY SORTNAME ORDER BY SORTNAME ASC;";
		
		$db = mysql_query($query) or die (mysql_error($dbconnection));
		
		$records = mysql_num_rows($db); 
		
		echo "<div id='inventory_canvas'>"; 
		
		echo "<div id='recent-pics'><em>Loading pics of recently-added buildings...</em></div>";
			
			echo "<p>There are " . $records . " buildings in the Inventory.</p>\n"; 
			
			$first = "Z";
			echo "<div>";
			
			while ($row = mysql_fetch_array($db, MYSQL_BOTH)) {								

				$name = $row['SORTNAME']; // this has no "the"
				$prettyname = stripslashes($name);
				$quantity = $row['quantity'];
				
				$initial = substr($name, 0, 1);
				if ( $initial !== $first) {
					echo "</div><div class='invfloat'>"; 
					echo "<h2>" . $initial . "</h2>" ;
					$first = $initial ;
				} 
				
				// $linkname = str_replace(" ", "_", $name) ;
				$linkname = urlencode($prettyname) ;
				
				
				echo "<p><a href='/name/" . $linkname . "'>" . $prettyname . "</a>";
				
				if ($quantity > 1) {
					echo " (" . $quantity . " buildings)" ;
				}
				
				"</p>";
			
				// this works for the thumbnail pics		
				//	echo "<div class='bldgfloat' id='build" . $row[0] . "'>" ;
				//	echo $row[1]; 
				//	echo "<br></div>";

				}
			
				mysql_close($dbconnection);
			
			?>
			
		</div>
		
		<div id='sidebar'>
			<!-- <p>View by name | face</p> -->
		</div>
		
		<div class="push"></div>
		
		</div>
		
		<!-- footer here -->
		<?php include "include/footer.inc"; ?>
		
</body>
	
</html>