<?php

class platform extends PANEL_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
		$this->load->view( 'panel/master/platform' );
    }
	
	function action() {
		$action = (isset($_POST['action'])) ? $_POST['action'] : '';
		unset($_POST['action']);
		
		// user
		$user = $this->User_model->get_session();
		
		$result = array();
		if ($action == 'update') {
			$_POST['store_id'] = $user['store_active']['store_id'];
			$result = $this->Platform_model->update($_POST);
		} else if ($action == 'delete') {
			$result = $this->Platform_model->delete($_POST);
		}
		
		echo json_encode($result);
	}
	
	function grid() {
		// user
		$user = $this->User_model->get_session();
		
		$_POST['column'] = array('name' );
		$output = array(
			"sEcho" => intval($_POST['sEcho']),
			"aaData" => $this->Platform_model->get_array($_POST),
			"iTotalDisplayRecords" => $this->Platform_model->get_count()
		);
		echo json_encode( $output );
	}
}