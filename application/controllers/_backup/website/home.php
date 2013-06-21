<?php

class home extends CI_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
		$store_name = get_store();
		
		if (empty($store_name)) {
			header('Location: '.base_url('lintasgps'));
			exit;
			
			$this->load->view( 'website/home' );
		} else {
			$this->load->view( 'website/store/theme/calisto/home' );
		}
    }
}