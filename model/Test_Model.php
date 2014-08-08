<?php
class Test_Model extends Model {
	function give_shit() {
		echo 'Well, I dont give a shit.';
	}

	function show_database_info() {
		var_dump($this->db->get_charset());
	}

	function get_all_blogs() {
		$sql = 'select * from ' . $this->table('blog');
		$result = $this->db->query($sql);

		$blogs = array();
		while($row = $this->db->fetch_array($result)) {
			$blogs[] = $row;
		}

		foreach ($blogs as $blog) {
			var_dump($blog);
			echo '<br/>';
		}
		//return $blogs;
	}
}
?>