<?php

class item extends CI_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
		$this->load->view( 'website/store/theme/calisto/item' );
    }
}