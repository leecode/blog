<?php
class User_Model extends Model {
	public function save() {
		$sql = null;

		$db_params = new DBParams();
		$uid = $this->uid;
		if(!$uid) {
			$sql = 'insert into ' . $this->table('users') . ' (name, password, mail, screenName, created, activated) '
					. ' values (?, ?, ?, ?, ?, ?)';

			$db_params->add('s', $this->name);
			$db_params->add('s', $this->password);
			$db_params->add('s', $this->mail);
			$db_params->add('s', $this->screenName);
			$db_params->add('i', $this->created);
			$db_params->add('i', $this->activated);

		} else {
			$sql = 'update ' . $this->table('users') . ' set mail = ?, screenName = ? where uid = ?';

			$db_params->add('s', $this->mail);
			$db_params->add('s', $this->screenName);
			$db_params->add('i', $uid);
		}

		$this->db->stmt_init();
		$ready = $this->db->prepare($sql);
		if($ready) {
			$this->db->bind_params($db_params);
			$this->db->stmt_execute();
		}
		$this->db->stmt_close();

		return $this->db->insert_id();
	}

	public function delete() {
		$uid = $this->uid;
		if(!$uid) {
			$sql = 'delete from ' . $this->table('users') . ' where uid = ?';

			$this->db->stmt_init();
			$this->db->prepare($sql);
			$this->db->bind_params('i', $uid);

			$this->db->stmt_execute();
			$this->db->stmt_close();
		}
	}

	public function delete_batch($uids) {
		if(!empty($uids)) {
			$sql = 'delete from ' . $this->table('users') . ' where uid in (' . $uids . ')';

			$this->db->query($sql);
		}
	}

	public function get_by_uid($uid) {
		$sql = "select uid, name, password, mail, screenName, created, activated from " 
				. $this->table('users') . ' where uid = ?';

		$this->db->stmt_init();
		$this->db->prepare($sql);
		$this->db->bind_params('i', $uid);

		$row = $this->db->stmt_fetch_array();
		$this->db->stmt_close();
		return $row[0];
	}

	public function get_by_name($name) {
		$sql = "select uid, name, password, mail, screenName, created, activated from " 
				. $this->table('users') . ' where name = ?';

		$this->db->stmt_init();
		$this->db->prepare($sql);
		$this->db->bind_params('s', $name);
		$row = $this->db->stmt_fetch_array();
		$this->db->stmt_close();

		return $row[0];
	}

	public function get_users($offset = 0, $limit = 10, $q_screenName = '', $is_count = false) {
		$query_cols = 'uid, name, screenName, mail, created, activated';
		if($is_count) {
			$query_cols = 'count(1) total';
		}

		$sql = "select $query_cols from " . $this->table('users') . ' where 1=1';
		$sort_part = 'order by created desc';

		$db_params = new DBParams();

		if(!empty($q_screenName)) {
			$sql .= " and (screenName like ? or name like ?) ";
			$q = '%' . $q_screenName . '%';
			$db_params->add('s', $q);
			$db_params->add('s', $q);
		}

		$sql .= " $sort_part ";
		if($offset >= 0) {
			$sql .= " limit ?,?";
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
			return $result[0]['total'];
		}
	}
}
?>