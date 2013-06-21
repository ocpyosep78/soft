<?php

class user_payment extends PANEL_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
		$this->load->view( 'panel/account/user_payment' );
    }
	
	function action() {
		$action = (isset($_POST['action'])) ? $_POST['action'] : '';
		unset($_POST['action']);
		
		// user
		$user_session = $this->User_model->get_session();
		
		$result = array();
		if ($action == 'update') {
			if (empty($_POST['id'])) {
				$_POST['status'] = 'pending';
				$_POST['payment_date'] = $this->config->item('current_date_only');
				$_POST['user_id'] = (!empty($_POST['user_id'])) ? $_POST['user_id'] : $user_session['id'];
				
			}
			
			if (isset($_POST['user_id'])) {
				// detail user
				$user = $this->User_model->get_by_id(array( 'id' => $_POST['user_id'] ));
				
				// validation
				if (isset($_POST['value']) && $_POST['value'] > $user['deposit']) {
					$result['status'] = false;
					$result['message'] = 'Maaf, nilai deposit anda belum mencukupi.';
					echo json_encode($result);
					exit;
				}
			}
			
			$result = $this->User_Payment_model->update($_POST);
		} else if ($action == 'update_deposit') {
			$result = $this->User_Payment_model->update($_POST);
			
			if (!empty($_POST['status']) && $_POST['status'] == 'confirm') {
				$user_payment = $this->User_Payment_model->get_by_id(array( 'id' => $_POST['id'] ));
				$user = $this->User_model->get_by_id(array( 'id' => $user_payment['user_id'] ));
				$param_deposit = array( 'id' => $user['id'], 'deposit' => $user['deposit'] - $user_payment['value'] );
				$this->User_model->update($param_deposit);
			}
		} else if ($action == 'delete') {
			$result = $this->User_Payment_model->delete($_POST);
		}
		
		echo json_encode($result);
	}
	
	function grid() {
		$_POST['column'] = array( 'payment_date', 'fullname', 'email', 'value', 'note', 'status' );
		
		$output = array(
			"sEcho" => intval($_POST['sEcho']),
			"aaData" => $this->User_Payment_model->get_array($_POST),
			"iTotalDisplayRecords" => $this->User_Payment_model->get_count()
		);
		echo json_encode( $output );
	}
}