<?php
	$temp = 'username=xiaoliangleecode@gmail';
	list($prop_name, $prop_value) = explode('=', $temp);

	echo 'prop_name ' . $prop_name .', prop_value ' . $prop_value;
	echo "\n";

	$query_str = "?app=admin&controller=fuck&action=shit";

	$arr = parse_url($query_str);

	echo var_export($arr, true);
?>