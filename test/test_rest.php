<?php
	$request_uri = 'blog/admin/contents/2';

	if(0 === strpos($request_uri, '/')) {
		$request_uri = substr($request_uri, 1);	
	}
	
	$app_name = 'blog';

	list($app, $module, $controller, $entity_id) = explode('/', $request_uri);

	echo "APP : $app, MODULE : $module, CONTROLLER : $controller, ACTION : $action, ID : $entity_id";
?>