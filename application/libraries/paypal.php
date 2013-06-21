<?php
class paypal {
    function __construct() {
        $this->CI =& get_instance();
    }
	
	function get_access_token($param) {
		/*
			api.sandbox.paypal.com
			$param['client_id'] = 'AU0L0hAN7pMABeNL9E0EFb_wZx8SEAxoL1iXlt5FMsPJP_Oyb5WzABfnr07X';
			$param['client_secret'] = 'EHdp9hAuOnRUC603-FvyabES7kQ6Yv38MYRjHGQ4lUWO20qLlfUg4w3Hp0ks';
			
			api.paypal.com
			$param['client_id'] = 'AZa_1RBzHEfEo2uNx-ByHsGb3MSr47zrrpoSk_v65JMavPIu-z3q86teJNvp';
			$param['client_secret'] = 'EAvOaxBIxUXQiNjSHKkHW_onJG3b1Bmoakl8ISVptzxi_Lb_08I3LjBofNc7';
		/*	*/
		
		$curl = curl_init($param['url']); 
		curl_setopt($curl, CURLOPT_POST, true); 
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_USERPWD, $param['client_id'] . ":" . $param['client_secret']);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($curl, CURLOPT_POSTFIELDS, $param['data']);
		$response = curl_exec( $curl );
		if (empty($response)) {
			// some kind of an error happened
			die(curl_error($curl));
			curl_close($curl); // close cURL handler
		} else {
			$info = curl_getinfo($curl);
			curl_close($curl); // close cURL handler
			if ($info['http_code'] != 200 && $info['http_code'] != 201 ) {
				echo "Received error: " . $info['http_code']. "\n";
				echo "Raw response:".$response."\n";
				die();
			}
		}
		
		// Convert the result from JSON format to a PHP array 
		$jsonResponse = json_decode( $response );
		return $jsonResponse;
	}
	
	function make_post_call($param) {
		$curl = curl_init($param['url']); 
		
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array( 'Authorization: Bearer '.$param['token'], 'Accept: application/json', 'Content-Type: application/json' ));
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($param['data']));
		$response = curl_exec( $curl );
		
		if (empty($response)) {
			// some kind of an error happened
			die(curl_error($curl));
			curl_close($curl); // close cURL handler
		} else {
			$info = curl_getinfo($curl);
			curl_close($curl); // close cURL handler
			if ($info['http_code'] != 200 && $info['http_code'] != 201 ) {
				echo "Received error: " . $info['http_code']. "\n";
				echo "Raw response:".$response."\n";
				die();
			}
		}
		
		$jsonResponse = json_decode($response, TRUE);
		return $jsonResponse;
	}
	
	/*	Region Paypal Session */
	
	function set_link($link) {
		$_SESSION['paypal_link'] = $link;
	}
	
	function get_link() {
		$link = $_SESSION['paypal_link'];
		return $link;
	}
	
	/*	End Paypal Session */
	
/*
$host = 'https://api.sandbox.paypal.com';

$clientId = 'AYUV8RAlyLyqEOOksa07wAYqy0-K8x5ogqstjQie3VS3OFhys0xTgr66muSw';
$clientSecret = 'EEVNYRCCCNvCCN3JMlb7ZHHPvsV82eegU74ywIYV1LSJPvWec9HKoYXVcuxY';

$token = '';





// get token
$url = $host.'/v1/oauth2/token'; 
$postArgs = 'grant_type=client_credentials';
$token = get_access_token($url, $postArgs);

if (empty($_GET['PayerID'])) {
	// initiate payment with paypal
	$url = $host.'/v1/payments/payment';
	$payment = array (
		'intent' => 'sale',
		'payer' => array ( 'payment_method' => 'paypal' ),
		'transactions' => array (
			array (
				'amount' => array( 'total' => '1.01', 'currency' => 'USD' ),
				'description' => 'payment using a PayPal account'
			)
		),
		'redirect_urls' => array (
			'return_url' => 'http://localhost:8666/paypal/trunk/test_payment.php',
			'cancel_url' => 'http://localhost:8666/1/calisto/cancal_url'
		)
	);
	$json = json_encode($payment);
	$json_resp = make_post_call($url, $json);
	foreach ($json_resp['links'] as $link) {
		echo $link['rel'].' - '.$link['href'].'<br />';
	}
} else {
	$payment_execute_url = 'https://api.sandbox.paypal.com/v1/payments/payment/PAY-3AU87708VN2263940KGRNAMI/execute';
	$payment_execute = array( 'payer_id' => $_GET['PayerID'] );
	$json = json_encode($payment_execute);
	$json_resp = make_post_call($payment_execute_url, $json);
	
	if ($json_resp['state'] == 'approved') {
		echo 'Transaksi anda berhasil, terima kasih.';
	}
}

/*	*/
}
?>