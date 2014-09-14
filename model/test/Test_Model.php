<?php

class Test_Model extends Model {
	public function index() {
		echo 'yes, you got me ';
	}

	public function save() {
		// Create a prepared statment.
		$stmt = mysqli_stmt_init($this->db->get_conn());

		if(mysqli_stmt_prepare($stmt, 'insert into app_test (name, description) values(?, ?)')) {
			$params = new DBParams();
			$params->add('s', $this->name);
			$params->add('s', $this->description);

			$func_params = array($stmt);

			$func_params = array_merge($func_params, $params->parse());

			var_dump($func_params);

			call_user_func_array('mysqli_stmt_bind_param', $func_params);

			//mysqli_stmt_bind_param($stmt, $parameters['types'], $parameters['values']);
			mysqli_stmt_execute($stmt);

			mysqli_stmt_close($stmt);
			echo 'are we good ?';
		} else {
			echo 'what the fuck ?';
		}
	}

	public function showList() {
		$this->db->stmt_init();
		$this->db->prepare('select * from app_test');
		$result = $this->db->stmt_fetch_object();
		$this->db->stmt_close();
		var_export($result);
	}

	public function showAll() {
		$stmt = mysqli_stmt_init($this->db->get_conn());

		if(mysqli_stmt_prepare($stmt, 'select * from app_test where name = ?')) {
			call_user_func_array('mysqli_stmt_bind_param', array($stmt, 's', 'lee44'));

			$metadata = mysqli_stmt_result_metadata($stmt);

			$fieldNames = array(&$stmt);

			$obj = new stdClass();
			while($field = mysqli_fetch_field($metadata)) {
				$fn = $field->name;
				$fieldNames[] = &$obj->$fn;
			}

			call_user_func_array('mysqli_stmt_bind_result', $fieldNames);

			mysqli_stmt_execute($stmt);
			while(mysqli_stmt_fetch($stmt)) {
				var_export($obj);	
			}

			
		} else {
			echo 'what the hell ?';
		}
	}

	public function listContents() {
		$this->db->stmt_init();
		$this->db->prepare('select * from app_test where id = ?');

		$db_params = new DBParams();
		$db_params->add('i', "4");
		$this->db->bind_params($db_params);

		$result = $this->db->stmt_fetch_object();
		$this->db->stmt_close();

		echo "<br/>";
		var_dump($result);
	}

	public function updateContents() {
		$this->db->stmt_init();
		$this->db->prepare('update app_test set description = ? where id = ?');

		$db_params = new DBParams();
		$db_params->add('s', 'This is a description;drop table app_test; ----');
		$db_params->add('i', '5');
		$this->db->bind_params($db_params);

		$this->db->stmt_execute();
		$this->db->stmt_close();
		echo "DONE";
	}
}
?>