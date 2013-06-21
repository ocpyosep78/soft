<?php
    
    class store_image_slide extends PANEL_Controller {
        function __construct() {
            parent::__construct();
            // user
            $this->user = $this->User_model->get_session();
        }
        
        function index() {
            $this->load->view( 'panel/store/store_image_slide' );
        }
        
        function action() {
            $action = (isset($_POST['action'])) ? $_POST['action'] : '';
            unset($_POST['action']);
            
            $result = array();
            if ($action == 'update') {
				$_POST['store_id'] = $this->user['store_active']['store_id'];
                $result = $this->Store_Image_Slide_model->update($_POST);
			} else if ($action == 'delete') {
                $result = $this->Store_Image_Slide_model->delete($_POST);
            }
            
            echo json_encode($result);
        }
        
        function grid() {
            $user = $this->User_model->get_session();
            $_POST['column'] = array(  'title', 'content', 'active_text' );
            $_POST['store_id'] = $this->user['store_active']['store_id'];
            
            $output = array(
			"sEcho" => intval($_POST['sEcho']),
			"aaData" => $this->Store_Image_Slide_model->get_array($_POST),
			"iTotalDisplayRecords" => $this->Store_Image_Slide_model->get_count()
            );
            echo json_encode( $output );
        }
    }            