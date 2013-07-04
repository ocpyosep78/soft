<?php
	// force download do not support buffer
	$need_buffer = true;
	preg_match('/download/i', $_SERVER['REQUEST_URI'], $match);
	if (count($match) > 0) {
		$need_buffer = false;
	}
	if ($need_buffer) {
		ob_start();
	}
	
    //error_reporting(E_ALL);
    class item extends CI_Controller {
        function __construct() {
            
            parent::__construct();
        }
        
        function index() {
            $method_name = (isset($this->uri->segments[2])) ? $this->uri->segments[2] : '';
            if (method_exists($this, $method_name)) {
                $this->$method_name();
			} else {
                $this->load->view( 'website/item' );
            }
        }
        
        function buy() {
            $this->load->view( 'website/item_buy' );
        }
        
        function invoice() {
            $this->load->view( 'website/item_invoice' );
        }
        
        function download() {
            preg_match('/([\d]+)\/([\d]+)/i', $_SERVER['REQUEST_URI'], $match);
            $item_id = (!empty($match[1])) ? $match[1] : 0;
            $file_no = (!empty($match[2])) ? $match[2] : 0;
            
            // data
            $user = $this->User_model->get_session();
            $item = $this->Item_model->get_by_id(array( 'id' => $item_id ));
            
            // make sure this user have buy this file
            $is_buy = $this->User_Item_model->is_buy(array( 'item_id' => $item_id, 'user_id' => $user['id'] ));
            if (! $is_buy) {
                echo 'Please login / buy this item';
                exit;
            }
            
            // get file info
            $path_file = $this->config->item('base_path').'/../files';
            $path_file = realpath($path_file).'/'.$item['array_filename'][$file_no];
			
            // force download
            header('Content-Disposition: attachment; filename=' . basename($path_file));
            readfile($path_file);
            exit;
        }
        
        function payment() {
            // action
            $action = (!empty($_POST['action'])) ? $_POST['action'] : '';
            unset($_POST['action']);
            
            // make sure, buyer have email, force add user by email
            $is_login = $this->User_model->is_login();
            if (!$is_login) {
                $temp = $this->User_model->get_by_id(array( 'email' => $_POST['email']));
                if (count($temp) == 0) {
                    $temp = $this->User_model->update(array( 'email' => $_POST['email']));
                }
                
                $user = $this->User_model->get_by_id(array( 'id' => $temp['id'] ));
                $this->User_model->set_session($user);
            }
            
            // filter payment method
            // paypal
            $payment_type = (!empty($_POST['payment'])) ? $_POST['payment'] : '';
            if ($payment_type == 'paypal') 
            {
                $_POST['action'] = 'SetPaypalPayment';
            }
            elseif ($payment_type == 'ipaymupay') 
            {
                $_POST['action'] = 'SetIpaymuPayment';
            }
            
            // item
            $item = $this->Item_model->get_by_id(array( 'id' => $_POST['id'] ));
            
            // process
            $result = $this->$payment_type(array( 'item_id' => $item['id'], 'price' => $item['price'] ));
            
            echo json_encode($result);
        }
		
		function ipaymu2() 
		{
			$email = empty($_POST['email'])?'':$_POST['email'];
			$item_id = empty($_POST['item_id'])?'':$_POST['item_id'];
			$item_price = empty($_POST['item_price'])?'':$_POST['item_price'];
			$item_add = empty($_POST['item_add'])?'':$_POST['item_add'];
			$checkout_id = empty($_POST['checkout_id'])?'':$_POST['checkout_id'];
			
            $user = $this->User_model->get_session();
			if ($user && !$email)
				$email = $user['email'];
				
			$item = $this->Item_model->get_by_id(array( 'id' => $item_id ));
			
            $url = 'https://my.ipaymu.com/payment.htm';
            $params = array(
				'key'      => 'T0JZ9psRD8JW9EUCXIVvFUuUQC.fw1', // API Key Merchant / Penjual
				'action'   => 'payment',
				'product'  => $item['name'],
				'price'    => $item_price ? $item_price : $item['price'], // Total Harga
				'quantity' => 1,
				'comments' => 'Pembayaran untuk LintasApps.com', // Optional
				'ureturn'  => base_url('item/thanks?tipe=ipaymu&email='.rawurlencode($email).'&id='.$checkout_id),
				'unotify'  => base_url('item/ipaymu_notif?id='.$checkout_id),
				'ucancel'  => base_url('item/buy/'.$item['id']),
				'format'   => 'json',
            );
            $params_string = http_build_query($params);
			
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			$request = curl_exec($ch);
			
			$logFile = str_replace('\\', '/', FCPATH) . '/application/logs/ipaymu_log.txt'; // Make sure this file exists and is writable
			$timestamp = date('Y-m-d H:i:s');
			file_put_contents( $logFile,
				"--- Begin ipaymu request $url $timestamp --\n".
				"Request: $params_string\n".
				"Response: $request\n".
				"-- End ipaymu request --\n\n",
				FILE_APPEND);
			
            if ( $request === false ) {
                echo 'Error: ' . curl_error($ch);
				curl_close($ch);
				echo '<script type="text/javascript">setTimeout(function() {location.href="'.base_url('item/buy/'.$item['id']).'"}, 1000*2)</script>';
				exit;
            } else {
				curl_close($ch);
                $result = json_decode($request, true);
                if( isset($result['url']) ) {
					header("Location: {$result['url']}");
					exit;
                } else {
                    $error_message="Request Error ". $result['Status'] .": ". $result['Keterangan'];
					echo "Error: $result[Keterangan] ($result[Status])";
					echo '<script type="text/javascript">setTimeout(function() {location.href="'.base_url('item/buy/'.$item['id']).'"}, 1000*2)</script>';
					exit;
                }
            }
		}
		
        function ipaymupay($param = array())
        {
            // $this->load->library('ipaymu');
            $action = (!empty($_POST['action'])) ? $_POST['action'] : '';
            unset($_POST['action']);
            // user
            $user = $this->User_model->get_session();
            $result = array();
            $param_ipaymu = array();
            if ($action == 'SetIpaymuPayment') {
                // total price
                $konversi_rupiah = $this->Default_Value_model->get_konversi_rupiah_dolar();
                $ipaymu_currency = number_format($param['price'] / $konversi_rupiah['value'], 2, '.', '');
                $this->ipaymu->set_session(array('item_id' => $param['item_id'], 'price' => $param['price'] ));
                $item = $this->Item_model->get_by_id(array( 'id' => $param['item_id']));
                $param_ipaymu['item_name'] = $item['name'];
                $param_ipaymu['item_price'] = $param['price'];
                $param_ipaymu['item_quantity'] = 1;
                $param_ipaymu['item_comment'] = 'transaksi dari lintasapps';
                // call ipaymu library
                $result = $this->ipaymu->ipaymuPay($param_ipaymu);
            }else {
                $result['message'] = 'transaksi Anda gagal';
            }
            return $result;
        }
        
        function ipaymu_notif()
        {
			$logFile = str_replace('\\', '/', FCPATH) . '/application/logs/ipaymu_log.txt';
			
			$ip = !empty($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['REMOTE_ADDR'];
			$timestamp = date('Y-m-d H:i:s');
			file_put_contents( $logFile,
				"--- Begin ipaymu notif $timestamp --\n".
				"URL: $_SERVER[REQUEST_URI]\n".
				"QUERY: $_SERVER[QUERY_STRING]\n".
				"IP: $ip\n".
				"POST: ".var_export($_POST, TRUE)."\n".
				"-- End ipaymu notif --\n\n",
				FILE_APPEND);
            
            $status = (!empty($_POST['status']))?$_POST['status']:''; // Status transaksi: 'berhasil' atau 'pending'
            $trx_id = (!empty($_POST['trx_id']))?$_POST['trx_id']:'';
            $sid = (!empty($_POST['sid']))?$_POST['sid']:'';
            $product = (!empty($_POST['product']))?$_POST['product']:'';
            $quantity = (!empty($_POST['quantity']))?$_POST['quantity']:'';
            $merchant = (!empty($_POST['merchant']))?$_POST['merchant']:'';
            $buyer = (!empty($_POST['buyer']))?$_POST['buyer']:'';
            $total = (!empty($_POST['total']))?$_POST['total']:'';
            $no_rekening_deposit = (!empty($_POST['no_rekening_deposit']))?$_POST['no_rekening_deposit']:'';
            $comments = (!empty($_POST['comments']))?$_POST['comments']:'';
            $referer = (!empty($_POST['referer']))?$_POST['referer']:'';
			
			$checkout_id = empty($_GET['id'])? 0 : $_GET['id'];
			$cdata = array();
			if ($checkout_id) {
				$r = mysql_query("SELECT * FROM checkout_data WHERE id = '$checkout_id'");
				if ($row = mysql_fetch_assoc($r)) {
					$cdata = json_decode( $row['data'], true );
				}
			}
			
			if ( $status == 'berhasil' ) {
				$cdata['status'] = 1;
				$cdata['ipaymu'] = 1;
				$cdata['ipaymu_id'] = $trx_id;
				$strdata = mysql_escape_string( json_encode($cdata) );
				mysql_query("UPDATE checkout_data SET data = '$strdata' WHERE id = '$checkout_id'");
			}
			
			mail("ferdhie@simetri.web.id", "IPAYMU: NOTIF", var_export($_POST, TRUE), "From: info@lintasapps.com");
			
            // insert into tabel
            $query = "INSERT INTO software.ipaymu (`status` ,`trx_id` ,`sid` ,`product` ,`quantity` ,`merchant` ,`buyer` ,`total` ,`no_rekening_deposit` ,`comments` ,`referer`)
					 VALUES ('$status', '$trx_id', '$sid', '$product', '$quantity', '$merchant', '$buyer', '$total', '$no_rekening_deposit', '$comments', '$referer')";
            $this->db->query($query);
			
            //$this->ipaymu->set_notify_session(array('status' => $status, 'trx_id' => $trx_id ));
        }
		
        function ipaymu_confirm()
        {
            // user
            $user = $this->User_model->get_session();
            $notify_payment = $this->ipaymu->get_notify_session();
            if($notify_payment['status'] == 'berhasil')
            {
                $ipaymu_session = $this->ipaymu->get_session();
                $item = $this->Item_model->get_by_id(array( 'id' => $ipaymu_session['item_id'] ));
                $invoice_no = $this->User_Item_model->get_max_no();
                
                // add invoice
                $param_update = array(
                'user_id' => $user['id'],
                'price' => $ipaymu_session['price'],
                'item_id' => $ipaymu_session['item_id'],
                'invoice_no' => $invoice_no,
                'payment_name' => 'paypal',
                'payment_date' => $this->config->item('current_datetime')
                );
                $this->User_Item_model->update($param_update);
                
                // sent mail
                $param_mail['to'] = $user['email'];
                $param_mail['subject']  = 'Invoice';
                $param_mail['message']  = '<h4>Terima kasih, berikut invoice anda:</h4>';
                $param_mail['message'] .= '<div>No : '.$invoice_no.'</div>';
                $param_mail['message'] .= '<div>Email : '.$user['email'].'</div>';
                $param_mail['message'] .= '<div>Item : '.$item['name'].' | '.$item['price_text'].'</div>';
                $param_mail['message'] .= '<div>Bayar melalui : paypal</div><br /><br />';
                $param_mail['message'] .= '<h4><a href="'.base_url('item/invoice/'.$invoice_no).'">Download</a></h4>';
                sent_mail($param_mail);
                
                $redirect_url = base_url('item/invoice/'.$invoice_no);
                header("Location: ".$redirect_url);
                exit;
            }
			echo 'GAGAL';
        }
		
		function doku_prepare() {
			$param['transidmerchant'] = $_POST['TRANSIDMERCHANT'];
			$param['words'] = $_POST['WORDS'];
			$param['trxstatus'] = 'Requested';
			
			// add login
			if (isset($_POST['email']))
				$this->User_model->force_login_buyer(array( 'email' => $_POST['email'] ));
			
			$transaction = $this->Doku_model->get_by_id(array( 'transidmerchant' => $param['transidmerchant'], 'trxstatus' => 'Requested' ));
			if (count($transaction) > 0) {
				$param['id'] = $transaction['id'];
			}
			
			$result = $this->Doku_model->update($param);
			echo json_encode($result);
		}
		
		function doku_redirect() {
			$this->doku->write_log();
			
			$param_get['transidmerchant'] = (isset($_POST['TRANSIDMERCHANT'])) ? $_POST['TRANSIDMERCHANT'] : '';
			$row = $this->Doku_model->get_by_id($param_get);
			
			// update record
			$result = array( 'status' => false );
			if ($_POST['STATUSCODE'] == '0000') {
				$param_update['id'] = $row['id'];
				$param_update['words'] = $_POST['WORDS'];
				$param_update['totalamount'] = $_POST['AMOUNT'];
				$param_update['payment_channel'] = $_POST['PAYMENTCHANNEL'];
				$param_update['paymentcode'] = $_POST['PAYMENTCODE'];
				$param_update['session_id'] = $_POST['SESSIONID'];
				$param_update['trxstatus'] = 'Done';
				$result = $this->Doku_model->update($param_update);
				
				// prepare data & invoice
				$checkout = $this->Checkout_Data_model->get_session();
				$invoice_no = $this->User_Item_model->get_max_no();
				$user = $this->User_model->get_session();
				
				// add invoice
				$param_update = array(
					'user_id' => @$user['id'],
					'price' => $param_update['totalamount'],
					'item_id' => $checkout['detail']->item_id,
					'invoice_no' => $invoice_no,
					'currency' => 'IDR',
					'payment_name' => 'doku',
					'payment_date' => $this->config->item('current_datetime')
				);
				$this->User_Item_model->update($param_update);
				$this->doku->set_session($param_update);
			} else {
				$param_update['id'] = $row['id'];
				$param_update['trxstatus'] = 'Failed';
				$result = $this->Doku_model->update($param_update);
				$result['message'] = 'Transaksi batal.';
			}
			
			$this->load->view( 'website/payment/doku_redirect' );
		}
		
		function doku_result() {
			$session = $this->doku->get_session($param_update);
			$invoice_link = base_url('item/invoice/'.$session['invoice_no']);
			
			header("Location: ".$invoice_link);
			exit;
		}

		function doku_notify() {
			$this->doku->write_log();
			
			$param_get['transidmerchant'] = (isset($_POST['TRANSIDMERCHANT'])) ? $_POST['TRANSIDMERCHANT'] : '';
			$param_get['trxstatus'] = 'Requested';
			$row = $this->Doku_model->get_by_id($param_get);
			
			// result from doku
			$result_message = (isset($_POST['RESULTMSG'])) ? $_POST['RESULTMSG'] : '';
			$result_message = strtoupper($result_message);
			
			// update row
			$result = array( 'status' => false, 'message' => '' );
			if (count($row) == 0) {
				$result['message'] = 'Record tidak ditemukan.';
			} else if ($result_message == 'SUCCESS') {
				$param_update['id'] = $row['id'];
				$param_update['words'] = $_POST['WORDS'];
				$param_update['trxstatus'] = $_POST['RESULTMSG'];
				$param_update['statustype'] = $_POST['STATUSTYPE'];
				$param_update['totalamount'] = $_POST['AMOUNT'];
				$param_update['response_code'] = $_POST['RESPONSECODE'];
				$param_update['approvalcode'] = $_POST['APPROVALCODE'];
				$param_update['payment_channel'] = $_POST['PAYMENTCHANNEL'];
				$param_update['paymentcode'] = $_POST['PAYMENTCODE'];
				$param_update['session_id'] = $_POST['SESSIONID'];
				$param_update['bank_issuer'] = $_POST['BANK'];
				$param_update['creditcard'] = $_POST['MCN'];
				$param_update['payment_date_time'] = $_POST['PAYMENTDATETIME'];
				$param_update['verifyid'] = $_POST['VERIFYID'];
				$param_update['verifyscore'] = $_POST['VERIFYSCORE'];
				$param_update['verifystatus'] = $_POST['VERIFYSTATUS'];
				$result = $this->Doku_model->update($param_update);
				
				// prepare data & invoice
				$checkout = $this->Checkout_Data_model->get_session();
				$invoice_no = $this->User_Item_model->get_max_no();
				$user = $this->get_session();
				
				// add invoice
				$param_update = array(
					'user_id' => $user['id'],
					'price' => $param_update['totalamount'],
					'item_id' => $checkout['detail']->item_id,
					'invoice_no' => $invoice_no,
					'payment_name' => 'doku',
					'payment_date' => $this->config->item('current_datetime')
				);
				$this->User_Item_model->update($param_update);
			} else {
				$param_update['id'] = $row['id'];
				$param_update['trxstatus'] = 'Failed';
				$result = $this->Doku_model->update($param_update);
				$result['message'] = 'Transaksi batal.';
			}
			
			echo json_encode($result);
		}
		
		function thanks() {
			if (!empty($_SESSION['checkout_id']))
				unset($_SESSION['checkout_id']);
		
			$checkout_id = empty($_GET['id']) ? 0 : $_GET['id'];
			$email = empty($_GET['email']) ? '' : $_GET['email'];
			$tipe = empty($_GET['tipe']) ? '' : $_GET['tipe'];
			$user = $this->User_model->get_session();
			
			if (!$checkout_id) {
				header("Location: " . base_url());
				exit;
			}
			
			$user_id = 0;
			
			if ($user) {
				$user_id = $user['id'];
				if (!$email)
					$email = $user['email'];
			}
			
			$cdata = array();
			$r = mysql_query("SELECT * FROM checkout_data WHERE id = '$checkout_id'");
			if ($row = mysql_fetch_assoc($r)) {
				$cdata = json_decode( $row['data'], true );
			}
			
			if ( !empty($cdata['status']) && $cdata['status'] == 1 ) {
				$price = $tipe == 'paypal' ? $cdata['paypal_dolar'] : $cdata['ipaymu_price'];
                $param_update = array(
					'user_id' => $user_id,
					'price' => $item['price'],
					'item_id' => $cdata['item_id'],
					'payment_name' => $tipe,
					'payment_date' => $this->config->item('current_datetime'),
					'currency' => $tipe == 'paypal' ? 'USD' : 'IDR',
					'konversi' => $tipe == 'paypal' ? $cdata['konversi'] : 0,
					'ref_id' => $tipe == 'paypal' ? $cdata['paypal_id'] : $cdata['ipaymu_id'],
					'terbayar' => $price,
                );
                $this->User_Item_model->update($param_update);
				$invoice_id = mysql_insert_id();
                $this->User_Item_model->update(array('id' => $invoice_id, 'invoice_no' => $invoice_id));
				$invoice_no = $invoice_id;
				
                // sent mail
				$pricestr = number_format($item['price'], 2, '.', ',');
				$tglstr = date('d-m-Y H:i:s', strtotime($param_update['payment_date']));
				mail($email, "Download Aplikasi Anda #$invoice_no | LintasApps.com", "Halo
				
Terima kasih atas pembelian anda di LintasApps.com
Berikut adalah resi pembelian anda:

Invoice #$invoice_no
Tanggal: $tglstr
Item: $item[name]
Harga: Rp$pricestr
Dibayar Melalui: $tipe
Harga Terbayar: ".($tipe == 'paypal' ? 'US $':'Rp').number_format($price, 2, '.', '')."

Download Item Anda
".base_url('item/invoice/'.$invoice_no)."

Terima kasih

--
LintasApps.com
", "From: noreply@lintasapps.com");

                $redirect_url = base_url('item/invoice/'.$invoice_no);
                header("Location: ".$redirect_url);
                exit;
			} else {
				$_SESSION['email'] = $email;
				if ( !empty($_GET['tmp']) ) {
					header("Location: ".base_url('item/'.$cdata['item_id']) . '?error=1');
				} else {
					header("Location: ".base_url('item/buy/'.$cdata['item_id']) . '?error=1');
				}
			}
		}
		
		function paypalnotify() {
			$raw_post_data = file_get_contents('php://input');
			
			if (!$raw_post_data) {
				die("BUKAN UNTUK ANDA!");
			}
			
			$raw_post_array = explode('&', $raw_post_data);
			$logFile = str_replace('\\', '/', FCPATH) . '/application/logs/paypal_log.txt'; // Make sure this file exists and is writable
			$stamp = date('H:i:s, j-m-y');
			$ip = !empty($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['REMOTE_ADDR'];
			
			if ($file = fopen($logFile, 'ab+')) {
				fputs($file, "--- Submitted @ ". $stamp . " & Array contents: --- \r\n");
				fputs($file, var_export($raw_post_array, true) . "\r\n");
				fputs($file,"--- IP ADDRESS of sender ---: ". $ip ." \r\n");
				fputs($file,"--- End of array contents --- \r\n");
				fclose($file);
			}
				
			$myPost = array();
			foreach ($raw_post_array as $keyval) {
				$keyval = explode ('=', $keyval);
				if (count($keyval) == 2)
					$myPost[$keyval[0]] = urldecode($keyval[1]);
			}
			$_req = 'cmd=_notify-validate';
			foreach ($myPost as $key => $value) {
				$value = urlencode(stripslashes($value));
				$_req .= "&$key=$value";
			}
			 
			$item_name = $_POST['item_name'];
			$item_number = $_POST['item_number'];
			$payment_status = $_POST['payment_status'];
			$payment_amount = $_POST['mc_gross'];
			$payment_currency = $_POST['mc_currency'];
			$txn_id = $_POST['txn_id'];
			$receiver_email = $_POST['receiver_email'];
			$payer_email = $_POST['payer_email'];
			$user_email = empty($_POST['custom']) ? '' : $_POST['custom'];

			// We need to post it back to PayPal system to validate
			$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
			//$header .= "Host: www.sandbox.paypal.com:443\r\n";
			$header .= "Content-type: text/html; charset=utf-8\r\n";
			$header .= "Content-Length: " . strlen($_req) . "\r\n\r\n";
			$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);
			if (!$fp) {
				mail("ferdhie@simetri.web.id", "IPN: HTTP ERROR while confirming IPN", "Failed open socket to PayPal server", "From: info@lintasapps.com");
			} else { //Connection sucessful
				fputs ($fp, $header . $_req);
				while (!feof($fp)) {
					$res = fgets($fp, 1024);
					if (strcmp ($res, "VERIFIED") == 0) {
					   // check the payment_status is Completed
					   // check that txn_id has not been previously processed
					   // check that receiver_email is your Primary PayPal email
					   // check that payment_amount/payment_currency are correct
					   // process payment
						mail("ferdhie@simetri.web.id", "IPN: VERIFIED", $_req, "From: info@lintasapps.com");
						
						$checkout_id = empty($_GET['id'])? 0 : $_GET['id'];
						$cdata = array();
						if ($checkout_id) {
							$r = mysql_query("SELECT * FROM checkout_data WHERE id = '$checkout_id'");
							if ($row = mysql_fetch_assoc($r)) {
								$cdata = json_decode( $row['data'], true );
							}
						}
						
						if ( $payment_status == 'Completed' && $receiver_email == 'info@simetri.web.id' && $payment_currency == 'USD' && $cdata && $payment_amount == $cdata['paypal_dolar'] ) {
							$cdata['status'] = 1;
							$cdata['paypal'] = 1;
							$cdata['paypal_id'] = $txn_id;
							$strdata = mysql_escape_string( json_encode($cdata) );
							mysql_query("UPDATE checkout_data SET data = '$strdata' WHERE id = '$checkout_id'");
						}
						
					} else if (strcmp ($res, "INVALID") == 0) {
						mail("ferdhie@simetri.web.id", "IPN: INVALID", $_req, "From: info@lintasapps.com");
					}
				}
				fclose ($fp);
			}
		}
		
        function paypal($param = array()) {
            // init payer
            if (isset($_GET['PayerID']) && !empty($_GET['PayerID'])) {
                $_POST['action'] = 'ExecutePaypalPayment';
            }
            
            $action = (!empty($_POST['action'])) ? $_POST['action'] : '';
            unset($_POST['action']);
            
            // user
            $user = $this->User_model->get_session();
            
            $result = array( 'status' => false, 'message' => '' );
            if ($action == 'SetPaypalPayment') {
                // total price
                $konversi_rupiah = $this->Default_Value_model->get_konversi_rupiah_dolar();
                $paypal_currency = number_format($param['price'] / $konversi_rupiah['value'], 2, '.', '');
                
                // get access token
                $token_param = array(
				'url' => PAYPAL_HOST.'/v1/oauth2/token',
				'data' => 'grant_type=client_credentials',
				'client_id' => PAYPAL_CLIENT_ID,
				'client_secret' => PAYPAL_CLIENT_SECRET
                );
                $token = $this->paypal->get_access_token($token_param);
                if (empty($token->access_token)) {
                    $result['status'] = false;
                    $result['message'] = 'Token fail.';
                    echo json_encode($result);
                    exit;
                }
                
                // set payment
                $payment_param = array(
				'token' => $token->access_token,
				'url' => PAYPAL_HOST.'/v1/payments/payment',
				'data' => array(
                'intent' => 'sale',
                'payer' => array( 'payment_method' => 'paypal' ),
                'transactions' => array(
                array(
                'amount' => array( 'total' => $paypal_currency, 'currency' => 'USD' ),
                'description' => 'payment using a PayPal account'
                )
                ),
                'redirect_urls' => array(
                'return_url' => base_url('item/paypal'),
                'cancel_url' => base_url()
                )
				)
                );
                $payment = $this->paypal->make_post_call($payment_param);
                $this->paypal->set_session(array( 'link' => $payment['links'], 'item_id' => $param['item_id'], 'price' => $param['price'] ));
                
                // get paypal approve url
                $paypal_approve_url = '';
                foreach ($payment['links'] as $array) {
                    if ($array['rel'] == 'approval_url') {
                        $paypal_approve_url = $array['href'];
                        break;
                    }
                }
                
                // result
                $result['status'] = true;
                $result['link_next'] = $paypal_approve_url;
            }
            else if ($action == 'ExecutePaypalPayment') {
                $paypal_session = $this->paypal->get_session();
                $item = $this->Item_model->get_by_id(array( 'id' => $paypal_session['item_id'] ));
                
                // get access token
                $token_param = array(
				'url' => PAYPAL_HOST.'/v1/oauth2/token',
				'data' => 'grant_type=client_credentials',
				'client_id' => PAYPAL_CLIENT_ID,
				'client_secret' => PAYPAL_CLIENT_SECRET
                );
                $token = $this->paypal->get_access_token($token_param);
                
                // execute payment
                $payment_param = array(
				'url' => $this->paypal->get_execute_link(),
				'data' => array( 'payer_id' => $_GET['PayerID'] ),
				'token' => $token->access_token
                );
                $payment = $this->paypal->make_post_call($payment_param);
                
                $payment['state'] = 'approved';
                if ($payment['state'] == 'approved') {
                    $invoice_no = $this->User_Item_model->get_max_no();
                    
                    // add invoice
                    $param_update = array(
					'user_id' => $user['id'],
					'price' => $paypal_session['price'],
					'item_id' => $paypal_session['item_id'],
					'invoice_no' => $invoice_no,
					'payment_name' => 'paypal',
					'payment_date' => $this->config->item('current_datetime')
                    );
                    $this->User_Item_model->update($param_update);
                    
                    // sent mail
                    $param_mail['to'] = $user['email'];
                    $param_mail['subject']  = 'Invoice';
                    $param_mail['message']  = '<h4>Terima kasih, berikut invoice anda:</h4>';
                    $param_mail['message'] .= '<div>No : '.$invoice_no.'</div>';
                    $param_mail['message'] .= '<div>Email : '.$user['email'].'</div>';
                    $param_mail['message'] .= '<div>Item : '.$item['name'].' | '.$item['price_text'].'</div>';
                    $param_mail['message'] .= '<div>Bayar melalui : paypal</div><br /><br />';
                    $param_mail['message'] .= '<h4><a href="'.base_url('item/invoice/'.$invoice_no).'">Download</a></h4>';
                    sent_mail($param_mail);
                    
                    $redirect_url = base_url('item/invoice/'.$invoice_no);
                    header("Location: ".$redirect_url);
                    exit;
                }
            }
            
            return $result;
        }
    }

	if ($need_buffer) {
		ob_flush();
	}
