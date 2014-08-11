<?php
class Comments_Model extends Model {
	/**
	 * 保存评论
	 */
	public function save() {
		$sql = null;

		$table = $this->table('comments');

		$sql = 'insert into ' . $table . ' (cid, created, text, parent, sub_parent) values(' . $this->attributes['cid']
			   . ', ' . $this->attributes['created'] . ', "'
			   . $this->attributes['text'] . '", ' . $this->attributes['parent'] . ',' . $this->attributes['sub_parent'] .')';

		$this->db->query($sql);
		return $this->db->insert_id();
	}

	/**
	 * 删除某一条评论
	 */
	public function delete() {
		$sql = null;
		$table = $this->table('comments');

		$sql = 'delete from ' . $table . ' where coid = ' . $this->attributes['coid'];

		$this->db->query($sql);
	}

	/**
	 * 批量删除评论
	 */
	public function delete_batch($coids) {
		if(!empty($coids)) {
			$sql = 'delete from ' . $this->table('comments') . ' where coid in (' . $coids . ')';
			$this->db->query($sql);
		}
	}

	/**
	 * 按照文章id删除评论列表
	 * @param $cid 文章id
	 */
	public function delete_by_cid($cid) {
		if(!empty($cid) && is_numeric($cid)) {
			$sql = 'delete from ' . $this->table('comments') . ' where cid = ' . $cid;
			$this->db->query($sql);
		}
	}

	/**
	 * 按照文章id获取评论列表或总数
	 * @param $cid 文章id
	 * @param $is_count 是否为获取评论总数， 默认为false，即获取评论列表
	 */
	public function list_by_cid($cid, $offset = 0, $limit = 10, $q = '', $is_count = false, $is_manage = false) {
		$sql = null;

		$comment_table = $this->table('comments');

		if(!$is_count) {
			if($is_manage) {
				$sql = 'select comment.coid, comment.cid, comment.created, comment.text, comment.parent, content.title post_title from '
						. $comment_table . ' as comment, ' . $this->table('contents') . ' as content where comment.cid = content.cid and comment.parent = 0 ';
			} else {
				$sql = 'select coid, cid, created, text, parent from ' . $comment_table . ' as comment where parent = 0 ';	
			}
		} else {
			$sql = 'select count(1) total from ' . $comment_table . ' as comment where 1=1 and parent = 0';
		}
		
		if(!empty($cid) && is_numeric($cid) &&!$is_manage) {
			$sql .= ' and cid = ' . $cid;
		}

		if(!empty($q)) {
			$sql .= ' and comment.text like "%' . $q . '%" ';
		}

		$sql .= ' order by created desc';

		if(0 <= $offset) {
			$sql .= " limit $offset, $limit";
		}

		error_log('LEECODE_DEBUG >>>>>>>>>>>>>>>' . $sql);
		$result = $this->db->query($sql);

		if(!$is_count) {
			$comments = array();
			while($row = $this->db->fetch_array($result)) {
				$comments[] = $row;
			}

			return $comments;
		} else {
			$row = $this->db->fetch_array($result);
			$total = $row['total'];

			return $total;
		}
	}

	function list_by_parent($parent, $is_manage = false, $is_count = false) {
		$sql = null;
		$comment_table = $this->table('comments');

		if(!$is_count) {
			$sql = 'select coid, cid, created, text, parent from ' . $comment_table . ' as comment where parent = ' . $parent . ' order by created desc';	
		} else {
			$sql = 'select count(1) total from ' . $comment_table . ' as comment where parent = ' . $parent;
		}

		$result = $this->db->query($sql);

		if(!$is_count) {
			$comments = array();
			while($row = $this->db->fetch_array($result)) {
				$comments[] = $row;
			}
			return $comments;
		} else {
			$row = $this->db->fetch_array($result);
			return $row['total'];
		}
		
	}
}
?>