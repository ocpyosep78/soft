<?php

class order extends CI_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
		$order_id = (empty($this->uri->segments[2])) ? '' : $this->uri->segments[2];
			
		if (empty($order_id)) {
			$this->load->view( 'website/store/theme/calisto/order' );
		} else {
			$this->load->view( 'website/store/theme/calisto/order_detail' );
		}
    }
}