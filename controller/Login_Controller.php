<?php
class Login_Controller extends Controller {
	public function login() {
		$params = func_get_arg(0);
		$this->show_template('front/default/login', $params);
	}

	public function do_login() {
		$params = func_get_arg(0);


		$username = $params['username'];
		$password = $params['password'];

		if('leecode' != $username || 'leecode' != $password) {
			Commons::forward('index.php?controller=login&action=login');
		}
	}
}
?>