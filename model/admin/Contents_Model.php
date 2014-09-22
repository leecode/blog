<?php
class Contents_Model extends Model {
	public function save() {
		$sql = null;
		$table = $this->table('contents');
		$db_params = new DBParams();

		$cid = $this->cid;
		if(!isset($cid) || 0 >= $cid) {
			$sql = 'insert into ' . $table . ' (title, created, text, author_id) values (?, ?, ?, ?)';

			$this->db->stmt_init();
			$this->db->prepare($sql);

			$db_params->add('s', $this->title);
			$db_params->add('i', $this->created);
			$db_params->add('s', $this->text);
			$db_params->add('i', $this->author_id);
		} else {
			$sql = 'update ' . $table . ' set title = ? , modified = ?, text = ? where cid = ?';

			$this->db->stmt_init();
			$this->db->prepare($sql);

			$db_params->add('s', $this->title);
			$db_params->add('i', $this->modified);
			$db_params->add('s', $this->text);
			$db_params->add('i', $this->cid);

		}
		$this->db->bind_params($db_params);
		$this->db->stmt_execute();
		$this->db->stmt_close();
		return $this->db->insert_id();
	}

	public function get_by_cid($cid) {
		if(empty($cid)) {
			return false;
		}

		$sql = 'select cid, title, text, created, modified from ' . $this->table('contents') . ' where cid = ?';

		$this->db->stmt_init();
		$this->db->prepare($sql);

		$db_params = new DBParams();
		$db_params->add('i', $cid);

		$this->db->bind_params($db_params);
		$row = $this->db->stmt_fetch_array();

		$this->db->stmt_close();
		return $row[0];
	}

	public function delete() {
		if(!empty($this->cid)) {
			$sql = 'delete from ' . $this->table('contents') . ' where cid = ?';

			$this->db->stmt_init();
			$this->db->prepare($sql);
			$db_params = new DBParams();
			$db_params->add('i', $this->cid);

			$this->db->bind_params($db_params);
			$this->db->stmt_execute();
			$this->db->stmt_close();
		} else {

		}
	}

	public function delete_batch($cids) {
		if(!empty($cids)) {
			$sql = 'delete from ' . $this->table('contents') . ' where cid in (' . $cids . ')';

			$this->db->stmt_init();
			$this->db->prepare($sql);

			$this->db->stmt_execute();
			$this->db->stmt_close();
		} else {
		}	
	}

	public function list_contents($offset = 0, $limit = 10, $q_title = '', $is_count = false, $category = -1) {
		$query_cols = 'cid, title, text, author_id, created, modified';
		$db_params = new DBParams();

		if($is_count) {
			$query_cols = 'count(1) total';
		}

		$sql = "select $query_cols from " . $this->table('contents') . ' where 1=1';
		$sort_part = 'order by created desc';

		if(0 < $category && is_numeric($category)) {
			$sql .= " and cid in (select relationship.cid from " . $this->table('relationships') .
					" relationship where relationship.mid = ?)";
			$db_params->add('i', $category);
		}

		if(!empty($q_title)) {
			$sql .= " and title like ?";
			$db_params->add('s', '%' . $q_title . '%');
		}

		$sql .= " $sort_part ";
		if($offset >= 0) {
			$sql .= ' limit ?, ?';
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
			$count_row = $result[0]['total'];
			return $count_row;
		}
	}

}
?>