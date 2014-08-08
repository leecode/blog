<?php
class Relationships_Model extends Model {
	public function save() {
		$sql = null;
		$table = $this->table('relationships');

		$sql = 'insert into ' . $table . '(cid, mid) values (' 
							  . $this->attributes['cid'] . ', ' 
							  . $this->attributes['mid'] . ')';

		$this->db->query($sql);
		return $this->db->insert_id();
	}

	public function delete() {
		$sql = null;
		$cid = $this->attributes['cid'];
		$mid = $this->attributes['mid'];

		if(empty($cid) && empty($mid)) {
			error_log('Either "cid" or "mid" is needed at least to perform delete on relationships table; Delete failed.');
			return false;
		}

		$sql = 'delete from ' . $this->table('relationships') . ' where 1=1 ';

		if(!empty($cid)) {
			$sql .= ' and cid = ' . $cid;
		}

		if(!empty($mid)) {
			$sql .= ' and mid = ' . $mid;
		}

		$this->db->query($sql);
	}

	public function get_contents_count($mid) {
		if(empty($mid)) {
			return 0;
		}

		$sql = 'select count(1) post_count from ' . $this->table('relationships') . ' where mid = ' . $mid;

		$result = $this->db->query($sql);
		$row = $this->db->fetch_array($result);

		return $row['post_count'];
	}

	public function get_category_ids_of_contents($cid) {
		if(empty($cid)) {
			return 0;
		}

		$sql = 'select mid from ' . $this->table('relationships') . ' where cid = ' . $cid;

		$result = $this->db->query($sql);

		$category_ids = array();
		while($row = $this->db->fetch_array($result)) {
			$category_ids[] = $row['mid'];
		}

		return $category_ids;
	}
}
?>