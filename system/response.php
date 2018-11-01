<?php
define("FolderCache", "caches");
if(strpos($url,'/xem-phim/') !== false){
	define("TimeCache", 3600);
}else{
	define("TimeCache", 10000);
}

$cache = new cache(FolderCache,TimeCache);
$detect = new Mobile_Detect();
if(isset($_REQUEST['refresh_cache_all'])){
	$cache->delAllCache($_REQUEST['refresh_cache_all']);	
}
if(isset($_REQUEST['refresh_cache'])){
	$url = str_replace("?refresh_cache=1","",$url);
	if($detect->isMobile()){
		$result = $cache->delCache($url.'mb');
	}else{
		$result = $cache->delCache($url);
	}
}

if($detect->isMobile()){
	$html = $cache->readCache($url.'mb');
}else{
	$html = $cache->readCache($url);
}

if(!$html){
	$html = progess($curl->get($url));
}
if($detect->isMobile()){
	$cache->saveCache($url.'mb',$html);
}else{
	$cache->saveCache($url,$html);
}

$html = progess(file_get_contents($url));

echo $html;
exit;
