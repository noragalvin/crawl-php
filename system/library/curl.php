<?php
require_once 'Mobile_Detect.php';
class cURL {
var $headers;
var $user_agent;
var $compression;
var $cookie_file;
var $proxy;
	function __construct($cookies=TRUE,$cookie='cook.txt',$compression='gzip',$proxy='') {
		$this->headers['Accept'] = 'text/html, */*; q=0.01';
		$this->headers['Connection'] = 'Keep-Alive';
		//$this->headers['Content-type'] = 'application/x-www-form-urlencoded;charset=UTF-8';
		$this->headers['X-Requested-With'] = 'XMLHttpRequest';
		$this->headers['Cache-Control'] = 'max-age=0';
		$this->headers['Accept-Encoding'] = 'gzip, deflate';
		
		$this->user_agent = 'google';
		//$this->user_agent = $_SERVER['HTTP_USER_AGENT'];
		$this->compression=$compression;
		$this->proxy=$proxy;
		$this->cookies=$cookies;
		if ($this->cookies == TRUE) $this->cookie(DIR_LOGS . $cookie);
	}
	function cookie($cookie_file) {
		if (file_exists($cookie_file)) {
		$this->cookie_file=$cookie_file;
		} else {
		fopen($cookie_file,'w') or $this->error('The cookie file could not be opened. Make sure this directory has the correct permissions');
		$this->cookie_file=$cookie_file;
		fclose($this->cookie_file);
		}
	}
	function getheader($url) {
		$process = curl_init($url);
		curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
		curl_setopt($process, CURLOPT_HEADER, 1);
		curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent);
		if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file);
		if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file);
		curl_setopt($process,CURLOPT_ENCODING , $this->compression);
		curl_setopt($process, CURLOPT_TIMEOUT, 30);
		curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
		//curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($process,CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($process,CURLOPT_CAINFO, NULL);
		curl_setopt($process,CURLOPT_CAPATH, NULL);
		$return = curl_exec($process);
		curl_close($process);
		return $return;
	}
	function get($url) {
		if(strpos($url, '.flv')){
			return;
		}
		if (isset($proxy)) {  // If the $proxies array contains items, then
		    $proxy = $proxy[array_rand($proxy)];    // Select a random proxy from the array and assign to 
		}
		$process = curl_init($url);	
		if (isset($proxy)) {    // If the $proxy variable is set, then
		    curl_setopt($process, CURLOPT_PROXY, $proxy);    // Set CURLOPT_PROXY with proxy in $proxy variable
		}
		curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
		curl_setopt($process, CURLOPT_HEADER,FALSE);
		// curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent);
		$detect = new Mobile_Detect();
		if ( $detect->isMobile() ) {
			// curl_setopt($process, CURLOPT_USERAGENT, 'Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en) AppleWebKit/420.1 (KHTML, like Gecko) Version/3.0 Mobile/3B48b Safari/419.3');
			curl_setopt($process, CURLOPT_USERAGENT, 'Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1');
		}
		// if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file);
		// if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file);
		curl_setopt($process,CURLOPT_REFERER , 'http://phimbathu.com/');
		
		curl_setopt($process,CURLOPT_ENCODING , $this->compression);
		curl_setopt($process, CURLOPT_TIMEOUT, 30);
		curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($process,CURLOPT_SSL_VERIFYPEER,FALSE); 
		curl_setopt($process, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($process,CURLOPT_CAINFO, NULL); 
		curl_setopt($process,CURLOPT_CAPATH, NULL);
		// curl_setopt($process, CURLOPT_URL, $url);
		$return = curl_exec($process);
		curl_close($process);
		return $return;
	}
	function post($url,$data) {
		$process = curl_init($url);
		curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
		curl_setopt($process, CURLOPT_HEADER, 1);
		curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent);
		if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file);
		if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file);
		curl_setopt($process, CURLOPT_ENCODING , $this->compression);
		curl_setopt($process, CURLOPT_TIMEOUT, 30);
		curl_setopt($process, CURLOPT_POSTFIELDS, $data);
		curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($process, CURLOPT_POST, 1);
		curl_setopt($process,CURLOPT_SSL_VERIFYPEER, 0); 
		curl_setopt($process,CURLOPT_CAINFO, NULL); 
		curl_setopt($process,CURLOPT_CAPATH, NULL); 
		$return = curl_exec($process);
		curl_close($process);
		return $return;
	}
	function error($error) {
		echo "<center><div style='width:500px;border: 3px solid #FFEEFF; padding: 3px; background-color: #FFDDFF;font-family: verdana; font-size: 10px'><b>cURL Error</b><br>$error</div></center>";
		die;
	}
}

class cURL2 {
	var $headers;
	var $user_agent;
	var $compression;
	var $cookie_file;
	var $proxy;
	function __construct($cookies=TRUE,$cookie='cook.txt',$compression='gzip',$proxy='') {
		$this->headers[] = 'Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg';
		$this->headers[] = 'Connection: Keep-Alive';
		$this->headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8';
		//$this->user_agent = 'google';
		$this->user_agent = $_SERVER['HTTP_USER_AGENT'];
		$this->compression=$compression;
		$this->proxy=$proxy;
		$this->cookies=$cookies;
		if ($this->cookies == TRUE) $this->cookie(DIR_LOGS . $cookie);
	}
	function cookie($cookie_file) {
		if (file_exists($cookie_file)) {
			$this->cookie_file=$cookie_file;
		} else {
			fopen($cookie_file,'w') or $this->error('The cookie file could not be opened. Make sure this directory has the correct permissions');
			$this->cookie_file=$cookie_file;
			fclose($this->cookie_file);
		}
	}
	function getheader($url) {
		$process = curl_init($url);
		curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
		curl_setopt($process, CURLOPT_HEADER, 1);
		curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent);
		if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file);
		if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file);
		curl_setopt($process,CURLOPT_ENCODING , $this->compression);
		curl_setopt($process, CURLOPT_TIMEOUT, 30);
		curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
		//curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($process,CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($process,CURLOPT_CAINFO, NULL);
		curl_setopt($process,CURLOPT_CAPATH, NULL);
		$return = curl_exec($process);
		curl_close($process);
		return $return;
	}
	function get($url) {
		if(strpos($url, '.flv')){
			return;
		}
		//return file_get_contents($url);
		$process = curl_init($url);
		curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
		curl_setopt($process, CURLOPT_HEADER, 0);
		curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent);
		if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file);
		if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file);
		curl_setopt($process,CURLOPT_ENCODING , $this->compression);
		curl_setopt($process, CURLOPT_TIMEOUT, 30);
		curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
		//curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($process,CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($process,CURLOPT_CAINFO, NULL);
		curl_setopt($process,CURLOPT_CAPATH, NULL);
		$return = curl_exec($process);
		curl_close($process);
		return $return;
	}
	function post($url,$data) {
		$process = curl_init($url);
		curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
		curl_setopt($process, CURLOPT_HEADER, 1);
		curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent);
		if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file);
		if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file);
		curl_setopt($process, CURLOPT_ENCODING , $this->compression);
		curl_setopt($process, CURLOPT_TIMEOUT, 30);
		curl_setopt($process, CURLOPT_POSTFIELDS, $data);
		curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($process, CURLOPT_POST, 1);
		curl_setopt($process,CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($process,CURLOPT_CAINFO, NULL);
		curl_setopt($process,CURLOPT_CAPATH, NULL);
		$return = curl_exec($process);
		curl_close($process);
		return $return;
	}
	function error($error) {
		echo "<center><div style='width:500px;border: 3px solid #FFEEFF; padding: 3px; background-color: #FFDDFF;font-family: verdana; font-size: 10px'><b>cURL Error</b><br>$error</div></center>";
		die;
	}
}

class cURL3 {
	var $headers;
	var $user_agent;
	var $compression;
	var $cookie_file;
	var $proxy;
	function __construct($cookies=TRUE,$cookie='cook.txt',$compression='gzip',$proxy='') {
		$this->headers[] = 'Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg';
		$this->headers[] = 'Connection: Keep-Alive';
		$this->headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8';
		//$this->user_agent = 'google';
		$this->user_agent = 'Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_3_2 like Mac OS X; en-us) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8H7 Safari/6533.18.5';
		$this->compression=$compression;
		$this->proxy=$proxy;
		$this->cookies=$cookies;
		if ($this->cookies == TRUE) $this->cookie(DIR_LOGS . $cookie);
	}
	function cookie($cookie_file) {
		if (file_exists($cookie_file)) {
			$this->cookie_file=$cookie_file;
		} else {
			fopen($cookie_file,'w') or $this->error('The cookie file could not be opened. Make sure this directory has the correct permissions');
			$this->cookie_file=$cookie_file;
			fclose($this->cookie_file);
		}
	}
	function getheader($url) {
		$process = curl_init($url);
		curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
		curl_setopt($process, CURLOPT_HEADER, 1);
		curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent);
		if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file);
		if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file);
		curl_setopt($process,CURLOPT_ENCODING , $this->compression);
		curl_setopt($process, CURLOPT_TIMEOUT, 30);
		curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
		//curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($process,CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($process,CURLOPT_CAINFO, NULL);
		curl_setopt($process,CURLOPT_CAPATH, NULL);
		$return = curl_exec($process);
		curl_close($process);
		return $return;
	}
	function get($url) {
		if(strpos($url, '.flv')){
			return;
		}
		//return file_get_contents($url);
		$process = curl_init($url);
		curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
		curl_setopt($process, CURLOPT_HEADER, 0);
		curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent);
		if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file);
		if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file);
		curl_setopt($process,CURLOPT_ENCODING , $this->compression);
		curl_setopt($process, CURLOPT_TIMEOUT, 30);
		curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
		//curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($process,CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($process,CURLOPT_CAINFO, NULL);
		curl_setopt($process,CURLOPT_CAPATH, NULL);
		$return = curl_exec($process);
		curl_close($process);
		return $return;
	}
	function post($url,$data) {
		$process = curl_init($url);
		curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
		curl_setopt($process, CURLOPT_HEADER, 1);
		curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent);
		if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file);
		if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file);
		curl_setopt($process, CURLOPT_ENCODING , $this->compression);
		curl_setopt($process, CURLOPT_TIMEOUT, 30);
		curl_setopt($process, CURLOPT_POSTFIELDS, $data);
		curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($process, CURLOPT_POST, 1);
		curl_setopt($process,CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($process,CURLOPT_CAINFO, NULL);
		curl_setopt($process,CURLOPT_CAPATH, NULL);
		$return = curl_exec($process);
		curl_close($process);
		return $return;
	}
	function error($error) {
		echo "<center><div style='width:500px;border: 3px solid #FFEEFF; padding: 3px; background-color: #FFDDFF;font-family: verdana; font-size: 10px'><b>cURL Error</b><br>$error</div></center>";
		die;
	}
}
