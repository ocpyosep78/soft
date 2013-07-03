<?php

class pages extends PANEL_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
		$this->load->view( 'panel/master/pages' );
    }
	
	function action() {
		$action = (isset($_POST['action'])) ? $_POST['action'] : '';
		unset($_POST['action']);
		
		// user
		$user = $this->User_model->get_session();
		$result = array();
		if ($action == 'update') {
			$result = $this->Pages_model->update($_POST);
		} else if ($action == 'delete') {
			$result = $this->Pages_model->delete($_POST);
		}
		
		echo json_encode($result);
	}
	
	function grid() {
		// user
		$user = $this->User_model->get_session();
		
		$_POST['column'] = array( 'title','name','content' );
		$output = array(
			"sEcho" => intval($_POST['sEcho']),
			"aaData" => $this->Pages_model->get_array($_POST),
			"iTotalDisplayRecords" => $this->Pages_model->get_count()
		);
		echo json_encode( $output );
	}
}