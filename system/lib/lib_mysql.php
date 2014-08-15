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
}

?>