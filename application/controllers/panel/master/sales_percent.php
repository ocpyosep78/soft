<?php

class sales_percent extends PANEL_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
		$this->load->view( 'panel/master/sales_percent' );
    }
	
	function action() {
		$action = (isset($_POST['action'])) ? $_POST['action'] : '';
		unset($_POST['action']);
		
		// user
		$user = $this->User_model->get_session();
		
		$result = array();
		if ($action == 'update') {
			$result = $this->Sales_Percent_model->update($_POST);
		} else if ($action == 'delete') {
			$result = $this->Sales_Percent_model->delete($_POST);
		}
		
		echo json_encode($result);
	}
	
	function grid() {
		// user
		$user = $this->User_model->get_session();
		$_POST['column'] = array( 'percent','rupiah' );

		$output = array(
			"sEcho" => intval($_POST['sEcho']),
			"aaData" => $this->Sales_Percent_model->get_array($_POST),
			"iTotalDisplayRecords" => $this->Sales_Percent_model->get_count()
		);
		echo json_encode( $output );
	}
}