<?php

class withdraw extends PANEL_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
		$this->load->view( 'panel/order/withdraw' );
        
    }
	
	function action() {
		$action = (isset($_POST['action'])) ? $_POST['action'] : '';
		unset($_POST['action']);
		
		$result = array();
		if ($action == 'update') {
			$withdraw = $this->Withdraw_model->get_by_id(array( 'id' => $_POST['id'] ));
			$user = $this->User_model->get_by_id(array( 'id' => $withdraw['user_id'] ));
			
			// no update for withdraw
			if ($withdraw['status'] != 'pending') {
				$result['status'] = false;
				$result['message'] = 'Withdraw tidak bisa diperbaharui.';
			} else if ($withdraw['profit'] < MINIMIN_RUPIAH) {
				$result['status'] = false;
				$result['message'] = 'Withdraw gagal, profit dibawah minumum penarikan ('.rupiah(MINIMIN_RUPIAH).').';
			} else {
				$result = $this->Withdraw_model->update($_POST);
			}
		} else if ($action == 'delete') {
			$result = $this->Withdraw_model->delete($_POST);
		}
		
		echo json_encode($result);
	}
	
	function grid() {
		$_POST['column'] = array( 'request_datetime', 'user_name', 'amout_rp', 'amount_idr', 'prosentase', 'profit', 'currency', 'status' );
		$output = array(
			"sEcho" => intval($_POST['sEcho']),
			"aaData" => $this->Withdraw_model->get_array($_POST),
			"iTotalDisplayRecords" => $this->Withdraw_model->get_count()
		);
		echo json_encode( $output );
	}
	
	function view() {
		$action = (isset($_POST['action'])) ? $_POST['action'] : '';
		unset($_POST['action']);
		
		$this->load->view( 'panel/order/withdraw' );
	}
}