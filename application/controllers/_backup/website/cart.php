<?php

class cart extends CI_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
		$request_uri = $_SERVER['REQUEST_URI'];
		$request_uri = preg_replace('/\?.+$/i', '', $request_uri);
		
		preg_match('/(ajax|paypal)$/i', $request_uri, $match);
		if (!empty($match[0]) && in_array($match[0], array('ajax', 'paypal'))) {
			$this->$match[0]();
		} else {
			$this->load->view( 'website/store/theme/calisto/cart' );
		}
    }
	
	function ajax() {
		// store
		$store_name = get_store();
		$store = $this->Store_Detail_model->get_info(array('store_name' => $store_name));
		
		// action
		$action = (!empty($_POST['action'])) ? $_POST['action'] : '';
		unset($_POST['action']);
		
		$result = array( 'status' => false, 'message' => '' );
		if ($action == 'AddCart') {
			$param = $_POST;
			$param['store_id'] = $store['store_id'];
			
			// item
			$item = $this->Item_model->get_by_id(array('id' => $param['item_id']));
			$param['title'] = $item['title'];
			$param['tax'] = $item['tax'];
			$param['discount'] = $item['discount'];
			$param['price'] = $item['price'];
			$param['price_final'] = $item['price_final'];
			$param['currency_name'] = $item['currency_name'];
			$param['catalog_title'] = $item['catalog_title'];
			$param['category_title'] = $item['category_title'];
			$param['item_link'] = $item['item_link'];
			$param['catalog_link'] = $item['catalog_link'];
			$param['category_link'] = $item['category_link'];
			$param['thumbnail_link'] = $item['thumbnail_link'];
			$param['price_label'] = $item['price_label'];
			$this->Cart_model->update_cart($param);
			
			$result['status'] = true;
			$result['message'] = 'Item berhasil ditambahkan pada cart.';
			$result['final_price'] = $this->Cart_model->get_total_price();
			$result['item']['count'] = $this->Cart_model->get_count();
		}
		else if ($action == 'RemoveCart') {
			$this->Cart_model->delete_cart(array('item_id' => $_POST['item_id']));
			
			$result['status'] = true;
			$result['message'] = 'Item berhasil ditambahkan pada cart.';
			$result['final_price'] = $this->Cart_model->get_total_price();
			$result['item']['count'] = $this->Cart_model->get_count();
		}
		else if ($action == 'UpdateCartNote') {
			$this->Cart_model->update_cart_note($_POST);
			$result['status'] = true;
		}
		else if ($action == 'CompleteCart') {
			$param = array('status_nota_id' => STATUS_NOTA_PENDING, 'payment_method_id' => $_POST['payment_method_id']);
			$result = $this->Cart_model->complete_cart($param);
		}
		
		echo json_encode($result);
	}
	
	function paypal() {
		// store
		$store_name = get_store();
		$store = $this->Store_Detail_model->get_info(array('store_name' => $store_name));
		
		// init payer
		if (isset($_GET['PayerID']) && !empty($_GET['PayerID'])) {
			$_POST['action'] = 'ExecutePaypalPayment';
		}
		
		// action
		$action = (!empty($_POST['action'])) ? $_POST['action'] : '';
		unset($_POST['action']);
		
		$result = array( 'status' => false, 'message' => '' );
		if ($action == 'SetPaypalPayment') {
			// paypal config
			$paypal_param = array(
				'filter' => '[{"type":"numeric","comparison":"eq","value":"'.STORE_ID.'","field":"store_id"},{"type":"numeric","comparison":"eq","value":"'.PAYPAL_ID.'","field":"StorePaymentMethod.payment_method_id"}]',
				'limit' => 1
			);
			$paypal_array = $this->Store_Payment_Method_model->get_array($paypal_param);
			$paypal = $paypal_array[0];
			
			// get access token
			$token_param = array(
				'url' => PAYPAL_HOST.'/v1/oauth2/token',
				'data' => 'grant_type=client_credentials',
				'client_id' => $paypal['client_id'],
				'client_secret' => $paypal['client_secret']
			);
			$token = $this->paypal->get_access_token($token_param);
			if (empty($token->access_token)) {
				$result['status'] = false;
				$result['message'] = 'Token fail.';
				echo json_encode($result);
				exit;
			}
			
			// total price
			$konversi_rupiah = $this->Default_Value_model->get_konversi_rupiah_dolar();
			$total_price = $this->Cart_model->get_total_price();
			$paypal_currency = number_format($total_price['price'] / $konversi_rupiah['value'], 2, '.', '');
			
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
						'return_url' => site_url('cart/paypal'),
						'cancel_url' => site_url()
					)
				)
			);
			$payment = $this->paypal->make_post_call($payment_param);
			$this->paypal->set_link($payment['links']);
			
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
			$result['paypal_approval_url'] = $paypal_approve_url;
		}
		else if ($action == 'ExecutePaypalPayment') {
			// paypal config
			$paypal_param = array(
				'filter' => '[{"type":"numeric","comparison":"eq","value":"'.STORE_ID.'","field":"store_id"},{"type":"numeric","comparison":"eq","value":"'.PAYPAL_ID.'","field":"StorePaymentMethod.payment_method_id"}]',
				'limit' => 1
			);
			$paypal_array = $this->Store_Payment_Method_model->get_array($paypal_param);
			$paypal = $paypal_array[0];
			
			// get access token
			$token_param = array(
				'url' => PAYPAL_HOST.'/v1/oauth2/token',
				'data' => 'grant_type=client_credentials',
				'client_id' => $paypal['client_id'],
				'client_secret' => $paypal['client_secret']
			);
			$token = $this->paypal->get_access_token($token_param);
			
			// get paypal execute url
			$array_link = $this->paypal->get_link();
			$paypal_execute_url = '';
			foreach ($array_link as $array) {
				if ($array['rel'] == 'execute') {
					$paypal_execute_url = $array['href'];
					break;
				}
			}
			
			// execute payment
			$payment_param = array(
				'url' => $paypal_execute_url,
				'data' => array( 'payer_id' => $_GET['PayerID'] ),
				'token' => $token->access_token
			);
			$payment = $this->paypal->make_post_call($payment_param);
			if ($payment['state'] == 'approved') {
				$param = array('status_nota_id' => STATUS_NOTA_CONFIRM, 'payment_method_id' => PAYPAL_ID);
				$result = $this->Cart_model->complete_cart($param);
				
				$redirect_url = site_url('checkout/complete/'.$result['order_id']);
				header("Location: ".$redirect_url);
				exit;
			}
		}
		
		echo json_encode($result);
	}
}