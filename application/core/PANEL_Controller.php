<?php

class PANEL_Controller extends CI_Controller {
    function __construct() {
        parent::__construct();
		$this->User_model->login_user_store_required();
    }
}