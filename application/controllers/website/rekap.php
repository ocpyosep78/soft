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
			$saldo = $this->User_model->get_saldo(array( 'user_id' => $user['id'] ));
			
			// validation
			if ($saldo['saldo_profit'] < MINIMIN_RUPIAH) {
				$result['message'] = 'Maaf, minimum penarikan dana adalah '.rupiah(MINIMIN_RUPIAH);
				echo json_encode($result);
				exit;
			}
			
			$param_withdraw['user_id'] = $user['id'];
			$param_withdraw['request_datetime'] = $this->config->item('current_datetime');
			$param_withdraw['last_user_item_id'] = $saldo['last_user_item_id']['id'];
			$param_withdraw['amout_rp'] = $saldo['saldo_rupiah'];
			$param_withdraw['amount_idr'] = $saldo['saldo_dollar_at_rupiah'];
			$param_withdraw['prosentase'] = $saldo['saldo_percent']['percent'];
			$param_withdraw['currency'] = 'IDR';
			$param_withdraw['status'] = 'pending';
			$result = $this->Withdraw_model->update($param_withdraw);
			$result['message'] = 'Harap menunggu konfirmasi dari admin untuk proses penarikan dana.';
			
			@mail("herry@simetri.in", "Request Withdraw", $user['name']." Request withdraw, tolong diproses yaa bro", "From: info@lintasapps.com");
		}
		
		echo json_encode($result);
	}
}