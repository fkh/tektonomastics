<?php

	include 'connect.php';

	//connect to mysql
	$dbconnection = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error.');
	mysql_select_db($dbname, $dbconnection);
	

	//base query
	$query = "SELECT * FROM building WHERE lat > 0 and rowlock = 0";
	
	if (isset($_GET['id'])) {
		$query .= " and id = " . $_GET['id'] ;
	}

	//end query
	$query .= " ;" ;
	//$query .= " limit 3;" ; // for limiting selections for testing


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
		
		//name
		$sortname = $doc->createElement("sortname");
		$sortname->appendChild( 
			$doc->createTextNode($row['sortname'])
			);
		$b->appendChild($sortname);
		
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
		  $newnode->setAttribute("sortname",$row['sortname']);	
		  $newnode->setAttribute("address", $row['address']);  
		  $newnode->setAttribute("lat", $row['lat']);  
		  $newnode->setAttribute("lon", $row['lon']);  
		} 

			echo $dom->saveXML();
	
	}
	
	
?>




