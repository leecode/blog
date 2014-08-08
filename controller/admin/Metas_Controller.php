<?php
class Metas_Controller extends Controller {
	public function show() {
		$params = func_get_arg(0);

		$metas = $this->model('metas');
        $metas_list = $metas->list_metas($params['type']);

        error_log(var_export($params, true));
        error_log(var_export($metas_list, true));

        Application::load_model('admin/relationships');
        $relationships = $this->model('relationships');
        if('tag' != $params['type']) {
	        // 得到各个分类下的文章数量。
	        for($i = 0; $i < count($metas_list); $i++) {
	        	$post_count = $relationships->get_contents_count($metas_list[$i]['mid']);
	        	$metas_list[$i]['post_count'] = $post_count;
	        }
        }

        $metas_to_update = $metas->get_by_mid($params['mid']);

        $params['metas_list'] = $metas_list;
        $params['metas_to_update'] = $metas_to_update;

		$this->show_template('admin/manage-metas', $params);
	}

	public function add() {
		$params = func_get_arg(0);

		$meta = $this->model('metas');

		$meta->mid = $params['mid'];
		$meta->name = $params['name'];
		$meta->description = $params['description'];
		$meta->meta_order = $params['meta_order'];
		$meta->type = $params['type'];

		$meta->save();

		$path = 'admin/index.php?controller=metas&action=show&type=' . $params['type'];
		Commons::forward($path);
	}

	public function delete() {
		$params = func_get_arg(0);
        if(isset($params['mids']) && !empty($params['mids'])) {
            $cids = $params['mids'];
        }
        $result = array('success' => false);

        $meta = $this->model('metas');

        $meta->delete_batch($cids);
        $result['success'] = true;
        
        $json_str = json_encode($result);
        echo $json_str;
	}
}
?>