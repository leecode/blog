<?php
class Metas_Model extends Model {
	public function save() {
		$sql = null;
		$table = $this->table('metas');
		if(empty($this->attributes['mid'])) {
			$sql = 'insert into ' . $table . ' (name, type, description, meta_order) ' .
				   'values("' . $this->attributes['name'] . '","' . $this->attributes['type'] . '", "' 
				   . $this->attributes['description'] . '", ' . $this->attributes['meta_order'] . ')';
		} else {
			$sql = 'update ' . $table . ' set name = "' . $this->attributes['name'] 
				   . '", ' 
				   . 'description = "' . $this->attributes['description']
				   . '", meta_order = ' . $this->attributes['meta_order']
				   . ' where mid = ' . $this->attributes['mid'];
		}

		$this->db->query($sql);
		return $this->db->insert_id();
	}

	public function get_by_mid($mid) {
		if(empty($mid)) {
			return false;
		}

		$sql = 'select mid, name, type, description, meta_order from ' . $this->table('metas') . ' where mid = ' . $mid;
		$result = $this->db->query($sql);
		$row = $this->db->fetch_array($result);
		return $row;
	}

	public function delete() {
		if(!empty($this->attributes['mid'])) {
			$sql = 'delete from ' . $this->table('metas') . ' where mid = ' . $this->attributes['mid'];

			$this->db->query($sql);
		} else {

		}
	}

	public function delete_batch($mids) {
		if(!empty($mids)) {
			$sql = 'delete from ' . $this->table('metas') . ' where mid in (' . $mids . ')';

			$this->db->query($sql);
		} else {
		}
	}

	public function list_metas($type = 'category') {
		if(empty($type)) {
			$type = 'category';
		}
		$sql = 'select mid, name, description, type, meta_order from ' . $this->table('metas') .
			   ' where type = "' . $type . '" order by meta_order';

		$result = $this->db->query($sql);
		$metas = false;

		while($row = $this->db->fetch_array($result)) {
			$metas[] = $row;
		}

		return $metas;
	}
}
?>