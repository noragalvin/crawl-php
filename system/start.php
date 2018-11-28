<?php
$config['folder']['project'] = substr(str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']),0,-1);
$timecheck = 30*24*60*60;
$dir_project  = dirname(dirname(__FILE__));
define('HTTP_SERVER',  'http://'.$_SERVER['HTTP_HOST'] .$config['folder']['project']);
define('DIR_SYSTEM',$dir_project . '/system');
define('DIR_LIBRARY',DIR_SYSTEM . '/library/');
define('DIR_CACHE',DIR_SYSTEM . '/cache/');
define('DIR_LOGS',DIR_SYSTEM . '/log/');
define('COOKIE_TIME',30*24*60*60);
define('DOMAIN', 'vipmanga.com');

foreach (glob(DIR_LIBRARY . '*.php') as $file) 
{
	require_once($file);
}

$curl = new cURL();
$requset = new Request();
$response = new Response();
$cache = new Cache();
$devices = new Devices();

$browse = $devices->getBrowserInformation();


$current_url = 'http://' . $requset->server['HTTP_HOST'] . $requset->server['REQUEST_URI'];
if($config['folder']['project'] != '/'){
	$act = str_replace($config['folder']['project'], '', $requset->server['REQUEST_URI']);
	
}else{
	$act =  $requset->server['REQUEST_URI'];
}
// print_r($act);
// die();