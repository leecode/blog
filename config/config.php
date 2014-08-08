<?php
	/**
	 * 数据库配置
	 */
	$CONFIG['system']['db'] = array(
		'db_host' 			=> 'localhost',
		'db_user' 			=> 'root',
		'db_password' 		=> 'leecode',
		'db_database' 		=> 'test_mvc',
		'db_table_prefix' 	=> 'app',
		'db_charset' 		=> 'uft-8',
		//'db_conn' => ''
	);

	/**
	 * 自定义类库配置
	 */
	$CONFIG['system']['lib'] = array(
		'prefix' => 'my',
	);

	$CONFIG['system']['route'] = array(
		'default_controller' => 'home',		// 系统默认控制器
		'default_aciton'	 => 'index',	// 系统默认控制器
		'url_type' 			 => 1,			// 定义url的形式，1为普通模式，index.php?c=controller&a=action&id=2， 2为REST模式，暂不实现。
	);

	/*缓存配置*/ 
	$CONFIG['system']['cache'] = array( 
	    'cache_dir'     => 'cache', //缓存路径，相对于根目录 
	    'cache_prefix'  => 'cache_',//缓存文件名前缀 
	    'cache_time'    => 1800,    //缓存时间默认1800秒 
	    'cache_mode'    => 2,       //mode 1 为serialize ，model 2为保存为可执行文件    
	); 
?>