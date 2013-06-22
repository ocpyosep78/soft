<?php

class item extends CI_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
		$method_name = (isset($this->uri->segments[2])) ? $this->uri->segments[2] : '';
		if (method_exists($this, $method_name)) {
			$this->$method_name();
		} else {
			$this->load->view( 'website/item' );
		}
    }
	
	function buy() {
		$this->load->view( 'website/item_buy' );
	}
	
	function payment() {
		// action
		$action = (!empty($_POST['action'])) ? $_POST['action'] : '';
		unset($_POST['action']);
		
		// filter payment method
		$payment_type = (!empty($_POST['payment'])) ? $_POST['payment'] : '';
		if ($payment_type == 'paypal') {
			$_POST['action'] = 'SetPaypalPayment';
		}
		
		// item
		$item = $this->Item_model->get_by_id(array( 'id' => $_POST['id'] ));
		
		// process
		$result = $this->$payment_type(array( 'item_id' => $item['id'], 'price' => $item['price'] ));
		
		echo json_encode($result);
	}
	
	function paypal($param = array()) {
		// init payer
		if (isset($_GET['PayerID']) && !empty($_GET['PayerID'])) {
			$_POST['action'] = 'ExecutePaypalPayment';
		}
		
		$action = (!empty($_POST['action'])) ? $_POST['action'] : '';
		unset($_POST['action']);
		
		// user
		$user = $this->User_model->get_session();
		
		$result = array( 'status' => false, 'message' => '' );
		if ($action == 'SetPaypalPayment') {
			// total price
			$konversi_rupiah = $this->Default_Value_model->get_konversi_rupiah_dolar();
			$paypal_currency = number_format($param['price'] / $konversi_rupiah['value'], 2, '.', '');
			
			// get access token
			$token_param = array(
				'url' => PAYPAL_HOST.'/v1/oauth2/token',
				'data' => 'grant_type=client_credentials',
				'client_id' => PAYPAL_CLIENT_ID,
				'client_secret' => PAYPAL_CLIENT_SECRET
			);
			$token = $this->paypal->get_access_token($token_param);
			if (empty($token->access_token)) {
				$result['status'] = false;
				$result['message'] = 'Token fail.';
				echo json_encode($result);
				exit;
			}
			
			// set payment
			$payment_param = array(
				'token' => $token->access_token,
				'url' => PAYPAL_HOST.'/v1/payments/payment',
				'data' => array(
					'intent' => 'sale',
					'payer' => array( 'payment_method' => 'paypal' ),
					'transactions' => array(
						array(
							'amount' => array( 'total' => $paypal_currency, 'currency' => 'USD' ),
							'description' => 'payment using a PayPal account'
						)
					),
					'redirect_urls' => array(
						'return_url' => base_url('item/paypal'),
						'cancel_url' => base_url()
					)
				)
			);
			$payment = $this->paypal->make_post_call($payment_param);
			$this->paypal->set_session(array( 'link' => $payment['links'], 'item_id' => $param['item_id'] ));
			
			// get paypal approve url
			$paypal_approve_url = '';
			foreach ($payment['links'] as $array) {
				if ($array['rel'] == 'approval_url') {
					$paypal_approve_url = $array['href'];
					break;
				}
			}
			
			
			// result
			$result['status'] = true;
			$result['link_next'] = $paypal_approve_url;
		}
		else if ($action == 'ExecutePaypalPayment') {
			$paypal_session = $this->paypal->get_session();
			
			// get access token
			$token_param = array(
				'url' => PAYPAL_HOST.'/v1/oauth2/token',
				'data' => 'grant_type=client_credentials',
				'client_id' => PAYPAL_CLIENT_ID,
				'client_secret' => PAYPAL_CLIENT_SECRET
			);
			$token = $this->paypal->get_access_token($token_param);
			
			// execute payment
			$payment_param = array(
				'url' => $this->paypal->get_execute_link(),
				'data' => array( 'payer_id' => $_GET['PayerID'] ),
				'token' => $token->access_token
			);
			$payment = $this->paypal->make_post_call($payment_param);
			
			if ($payment['state'] == 'approved') {
				$this->User_Item_model->update(array( 'item_id' => $paypal_session['item_id'], 'user_id' => $user['id'] ));
				
				$redirect_url = base_url('item/download/'.$paypal_session['item_id']);
				header("Location: ".$redirect_url);
				exit;
			}
		}
		
		return $result;
	}
}