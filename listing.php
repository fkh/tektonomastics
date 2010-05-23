<html>

<head>
	<LINK href="style.css" rel="stylesheet" type="text/css"></LINK>
	
	
	<script type="text/javascript" src="lib/jquery-1.4.2.js"></script>  
	
	<script type="text/javascript"> 
		function loadPhotos(id) {
			$.get('getphotos.php?id='+id+'',function(data){
				$(data).appendTo('#build' + id + '');
			});
//			$('#build' + id + '').load("getphotos.php?id="+id+"").appendTo();
		}
	</script>
	
	
	<script type="text/javascript"> 
	
	function load() {
		
	<?php 
	
		include 'connect.php';	
	
		$dbconnection = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error.');
		mysql_select_db($dbname, $dbconnection);

		$query = "SELECT buildingId FROM flickr;";
		
		$db = mysql_query($query);
		
		while ($row = mysql_fetch_array($db, MYSQL_BOTH)) {			
			
			echo "loadPhotos(" . $row[0] . ");\n" ; 
			
		}
					
	?>
	
	}
	</script>
	
	
	  
	</head>

		<body onload=load()>
			
			

<?php
		



		//array of db field names
		$dbnames = Array("id", "lat", "lon", "address",  "boro", "zip", "city", "photo", "name", "note",   "contributor", "twitter", "timestamp");
		
		//ok, now get a database connection
		$dbconnection = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error.');
		mysql_select_db($dbname, $dbconnection);

		$query = "SELECT id, NAME FROM building ORDER BY SORTNAME ASC;";
		
		$db = mysql_query($query);
		

		

		while ($row = mysql_fetch_array($db, MYSQL_BOTH)) {								
		
			echo "<div class='bldgfloat' id='build" . $row[0] . "'>" ;
			echo $row[1]; 
			echo "<br></div>";
		}

		//tidy up the mysql connection
		mysql_close($dbconnection);
		?>
		
</body>
	
</html>