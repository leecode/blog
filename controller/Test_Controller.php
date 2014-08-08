<?php
class Test_Controller extends Controller {
 		public function __construct() {
            parent::__construct(); 
        } 

        public function index() {
            echo "test"; 
        }

        public function fuckMe() {
        	echo 'Are u fucking kidding me ?';
        }

        public function list_page() {
            $model = $this->model('test');
            $model->get_all_blogs();
        }
        
        public function showModel() {
        	$model = $this->model('test');
        	$data = array('value' => 'what the fuck ?');
        	$this->show_template('test/test', $data);
        	//$model->give_shit();
        }

        public function show_blog_page() {
            $model = $this->model('test');
            $this->show_template('test/front/blog');
        }

        public function shwo_database() {
            $model = $this->model('test');
            $model->show_database_info();
        }

        public function withTemplate() {
        	$model = $this->model('test');
        }
}
?>