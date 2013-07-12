<?php

class withdraw extends CI_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
		$param_withdraw['status'] = 'pending';
		$param_withdraw['limit'] = 1000;
		$array_withdraw = $this->Withdraw_model->get_array($param_withdraw);
		foreach ($array_withdraw as $withdraw) {
			if ($withdraw['profit'] < MINIMIN_RUPIAH) {
				continue;
			}
			
			$param_update = array( 'id' => $withdraw['id'], 'status' => 'confirm' );
			$this->Withdraw_model->update($param_update);
		}
		
		echo 'done';
    }
}