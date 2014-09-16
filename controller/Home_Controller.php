<?php
define('FRONT_PAGE_SIZE', 5);

class Home_Controller extends Controller {
	private static $PAGE_SIZE = 5;
	
	/**
	 * 博客前台首页
	 */
	public function index() {
		// if(!Commons::has_user_logged_in()) {
		// 	Commons::forward('index.php?controller=login&action=login');
		// }
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
		Application::load_model('admin/metas');
		Application::load_model('admin/relationships');

		$comments = $this->model('comments');
		$metas = $this->model('metas');
		$relationships = $this->model('relationships');

		for($i = 0; $i < count($posts); $i++) {
			$count_of_comments = $comments->list_by_cid($posts[$i]['cid'], -1, -1, '', true, false, false);
			$posts[$i]['comment_count'] = $count_of_comments;

			// 得到文章所属分类列表
			$cate_ids = $relationships->get_category_ids_of_contents($posts[$i]['cid']);
			$categories = array();
			foreach ($cate_ids as $cate_id) {
				$categories[] = $metas->get_by_mid($cate_id);
			}

			$posts[$i]['categories'] = $categories;
		}

		$page_count = (0 == (int)($post_count / FRONT_PAGE_SIZE)) ? 
							(int)($post_count / FRONT_PAGE_SIZE) : 
							(int)($post_count / FRONT_PAGE_SIZE) + 1;

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
		$all_comments_count = $comments->list_by_cid($params['cid'], -1, -1, '', true, false, false);

		for ($index = 0; $index < count($comments_of_post); $index++) {
			$sub_comments = $comments->list_by_parent($comments_of_post[$index]['coid']);
			$comments_of_post[$index]['sub-comments'] = $sub_comments;
		}

		Application::load_model('admin/metas');
		Application::load_model('admin/relationships');

		$relationships = $this->model('relationships');
		$metas = $this->model('metas');

		// 得到文章所属分类列表
		$cate_ids = $relationships->get_category_ids_of_contents($params['cid']);
		$categories = array();
		foreach ($cate_ids as $cate_id) {
			$categories[] = $metas->get_by_mid($cate_id);
		}

		$post['categories'] = $categories;
		$cates = $metas->list_metas();

		$params['categories'] = $cates;
		$params['post'] = $post;
		$params['comments'] = $comments_of_post;
		$params['comments_count'] = $count_of_comments;
		$params['all_comments_count'] = $all_comments_count;
		$params['page'] = $page;
		$params['page_size'] = $page_size;

		$this->show_template('front/default/post', $params);
	}

	/**
	 * 处理评论请求
	 */
	public function comment() {
		session_start();
		$params = func_get_arg(0);

		Application::load_model('admin/comments');

		$comments = $this->model('comments');
		$comments->cid = $params['cid'];
		$comments->text = $this->get_input('text');
		$comments->parent = empty($params['parent']) ? 0 : $params['parent'];
		$comments->sub_parent = empty($params['sub_parent']) ? 0 : $params['sub_parent'];
		$comments->created = time();
		$comments->author_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

		$comments->save();

		Commons::forward('index.php?cid=' . $params['cid'] . '&action=details');
	}
}
?>