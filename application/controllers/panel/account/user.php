<?php

class user extends PANEL_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
		$this->load->view( 'panel/account/user' );
    }
	
	function action() {
		$action = (isset($_POST['action'])) ? $_POST['action'] : '';
		unset($_POST['action']);
		
		$result = array();
		if ($action == 'update') {
			if (empty($_POST['passwd'])) {
				unset($_POST['passwd']);
			} else {
				$_POST['passwd'] = EncriptPassword($_POST['passwd']);
			}
			
			$check = $this->User_model->get_by_id(array('name' => $_POST['name']));
			if (count($check) > 0 && $_POST['id'] != $check['id']) {
				$result['status'] = false;
				$result['message'] = 'Username telah terpakai, mohon memakai username yang lain.';
			} else {
				$result = $this->User_model->update($_POST);
			}
		} else if ($action == 'delete') {
			$result = $this->User_model->delete($_POST);
		}
		
		echo json_encode($result);
	}
	
	function grid() {
		$user = $this->User_model->get_session();
		$_POST['column'] = array(  'fullname', 'name', 'email', 'address', 'deposit','is_active');
		
		$output = array(
			"sEcho" => intval($_POST['sEcho']),
			"aaData" => $this->User_model->get_array($_POST),
			"iTotalDisplayRecords" => $this->User_model->get_count()
		);
		echo json_encode( $output );
	}
}