<?php

class nota extends PANEL_Controller {
    function __construct() {
        parent::__construct();
        // user
		$this->user = $this->User_model->get_session();
    }
    
    function index() {
		$this->load->view( 'panel/order/nota' );
        
    }
	
	function action() {
		$action = (isset($_POST['action'])) ? $_POST['action'] : '';
		unset($_POST['action']);
		
		$result = array();
		if ($action == 'update') {
           // $_POST['store_id'] = $this->user['store_active']['store_id'];
			$nota = $this->Nota_model->get_by_id(array( 'id' => $_POST['id'] ));
			
			// no update for transaction
			if (! in_array($nota['status_nota_id'], array(STATUS_NOTA_CONFIRM, STATUS_NOTA_CANCEL))) {
				if ($_POST['status_nota_id'] == STATUS_NOTA_CONFIRM) {
					// add item to user buyer
					/*$array_item = $this->Transaction_model->get_array(array( 'nota_id' => $nota['id'] ));
					foreach ($array_item as $item) {
						$param_user_item = array(
							'nota_id' => $nota['id'],
							'user_id' => $nota['user_id'],
							'item_id' => $item['item_id']
						);
						$this->User_Item_model->update($param_user_item);
					}*/
					
				
				} else if ($_POST['status_nota_id'] == STATUS_NOTA_CANCEL) {
					$this->User_Item_model->delete(array( 'user_id' => $nota['user_id'], 'nota_id' => $nota['id'] ));
				}
			}
			
			$result = $this->Nota_model->update($_POST);
		} else if ($action == 'delete') {
			$result = $this->Nota_model->delete($_POST);
		}
		
		echo json_encode($result);
	}
	
	function grid() {
		$_POST['is_edit'] = 1;
		$_POST['column'] = array( 'id', 'nota_date', 'nota_name', 'nota_email', 'nota_currency_total', 'status_nota_name' );
		$_POST['store_id'] = $this->user['store_active']['store_id'];
		$_POST['is_custom']  = '<img class="cursor product" src="'.base_url('static/img/button_product.png').'" style="width: 15px; height: 16px;">  ';
		$output = array(
			"sEcho" => intval($_POST['sEcho']),
			"aaData" => $this->Nota_model->get_array($_POST),
			"iTotalDisplayRecords" => $this->Nota_model->get_count()
		);
		echo json_encode( $output );
	}
	
	function view() {
		$action = (isset($_POST['action'])) ? $_POST['action'] : '';
		unset($_POST['action']);
		
		if ($action == 'product_list') {
			$view = 'panel/order/nota_product_list';
		}
		
		$this->load->view( $view );
	}
}