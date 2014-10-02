<?php
class Login_Controller extends Controller {
	public function login() {
		$params = func_get_arg(0);
		$this->show_template('admin/login', $params);
	}

	public function do_login() {
		$params = func_get_arg(0);

		$username = $params['username'];
		$password = trim($params['password']);
		

		// Get user model, check user exist.
		Application::load_model('admin/user');
		$user_mgr = $this->model('user');
		$user = $user_mgr->get_by_name($username);

		$result = array();
		$result['success'] = false;

		if(is_null($user)) {
			$result['msg'] = '用户名错误';
			$result['elementName'] = 'username';

			echo json_encode($result);
			return;
		}

		if(md5($password) != $user['password']) {
			$result['msg'] = '密码错误';
			$result['elementName'] = 'password';

			echo json_encode($result);
			return;
		} else {
			session_start();

			$_SESSION['user_id'] = $user['uid'];
			$_SESSION['username'] = $user['name'];

			setcookie('user_id', $user['uid'], time() + (60 * 60 *24));
			setcookie('username', $user['name'], time() + (60 * 60 * 24));

			//Commons::forward('admin/index.php');
			$result['success'] = true;
			$result['url'] = 'index.php';
			echo json_encode($result);
			return;
		}
	}
}
?>