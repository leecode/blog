<?php
// 加载配置 
$app_root = dirname(dirname(dirname(__FILE__)));
require_once($app_root . '/config/config.php');
/**
 *
 */
class MySQL {
	/**
	 * 内置数据库连接对象
	 */
	private $conn;
	/**
	 * 内置结果集对象
	 */
	private $result;

	/**
	 * 内置mysqli_stmt对象
	 */
	private $stmt;

	/*
	 * 内置实例对象
	*/
	private static $instance = null;

	private function __construct() {
		if(!function_exists('mysqli_connect')) {
			die('ERROR:Server not support MySQL database...');			
		}

		$db_config = Commons::config('db');
		if(!$this->conn = mysqli_connect($db_config['db_host'], $db_config['db_user'], $db_config['db_password'], $db_config['db_database'])) {
			echo $this->get_error();
		}
		mysqli_query('set names "utf8"', $this->conn);
	}

	/**
	 * 静态方法，返回数据库连接实例
	 */
	public static function get_instance() {
		if (self::$instance == null) {
			self::$instance = new MySql();
		}
		return self::$instance;
	}

	/**
	 * 关闭数据库连接
	 */
	function close() {
		return mysqli_close($this->conn);
	}

	/**
	 * 发送查询语句
	 *
	 */
	function query($sql) {
		$this->result = mysqli_query($this->conn, $sql);		
		if (!$this->result) {
			die("SQL execute error : $sql <br />" . $this->get_error());
		}
		return $this->result;
	}

	/**
	 * 从结果集中取得一行作为关联数组/数字索引数组
	 *
	 */
	function fetch_array($result) {
		return mysqli_fetch_array($result, MYSQLI_ASSOC);
	}

	/**
	 * 从结果集中取得一行作为数字索引数组
	 *
	 */
	function fetch_row($result) {
		return mysqli_fetch_row($result);
	}

	/**
	 * 取得行的数目
	 *
	 */
	function num_rows($result) {
		return mysqli_affected_rows($this->conn);
		//return mysqli_num_rows($result);
	}



	/**
	 * 获取mysql错误编码
	 */
	function get_errno() {
		return mysqli_connect_errno();
	}

	/**
	 * 得到错误信息
	 */
	function get_error() {
		return mysqli_connect_error();
	}

	/**
	 * 得到上次插入的记录id
	 */
	function insert_id() {
		return mysqli_insert_id($this->conn);
	}

	/**
	 * 返回上次对数据的写操作(insert, update, delete, replace)影响到的记录数
	 */
	function affected_rows() {
		return mysqli_affected_rows($this->conn);
	}

	function num_fields($query) {
		return mysqli_filed_count($this->conn);
	}

	function get_charset() {
		return mysqli_get_charset($this->conn);
	}

	function real_escape_string($escape_str) {
		return mysqli_real_escape_string($this->conn, $escape_str);
	}

	function get_conn() {
		return $this->conn;
	}
	/**
	 * New APIs for using mysqli prepared statment.
	 */
	function stmt_init() {
		$this->stmt = mysqli_stmt_init($this->conn);
		return $this->stmt;
	}

	function prepare($sql) {
		mysqli_stmt_prepare($this->stmt, $sql);
	}

	function bind_params() {
		$args_count = func_num_args();
		$args = func_get_args();
		$func_params = array($this->stmt);
		// Arguments is DBParams instantce
		if(1 == $args_count && $args[0] instanceof DBParams) {
			$func_params = array_merge($func_params, $args[0]->parse());
		} else {
			$types = $args[0];
			$values = $args[1];
			if(!is_array($values)) {
				$values = array($values);
			}
			$db_params = array_merge(array($types), $values);
			$func_params = array_merge($func_params, $db_params);
		}
		
		call_user_func_array('mysqli_stmt_bind_param', $func_params);
	}


	function stmt_execute() {
		mysqli_stmt_execute($this->stmt);
	}

	function stmt_fetch_array() {
		$metadata = mysqli_stmt_result_metadata($this->stmt);

		$fieldNames = array(&$this->stmt);

		$obj = new stdClass();
		while($field = mysqli_fetch_field($metadata)) {
			$fieldName = $field->name;
			$fieldNames[] = &$obj->$fieldName;	// Pass reference, so $obj can be updated.
		}

		call_user_func_array('mysqli_stmt_bind_result', $fieldNames);

		$result_obj = array();
		mysqli_stmt_execute($this->stmt);
		$i = 0;
		while(mysqli_stmt_fetch($this->stmt)) {
			foreach ($obj as $key => $value) {
				$result_obj[$i][$key] = $value;
			}
			$i++;
		}

		return $result_obj;
	}

	/**
	 * Fetch object or object array.
	 */
	function stmt_fetch_object() {
		$metadata = mysqli_stmt_result_metadata($this->stmt);

		$fieldNames = array(&$this->stmt);

		$obj = new stdClass();
		while($field = mysqli_fetch_field($metadata)) {
			$fieldName = $field->name;
			$fieldNames[] = &$obj->$fieldName;
		}

		call_user_func_array('mysqli_stmt_bind_result', $fieldNames);

		$result_obj = array();
		mysqli_stmt_execute($this->stmt);
		$i = 0;
		while(mysqli_stmt_fetch($this->stmt)) {
			$row = new stdClass();

			// Can not use $result_obj[] = $obj directly, seems has something to do with array reference, not sure about that.
			foreach ($obj as $prop => $value) {
				$row->$prop = $value;
			}
			$result_obj[] = $row;
		}

		return $result_obj;
	}

	function stmt_close() {
		mysqli_stmt_close($this->stmt);
	}
}

/**
 * Util class for binding params of prepared statement.
 */
class DBParams {
	/**
	 * types finally would be like 'sids', which indicates types of four params, 
	 * type of whom are 'string', 'int', 'double' and 'string'.
	 */
	private $types = '';
	private $values = array();

	/**
	 * Add param together with its type.
	 * @param $type  type of param, must be a valid one, reference mysqli_stmt::bind_param.
	 * @param $value value of param.
	 */
	public function add($type, $value) {
		$this->values[] = $value;
		$this->types .= $type;
	}

	public function parse() {
		return array_merge(array($this->types), $this->values);
	}
}
?>