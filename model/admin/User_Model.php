<?php
class User_Model extends Model {
	public function save() {
		$sql = null;

		if(empty($this->attributes['uid'])) {
			$sql = 'insert into ' . $this->table('users') . ' (name, password, mail, screenName, created, activated) '
					. ' values("' . $this->attributes['name'] . '", "' . md5($this->attributes['password']) . '", "'
					. $this->attributes['mail'] . '", "' . $this->attributes['screenName'] . '",' . $this->attributes['created']
					. ', ' . $this->attributes['activated'] . ')';
		} else {
			$sql = 'update ' . $this->table('users') . ' set '#password = "' . md5($this->attributes['password']) . '", '
					. ' mail = "' . $this->attributes['mail'] . '",screenName="' . $this->attributes['screenName'] . '"'
					. ' where uid = ' . $this->attributes['uid'];
		}

		$this->db->query($sql);
		return $this->db->insert_id();
	}

	public function delete() {
		if(!empty($this->attributes['uid'])) {
			$sql = 'delete from ' . $this->table('users') . ' where uid = ' . $this->attributes['uid'];

			$this->db->query($sql);
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
				. $this->table('users') . ' where uid = ' . $uid;

		$result = $this->db->query($sql);
		$row = $this->db->fetch_array($result);

		return $row;
	}

	public function get_by_name($name) {
		$sql = "select uid, name, password, mail, screenName, created, activated from " 
				. $this->table('users') . ' where name = "' . $name . '"';

		$result = $this->db->query($sql);
		$row = $this->db->fetch_array($result);

		return $row;
	}

	public function get_users($offset = 0, $limit = 10, $q_screenName = '', $is_count = false) {
		$query_cols = 'uid, name, screenName, mail, created, activated';
		if($is_count) {
			$query_cols = 'count(1) total';
		}

		$sql = "select $query_cols from " . $this->table('users') . ' where 1=1';
		$sort_part = 'order by created desc';

		if(!empty($q_screenName)) {
			$sql .= " and (screenName like '%$q_screenName%' or name like '%$q_screenName%') ";
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