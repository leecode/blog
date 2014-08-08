<?php
	$params = array('controller' => 'contents',
					'action'     => 'add',
					'value'      => 'shit',
					'cids'		 => '2,3,4,5,56',
				 );

	echo to_query_str($params);
	function to_query_str($params_array) {
		$result = '';

		foreach ($params_array as $param_name => $value) {
			
			$result .= "{$param_name}={$value}&";
		}

		$result = substr($result, 0, strlen($result) - 1);

		return $result;
	}
?>