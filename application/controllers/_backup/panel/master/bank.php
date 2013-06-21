<?php
    
    class bank extends PANEL_Controller {
        function __construct() {
            parent::__construct();
        }
        
        function index() {
            $this->load->view( 'panel/master/bank' );
        }
        
        function action() {
            $action = (isset($_POST['action'])) ? $_POST['action'] : '';
            unset($_POST['action']);
            
            $result = array();
            if ($action == 'update') {
                $this->load->helper('resize');
                ImageResize($this->config->item('base_path').'/static/upload/'.$_POST['image'], $this->config->item('base_path').'/static/upload/'.$_POST['image'], 250, 250, 1);
                $result = $this->Bank_model->update($_POST);
                } else if ($action == 'delete') {
                if(file_exists($this->config->item('base_path').'/static/upload/'.$_POST['image']))
                {
                    unlink($this->config->item('base_path').'/static/upload/'.$_POST['image']);
                }
                $result = $this->Bank_model->delete($_POST);
            }
            
            echo json_encode($result);
        }
        
        function grid() {
            $user = $this->User_model->get_session();
            $_POST['column'] = array(  'title', 'active_text' );
            
            $output = array(
			"sEcho" => intval($_POST['sEcho']),
			"aaData" => $this->Bank_model->get_array($_POST),
			"iTotalDisplayRecords" => $this->Bank_model->get_count()
            );
            echo json_encode( $output );
        }
    }            