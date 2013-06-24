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
    
    /*	PAYPAL */
    define('PAYPAL_HOST',							'https://api.sandbox.paypal.com');
    define('PAYPAL_CLIENT_ID',						'AU0L0hAN7pMABeNL9E0EFb_wZx8SEAxoL1iXlt5FMsPJP_Oyb5WzABfnr07X');
    define('PAYPAL_CLIENT_SECRET',					'EHdp9hAuOnRUC603-FvyabES7kQ6Yv38MYRjHGQ4lUWO20qLlfUg4w3Hp0ks');
    
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
    define('DEFAULT_VALUE',							'default_value');
    define('ITEM',									'item');
    define('ITEM_STATUS',									'item_status');
    define('PLATFORM',								'platform');
    define('USER',									'user');
    define('USER_ITEM',								'user_item');
    define('NOTA',								'nota');
    define('STATUS_NOTA',								'status_nota');
    define('TRANSACTION',								'transaction');
    
    
    /* End of file constants.php */
/* Location: ./application/config/constants.php */