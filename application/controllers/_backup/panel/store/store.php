<?php

class store extends PANEL_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
		$this->load->view( 'panel/store/store' );
    }
	
	function action() {
		$action = (isset($_POST['action'])) ? $_POST['action'] : '';
		unset($_POST['action']);
		
		$result = array();
		if ($action == 'update') {
			$result = $this->Store_model->update($_POST);
		} else if ($action == 'delete') {
			$result = $this->Store_model->delete($_POST);
		}
		
		echo json_encode($result);
	}
	
	function grid() {
		$user = $this->User_model->get_session();
		$_POST['column'] = array(  'title', 'name', 'domain' );
		$_POST['store_id'] = $user['store_active']['store_id'];
		
		$output = array(
			"sEcho" => intval($_POST['sEcho']),
			"aaData" => $this->Store_model->get_array($_POST),
			"iTotalDisplayRecords" => $this->Store_model->get_count()
		);
		echo json_encode( $output );
	}
}