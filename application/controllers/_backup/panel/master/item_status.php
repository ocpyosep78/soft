<?php

class item_status extends PANEL_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
		$this->load->view( 'panel/master/item_status' );
    }
	
	function action() {
		$action = (isset($_POST['action'])) ? $_POST['action'] : '';
		unset($_POST['action']);
		
		$result = array();
		if ($action == 'update') {
			$result = $this->Item_Status_model->update($_POST);
		} else if ($action == 'delete') {
			$result = $this->Item_Status_model->delete($_POST);
		}
		
		echo json_encode($result);
	}
	
	function grid() {
		$_POST['column'] = array(  'name' );
		$output = array(
			"sEcho" => intval($_POST['sEcho']),
			"aaData" => $this->Item_Status_model->get_array($_POST),
			"iTotalDisplayRecords" => $this->Item_Status_model->get_count()
		);
		echo json_encode( $output );
	}
}