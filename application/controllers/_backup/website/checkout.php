<?php

class checkout extends CI_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
		if ($this->uri->segments[2] == 'step') {
			$step = (isset($this->uri->segments[3])) ? $this->uri->segments[3] : 1;
			
			// hardcode for software
			$step = 2;
			$view = 'website/store/theme/calisto/checkout_step'.$step;
		} else if ($this->uri->segments[2] == 'complete') {
			$view = 'website/store/theme/calisto/checkout_complete';
		}
		
		$this->load->view( $view );
    }
}