<?php
    
    class theme extends PANEL_Controller {
        function __construct() {
            parent::__construct();
        }
        
        function index() {
            $this->load->view( 'panel/product/theme' );
        }
        
        function action() {
            $action = (isset($_POST['action'])) ? $_POST['action'] : '';
            unset($_POST['action']);
            
            $result = array();
            if ($action == 'update') {
                $result = $this->Theme_model->update($_POST);
                } else if ($action == 'delete') {
                $result = $this->Theme_model->delete($_POST);
            }
            
            echo json_encode($result);
        }
        function grid() {
            $_POST['column'] = array('code', 'name','is_premium' );
            $output = array(
			"sEcho" => intval($_POST['sEcho']),
			"aaData" => $this->Theme_model->get_array($_POST),
			"iTotalDisplayRecords" => $this->Theme_model->get_count()
            );
            echo json_encode( $output );
        }
    }    