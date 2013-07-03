<?php
    class ipaymu {
        function __construct() {
            $this->CI =& get_instance();
        }
        
        // param ipaymuPay
        /*
            $param_ipaymu['item_name'] = $item['name'];
            $param_ipaymu['item_price'] = $param['price'];
            $param_ipaymu['item_quantity'] = 1;
            $param_ipaymu['item_comment'] = 'transaksi dari lintasapps';
        */
        function ipaymuPay($param=array())
        {
            
            $error_message = "";
            $result = array( 'status' => false, 'message' => '' );
            // URL Payment IPAYMU
            $url = 'https://my.ipaymu.com/payment.htm';
            
            // Prepare Parameters
            $params = array(
            'key'      => 'T0JZ9psRD8JW9EUCXIVvFUuUQC.fw1', // API Key Merchant / Penjual
            'action'   => 'payment',
            'product'  => $param['item_name'],
            'price'    => $param['item_price'], // Total Harga
            'quantity' => $param['item_quantity'],
            'comments' => $param['item_comment'], // Optional
            'ureturn'  => 'https://www.lintasapps.com/item/ipaymu_confirm',
            'unotify'  => 'https://www.lintasapps.com/item/ipaymu_notif',
            'ucancel'  => 'https://www.lintasapps.com',
            
            /* Parameter untuk pembayaran lain menggunakan PayPal
            * ----------------------------------------------- */
            // 'invoice_number' => uniqid('INV-'), // Optional
            // 'paypal_email'   => 'email_paypal_merchant',
            // 'paypal_price'   => 1, // Total harga dalam kurs USD
            /* ----------------------------------------------- */
            
            'format'   => 'json' // Format: xml / json. Default: xml
            );
            
            $params_string = http_build_query($params);
            
            //open connection
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, count($params));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            
            //execute post
            $request = curl_exec($ch);
            
            if ( $request === false ) {
                $error_message='Curl Error: ' . curl_error($ch);
                $result['status'] = false;
                $result['message'] = $error_message;
            } 
            else 
            {
                $result = json_decode($request, true);
                if( isset($result['url']) )
                {
                    $urllink = $result['url'];
                    $result['status'] = true;
                    $result['link_next'] = $urllink;
                    
                }else 
                {
                    $error_message="Request Error ". $result['Status'] .": ". $result['Keterangan'];
                    $result['status'] = false;
                    $result['message'] = $error_message;
                }
            }
            curl_close($ch);
            return $result;
        }
        
        
        /*	Region Ipaymu Session */
        
        function set_session($array) {
            foreach ($array as $key => $value) {
                $_SESSION['ipaymu'][$key] = $value;
            }
        }
        
        function get_session() {
            $session = array();
            if (isset($_SESSION['ipaymu'])) {
                $session = $_SESSION['ipaymu'];
            }
            return $session;
        }
        
        function set_notify_session($array) {
            foreach ($array as $key => $value) {
                $_SESSION['notify_ipaymu'][$key] = $value;
            }
        }
        
        function get_notify_session() {
            $session = array();
            if (isset($_SESSION['notify_ipaymu'])) {
                $session = $_SESSION['notify_ipaymu'];
            }
            return $session;
        }
    }                            