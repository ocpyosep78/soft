<?php
    
    class transaction extends PANEL_Controller {
        function __construct() {
            parent::__construct();
            // user
            $this->user = $this->User_model->get_session();
        }
        
        function index() {
            $this->load->view( 'panel/order/transaction' );
        }
        
        function action() {
            $action = (isset($_POST['action'])) ? $_POST['action'] : '';
            unset($_POST['action']);
            
            $result = array();
            if ($action == 'update') {
                $result = $this->Transaction_model->update($_POST);
                } else if ($action == 'delete') {
                $result = $this->Transaction_model->delete($_POST);
            }
            
            echo json_encode($result);
        }
        
        function grid() {
			$_POST['status_nota_id'] = STATUS_NOTA_CONFIRM;
			$_POST['column'] = array( 'nota_date', 'id', 'nota_name', 'nota_email', 'nota_tax', 'nota_deposit', 'nota_total', 'nota_currency_total' );
			$_POST['is_custom']  = '<img class="cursor product" src="'.base_url('static/img/button_product.png').'" style="width: 15px; height: 16px;"> ';
			
			$output = array(
				"sEcho" => intval($_POST['sEcho']),
				"aaData" => $this->Nota_model->get_array($_POST),
				"iTotalDisplayRecords" => $this->Nota_model->get_count()
			);
			echo json_encode( $output );
        }
    }    