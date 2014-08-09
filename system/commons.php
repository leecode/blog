<?php
final class Commons {
	private function __construct() {

	}
	public static function load_lib($lib, $auto = TRUE) {
		if(empty($lib)) {
			trigger_error('The class to load can not be empty.');
		} elseif(TRUE === $auto) {
			return Application::$_lib[$lib];
		} elseif(FALSE === $auto) {
			return Application::new_lib($lib);
		}
	}

	public static function config($config) {
		return Application::$_config[$config];
	}

	public static function forward($path) {
		$host = $_SERVER['HTTP_HOST'];
		$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		$location = "http://{$host}{$uri}/{$path}";
		header("Location: {$location}");
		exit;
	}

	// Mainly for Application::load_model, uppercase the first word of last word, 'admin/metas' => 'admin/Metas'
	public static function uclastword($words, $delimiter) {
		$last_delimter_pos = strripos($words, $delimiter);

		$start_part = substr($words, 0, $last_delimter_pos);
		$model_name = ucfirst(substr($words, $last_delimter_pos + 1));

		return $start_part . $delimiter . $model_name;
	}

	public static function timeToDate($time, $format = 'F j, Y') {
		return date($format, $time);
	}
}
?>