<?php
class Contents_Model extends Model {
	public function save() {
		$sql = null;
		$table = $this->table('contents');
		if(empty($this->attributes['cid'])) {
			$sql = 'insert into ' . $table . ' (title, created, text, author_id) ' .
				   'values("' . $this->attributes['title'] . '",' . $this->attributes['created'] . ', "' 
				   . $this->attributes['text'] . '", ' . $this->attributes['author_id'] . ')';
		} else {
			$sql = 'update ' . $table . ' set title = "' . $this->attributes['title'] 
				   . '", modified = ' . $this->attributes['modified'] . ', ' 
				   . 'text = "' . $this->attributes['text']
				   . '" where cid = ' . $this->attributes['cid'];
		}

		$this->db->query($sql);
		return $this->db->insert_id();
	}

	public function get_by_cid($cid) {
		if(empty($cid)) {
			return false;
		}

		$sql = 'select cid, title, text, created, modified from ' . $this->table('contents') . ' where cid = ' . $cid;
		$result = $this->db->query($sql);
		$row = $this->db->fetch_array($result);
		return $row;
	}

	public function delete() {
		if(!empty($this->attributes['cid'])) {
			$sql = 'delete from ' . $this->table('contents') . ' where cid = ' . $this->attributes['cid'];

			$this->db->query($sql);
		} else {

		}
	}

	public function delete_batch($cids) {
		if(!empty($cids)) {
			$sql = 'delete from ' . $this->table('contents') . ' where cid in (' . $cids . ')';

			$this->db->query($sql);
		} else {
		}	
	}

	public function list_contents($offset = 0, $limit = 10, $q_title = '', $is_count = false, $category = -1) {
		$query_cols = 'cid, title, text, author_id, created, modified';
		if($is_count) {
			$query_cols = 'count(1) total';
		}

		$sql = "select $query_cols from " . $this->table('contents') . ' where 1=1';
		$sort_part = 'order by created desc';

		if(0 < $category && is_numeric($category)) {
			$sql .= " and cid in (select relationship.cid from " . $this->table('relationships') .
					" relationship where relationship.mid = $category)";
		}

		if(!empty($q_title)) {
			$sql .= " and title like '%$q_title%'";
		}

		$sql .= " $sort_part ";
		if($offset >= 0) {
			$sql .= " limit $offset, $limit";
		}

		$result = $this->db->query($sql);

		if(!$is_count) {
			$contents = array();
			while($row = $this->db->fetch_array($result)) {
				$contents[] = $row;
			}

			return $contents;
		} else {
			$count_row = $this->db->fetch_array($result);
			return $count_row['total'];
		}
	}

}
?>