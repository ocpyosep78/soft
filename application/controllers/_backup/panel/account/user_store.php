<?php

class user_store extends PANEL_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
		$this->load->view( 'panel/account/user_store' );
    }
	
	function action() {
		$action = (isset($_POST['action'])) ? $_POST['action'] : '';
		unset($_POST['action']);
		
		$result = array();
		if ($action == 'update') {
			$result = $this->User_Store_model->update($_POST);
		} else if ($action == 'delete') {
			$result = $this->User_Store_model->delete($_POST);
		}
		
		echo json_encode($result);
	}
	
	function grid() {
		$_POST['column'] = array(  'title', 'name', 'domain' );
		$output = array(
			"sEcho" => intval($_POST['sEcho']),
			"aaData" => $this->User_Store_model->get_array($_POST),
			"iTotalDisplayRecords" => $this->User_Store_model->get_count()
		);
		echo json_encode( $output );
	}
}