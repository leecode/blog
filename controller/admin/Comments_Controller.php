<?php
class Comments_Controller extends Controller {
	/**
	 * 评论管理主页面
	 */
	public function index() {
		$params = func_get_arg(0);

		$page = $params['page'];
		$page_size = $params['page_size'];
		$q = urldecode($params['q']);

		if(empty($page) || !is_numeric($page)) {
          $page = 1;
        }

        if(empty($page_size) || !is_numeric($page_size)) {
          $page_size = 10;
        }

		$comments = $this->model('comments');
		$comment_list = $comments->list_by_cid(null, ($page - 1) * $page_size, $page_size, $q, false, true, false);
		$comments_count = $comments->list_by_cid(null, -1, -1, $q, true, true, false);

		Application::load_model('admin/user');
		$user_mgr = $this->model('user');
		for($i = 0; $i < count($comment_list); $i++) {
			$user = $user_mgr->get_by_uid($comment_list[$i]['author_id']);
			if(is_null($user)) {
				$user = array('name' => '游客');
			}
			$comment_list[$i]['author'] = $user;
		}


		$params['comments'] = $comment_list;
		$params['page_size'] = $page_size;
		$params['page'] = $page;
		$params['total'] = $comments_count;

		$this->show_template('admin/manage-comments', $params);
	}

	public function delete() {
		$params = func_get_arg(0);

		if(isset($params['coids']) && !empty($params['coids'])) {
			$coids = $params['coids'];
		}

		$result = array('success' => false);

        $contents = $this->model('comments');

        $contents->delete_batch($coids);
        $result['success'] = true;
        
        $json_str = json_encode($result);
        echo $json_str;
	}

	/**
	 * 回复某条评论。
	 */
	public function reply() {
		$params = func_get_arg(0);

		$comments = $this->model('comments');
		$comments->cid = $params['cid'];
		$comments->text = $this->get_input('text');
		$comments->parent = empty($params['parent']) ? 0 : $params['parent'];
		$comments->created = time();
		$comments->author_id = Commons::get_loggedin_user_id();
		if(!$comments->author_id) {
			$comments->author_id = 0;
		}

		$comments->save();

		$result = array('success' => true);
		$response = json_encode($result);

		echo $response;
	}
}
?>