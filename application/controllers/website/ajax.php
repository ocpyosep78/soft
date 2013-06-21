<?php

class ajax extends CI_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
		$_GET['limit'] = 25;
		
		preg_match('/ajax\/([a-z0-9]+)/i', $_SERVER['REQUEST_URI'], $match);
		$method = (empty($match[1])) ? '' : $match[1];
		$this->$method();
    }
	
	function user() {
		$action = (empty($_POST['action'])) ? '' : $_POST['action'];
		
		$result = array('status' => false, 'message' => '');
		if ($action == 'register') {
			$user = $this->User_model->get_by_id(array('email' => $_POST['email']));
			if (count($user) > 0) {
				$result['message'] = 'Email sudah terdaftar dalam database kami, mohon melakukan login atau reset password.';
			} else {
				$_POST['passwd'] = EncriptPassword($_POST['passwd']);
				$result = $this->User_model->update($_POST);
			}
		} else if ($action == 'login') {
			$passwd = EncriptPassword($_POST['passwd']);
			$user = $this->User_model->get_by_id(array('email' => $_POST['email']));
			
			$result = array('status' => false, 'message' => 'User dan Password anda tidak ada yang sama dalam data kami.');
			if (count($user) > 0) {
				if ($user['passwd'] == $passwd) {
					$result['status'] = true;
					$result['message'] = '';
					
					unset($user['passwd']);
					$this->User_model->set_session($user);
				}
			}
		} else if ($action == 'ResetPassword') {
			$result['message'] = 'Akan dilanjutkan';
		}
		
		echo json_encode($result);
	}
	
	function view() {
		$action = (empty($_POST['action'])) ? '' : $_POST['action'];
		if (empty($action)) {
			exit;
		}
		
		$this->load->view( 'website/store/theme/calisto/'.$action );
	}
	
	function logout() {
		$this->User_model->delete_session();
		header("Location: ".site_url());
		exit;
	}
}