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

	public static function has_user_logged_in() {
		session_start();

		// if user was not logged in , try to log in by cookie.
		if(!isset($_SESSION['user_id'])) {
			if(isset($_COOKIE['user_id']) && isset($_COOKIE['username'])) {
				$_SESSION['user_id'] = $_COOKIE['user_id'];
				$_SESSION['username'] = $_COOKIE['username'];
			}
		}

		return isset($_SESSION['user_id']);
	}

	public static function get_loggedin_user_id() {
		session_start();

		return $_SESSION['user_id'];
	}
}
?>