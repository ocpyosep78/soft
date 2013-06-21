<?php

if (! class_exists('CURL')) {
	class CURL {
		var $callback = false;

		function setCallback($func_name) {
			$this->callback = $func_name;
		}

		function doRequest($method, $url, $vars, $referer_address) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_REFERER, $referer_address);
			curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
			curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
			curl_setopt($ch, CURLOPT_TIMEOUT, 20);
			if ($method == 'POST') {
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
			}
			$data = curl_exec($ch);
			curl_close($ch);
			if ($data) {
				if ($this->callback) {
					$callback = $this->callback;
					$this->callback = false;
					return call_user_func($callback, $data);
				} else {
					return $data;
				}
			} else {
				if
				(is_resource($ch))
					return curl_error($ch);
				else
					return false;
			}
		}

		function get($url, $referer_address = '') {
			return $this->doRequest('GET', $url, 'NULL', $referer_address);
		}

		function post($url, $vars, $referer_address = '') {
			return $this->doRequest('POST', $url, $vars, $referer_address);
		}
	}
}

class api {
    function __construct() {
        $this->CI =& get_instance();
		$this->Curl = new CURL();
    }
	
	function get_token($Param) {
		date_default_timezone_set('UTC');
		$time = time();
		$hash = substr(md5($time . 'INDOCRM' . $Param['indocrm_privatekey']), 5, 8);
		$token = $time . '-' . $Param['indocrm_client_id'] . '-' . $hash;
		
		return $token;
	}
	
	function request($url, $param) {
		// Generate Token
		$ApiKey = $this->CI->Company_model->GetByID(array('company_id' => $param['company_id']));
		if (empty($ApiKey['indocrm_client_id']) || empty($ApiKey['indocrm_privatekey'])) {
			return array('api_result' => 0);
		}
		$token = $this->get_token($ApiKey);
		
		// Add Token
		$param['t'] = $token;
		$ResultJson = $this->Curl->post($url, $param);
		
		// Sync Data
		$Result = json_decode($ResultJson);
		$Result->api_result = (isset($Result->success) && $Result->success) ? 1 : 0;
		
		if (isset($Result->customer_id) && !empty($Result->customer_id)) {
			$Result->indocrm_id = $Result->customer_id;
		}
		
		unset($Result->success);
		unset($Result->customer_id);
		$Result = (array)$Result;
		
		// Debug Command
		// echo $url; print_r($Result); print_r($param); exit;
		
		return $Result;
	}
}
?>