<?php
class Metas_Model extends Model {
	public function save() {
		$sql = null;
		$table = $this->table('metas');
		$is_update = false;
		if(!$this->mid) {
			$sql = 'insert into ' . $table . ' (name, type, description, meta_order) values(?, ?, ?, ?)';
		} else {
			$is_update = true;
			$sql = 'update ' . $table . ' set name = ?, description = ?, meta_order = ? where mid = ?';
		}

		$this->db->stmt_init();
		$this->db->prepare($sql);

		$db_params = new DBParams();
		if($is_update) {
			$db_params->add('s', $this->name);
			$db_params->add('s', $this->description);
			$db_params->add('i', $this->meta_order);
			$db_params->add('i', $this->mid);
		} else {
			$db_params->add('s', $this->name);
			$db_params->add('s', $this->type);
			$db_params->add('s', $this->description);
			$db_params->add('i', $this->meta_order);	
		}
		
		$this->db->bind_params($db_params);
		$this->db->stmt_execute();
		$this->db->stmt_close();

		return $this->db->insert_id();
	}

	public function increse_count() {
		if(!$this->mid) {
			error_log("Trying to increse count to category with no mid");
			return false;
		}
		// 只更新文章数量
		if('category' == $this->type) {
			$sql = 'update ' . $this->table('metas') . ' set count = count + 1 where mid = ?';

			$this->db->stmt_init();
			$this->db->prepare($sql);
			$this->db->bind_params('i', $this->mid);
			$this->db->stmt_execute();
			$this->db->stmt_close();
		}
	}

	public function decrese_count() {
		if(!$this->mid) {
			error_log("Trying to decrese count to category with no mid");
			return false;
		}
		// 只更新文章数量
		if('category' == $this->type) {
			$sql = 'update ' . $this->table('metas') . ' set count = count - 1 where count > 0 and mid = ?';

			$this->db->stmt_init();
			$this->db->prepare($sql);
			$this->db->bind_params('i', $this->mid);
			$this->db->stmt_execute();
			$this->db->stmt_close();
		}	
	}

	public function decrese_count_batch($mids) {
		if(empty($mids)) {
			error_log('Trying to descres count to categories with no mids specified.');
			return false;
		}

		$sql = 'update ' . $this->table('metas') . ' set count = count - 1 where count > 0 and mid in ('. $mids .')';

		$this->db->stmt_init();
		$this->db->prepare($sql);
		$this->db->stmt_execute();
		$this->db->stmt_close();
	}

	public function increse_count_batch($mids) {
		if(empty($mids)) {
			error_log('Trying to increse count to categories with no mids specified.');
			return false;
		}

		$sql = 'update ' . $this->table('metas') . ' set count = count + 1 where mid in (' . $mids . ')';

		$this->db->stmt_init();
		$this->db->prepare($sql);
		$this->db->stmt_execute();
		$this->db->stmt_close();
	}

	public function get_by_mid($mid) {
		if(!$mid) {
			return false;
		}

		$sql = 'select mid, name, type, description, meta_order from ' . $this->table('metas') . ' where mid = ?';

		$this->db->stmt_init();
		$this->db->prepare($sql);
		$this->db->bind_params('i', $mid);

		$row = $this->db->stmt_fetch_array();
		$this->db->stmt_close();

		return $row[0];
	}

	public function delete() {
		if($this->mid) {
			$sql = 'delete from ' . $this->table('metas') . ' where mid = ?';

			$this->db->stmt_init();
			$this->db->prepare($sql);
			$this->db->bind_params('i', $this->mid);
			$this->db->stmt_execute();
			$this->db->stmt_close();
		} else {

		}
	}

	public function delete_batch($mids) {
		if(!empty($mids)) {
			$sql = 'delete from ' . $this->table('metas') . ' where mid in ('. $mids. ')';

			$this->db->stmt_init();
			$this->db->prepare($sql);
			$this->db->stmt_execute();
			$this->db->stmt_close();
		} else {
		}
	}

	public function list_metas($type = 'category') {
		if(empty($type)) {
			$type = 'category';
		}
		$sql = 'select mid, name, description, type, meta_order , count from ' . $this->table('metas') .
			   ' where type = ? order by meta_order';

		$this->db->stmt_init();
		$this->db->prepare($sql);
		$this->db->bind_params('s', $type);
		$metas = $this->db->stmt_fetch_array();
		$this->db->stmt_close();

		return $metas;
	}
}
?>