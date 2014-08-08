<?php
	$model_path = 'admin/metas';

	echo uclastword($model_path, '/');

	function uclastword($words, $delimiter) {
		$last_delimter_pos = strripos($words, $delimiter);

		$start_part = substr($words, 0, $last_delimter_pos);
		$model_name = ucfirst(substr($words, $last_delimter_pos + 1));

		return $start_part . $delimiter . $model_name;
	}
?>