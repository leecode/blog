<?php
class Home_Controller extends Controller {
	/**
	 * 进入后台管理主页面
	 */
	public function index() {
		if(Commons::has_user_logged_in()) {
			$this->show_template('admin/index');	
		} else {
			Commons::forward('admin/index.php?controller=login&action=login');
		}
	}
}
?>