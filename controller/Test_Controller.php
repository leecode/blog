<?php
class Test_Controller extends Controller {
	public function index() {
		Application::load_model('test/test');

		$model = new Test_Model();

		$model->showAll();
		echo "<br/>Done";

		//$model->showAll();
	}

	public function listContents() {
		echo "FUCK</br>";
		Application::load_model('test/test');

		$model = new Test_Model();

		$model->listContents();
	}

	public function update() {
		Application::load_model('test/test');

		$model = new Test_Model();

		$model->updateContents();
	}

}
?>