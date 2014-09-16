<?php
class Comments_Model extends Model {
	/**
	 * 保存评论
	 */
	public function save() {
		$sql = null;

		$table = $this->table('comments');

		$sql = 'insert into ' . $table . ' (cid, created, text, parent, sub_parent, author_id) values(?, ?, ?, ?, ?, ?)';

		$this->db->stmt_init();
		$this->db->prepare($sql);

		$db_params = new DBParams();
		$db_params->add('i', $this->cid);
		$db_params->add('i', $this->created);
		$db_params->add('s', $this->text);
		$db_params->add('i', $this->parent);
		$db_params->add('i', $this->sub_parent);
		$db_params->add('i', $this->author_id);

		$this->db->bind_params($db_params);
		$this->db->stmt_execute();
		$this->db->stmt_close();
		return $this->db->insert_id();
	}

	/**
	 * 删除某一条评论
	 */
	public function delete() {
		$sql = null;
		$table = $this->table('comments');

		$sql = 'delete from ' . $table . ' where coid = ?' . $this->attributes['coid'];

		$this->db->stmt_init();
		$this->db->prepare($sql);

		$this->db->bind_params('i', $this->coid);
		$this->db->stmt_execute();
		$this->db->stmt_close();
	}

	/**
	 * 批量删除评论
	 */
	public function delete_batch($coids) {
		if(!empty($coids)) {
			$sql = 'delete from ' . $this->table('comments') . ' where coid in (?)';

			$this->db->stmt_init();
			$this->db->prepare($sql);

			$this->db->bind_params('s', $coids);
			$this->db->stmt_execute();
			$this->db->stmt_close();
		}
	}

	/**
	 * 按照文章id删除评论列表
	 * @param $cid 文章id
	 */
	public function delete_by_cid($cid) {
		if(!empty($cid) && is_numeric($cid)) {
			$sql = 'delete from ' . $this->table('comments') . ' where cid = ?';

			$this->db->stmt_init();
			$this->db->prepare($sql);
			$this->db->bind_params('i', $cid);
			$this->db->stmt_execute();
			$this->db->stmt_close();
		}
	}

	/**
	 * 按照文章id获取评论列表或总数
	 * @param $cid 文章id
	 * @param $is_count 是否为获取评论总数， 默认为false，即获取评论列表
	 */
	public function list_by_cid($cid, $offset = 0, $limit = 10, $q = '', $is_count = false, $is_manage = false, $not_nested = true) {
		$sql = null;
		$db_params = new DBParams();

		$comment_table = $this->table('comments');

		$no_nested = '';
		if($not_nested) {
			$no_nested = ' and comment.parent = 0';
		}

		if(!$is_count) {
			if($is_manage) {
				$sql = 'select comment.coid, comment.cid, comment.created, comment.text, comment.parent, comment.author_id, content.title post_title from '
						. $comment_table . ' as comment, ' . $this->table('contents') . ' as content where comment.cid = content.cid ' . $no_nested;
			} else {
				$sql = 'select coid, cid, created, text, parent from ' . $comment_table . ' as comment where 1=1 ' . $no_nested;	
			}
		} else {
			$sql = 'select count(1) total from ' . $comment_table . ' as comment where 1=1 ' . $no_nested;
		}
		
		if(!empty($cid) && is_numeric($cid) &&!$is_manage) {
			$sql .= ' and cid = ?';
			$db_params->add('i', $cid);
		}

		if(!empty($q)) {
			$sql .= ' and comment.text like ?';
			$db_params->add('s', "%$q%");
		}

		$sql .= ' order by created desc';

		if(0 <= $offset) {
			$sql .= " limit ?, ?";
			$db_params->add('i', $offset);
			$db_params->add('i', $limit);
		}

		$this->db->stmt_init();
		$this->db->prepare($sql);
		$this->db->bind_params($db_params);

		$result = $this->db->stmt_fetch_array();
		$this->db->stmt_close();

		if(!$is_count) {
			return $result;
		} else {
			$total = $result[0]['total'];

			return $total;
		}
	}

	function list_by_parent($parent, $is_manage = false, $is_count = false) {
		$sql = null;
		$comment_table = $this->table('comments');

		if(!$is_count) {
			$sql = 'select coid, cid, created, text, parent from ' . $comment_table . ' as comment where parent = ? order by created desc';	
		} else {
			$sql = 'select count(1) total from ' . $comment_table . ' as comment where parent = ?';
		}

		$db_params = new DBParams();
		$db_params->add('i', $parent);

		$this->db->stmt_init();
		$this->db->prepare($sql);
		$this->db->bind_params($db_params);
		$result = $this->db->stmt_fetch_array();
		$this->db->stmt_close();

		if(!$is_count) {
			return $result;
		} else {
			return $result[0]['total'];
		}
		
	}
}
?>
