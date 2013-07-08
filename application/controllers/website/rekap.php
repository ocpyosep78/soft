<?php

class rekap extends CI_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
		$method_name = (isset($this->uri->segments[2])) ? $this->uri->segments[2] : '';
		if (method_exists($this, $method_name)) {
			$this->$method_name();
		} else {
			$this->load->view( 'website/rekap' );
		}
    }
	
	function action() {
		$action = (isset($_POST['action'])) ? $_POST['action'] : '';
		unset($_POST['action']);
		
		$user = $this->User_model->get_session();
		$user = $this->User_model->get_by_id(array( 'id' => $user['id'] ));
		
		$result = array( 'status' => false, 'message' => '' );
		if ($action == 'add_withdraw') {
			// validation
			if ($user['saldo_rupiah'] < $_POST['value_rupiah']) {
				$result['message'] = 'Maaf dana anda tidak mencukupi.';
				echo json_encode($result);
				exit;
			} else if($user['saldo_dollar'] < $_POST['value_dollar']) {
				$result['message'] = 'Maaf dana anda tidak mencukupi.';
				echo json_encode($result);
				exit;
			} else if ($_POST['value_dollar'] < MINIMIN_DOLLAR && $_POST['value_rupiah'] < MINIMIN_RUPIAH) {
				$result['message'] = 'Maaf, minimum penarikan dana adalah '.dollar(MINIMIN_DOLLAR).' atau '.rupiah(MINIMIN_RUPIAH);
				echo json_encode($result);
				exit;
			}
			
			$param_withdraw['user_id'] = $user['id'];
			$param_withdraw['withdraw_date'] = $this->config->item('current_datetime');
			$param_withdraw['value_rupiah'] = $_POST['value_rupiah'];
			$param_withdraw['value_dollar'] = $_POST['value_dollar'];
			$param_withdraw['status'] = 'pending';
			$result = $this->Withdraw_model->update($param_withdraw);
			$result['message'] = 'Harap menunggu konfirmasi dari admin untuk proses penarikan dana.';
			
			@mail("herry@simetri.in", "Request Withdraw", $user['name']." Request withdraw, tolong diproses yaa bro", "From: info@lintasapps.com");
		}
		
		echo json_encode($result);
	}
}