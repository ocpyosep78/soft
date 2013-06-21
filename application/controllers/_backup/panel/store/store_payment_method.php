<?php
    
    class store_payment_method extends PANEL_Controller {
        function __construct() {
            parent::__construct();
            // user
            $this->user = $this->User_model->get_session();
        }
        
        function index() {
            $this->load->view( 'panel/store/store_payment_method' );
        }
        
        function action() {
            $action = (isset($_POST['action'])) ? $_POST['action'] : '';
            unset($_POST['action']);
            
            $result = array();
            if ($action == 'update') {
                $_POST['store_id'] = $this->user['store_active']['store_id'];
                $result = $this->Store_Payment_Method_model->update($_POST);
                } else if ($action == 'delete') {
                $result = $this->Store_Payment_Method_model->delete($_POST);
            }
            
            echo json_encode($result);
        }
        
        function grid() {
            $_POST['column'] = array(  'name' );
            $_POST['store_id'] = $this->user['store_active']['store_id'];
            
            $output = array(
			"sEcho" => intval($_POST['sEcho']),
			"aaData" => $this->Store_Payment_Method_model->get_array($_POST),
			"iTotalDisplayRecords" => $this->Store_Payment_Method_model->get_count()
            );
            echo json_encode( $output );
        }
    }    