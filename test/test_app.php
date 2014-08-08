<?php
	$url_array = parse_url($_SERVER['REQUEST_URI']);

	var_dump($_SERVER);
	echo "<br/>";
	var_dump($url_array);
?>