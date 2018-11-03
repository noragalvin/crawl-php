<?php
define("FolderCache", "caches");
if(strpos($url,'/manga/') !== false){
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

if(strpos($url, "ajax") > -1) {
	$url = str_replace("ajax/","",$url);
} 

if(strpos($url, ".php") > -1) {
	$url = str_replace(".php","",$url);
} 
// print_r($url);
// print_r(file_get_contents($url));
// die();
try{
	$html = progess(file_get_contents($url));
	echo $html;
}catch(Exception $e){
	echo "Press F5 to refresh page";
}

exit;
