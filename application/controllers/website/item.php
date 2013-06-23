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
	
	function invoice() {
		$this->load->view( 'website/item_invoice' );
	}
	
	function download() {
		preg_match('/([\d]+)\/([\d]+)/i', $_SERVER['REQUEST_URI'], $match);
		$item_id = (!empty($match[1])) ? $match[1] : 0;
		$file_no = (!empty($match[2])) ? $match[2] : 0;
		
		// data
		$user = $this->User_model->get_session();
		$item = $this->Item_model->get_by_id(array( 'id' => $item_id ));
		
		// make sure this user have buy this file
		$is_buy = $this->User_Item_model->is_buy(array( 'item_id' => $item_id, 'user_id' => $user['id'] ));
		if (! $is_buy) {
			echo 'Please login / buy this item';
			exit;
		}
		
		// get file info
		$path_file = $this->config->item('base_path').'/../files';
		$path_file = realpath($path_file).'/'.$item['array_filename'][$file_no];
		
		// force download
		header('Content-Disposition: attachment; filename=' . basename($path_file));
		readfile($path_file);
		exit;
	}
	
	function payment() {
		// action
		$action = (!empty($_POST['action'])) ? $_POST['action'] : '';
		unset($_POST['action']);
		
		// make sure, buyer have email, force add user by email
		$is_login = $this->User_model->is_login();
		if (!$is_login) {
			$temp = $this->User_model->get_by_id(array( 'email' => $_POST['email']));
			if (count($temp) == 0) {
				$temp = $this->User_model->update(array( 'email' => $_POST['email']));
			}
			
			$user = $this->User_model->get_by_id(array( 'id' => $temp['id'] ));
			$this->User_model->set_session($user);
		}
		
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
			$this->paypal->set_session(array( 'link' => $payment['links'], 'item_id' => $param['item_id'], 'price' => $param['price'] ));
			
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
			$item = $this->Item_model->get_by_id(array( 'id' => $paypal_session['item_id'] ));
			
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
			
			$payment['state'] = 'approved';
			if ($payment['state'] == 'approved') {
				$invoice_no = $this->User_Item_model->get_max_no();
				
				// add invoice
				$param_update = array(
					'user_id' => $user['id'],
					'price' => $paypal_session['price'],
					'item_id' => $paypal_session['item_id'],
					'invoice_no' => $invoice_no,
					'payment_name' => 'paypal',
					'payment_date' => $this->config->item('current_datetime')
				);
				$this->User_Item_model->update($param_update);
				
				// sent mail
				$param_mail['to'] = $user['email'];
				$param_mail['subject']  = 'Invoice';
				$param_mail['message']  = '<h4>Terima kasih, berikut invoice anda:</h4>';
				$param_mail['message'] .= '<div>No : '.$invoice_no.'</div>';
				$param_mail['message'] .= '<div>Email : '.$user['email'].'</div>';
				$param_mail['message'] .= '<div>Item : '.$item['name'].' | '.$item['price_text'].'</div>';
				$param_mail['message'] .= '<div>Bayar melalui : paypal</div><br /><br />';
				$param_mail['message'] .= '<h4><a href="'.base_url('item/invoice/'.$invoice_no).'">Download</a></h4>';
				sent_mail($param_mail);
				
				$redirect_url = base_url('item/invoice/'.$invoice_no);
				header("Location: ".$redirect_url);
				exit;
			}
		}
		
		return $result;
	}
}