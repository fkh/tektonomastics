<?php

	include 'connect.php';

	//connect to mysql
	$dbconnection = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error.');
	mysql_select_db($dbname, $dbconnection);

	$query = "SELECT * FROM building WHERE lat > 0";
	$result = mysql_query($query);
	if (!$result) {  
	  die('Invalid query: ' . mysql_error());
	}
	
	//write out the file
	header("Content-type: text/xml"); 


	if ($_GET['type'] == "new") {

	//new method:
	$doc = new DOMDocument("1.0");
	$r = $doc->createElement("buildings");
	
	$doc->appendChild($r);
	
	while ($row = @mysql_fetch_assoc($result)){  
		
		$b = $doc->createElement("building");
		
		//id
		$id = $doc->createElement("id");
		$id->appendChild( 
			$doc->createTextNode($row['id'])
			);
		$b->appendChild($id);
	
		//name
		$name = $doc->createElement("name");
		$name->appendChild( 
			$doc->createTextNode($row['name'])
			);
		$b->appendChild($name);	
		
		//address
		$address = $doc->createElement("address");
		$address->appendChild( 
			$doc->createTextNode($row['address'])
			);
		$b->appendChild($address);	
		
		//lat
		$lat = $doc->createElement("lat");
		$lat->appendChild( 
			$doc->createTextNode($row['lat'])
			);
		$b->appendChild($lat);
		
		//finish up
		$r->appendChild($b);
	}

	echo $doc->saveXML();

	
	
	} else { 

		//default method
		// Start XML file, create parent node
		$dom = new DOMDocument("1.0");
		$node = $dom->createElement("markers");
		$parnode = $dom->appendChild($node);

		while ($row = @mysql_fetch_assoc($result)){  
		  // ADD TO XML DOCUMENT NODE  
		  $node = $dom->createElement("marker");  
		  $newnode = $parnode->appendChild($node);   
		  $newnode->setAttribute("building",$row['id']);
		  $newnode->setAttribute("name",$row['name']);
		  $newnode->setAttribute("address", $row['address']);  
		  $newnode->setAttribute("lat", $row['lat']);  
		  $newnode->setAttribute("lon", $row['lon']);  
		  $newnode->setAttribute("photo", $row['photo']);  
		} 

			echo $dom->saveXML();
	
	}
	
	
?>




