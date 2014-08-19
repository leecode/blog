<?php
class User_Controller extends Controller {
	public function index() {
		$this->show_template('admin/user');
	}

	public function add() {
		$params = func_get_arg(0);

		$user_mgr = $this->model('user');
		$user_mgr->name = $params['name'];
		$screen_name_empty = false;

		$user_mgr->screenName = $params['screenName'];
		$user_mgr->mail = $params['mail'];
		$user_mgr->created = time();
		$user_mgr->activated = 1;	// activated.

		if($user_mgr->screenName == '') {
			$user_mgr->screenName = $user_mgr->name;
		}

		$user_mgr->save();

		$users = $user_mgr->get_users();
		var_dump($users);
		//error_log(var_export('expression'))
//		error_log(var_export($params, true));
	}
}
?>