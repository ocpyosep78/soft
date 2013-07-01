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
			if (isset($_POST['item_screenshot'])) {
				$_POST['screenshot'] = json_encode($_POST['item_screenshot']);
			}
			
			$_POST['user_id'] = empty($user['id'])?0:$user['id'];
            // Strip HTML and PHP tags from a string
            $_POST['description'] = strip_tags($_POST['description']);
			$result = $this->Item_model->update($_POST);
			if ($result['status']) {
			
				$result['link_next'] = base_url('post/confirm/'.$result['id']);
                $item = $this->Item_model->get_by_id(array('id'=>$result['id']));
				
				if ($user && $_POST['item_status_id'] == ITEM_STATUS_PENDING) {
					mail( $user['email'], 'Aplikasi Menunggu Persetujuan | LintasApps.com', "Hallo

Terima kasih telah mengupload aplikasi anda di LintasApps.com
Saat ini aplikasi anda menunggu persetujuan dari kami.
ID Aplikasi : $item[id]
Nama: $item[name]

Proses persetujuan paling lama adalah 7 hari kerja, jika tidak ada respon dalam jangka waktu 7 hari kerja, silahkan kontak kami di info@lintasapps.com atau 0341-406633.
Sertakan ID Aplikasi anda untuk mempercepat proses eskalasi.

Terima kasih,
--
LintasApps.com", "From: info@lintasapps.com" );
				}
				
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
			$user_name = $this->User_model->get_by_id(array('name' => $_POST['name']));
			$user_email = $this->User_model->get_by_id(array('email' => $_POST['email']));
			$next_url = empty($_POST['next_url']) ? base_url('thank') : $_POST['next_url'];
			
			if (count($user_name) > 0) {
				$result['message'] = 'Username sudah terdaftar dalam database kami, mohon menggunakan username yang lain.';
			} else if (count($user_email) > 0) {
				$result['message'] = 'Email sudah terdaftar dalam database kami, mohon melakukan login atau reset password.';
			} else {
				$passwd_raw = $_POST['passwd'];
				$_POST['passwd'] = EncriptPassword($_POST['passwd']);
				$result = $this->User_model->update($_POST);
				$result['status'] = true;
				$result['link_next'] = $next_url;
				
				// user
				$user_login = $this->User_model->get_by_id(array( 'id' => $result['id'] ));
				
				// force login
				unset($user_login['passwd']);
				$this->User_model->set_session($user_login);
				
				@mail($user_login['email'], "Selamat datang di LintasApps.com", "Hello,

Terima kasih telah mendaftar di LintasApps.com, berikut informasi akun anda:

Username: $user_login[name]
Password: $passwd_raw

Silahkan login dan mulai mendownload aplikasi di LintasApps.com dengan link dibawah:
https://www.lintasapps.com/login

Terima Kasih
--
LintasApps.com
", "From: info@lintasapps.com");
				
			}
		}
		else if ($action == 'login') {
			$passwd = EncriptPassword($_POST['passwd']);
			$user = $this->User_model->get_by_id(array('name' => $_POST['name']));
			$next_url = empty($_POST['next_url']) ? base_url() : $_POST['next_url'];
			
			$result = array('status' => false, 'message' => 'User dan Password anda tidak ada yang sama dalam data kami.');
			if (count($user) > 0) {
				if ($user['passwd'] == $passwd) {
					$result['status'] = true;
					$result['message'] = '';
					$result['link_next'] = $next_url;
					
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
			
			@mail($user['email'], "Permintaan Reset Password | LintasApps.com", "Hallo

Anda melakukan permintaan reset password akun anda di LintasApps.com. Silahkan klik link dibawah untuk me-reset password akun anda
".base_url('login/?reset='.$reset).".

Jika anda tidak merasa mengubah password di LintasApps.com, silahkan abaikan e-mail ini.

Terima kasih
--
LintasApps.com", "From: info@lintasapps.com");
			
			$result['message'] = 'Email untuk mereset password anda berhasil dikirim';
		}
		
		echo json_encode($result);
	}
	
	function mail() {
		$param['to'] = 'info@simetri.web.id';
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