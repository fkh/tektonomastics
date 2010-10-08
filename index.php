<html>

<head>
	<title>Tektonomastics</title>
	<meta name="description" content="Join us in a collaborative effort to map the named residential buildings of New York City and beyond.">
	<?php include "include/head.inc"; ?>
	<meta name="google-site-verification" content="ySru3pHB_MjPiBIvrGW8cqebreDoL-0zxz31WOh-to8" />
	
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>  
	
	<script type="text/javascript"> 
		function loadPhotos() {
			$.get('/getphotos.php?recent=1', function(data){
				$('#footerimg').html(data);
				});
		}
	</script>
	
</head>

<body onload="loadPhotos();">
	
	<!-- header/navigation -->
	<?php include "include/header.inc"; ?>
	
	

 
<ul id="brickwall">
	<li id="seemap"><a href="/map/" title=""><span>See Map</span></a></li>
	<li id="addbldg"><a href="/map/" title=""><span>Add a Building</span></a></li>
	<li id="findname"><a href="/inventory/" title=""><span>Find a Name</span></a></li>
	<li id="newsitem"><a href="http://blog.tektonomastics.org/"><span>News Item</span></a></li>
</ul>


	<div id='footerimg'><em>Loading recent pics<br><br><br></em></div>

	<?php
	
	include 'connect.php';
	
	$dbconnection = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error: ' . mysql_error() );
	mysql_select_db($dbname, $dbconnection);
	
	$query = "SELECT id, NAME, SORTNAME, timestamp FROM building WHERE rowlock = 0 GROUP BY SORTNAME ORDER BY timestamp DESC limit 5;";
	
	$db = mysql_query($query) or die (mysql_error($dbconnection));
	
	$records = mysql_num_rows($db); 
		
		echo "<div id='footer'><p>Most recent names ";
		
		while ($row = mysql_fetch_array($db, MYSQL_BOTH)) {								

			$name = $row['SORTNAME']; // this has no "the"
			$prettyname = stripslashes($name);
			
			
			// $linkname = str_replace(" ", "_", $name) ;
			$linkname = urlencode($prettyname) ;
			
			echo " | ";
			echo "<a href='/name/" . $linkname . "'>" . $prettyname . "</a>";
		
		}
		
	?>
	<!-- footer -->
	<?php include "include/footer.inc";?>
</body>
</html>