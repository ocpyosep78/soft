<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cart_model extends CI_Model {
    function __construct() {
        parent::__construct();
		
		/*	Cart Info */
		/*	Cart Session
			name : user_cart => array(
				array( 'store_id' => 1, 'item_id' => 1, 'quantity' => 1 ),
				array( 'store_id' => 1, 'item_id' => 1, 'quantity' => 1 )
			)
			name : user_nota => array note
		/*	*/
    }
	
	function update_cart($param) {
		if (empty($param['item_id'])) {
			return;
		}
		
		$user_cart = $this->get_array();
		
		$is_avaliable_on_cart = false;
		foreach ($user_cart as $key => $item) {
			if (empty($item['item_id'])) {
				continue;
			} else if ($item['item_id'] == $param['item_id']) {
				$is_avaliable_on_cart = true;
				$user_cart[$key]['quantity'] = $param['quantity'];
			}
		}
		
		if (! $is_avaliable_on_cart) {
			$user_cart[] = $param;
		}
		
		$_SESSION['user_cart'] = $user_cart;
	}
	
	function get_array() {
		if (! isset($_SESSION['user_cart'])) {
			$_SESSION['user_cart'] = array();
		}
		
		return $_SESSION['user_cart'];
	}
	
	function get_count() {
		$array_cart = $this->get_array();
		return count($array_cart);
	}
	
	function get_item_id() {
		$list_item_id = 0;
		$array_cart = $this->get_array();
		foreach ($array_cart as $item) {
			if (empty($item['item_id'])) {
				continue;
			}
			
			$list_item_id = (empty($list_item_id)) ? $item['item_id'] : $list_item_id.','.$item['item_id'];
		}
		
		return $list_item_id;
	}
	
	function get_total_price() {
		$user_cart = $this->get_array();
		
		$result = array('price' => 0, 'currency' => '');
		foreach ($user_cart as $item) {
			if (empty($item['item_id'])) {
				continue;
			}
			
			$result['price'] += ($item['price_final'] * $item['quantity']);
			$result['currency'] = $item['currency_name'];
		}
		$result['price_label'] = $result['currency'].' '.$result['price'];
		
		return $result;
	}
	
	function delete_cart($param) {
		if (isset($param['is_clear']) && $param['is_clear'] == 1) {
			$user_cart = array();
		} else {
			$user_cart = $this->get_array();
			foreach ($user_cart as $key => $item) {
				if ($item['item_id'] == $param['item_id']) {
					unset($user_cart[$key]);
				}
			}
		}
		
		$_SESSION['user_cart'] = $user_cart;
	}
	
	function complete_cart($param) {
		// store
		$store_name = get_store();
		$store = $this->Store_Detail_model->get_info(array('store_name' => $store_name));
		
		$nota = $this->Cart_model->get_cart_note();
		$array_item = $this->Cart_model->get_array();
		
		// user
		$is_login = $this->User_model->is_login();
		$user = ($is_login) ? $this->User_model->get_session() : array('id' => 0);
		
		// total price
		$total_price = $this->Cart_model->get_total_price();
		
		// tax
		$tax_store = $this->Default_Value_model->get_tax_store();
		
		// insert nota
		$param_nota = array(
			'user_id' => $user['id'],
			'store_id' => $store['store_id'],
			'status_nota_id' => $param['status_nota_id'],
			'payment_method_id' => $param['payment_method_id'],
			'nota_note' => @$nota['nota_note'],
			'nota_name' => $user['fullname'],
			'nota_email' => $user['email'],
			'nota_phone' => @$nota['nota_phone'],
			'nota_zipcode' => @$nota['nota_zipcode'],
			'nota_city' => @$nota['nota_city'],
			'nota_country' => @$nota['nota_country'],
			'nota_date' => $this->config->item('current_datetime'),
			'nota_currency' => $total_price['currency'],
			'nota_total' => $total_price['price']
		);
		$update_nota = $this->Nota_model->update($param_nota);
		
		// insert item
		$total_tax = $total_deposit = 0;
		foreach ($array_item as $item) {
			$total = $item['price_final'] * $item['quantity'];
			$tax = ($tax_store['value'] * $total) / 100;
			$deposit = $total - $tax;
			
			// get total
			$total_tax += $tax;
			$total_deposit += $deposit;
			
			// transaction
			$param_item = array(
				'item_id' => $item['item_id'],
				'nota_id' => $update_nota['id'],
				'quantity' => $item['quantity'],
				'discount' => $item['discount'],
				'price' => $item['price'],
				'price_final' => $item['price_final'],
				'currency' => $item['currency_name'],
				'tax' => $tax,
				'deposit' => $deposit,
				'total' => $total
			);
			$this->Transaction_model->update($param_item);
			
			if ($param['status_nota_id'] == STATUS_NOTA_CONFIRM) {
				// user item
				$param_user = array(
					'user_id' => $user['id'],
					'item_id' => $item['item_id'],
					'nota_id' => $update_nota['id']
				);
				$this->User_Item_model->update($param_user);
				
				// update user deposit
				$this->Store_model->add_deposit(array( 'store_id' => $store['store_id'], 'nota_total' => $param_nota['nota_total'] ));
			}
		}
		
		// update nota
		$param_nota = array( 'id' => $update_nota['id'], 'nota_tax' => $total_tax , 'nota_deposit' => $total_deposit );
		$this->Nota_model->update($param_nota);
		
		$this->Cart_model->delete_cart(array('is_clear' => 1));
		$this->Cart_model->delete_cart_note(array('is_clear' => 1));
		
		$result['status'] = true;
		$result['order_id'] = $update_nota['id'];
		
		return $result;
	}
	
	/*	Region Cart Note */
	
	function update_cart_note($param) {
		foreach ($param as $key => $value) {
			$_SESSION['user_nota'][$key] = $value;
		}
	}
	
	function get_cart_note() {
		$user_note = (isset($_SESSION['user_nota'])) ? $_SESSION['user_nota'] : array();
		return $user_note;
	}
	
	function delete_cart_note() {
		$_SESSION['user_nota'] = array();
	}
	
	/*	End Region Cart Note */
}