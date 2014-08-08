<?php
/**
 * 核心控制器
 */
class Controller {
	public function __construct() {
		// Nothing for now.
	}

	// 实例化model.
	final protected function model($model) {
		if(empty($model)) {
			trigger_error('Can not instatitate empty model.');
		}
		$mode_class_name = ucfirst($model) . '_Model';
		return new $mode_class_name;
	}

	// 加载模板文件。
	final protected function show_template($path, $data = array()) {
		$template = Commons::load_lib('template');
		$template->init($path, $data);
		$template->out_put();
	}

	final protected function get_input($variable, $default = "") {
		$var = $default;

		if (isset($_REQUEST[$variable])) {
			if (is_array($_REQUEST[$variable])) {
				$var = $_REQUEST[$variable];
			} else {
				$var = trim($_REQUEST[$variable]);
			}
		}

		return $var;
	}
}
?>