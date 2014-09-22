<?php
class Relationships_Model extends Model {
	public function save() {
		$sql = null;
		$table = $this->table('relationships');

		// $sql = 'insert into ' . $table . '(cid, mid) values (' 
		// 					  . $this->attributes['cid'] . ', ' 
		// 					  . $this->attributes['mid'] . ')';
		$sql = 'insert into ' . $table . ' (cid, mid) values (?, ?)';

		$this->db->stmt_init();
		$this->db->prepare($sql);

		$db_params = new DBParams();
		$db_params->add('i', $this->cid);
		$db_params->add('i', $this->mid);

		$this->db->bind_params($db_params);

		$this->db->stmt_execute($sql);
		$this->db->stmt_close();
		return $this->db->insert_id();
	}

	public function delete() {
		$sql = null;
		$cid = $this->cid;
		$mid = $this->mid;

		if(empty($cid) && empty($mid)) {
			error_log('Either "cid" or "mid" is needed at least to perform delete on relationships table; Delete failed.');
			return false;
		}

		$sql = 'delete from ' . $this->table('relationships') . ' where 1=1 ';
		$db_params = new DBParams();

		if(!empty($cid)) {
			$sql .= ' and cid = ?';
			$db_params->add('i', $cid);
		}

		if(!empty($mid)) {
			$sql .= ' and mid = ?';
			$db_params->add('i', $mid);
		}

		$this->db->stmt_init();
		$this->db->prepare($sql);
		$this->db->bind_params($db_params);
		$this->db->stmt_execute();
		$this->db->stmt_close();
	}

	public function get_contents_count($mid) {
		if(empty($mid)) {
			return 0;
		}

		$sql = 'select count(1) post_count from ' . $this->table('relationships') . ' where mid = ?';

		$this->db->stmt_init();
		$this->db->prepare($sql);
		$this->db->bind_params('i', $mid);

		$this->db->stmt_fetch_array();

		$result = $this->db->query($sql);
		$row = $this->db->fetch_array($result);
		$this->db->stmt_close();

		return $row[0]['post_count'];
	}

	public function get_category_ids_of_contents($cid) {
		if(empty($cid)) {
			return 0;
		}

		$sql = 'select mid from ' . $this->table('relationships') . ' where cid = ?';

		$this->db->stmt_init();
		$this->db->prepare($sql);
		$this->db->bind_params('i', $cid);
		$this->db->stmt_execute();

		$result = $this->db->stmt_fetch_array();
		$this->db->stmt_close();

		foreach ($result as $row) {
			$category_ids[] = $row['mid'];
		}

		return $category_ids;
	}
}
?>