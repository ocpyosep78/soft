<?php

class payment_method extends PANEL_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
		$this->load->view( 'panel/master/payment_method' );
    }
	
	function action() {
		$action = (isset($_POST['action'])) ? $_POST['action'] : '';
		unset($_POST['action']);
		
		$result = array();
		if ($action == 'update') {
			$result = $this->Payment_Method_model->update($_POST);
		} else if ($action == 'delete') {
			$result = $this->Payment_Method_model->delete($_POST);
		}
		
		echo json_encode($result);
	}
	
	function grid() {
		$_POST['column'] = array(  'name' );
		$output = array(
			"sEcho" => intval($_POST['sEcho']),
			"aaData" => $this->Payment_Method_model->get_array($_POST),
			"iTotalDisplayRecords" => $this->Payment_Method_model->get_count()
		);
		echo json_encode( $output );
	}
}