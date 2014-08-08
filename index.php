<?php
	require(dirname(__FILE__) . '/system/app.php');
	require dirname(__FILE__) . '/config/config.php';

	require dirname(__FILE__) . '/system/commons.php';
	
	Application::run($CONFIG);
?>