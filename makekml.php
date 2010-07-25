<?php

	include 'connect.php';

	// thanks to google for this sample code
	// http://code.google.com/apis/kml/articles/phpmysqlkml.html#outputkml

	//connect to mysql
	$dbconnection = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error.');
	mysql_select_db($dbname, $dbconnection);

	$query = "SELECT * FROM building WHERE lat > 0 and rowlock = 0;";
	$result = mysql_query($query);
	if (!$result) {  
	  die('Invalid query: ' . mysql_error());
	}
	
	// Creates the Document.
	$dom = new DOMDocument('1.0', 'UTF-8');
	
	// Creates the root KML element and appends it to the root document.
	$node = $dom->createElementNS('http://earth.google.com/kml/2.1', 'kml');
	$parNode = $dom->appendChild($node);

	// Creates a KML Document element and append it to the KML element.
	$dnode = $dom->createElement('Document');
	$docNode = $parNode->appendChild($dnode);

	// Iterates through the MySQL results, creating one Placemark for each row.
	while ($row = @mysql_fetch_assoc($result))
	{
	  // Creates a Placemark and append it to the Document.

	  $node = $dom->createElement('Placemark');
	  $placeNode = $docNode->appendChild($node);

	  // Creates an id attribute and assign it the value of id column.
	  $placeNode->setAttribute('id', 'placemark' . $row['id']);

	  // Create name, and description elements and assigns them the values of the name and address columns from the results.
	  $nameNode = $dom->createElement('name',htmlentities($row['name']));
	  $placeNode->appendChild($nameNode);
	  $descNode = $dom->createElement('description', $row['address']);
	  $placeNode->appendChild($descNode);

	  // Creates a Point element.
	  $pointNode = $dom->createElement('Point');
	  $placeNode->appendChild($pointNode);

	  // Creates a coordinates element and gives it the value of the lng and lat columns from the results.
	  $coorStr = $row['lon'] . ','  . $row['lat'];
	  $coorNode = $dom->createElement('coordinates', $coorStr);
	  $pointNode->appendChild($coorNode);
	}

	$kmlOutput = $dom->saveXML();
	Header('Content-type: application/vnd.google-earth.kml+xml; filename=tektonomastics.kml"');
	Header("Content-Disposition: attachment; filename=tektonomastics.kml"); 
	echo $kmlOutput;
	
?>