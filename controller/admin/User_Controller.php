<?php
class User_Controller extends Controller {
	public function index() {
		$params = func_get_arg(0);

		$user_mgr = $this->model('user');

		$page = $params['page'];
		$page_size = $params['page_size'];
		$q_screenName = $params['q'];

		if(empty($page) || !is_numeric($page)) {
          $page = 1;
        }

        if(empty($page_size) || !is_numeric($page_size)) {
          $page_size = 10;
        }

        $q_screenName = trim($q_screenName);
		$users = $user_mgr->get_users(($page - 1) * $page_size, $page_size, $q_screenName);
		$total = $user_mgr->get_users(-1, -1, $q_screenName, true);

		$params['users'] = $users;
		$params['total'] = $total;
		$params['page_size'] = $page_size;
        $params['page'] = $page;

		$this->show_template('admin/manage-users', $params);
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

		$uid = $params['uid'];
		$user_mgr->uid = $uid;


		if($user_mgr->screenName == '') {
			$user_mgr->screenName = $user_mgr->name;
		}

		$user_mgr->save();

		$users = $user_mgr->get_users();

		Commons::forward('admin/index.php?controller=user');
	}

	public function user() {
		$params = func_get_arg(0);

		$uid = $params['uid'];
		if(!empty($uid) && is_numeric($uid)) {
			$user_mgr = $this->model('user');
			$user = $user_mgr->get_by_uid($uid);
		}

		$this->show_template('admin/user', array('user' => $user));
	}

	public function delete() {
		$params = func_get_arg(0);
		$result = array('success' => false);

		$uids = trim($params['uids']);

		$user_mgr = $this->model('user');
		$user_mgr->delete_batch($uids);

		$result['success'] = true;
		echo json_encode($result);
	}
}
?>