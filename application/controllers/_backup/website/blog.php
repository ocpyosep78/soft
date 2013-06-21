<?php

class blog extends CI_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
		$check_name = (empty($this->uri->segments[2])) ? '' : $this->uri->segments[2];
		preg_match('/page_([0-9]+)$/i', $check_name, $match);
		
		// index / detail
		$view = 'index';
		if (isset($match[1]) && !empty($match[1])) {
			$view = 'index';
		} else if (!empty($check_name)) {
			$view = 'detail';
		}
		
		if ($view == 'index') {
			$this->load->view( 'website/store/theme/calisto/blog' );
		} else {
			$this->load->view( 'website/store/theme/calisto/blog_single' );
		}
    }
}