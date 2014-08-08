<?php
define('SYSTEM_PATH', dirname(__FILE__));
define('ROOT_PATH',  substr(SYSTEM_PATH, 0,-7));	// -7 indicates '/system'.
define('SYS_LIB_PATH', SYSTEM_PATH.'/lib');
define('APP_LIB_PATH', ROOT_PATH.'/lib');
define('SYS_CORE_PATH', SYSTEM_PATH.'/core');
define('CONTROLLER_PATH', ROOT_PATH.'/controller');
define('MODEL_PATH', ROOT_PATH.'/model');
define('VIEW_PATH', ROOT_PATH.'/view');
define('LOG_PATH', ROOT_PATH.'/error/');

final class Application {
	public static $_lib = null;
	public static $_config = null;

	public static function init() {
		// 设置所有需要在框架启动时加载的类。
		self::set_auto_libs();

		require SYS_CORE_PATH . '/model.php';
		require SYS_CORE_PATH . '/controller.php';
	}

	// 启动应用。
	public static function run($config) {
		self::$_config = $config['system'];
        self::init();
		self::auto_load();

		self::$_lib['route']->set_url_type(self::$_config['route']['url_type']);
		$url_array = self::$_lib['route']->get_url_array();
		self::route_to_cm($url_array);
	}

	// 自动加载并实例化框架启动时所需要的类。
	public static function auto_load() {
		foreach (self::$_lib as $lib_name => $lib_file) {
			// 包含类文件
			require $lib_file;
			if('mysql' != $lib_name) {
					// 实例化
				$lib = ucfirst($lib_name);
				self::$_lib[$lib_name] = new $lib;
			}
		}
	}

	/**
	 * 加载类库
	 */
	public static function new_lib($class_name) {
		$app_lib = APP_LIB_PATH . '/' . self::$_config['lib']['prefix'] . '_' . $class_name . '.php';
		$system_lib = SYS_LIB_PATH . '/lib_' . $class_name . '.php';

		if(file_exists($app_lib)) {
			require $app_lib;
			$class_name = ucfirst(self::$_config['lib']['prefix']) . ucfirst($class_name);
			return new $class_name;
		} elseif(file_exists($system_lib)) {
			require $system_lib;
			return self::$_lib["$class_name"] = new $class_name;
		} else {
			trigger_error('Loading ' . $class_name . ', class does not exists');
		}
	}

	public static function load_model($model_name) {
		if(empty($model_name)) {
			trigger_error('The name of model to load can not empty.');
			die;
		}

		$model_file = MODEL_PATH . '/' . Commons::uclastword($model_name, '/') . '_Model.php';
		if(file_exists($model_file)) {
			require $model_file;
		} else {
			die('No such model ' . $model_name . ' found in model path.');
		}
	}

	// Route qurey url to controller & model.
	public static function route_to_cm($url_array = array()) {
		$app = '';
		$controller = '';
		$action = '';
		$model = '';
		$params = '';

		if(isset($url_array['app'])) {
			$app = $url_array['app'];
		}

		if(isset($url_array['controller'])) {
			$controller = $model = ucfirst($url_array['controller']);
		} else {
			$controller = $model = ucfirst(self::$_config['route']['default_controller']);
		}

		if($app) {
			$controller_file = CONTROLLER_PATH . "/{$app}/{$controller}_Controller.php";
			$model_file = MODEL_PATH . "/{$app}/{$model}_Model.php";
		} else {
			$controller_file = CONTROLLER_PATH . "/{$controller}_Controller.php";
			$model_file = MODEL_PATH . "/{$model}_Model.php";
		}

		if(isset($url_array['action'])) {
			$action = $url_array['action'];
		} else {
			$action = self::$_config['route']['default_aciton'];
		}

		if(isset($url_array['params'])) {
			$params = $url_array['params'];
		}

		if(file_exists($controller_file)) {
			if(file_exists($model_file)) {
				require $model_file;
			}

			require $controller_file;
			$controller_class_name = $controller . '_Controller';
			$controller = new $controller_class_name;

			if($action) {
				if(method_exists($controller, $action)) {
					isset($params) ? $controller->$action($params) : $controller->$action();
				} else {
					die('Controller ' . $controller_class_name . ' does not has method named ' . $action);
				}
			} else {
				die('No action method specified.');
			}
		} else {
			die('No controller file related to controller ' . $controller);
		}
	}

	public static function set_auto_libs() {
		self::$_lib = array(
			'route'     => SYS_LIB_PATH . '/lib_route.php',
			'mysql'     => SYS_LIB_PATH . '/lib_mysql.php',
			'template'  => SYS_LIB_PATH . '/lib_template.php',
			//'cache'     => SYS_LIB_PATH . '/lib_cache.php',
			//'thumbnail' => SYS_LIB_PATH . '/lib_thumbnail.php',

		);
	}
}


?>