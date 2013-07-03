<?php

class capthca extends CI_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
		$method_name = (isset($this->uri->segments[2])) ? $this->uri->segments[2] : '';
		if (method_exists($this, $method_name)) {
			$this->$method_name();
		} else {
			$this->load->view( 'website/capthca' );
		}
    }
	
	function set_human() {
		$action = (isset($_POST['action'])) ? $_POST['action'] : '';
		
		$result = array( 'status' => false, 'message' => '' );
		if ($action == 'Check') {
			if (@$_SESSION['human_captha'] == $_POST['captcha']) {
				$this->User_model->set_human();
				$result['status'] = true;
			} else {
				$result['message'] = 'Captha anda salah.';
			}
		}
		
		echo json_encode($result);
	}
}