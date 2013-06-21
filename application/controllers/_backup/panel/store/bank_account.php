<?php

class bank_account extends PANEL_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
		$this->load->view( 'panel/store/bank_account' );
    }
	
	function action() {
		$action = (isset($_POST['action'])) ? $_POST['action'] : '';
		unset($_POST['action']);
		
		$result = array();
		if ($action == 'update') {
			$result = $this->Bank_Account_model->update($_POST);
		} else if ($action == 'delete') {
			$result = $this->Bank_Account_model->delete($_POST);
		}
		
		echo json_encode($result);
	}
	
	function grid() {
		$user = $this->User_model->get_session();
		$_POST['column'] = array( 'title', 'no_rekening', 'pemilik' );
		$_POST['store_id'] = $user['store_active']['store_id'];
		
		$output = array(
			"sEcho" => intval($_POST['sEcho']),
			"aaData" => $this->Bank_Account_model->get_array($_POST),
			"iTotalDisplayRecords" => $this->Bank_Account_model->get_count()
		);
		echo json_encode( $output );
	}
}