<?php

class default_value extends PANEL_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
		$this->load->view( 'panel/master/default_value' );
    }
	
	function action() {
		$action = (isset($_POST['action'])) ? $_POST['action'] : '';
		unset($_POST['action']);
		
		$result = array();
		if ($action == 'update') {
			$result = $this->Default_Value_model->update($_POST);
		} else if ($action == 'delete') {
			$result = $this->Default_Value_model->delete($_POST);
		}
		
		echo json_encode($result);
	}
	
	function grid() {
		$_POST['column'] = array( 'name', 'value' );
		$output = array(
			"sEcho" => intval($_POST['sEcho']),
			"aaData" => $this->Default_Value_model->get_array($_POST),
			"iTotalDisplayRecords" => $this->Default_Value_model->get_count()
		);
		echo json_encode( $output );
	}
}