<?php
class Contents_Controller extends Controller {
	public function __construct() {
        parent::__construct();
    }

    // 显示“新增/编辑文章”页面。
    public function write_post() {
        $params = func_get_arg(0);

        if(is_array($params) && isset($params['cid'])) {
            $content = $this->model('contents');
            $content_item = $content->get_by_cid($params['cid']);
        }

        Application::load_model('admin/metas');
        Application::load_model('admin/relationships');

        $metas = $this->model('metas');
        $metas_list = $metas->list_metas();
        $tag_list = $metas->list_metas('tag');

        $relationships = $this->model('relationships');
        if(isset($params['cid'])) {
            $relations = $relationships->get_category_ids_of_contents($params['cid']);
        }

        $this->show_template('admin/write-post', array('content_item' => $content_item,
                                                       'metas_list' => $metas_list,
                                                       'tag_list' => $tag_list,
                                                       'relationships' => $relations));
    }

    // 添加文章请求。
    public function add() {
        $params = func_get_arg(0);

    	$contents = $this->model('contents');

        $cid = $params['cid'];
        $contents->cid = $cid;
        $contents->title = $params['title'];
        $contents->text = $this->get_input('text'); // 此处不能使用$params['text'], parse_url会将不合法的字符用'_'代替。
        // $contents->author_id = isset($params['author_id']) &&
        //                        is_numeric($params['author_id']) ? $params['author_id'] : -1;
        $contents->author_id = Commons::get_loggedin_user_id();

        if(!empty($cid) && is_numeric($cid)) {
            $contents->modified = time();
        } else {
            $contents->created = time();
        }

		$cid = $contents->save();

        if(empty($params['categories'])) {
            $params['categories'] = 1;
        }
        $category_ids = explode(',', $params['categories']);

        $tags = $this->get_input('tags');     // 传递数组的话，目前只能通过从$_REQUEST/$_POST中获取。

        Application::load_model('admin/metas');
        $metas = $this->model('metas');

        $tag_ids = array();

        foreach ($tags as $tag) {
            $metas->name = $tag;
            $metas->type = 'tag';
            $metas->description = $tag;
            $metas->meta_order = 0;

            $tag_id = $metas->save();
            $tag_ids[] = $tag_id;
        }

        $meta_ids = array_merge($category_ids, $tag_ids);

        Application::load_model('admin/relationships');
        // 先删除所有的relationships
        $relationships = $this->model('relationships');
        $relationships->cid = 0 == $cid ? $contents->cid : $cid;

        // 处理文章分类中的文章总数。
        // 先将文章之前所属的分类中的文章数量全部减1，之后再把文章现在所属的各个分类的文章数量加1.
        $former_cate_ids = $relationships->get_category_ids_of_contents($relationships->cid);

        $metas->type = 'category';
        foreach ($former_cate_ids as $cate_id) {
            $metas->mid = $cate_id;
            $metas->decrese_count();
        }

        foreach ($category_ids as $cate_id) {
            $metas->mid = $cate_id;
            $metas->increse_count();
        }
        // 处理文章分类中的文章总数记录结束。

        $relationships->delete();
        // 重新创建relationships
        foreach ($meta_ids as $cate) {
            $relationships->mid = $cate;
            $relationships->save();
        }
        // Should forward to post manage page.
        Commons::forward('admin/index.php?controller=contents&action=show');
    }

    // 显示“文章管理”页面
    public function show() {
    	// Get query params
    	$params = func_get_arg(0);
        $contents = $this->model('contents'); 

        $page = $params['page'];
        $page_size = $params['page_size'];
        $q = $params['q'];
        $category = $params['category'];

        if(empty($page) || !is_numeric($page)) {
          $page = 1;
        }

        if(empty($page_size) || !is_numeric($page_size)) {
          $page_size = 10;
        }

        $q_title = trim($q);
         
        $contents_list = $contents->list_contents(($page - 1) * $page_size, $page_size, $q_title, false, $category);
        $total = $contents->list_contents(-1, -1, $q_title, true, $category);


        Application::load_model('admin/comments');
        $comments = $this->model('comments');

        Application::load_model('admin/user');
        $user_mgr = $this->model('user');

        for($i = 0; $i < count($contents_list); $i++) {
            $count_of_comments = $comments->list_by_cid($contents_list[$i]['cid'], -1, -1, '', true, false, false);
            $user = $user_mgr->get_by_uid($contents_list[$i]['author_id']);

            $contents_list[$i]['comment_count'] = $count_of_comments;
            $contents_list[$i]['author'] = $user;

        }

        // 用于分页显示
        $params['contents_list'] = $contents_list;
        $params['total'] = $total;
        $params['page_size'] = $page_size;
        $params['page'] = $page;

        Application::load_model('admin/metas');

        $metas = $this->model('metas');
        $metas_list = $metas->list_metas();

        $params['metas_list'] = $metas_list;
        $this->show_template('admin/manage-post', $params);
    }

    public function delete() {
        $params = func_get_arg(0);
        if(isset($params['cids']) && !empty($params['cids'])) {
            $cids = $params['cids'];
        }
        $result = array('success' => false);

        $contents = $this->model('contents');
        $contents->delete_batch($cids);

        // 处理post-meta之间的关系
        Application::load_model('admin/relationships');
        Application::load_model('admin/metas');

        $relationships = $this->model('relationships');
        $metas = $this->model('metas');

        $post_ids = explode(',', $cids);
        foreach ($post_ids as $post_id) {
            $cate_ids = $relationships->get_category_ids_of_contents($post_id);
            $mids = implode(',', $cate_ids);

            // 处理文章分类
            $metas->decrese_count_batch($mids);

            // 处理relationships
            $relationships->cid = $post_id;
            $relationships->delete();
        }

        $result['success'] = true;
        
        $json_str = json_encode($result);
        echo $json_str;
    }
}
?>