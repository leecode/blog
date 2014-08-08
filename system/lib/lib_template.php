<?php
final class Template {
	public $template_name = null;
	public $data = array();
	public $out_put = null;

	public function init($template_name, $data) {
		if(empty($template_name)) {
			trigger_error('template name should not be empty.');
		}

		$this->template_name = $template_name;
		$this->data = $data;
		$this->render();
	}

	public function render() {
		$view_file = VIEW_PATH . '/' . $this->template_name . '.php';

		if(file_exists($view_file)) {
			extract($this->data);

			ob_start();
			include $view_file;
			$content = ob_get_contents();
			ob_end_clean();

			$this->out_put = $content;
		} else {
			trigger_error('loading tempalte file [' . $view_file . '] failed.');
		}
	}

	public function out_put() {
		echo $this->out_put;
	}
}
?>