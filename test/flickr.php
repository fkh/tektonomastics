<? 

include "../include/phpFlickr.php";

echo "...";

// set up flickr authentication
$f = new phpFlickr('247d8333f05337cfc918849ff141b0c6', 'b449e6d4f9bb6d30');
$f->setToken('72157623802048061-6ef5b17ea0b52483');

//echo $f->sync_upload("../images/1dd8fe63d8d9430247514dda6b2e60fa.jpg");

$sizes = array();

$sizes = $f->photos_getSizes(4550457166);

print_r($sizes);

echo "<img src='" . $sizes[0][source] . "'>";

//testing 2

?>