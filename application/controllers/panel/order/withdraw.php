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
			} else if ($user['saldo_rupiah'] < $withdraw['value_rupiah'] || $user['saldo_dollar'] < $withdraw['value_dollar']) {
				$_POST['status'] = 'cancel';
				$this->Withdraw_model->update($_POST);
				
				$result['status'] = false;
				$result['message'] = 'Saldo user yang bersangkutan tidak mencukupi.';
			} else {
				$param_withdraw['id'] = $withdraw['user_id'];
				$param_withdraw['saldo_rupiah'] = 0 - $withdraw['value_rupiah'];
				$param_withdraw['saldo_dollar'] = 0 - $withdraw['value_dollar'];
				$this->User_model->update_saldo($param_withdraw);
				
				$result = $this->Withdraw_model->update($_POST);
			}
		} else if ($action == 'delete') {
			$result = $this->Withdraw_model->delete($_POST);
		}
		
		echo json_encode($result);
	}
	
	function grid() {
		$_POST['column'] = array( 'withdraw_date', 'user_name', 'value_rupiah', 'value_dollar', 'status' );
		
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