<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$url = (isset($_SERVER['argv']) && isset($_SERVER['argv'][0])) ? $_SERVER['argv'][0] : '';
$url = preg_replace('/^\//i', '', $url);
$url = preg_replace('/\/$/i', '', $url);
$array_url = explode('/', $url);

if (! in_array($array_url[0], array('panel', 'cron'))) {
	$route['login'] = "website/login";
	$route['logout'] = "website/logout";
	$route['ajax/(:any)'] = "website/ajax";
	$route['post'] = "website/post";
	$route['post/(:any)'] = "website/post";
	$route['item/(:any)'] = "website/item";
	$route['browse'] = "website/browse";
	$route['browse/(:any)'] = "website/browse";
	$route['author'] = "website/author";
	$route['author/(:any)'] = "website/author";
	$route['history'] = "website/history";
	$route['history/(:any)'] = "website/history";
	$route['thank'] = "website/thank";
	$route['rekap'] = "website/rekap";
	$route['rekap/(:any)'] = "website/rekap";
	$route['hello'] = "website/hello";
	$route['hello/(:any)'] = "website/hello";
	$route['contact'] = "website/contact";
    $route['pages/(:any)'] = "website/pages";
}

$route['default_controller'] = "website/home";
$route['404_override'] = '';


/* End of file routes.php */
/* Location: ./application/config/routes.php */