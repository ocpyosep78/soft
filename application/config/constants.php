<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    
    define('FILE_READ_MODE', 0644);
    define('FILE_WRITE_MODE', 0666);
    define('DIR_READ_MODE', 0755);
    define('DIR_WRITE_MODE', 0777);
    
    define('FOPEN_READ',							'rb');
    define('FOPEN_READ_WRITE',						'r+b');
    define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
    define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
    define('FOPEN_WRITE_CREATE',					'ab');
    define('FOPEN_READ_WRITE_CREATE',				'a+b');
    define('FOPEN_WRITE_CREATE_STRICT',				'xb');
    define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');
    
    define('SHA_SECRET',							'raHa5!4');
    define('ITEM_STATUS_PENDING',					'1');
    define('ITEM_STATUS_APPROVE',					'2');
    
    /*	PAYPAL  sandbox */
	
    //define('PAYPAL_HOST',							'https://api.sandbox.paypal.com');
    //define('PAYPAL_CLIENT_ID',						'AU0L0hAN7pMABeNL9E0EFb_wZx8SEAxoL1iXlt5FMsPJP_Oyb5WzABfnr07X');
    //define('PAYPAL_CLIENT_SECRET',					'EHdp9hAuOnRUC603-FvyabES7kQ6Yv38MYRjHGQ4lUWO20qLlfUg4w3Hp0ks');
    
    /*	PAYPAL  live */
    define('PAYPAL_HOST',							'https://api.paypal.com');
    define('PAYPAL_CLIENT_ID',						'AZa_1RBzHEfEo2uNx-ByHsGb3MSr47zrrpoSk_v65JMavPIu-z3q86teJNvp');
    define('PAYPAL_CLIENT_SECRET',					'EAvOaxBIxUXQiNjSHKkHW_onJG3b1Bmoakl8ISVptzxi_Lb_08I3LjBofNc7');
	/*	*/
	
	/*
    define('PAYPAL_HOST',							'https://api.paypal.com');
    define('PAYPAL_CLIENT_ID',						'AZa_1RBzHEfEo2uNx-ByHsGb3MSr47zrrpoSk_v65JMavPIu-z3q86teJNvp');
    define('PAYPAL_CLIENT_SECRET',					'EAvOaxBIxUXQiNjSHKkHW_onJG3b1Bmoakl8ISVptzxi_Lb_08I3LjBofNc7');
	/*	*/
    
	/* WITHDRAW */
	define('MINIMIN_RUPIAH',						1000000);
	define('MINIMIN_DOLLAR',						1500);
	
	/*	DOKU */
    /*
    define('DOKU_HOST',							'https://pay.doku.com/Suite/Receive');
	/*	*/
	define('DOKU_HOST',							'http://103.10.129.17/Suite/Receive');
	/* TIPE ACCOUNT */
    define('PAYPAL',							'1');
    define('BANK',							'2');
    /* USER */
    define('STATUS_USER_NEW',							'0');
    define('STATUS_USER_CONFIRM',							'1');
    define('STATUS_USER_BANNED',							'2');
    
    /*NOTA*/
    define('STATUS_NOTA_PENDING',							'1');
    define('STATUS_NOTA_CONFIRM',							'2');
    define('STATUS_NOTA_CANCEL',							'3');
    /*ITEM*/
    define('STATUS_ITEM_PENDING',							'1');
    define('STATUS_ITEM_APPROVE',							'2');
    
    define('CATEGORY',								'category');
    define('PAGES',								'pages');
    define('CHECKOUT_DATA',							'checkout_data');
    define('DEFAULT_VALUE',							'default_value');
    define('DOKU',									'doku');
    define('ITEM',									'item');
    define('ITEM_STATUS',									'item_status');
    define('PLATFORM',								'platform');
    define('USER',									'user');
    define('USER_ITEM',								'user_item');
    define('NOTA',									'nota');
    define('SALES_PERCENT',							'sales_percent');
    define('STATUS_NOTA',							'status_nota');
    define('TRANSACTION',							'transaction');
    define('WITHDRAW',								'user_withdraw');
    
    
    /* End of file constants.php */
/* Location: ./application/config/constants.php */