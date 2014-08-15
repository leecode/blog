<?php
final class Route {
	public $url_query;
	public $url_type;
	public $route_url = array();

	public function __construct() {
		$to_parse = '';
		$query_string = $_SERVER['QUERY_STRING'];
		$query = parse_url($_SERVER['REQUEST_URI']);

		// From redirect rule.
		if(false === strpos($query['query'], $query_string)) {
			// 针对POST请求作出相应的处理。
			if('POST' === $_SERVER['REQUEST_METHOD']) {
				$query_string = $query_string . '&' . $this->to_query_str($_POST);
			}
			$to_parse = '?' . $query_string;
			if(!empty($query['query'])) {
				$to_parse .= '&' . $query['query'];
			}
		} else {
			$to_parse = $_SERVER['REQUEST_URI'];
		}

		$this->url_query = parse_url($to_parse);
	}

	public function set_url_type($url_type = 1) {
		$this->url_type = $url_type;
	}

	public function get_url_array() {
		$this->make_url();
		return $this->route_url;
	}

	public function make_url() {
		switch ($this->url_type) {
			case 1:
				$this->query_to_array();
				break;
			case 2:
				$this->pathinfo_to_array();
				break;
		}
	}

	// 将请求参数转换为数组
	// localhost/myapp/index.php/app=admin&controller=index&action=edit&id=9&fid=10 
	// => array('app' => 'admin', 'controller' =>'index', 'action' =>'edit', 'id' =>array('id' => 9, 'fid' =>10))
	public function query_to_array() {
		$arr = !empty($this->url_query['query']) ? explode('&', $this->url_query['query']) : array();

		$result = $tmp = array();

		if(count($arr) > 0) {
			foreach ($arr as $item) {
				list($param_name, $param_value) = explode('=', $item);
				$result[$param_name] = $param_value;
			}

			if(isset($result['app'])) {
				$this->route_url['app'] = $result['app'];
				unset($result['app']);
			}

			if(isset($result['controller'])) {
				$this->route_url['controller'] = $result['controller'];
				unset($result['controller']);
			}

			if(isset($result['action'])) {
				$this->route_url['action'] = $result['action'];
				unset($result['action']);
			}

			if(count($result > 0)) {
				$this->route_url['params'] = $result;
			}
		} else {
			$this->route_url = array();
		}
	}

	public function pathinfo_to_array() {

	}

	protected function to_query_str($params_array) {
		$result = '';

		foreach ($params_array as $param_name => $value) {
			$result .= "$param_name=$value&";
		}

		$result = substr($result, 0, strlen($result) - 1);

		return $result;
	}
}
?>