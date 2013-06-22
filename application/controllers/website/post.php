<?php

class post extends CI_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
		$method_name = (isset($this->uri->segments[2])) ? $this->uri->segments[2] : '';
		if (!empty($method_name)) {
			$this->$method_name();
		} else {
			$this->load->view( 'website/post' );
		}
    }
	
	function confirm() {
		$this->load->view( 'website/post_confirm' );
	}
}