<?php

class contact extends CI_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
		preg_match('/contact\/([a-z]+)$/i', $_SERVER['REQUEST_URI'], $match);
		if (isset($match[1]) && in_array($match[1], array('ajax'))) {
			$method = $match[1];
			$this->$method();
		} else {
			$this->load->view( 'website/store/theme/calisto/contact' );
		}
    }
	
	function ajax() {
		$action = (!empty($_POST['action'])) ? $_POST['action'] : '';
		
		$result = array( 'status' => false, 'message' => '' );
		if ($action == 'SendMessage') {
			$store_name = get_store();
			$store = $this->Store_model->get_by_id(array('name' => $store_name));
			
			// generate message
			$message = '';
			$message .= 'Name : '.$_POST['form_name']."\n";
			$message .= 'Email : '.$_POST['form_email']."\n";
			$message .= 'Topic : '.$_POST['form_topic']."\n";
			$message .= 'Message : '.$_POST['form_message']."\n\n";
			$message .= "--\n".$store['title'];
			
			$param = array(
				'email' => $store['user_email'],
				'header' => 'From: '.$store['title'].' <info@simetri.web.id>',
				'subject' => 'Contact Us - '.$store['title'],
				'message' => $message
			);
			sent_mail($param);
			
			$result['status'] = true;
			$result['message'] = 'Pesan anda berhasil terkirim.';
		}
		
		echo json_encode($result);
	}
}