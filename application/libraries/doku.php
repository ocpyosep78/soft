<?php
class doku {
	function __construct() {
		$this->CI =& get_instance();
	}
	
	function write_log() {
		$logFile = str_replace('\\', '/', FCPATH) . '/application/logs/doku_notify_log.txt';
		
		$ip = !empty($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['REMOTE_ADDR'];
		$timestamp = date('Y-m-d H:i:s');
		file_put_contents( $logFile,
			"--- Begin doku notif $timestamp --\n".
			"URL: $_SERVER[REQUEST_URI]\n".
			"QUERY: $_SERVER[QUERY_STRING]\n".
			"IP: $ip\n".
			"POST: ".var_export($_POST, TRUE)."\n".
			"-- End doku notif --\n\n",
			FILE_APPEND);
	}
	
	/*	Region Doku Session */
	
	function set_session($array) {
		foreach ($array as $key => $value) {
			$_SESSION['doku'][$key] = $value;
		}
	}
	
	function get_session() {
		$session = array();
		if (isset($_SESSION['doku'])) {
			$session = $_SESSION['doku'];
		}
		
		return $session;
	}
	
	/*	End Doku Session */
}