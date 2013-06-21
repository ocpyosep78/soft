<?php

class autocomplete extends CI_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
		$_GET['limit'] = 25;
		
		preg_match('/autocomplete\/([a-z0-9]+)/i', $_SERVER['REQUEST_URI'], $match);
		$method = (empty($match[1])) ? '' : $match[1];
		$this->$method();
    }
	
	function city() {
		$param = array('filter' => '[{"type":"custom","field":"City.title LIKE \'%'.$_GET["q"].'%\'","value":""}]', 'limit' => $_GET['limit']);
		$array = $this->City_model->get_array($param);
		foreach ($array as $city) {
			echo $city['auto_complete']."\n";
		}
	}
}