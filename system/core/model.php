<?php
class Model {
	// 数据库连接对象
	protected $db = null;

	protected $attributes;

	final public function __construct() {
		header('Content-Type:text/html;charset=utf-8');

		// 得到数据库连接对象。
		$this->db = MySQL::get_instance();
	}

	/**
	 * 得到数据库表全名
	 * @param string table_name 数据库表名
	 */
	final protected function table($table_name) {
		$config_db = Commons::config('db');
		return $config_db['db_table_prefix'] . '_' . $table_name;
	}


	// Magic method.
	public function __get($attr) {
		if(isset($this->attributes[$attr])) {
			return $this->attributes[$attr];
		}
		return false;
	}

	// Magic method.
	public function __set($attr, $value) {
		$this->attributes[$attr] = $this->db->real_escape_string($value);
	}
}
?>