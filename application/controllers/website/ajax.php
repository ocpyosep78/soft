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
	
	function item() {
		$action = (empty($_POST['action'])) ? '' : $_POST['action'];
		
		// user
		$user = $this->User_model->get_session();
		
		$result = array('status' => false, 'message' => '');
		if ($action == 'update') {
			if (empty($_POST['id'])) {
				$_POST['item_status_id'] = ITEM_STATUS_PENDING;
			}
			if (isset($_POST['item_file'])) {
				$_POST['filename'] = json_encode($_POST['item_file']);
			}
			
			$_POST['user_id'] = @$user['id'];
			$result = $this->Item_model->update($_POST);
			if ($result['status']) {
				$result['link_next'] = base_url('post/confirm/'.$result['id']);
			}
		} else if ($action == 'get_item') {
			$result = $this->Item_model->get_by_id(array( 'id' => $_POST['item_id'] ));
		}
		
		echo json_encode($result);
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
		}
		else if ($action == 'login') {
			$passwd = EncriptPassword($_POST['passwd']);
			$user = $this->User_model->get_by_id(array('name' => $_POST['name']));
			
			$result = array('status' => false, 'message' => 'User dan Password anda tidak ada yang sama dalam data kami.');
			if (count($user) > 0) {
				if ($user['passwd'] == $passwd) {
					$result['status'] = true;
					$result['message'] = '';
					$result['link_next'] = base_url();
					
					unset($user['passwd']);
					$this->User_model->set_session($user);
				}
			}
		}
		else if ($action == 'forgot') {
			$user = $this->User_model->get_by_id(array( 'name' => $_POST['name'] ));
			if (count($user) == 0) {
				$result['message'] = 'User anda tidak ditemukan';
				echo json_encode($result);
				exit;
			}
			
			$reset = EncriptPassword($user['id'] . time());
			$param_update['id'] = $user['id'];
			$param_update['reset'] = $reset;
			$this->User_model->update($param_update);
			
			$param['to'] = $user['email'];
			$param['subject']  = 'Request Reset Password';
			$param['message']  = 'Berikut link untuk mereset password lama anda, harap abakain email ini jika anda tidak ingin merubah password saat ini.<br />';
			$param['message'] .= base_url('login/?reset='.$reset);
			sent_mail($param);
			
			$result['message'] = 'Email untuk mereset password anda berhasil dikirim';
		}
		
		echo json_encode($result);
	}
	
	function mail() {
		$param['to'] = 'info@simetri.com';
		$param['subject'] = $_POST['subject'];
		$param['message'] = $_POST['description'];
		sent_mail($param);
		
		$result = array('status' => true, 'message' => 'Email berhasil dikirim.');
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