<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| AUTO-LOADER
| -------------------------------------------------------------------
| This file specifies which systems should be loaded by default.
|
| In order to keep the framework as light-weight as possible only the
| absolute minimal resources are loaded by default. For example,
| the database is not connected to automatically since no assumption
| is made regarding whether you intend to use it.  This file lets
| you globally define which systems you would like loaded with every
| request.
|
| -------------------------------------------------------------------
| Instructions
| -------------------------------------------------------------------
|
| These are the things you can load automatically:
|
| 1. Packages
| 2. Libraries
| 3. Helper files
| 4. Custom config files
| 5. Language files
| 6. Models
|
*/

/*
| -------------------------------------------------------------------
|  Auto-load Packges
| -------------------------------------------------------------------
| Prototype:
|
|  $autoload['packages'] = array(APPPATH.'third_party', '/usr/local/shared');
|
*/

$autoload['packages'] = array();


/*
| -------------------------------------------------------------------
|  Auto-load Libraries
| -------------------------------------------------------------------
| These are the classes located in the system/libraries folder
| or in your application/libraries folder.
|
| Prototype:
|
|	$autoload['libraries'] = array('database', 'session', 'xmlrpc');
*/

$autoload['libraries'] = array('database', 'session', 'api', 'paypal');


/*
| -------------------------------------------------------------------
|  Auto-load Helper Files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['helper'] = array('url', 'file');
*/

$autoload['helper'] = array('date', 'common', 'recaptcha', 'excel_reader', 'url', 'resize');


/*
| -------------------------------------------------------------------
|  Auto-load Config files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['config'] = array('config1', 'config2');
|
| NOTE: This item is intended for use ONLY if you have created custom
| config files.  Otherwise, leave it blank.
|
*/

$autoload['config'] = array();


/*
| -------------------------------------------------------------------
|  Auto-load Language files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['language'] = array('lang1', 'lang2');
|
| NOTE: Do not include the "_lang" part of your file.  For example
| "codeigniter_lang.php" would be referenced as array('codeigniter');
|
*/

$autoload['language'] = array();


/*
| -------------------------------------------------------------------
|  Auto-load Models
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['model'] = array('model1', 'model2');
|
*/

$autoload['model'] = array(
	'Item_model', 'Item_Status_model','User_model', 'Category_model', 'Platform_model', 'Default_Value_model', 'User_Item_model'
	/*
	'Item_model','Item_Catalog_model','Item_Category_model', 'Catalog_model', 'Ajax_model', 'Store_Detail_model', 'Blog_model', 'Category_model', 'Store_model','Theme_model', 'Item_Price_model','Picture_model',
	'Currency_model', 'Theme_model', 'Item_Picture_model', 'Cart_model', 'City_model', 'Country_model', 'Province_model', 'Nota_model', 'Transaction_model', 'User_model', 'Address_model', 'Newsletter_model',
	'Store_Image_Slide_model', 'Item_Status_model', 'Blog_Status_model', 'User_Store_model', 'Payment_Method_model', 'Store_Payment_Method_model', 'Bank_model','Bank_Account_model', 'Status_Nota_model','Shipment_model',
	'Default_Value_model','File_model','Item_File_model', 'User_Item_model', 'User_Payment_model'
	/*	*/
);


/* End of file autoload.php */
/* Location: ./application/config/autoload.php */