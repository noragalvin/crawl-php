<?php
	function cURL($url) {
		$c = curl_init();
		$opts = [
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_USERAGENT => '[FBAN/FB4A;FBAV/35.0.0.48.273;FBDM/{density=1.33125,width=800,height=1205};FBLC/en_US;FBCR/;FBPN/com.facebook.katana;FBDV/Nexus 7;FBSV/4.1.1;FBBK/0;]',
			CURLOPT_SSL_VERIFYPEER => false
		];
		curl_setopt_array($c, $opts);
		$d = curl_exec($c);
		curl_close($c);
		return $d;
	}
?>