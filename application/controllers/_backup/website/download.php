<?php

class download extends CI_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
		$this->User_model->login_user_required();
	
		preg_match('/download\/([a-z]+)\//i', $_SERVER['REQUEST_URI'], $match);
		if (!empty($match[1]) && in_array($match[1], array('ajax', 'request'))) {
			$method_name = $match[1];
			$this->$method_name();
		} else {
			$this->load->view( 'website/store/theme/calisto/download' );
		}
    }
	
	function request() {
		preg_match('/\/([0-9]+)/i', $_SERVER['REQUEST_URI'], $match);
		$file_id = (!empty($match[1])) ? $match[1] : 0;
		if (empty($file_id)) {
			exit;
		}
		
		$this->User_model->login_user_required();
		$user = $this->User_model->get_session();
		
		// make sure this user have buy this file
		$file = $this->File_model->get_by_id(array( 'id' => $file_id ));
		$user_item = $this->User_Item_model->get_array(array( 'item_id' => $file['item_id'] ));
		if (count($user_item) == 0) {
			echo 'Please login / buy this item';
			exit;
		}
		
		// get file info
		$path_file = $this->config->item('base_path').'/../files';
		$path_file = realpath($path_file).'/'.$file['file_name'];
		
		// force download
		header('Content-Disposition: attachment; filename=' . basename($path_file));
		readfile($path_file);
		exit;
	}
	
	/*
	function ajax() {
		// store
		$store_name = get_store();
		$store = $this->Store_Detail_model->get_info(array('store_name' => $store_name));
		
		// action
		$action = (!empty($_POST['action'])) ? $_POST['action'] : '';
		unset($_POST['action']);
		
		if ($action == 'GetByID') {
			$result = $this->Address_model->get_by_id(array('id' => $_POST['id']));
		}
		
		echo json_encode($result);
	}
	/*	*/
}