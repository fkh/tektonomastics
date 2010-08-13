<html>

<head>
	<title> Tektonomastics</title>
	<?php include "include/head.inc"; ?>
	
	  <script type="text/javascript" src="/lib/protovis-d3.2.js"></script>
	<script type="text/javascript" src="/viz/year.js"></script>
	
	<?php
	
	include 'connect.php';
			
	$dbconnection = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error.');
	mysql_select_db($dbname, $dbconnection);

	$query = "select timestamp from building order by timestamp asc;";
		
	$db = mysql_query($query);
	
	print "<script type=\"text/javascript\">\n";
	print "var updates = [";
	
	$update = 1;
	
	while ($row = mysql_fetch_array($db, MYSQL_BOTH)) {	

		print "{timestamp: " . $row['timestamp'] ;
		print ", volume: " . $update  ;
		print "},\n";
		
		$update ++; 

		}
		
	print "];\n";
	print "</script>\n";							
	
	?>

	
</head>

<body>
	
	<div id="body_container">
		
	<!-- header/navigation -->
	<?php include "include/header.inc"; ?>
	<?php include "include/navbar.inc"; ?>
	
	<p>We use <a href="http://vis.stanford.edu/protovis/">Protoviz</a> to dynamically generate these charts from the inventory of buildings. Interested in getting the data? Scroll down for a kml.</p>
	
	<h3>Buildings by year</h3>
	<p>Year of construction.</p>
	
	  <script type="text/javascript+protovis">

	/* years = pv.nest(years)
		.key(function(d) d.buildings)
	    .key(function(d) d.year)
	    .map(); */ 
	
	var vis = new pv.Panel()
	    .width(650)
	    .height(200)
	    .bottom(20)
	    .left(20)
	    .right(20)
	    .top(5);
	
	vis.add(pv.Bar)
	    .data(years)
		.width(6)
	    .height(function(d) d.buildings * 12)
	    .bottom(0)
	    .left(function() this.index * 8)
		.anchor("top")
		.add(pv.Label)
		.visible(function(d) d.buildings > 0)
		.text(function(d) d.year)
		.textAlign("left")
	    .textBaseline("middle")
		.textMargin(5)
	    .textAngle(-Math.PI / 2);
	    
	vis.render();

	    </script>
	

	
		<h3>Buildings by neighborhood</h3>
		<p>Location.</p>

		<p align="center">
		  <script type="text/javascript+protovis">
	
		var w = 400,
		    h = 400,
		    r = w / 3;
				 
		var a = 2.0 * Math.PI / (nabes.buildings);
	    		
		var vis = new pv.Panel()
		    .width(w)
		    .height(h)
		    .bottom(30)
		    .left(30)
		    .right(30)
		    .top(30);
 
		/* The wedge, with centered label. */
		vis.add(pv.Wedge)
		    .data(nabes)
		    .bottom(w / 2)
		    .left(w / 2)
		    .innerRadius(r - 30)
		    .outerRadius(r)
		    .angle(function(d) d.buildings / 120 * 2 * Math.PI);
		
		vis.add(pv.Wedge)
			 .data(nabes)
			.innerRadius(180)
		    .outerRadius(180)
		    .bottom(w / 2)
		    .left(w / 2)
		    .angle(function(d) d.buildings / 120 * 2 * Math.PI)
		  .anchor("center").add(pv.Label)
			.textBaseline("middle")
			.textAlign("center")
		   .text(function(d) d.nabe);
		
		vis.render();
	
	
	</script></p>
	
	<h3>Project activity</h3>
	<p>Buildings added to the database, since April 2010.</p>

  	  <script type="text/javascript+protovis">

	/* years = pv.nest(years)
		.key(function(d) d.buildings)
	    .key(function(d) d.year)
	    .map(); */ 

	var w = 650,
		h = 200,
		x = pv.Scale.linear(updates, function(d) d.timestamp).range(0, w),
	    y = pv.Scale.linear(0, 150).range(0, h);
	
	
	var vis = new pv.Panel()
	    .width(w)
	    .height(h)
	    .bottom(20)
	    .left(20)
	    .right(5)
	    .top(5);

	
		vis.add(pv.Line)
		    .data(updates)
		    .interpolate("step-after")
		    .bottom(function(d) y(d.volume))
			.left(function(d) x(d.timestamp));
	
		//axis ticks
		vis.add(pv.Rule)
		    .data(x.ticks())
		    .visible(function(d) d > 0)
		    .left(x)
		    .strokeStyle("#eee")
		  .add(pv.Rule)
		    .bottom(-5)
		    .height(5)
		    .strokeStyle("#000");
		

	vis.render();


	    </script>

		<h3>Unit sizes</h3>
		<p>Building height in floors, and number of units. Size of dot indicates average apartment size, and the color shows neighborhood (yes... it needs a key).</p>
		
		    <script type="text/javascript+protovis">
		
		//scatter plot

		/* Sizing and scales. */
		var w = 650,
		    h = 200,
		    x = pv.Scale.linear(0, 140).range(0, w),
		    y = pv.Scale.linear(0, 14).range(0, h),
		    c = pv.Scale.log(1, 100).range("orange", "brown");

		/* The root panel. */
		var vis = new pv.Panel()
		    .width(w)
		    .height(h)
		    .bottom(20)
		    .left(40)
		    .right(10)
		    .top(5);

		/* Y-axis and ticks. */
		vis.add(pv.Rule)
		    .data(y.ticks())
		    .bottom(y)
		    .strokeStyle(function(d) d ? "#eee" : "#000")
		  .anchor("left").add(pv.Label)
		    .visible(function(d) d > 0 && d < 50000)
		    .text(y.tickFormat);

		/* X-axis and ticks. */
		vis.add(pv.Rule)
		    .data(x.ticks())
		    .left(x)
		    .strokeStyle(function(d) d ? "#eee" : "#000")
		  .anchor("bottom").add(pv.Label)
		    .visible(function(d) d > 0 && d < 220)
		    .text(x.tickFormat);

		/* The dot plot! */
		vis.add(pv.Panel)
		    .data(buildings)
		  .add(pv.Dot)
		    .left(function(d) x(d.units))
		    .bottom(function(d) y(d.floors))
			.visible(function(d) d.units > 0 && d.area > 0)
		    .strokeStyle(pv.Colors.category20().by(function(d) d.nabe))
		    //.fillStyle(pv.Colors.category20().by(function(d) d.nabe))
		    .size(function(d) d.area/d.units/20)
		    .title(function(d) d.building);
			;
			
		vis.render();

		    </script>

	<h3>Download the buildings data</h3>

	<br><p>Thanks for sharing the building names and photos you spot on your travels! Grab locations of all the buildings here, to view them in Google Earth, GIS or another map program. 
	
	<p><a href="/makekml.php">Download all buildings in a Google Earth compatible KML file.</a></p>		
		
		<a href="http://blog.tektonomastics.org/">Let us know</a> if you make something cool with them.</p> <br>
	
	<!-- footer -->
	<?php include "include/footer.inc"; ?>
	
	</div>
</body>
</html>