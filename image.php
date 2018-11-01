<?php 
if(isset($_GET['image_src'])){
	switch( $file_extension ) {
	    case "gif": $ctype="image/gif"; break;
	    case "png": $ctype="image/png"; break;
	    case "jpeg":
	    case "jpg": $ctype="image/jpeg"; break;
	    default:
	}
	header('Content-type: ' . $ctype);
	$timeout = 10;
	$headers[] = 'Referer: http://bilutv.com/';
	$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36';
	$process = curl_init();
	if (isset($proxy)) {
	    $proxy = $proxy[array_rand($proxy)];
	}
	if (isset($proxy)) {
	    curl_setopt($process, CURLOPT_PROXY, $proxy);
	}
	curl_setopt($process, CURLOPT_URL, $_GET['image_src']);
	curl_setopt($process, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($process, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($process, CURLOPT_TIMEOUT, $timeout);
	curl_setopt($process, CURLOPT_HTTPGET, TRUE);
		curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($process, CURLOPT_BINARYTRANSFER,1);
	$result = curl_exec($process);
	curl_close($process);
	$filename = basename($_GET['image_src']);
	$file_extension = strtolower(substr(strrchr($filename,"."),1));

	echo $result;
}