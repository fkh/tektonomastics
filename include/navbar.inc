<div id="navigation">
	<ul id="menuitems">
	<li id="about"><a <?php if (strpos($_SERVER['PHP_SELF'], 'about.php')) echo 'class="currentabout"';?> href="/about/" title=""><span>About</span></a></li>
	<li id="map"><a <?php if (strpos($_SERVER['PHP_SELF'], 'map.php')) echo 'class="currentmap"';?> href="/map/" title=""><span>Map</span></a></li>
	<li id="inventory"><a <?php if (strpos($_SERVER['PHP_SELF'], 'inventory.php')) echo 'class="currentinventory"';?> href="/inventory/" title=""><span>Inventory</span></a></li>
	<li id="blog"><a href="http://blog.tektonomastics.org/" title=""><span>Blog</span></a></li>
	</ul>
</div>