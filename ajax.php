<?php 
require_once 'system/library/cache.php';
require_once 'system/library/Mobile_Detect.php';
if(isset($_REQUEST['refresh_cache']) && isset($_REQUEST['url'])){
	define("FolderCache", "caches");
	define("TimeCache", 60000);	
	$url = str_replace("videohot.co", "bilutv.com",$_REQUEST['url']);
	$cache = new cache(FolderCache,TimeCache);
	$detect = new Mobile_Detect();
	if($detect->isMobile()){
		$result = $cache->delCache($url.'mb');
	}else{
		$result = $cache->delCache($url);
	}	
	die;
}