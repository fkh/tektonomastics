<html>

<head>
	<title>Tektonomastics</title>
	
		<?php include "include/head.inc"; ?>
	
	<!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>	 
	-->
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>  

  <!-- jQuery -->
  <script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.2.min.js"></script>

  <!-- DataTables -->
  <script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js"></script>
	
	<script type="text/javascript"> 

	// make the table interactive
	$(document).ready(function() {
      $('#buildings').dataTable({
      "bPaginate": false,
      "bLengthChange": false,
      "bFilter": true,
      "bSort": true,
      "bInfo": false,
      "bAutoWidth": true
      });
  } );
	
		function loadPhotos() {
		  
			$.get('/getphotos.php?recent=1', function(data){
				$('#recent-pics').html(data);
							});
							
				/** 			
			var pics = $('.pic');
      for (var i = 0; i < pics.length; ++i) {
        
        var pic = $('.pic').eq(i);
        var currentBuilding = pic.text();
	      var picUrl = "/getphotos.php?id=" + currentBuilding;
		    $.get(picUrl, function(data){
		      $('.pic').eq(i).html(data);
  	    });
        };	
        
        **/
		};
		
	</script>
	
	

	  
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
		
		$query = "SELECT id, NAME, SORTNAME, ADDRESS, CITY FROM building WHERE rowlock = 0 ORDER BY SORTNAME ASC;";
		
		$db = mysql_query($query) or die (mysql_error($dbconnection));
		
		$records = mysql_num_rows($db); 
		
		echo "<div id='inventory_canvas'>"; 
		
		echo "<div id='recent-pics'><em>Loading pics of recently-added buildings...</em></div>";
			
			echo "<p>There are " . $records . " buildings in the Inventory.</p>\n"; 

			echo "<div>";
						
			echo "<table class='dataTable' id='buildings'>";
			echo "<thead>";
			echo "<tr>";
			echo "<th>Name</th>";
			echo "<th>Address</th>";
			echo "<th>City</th>";
			echo "</tr>";
			echo "</thead>\n";
			

			
			while ($row = mysql_fetch_array($db, MYSQL_BOTH)) {								

				$name = $row['SORTNAME']; // this has no "the"
				$prettyname = stripslashes($name);
				$linkname = urlencode($prettyname) ;
				
				echo "<tr>";
				echo "<td class='building-name'><a href='/name/" . $linkname . "'>" . $prettyname . "</a></td>";
				echo "<td class='building-address'>$row['ADDRESS']</td>";
				echo "<td class='building-city'>$row['CITY']</td>";				
				echo "</tr>\n";
				
				}
			
				mysql_close($dbconnection);
			
			?>
			
			</table>
		
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