<?php
define('FRONT_PAGE_SIZE', 5);

class Home_Controller extends Controller {
	private static $PAGE_SIZE = 5;
	
	/**
	 * 博客前台首页
	 */
	public function index() {
		$params = func_get_arg(0);
		Application::load_model('admin/contents');

		$contents = $this->model('contents');
		$page = $params['page'];
		$q = $params['q'];

		if(empty($page) || !is_numeric($page)) {
			$page = 1;
		}

		$category = $params['category'];

		$posts = $contents->list_contents(($page - 1) * FRONT_PAGE_SIZE, FRONT_PAGE_SIZE, $q, false, $category);
		$post_count = $contents->list_contents(-1, -1, $q, true, $category);

		Application::load_model('admin/comments');
		$comments = $this->model('comments');

		for($i = 0; $i < count($posts); $i++) {
			$count_of_comments = $comments->list_by_cid($posts[$i]['cid'], -1, -1, '', true, false, false);
			$posts[$i]['comment_count'] = $count_of_comments;
		}

		$page_count = (0 == (int)($post_count / FRONT_PAGE_SIZE)) ? 
							(int)($post_count / FRONT_PAGE_SIZE) : 
							(int)($post_count / FRONT_PAGE_SIZE) + 1;

		Application::load_model('admin/metas');
		$metas = $this->model('metas');
		$cates = $metas->list_metas();

		$params['categories'] = $cates;
		$params['posts'] = $posts;
		$params['page_count'] = $page_count;

		$this->show_template('front/default/index', $params);
	}

	/**
	 * 跳转到文章详细信息页面
	 */
	public function details() {
		$params = func_get_arg(0);

		Application::load_model('admin/contents');

		$contents = $this->model('contents');
		$post = $contents->get_by_cid($params['cid']);
		$page = isset($params['page']) ? $params['page'] : 1;
		$page_size = FRONT_PAGE_SIZE;


		Application::load_model('admin/comments');
		$comments = $this->model('comments');
		$comments_of_post = $comments->list_by_cid($params['cid'], ($page - 1) * FRONT_PAGE_SIZE, FRONT_PAGE_SIZE);
		$count_of_comments = $comments->list_by_cid($params['cid'], -1, -1, '', true);

		for ($index = 0; $index < count($comments_of_post); $index++) {
			$sub_comments = $comments->list_by_parent($comments_of_post[$index]['coid']);
			$comments_of_post[$index]['sub-comments'] = $sub_comments;
		}

		$params['post'] = $post;
		$params['comments'] = $comments_of_post;
		$params['comments_count'] = $count_of_comments;
		$params['page'] = $page;
		$params['page_size'] = $page_size;

		$this->show_template('front/default/post', $params);
	}

	/**
	 * 处理评论请求
	 */
	public function comment() {
		$params = func_get_arg(0);

		Application::load_model('admin/comments');

		$comments = $this->model('comments');
		$comments->cid = $params['cid'];
		$comments->text = $this->get_input('text');
		$comments->parent = empty($params['parent']) ? 0 : $params['parent'];
		$comments->sub_parent = empty($params['sub_parent']) ? 0 : $params['sub_parent'];
		$comments->created = time();

		$comments->save();

		Commons::forward('index.php?cid=' . $params['cid'] . '&action=details');
	}
}
?>