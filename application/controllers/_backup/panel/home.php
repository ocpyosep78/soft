<?php

class home extends CI_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
		$this->User_model->login_user_store_required();
		$this->load->view( 'panel/home' );
    }
	
	function action() {
		$action = (isset($_POST['action'])) ? $_POST['action'] : '';
		unset($_POST['action']);
		
		echo json_encode($result);
	}
	
    function login() {
		$is_login = $this->User_model->is_login_store();
		
		if ($is_login) {
			$this->load->view( 'panel/home' );
		} else {
			$this->load->view( 'panel/login' );
		}
    }
}