<?php
	header('Content-Type: text/plain; charset=utf-8');
	const encode = true;
	extract($_GET);
	$s = file_get_contents('php://input');
	extract($s == false ? [] : json_decode($s, true));
	if (!(isset($url) && isset($site) && $url != '')) exit('No action');
	switch ($site) {
		case 'phim14':
			require_once('init/simple_html_dom.php');
			require_once('site/phim14.php');
			$return = parseInfo($url, constant('encode'));
			break;

		case 'phimmoi':
			require_once('init/simple_html_dom.php');
			require_once('init/cURL.php');
			require_once('site/phimmoi.php');
			$return = parseInfo($url, constant('encode'));
			break;

		case 'phimbathu':
			require_once('init/simple_html_dom.php');
			require_once('site/phimbathu.php');
			$return = parseInfo($url, constant('encode'));
			break;

		case 'banhtv':
			require_once('init/simple_html_dom.php');
			require_once('site/banhtv.php');
			$return = parseInfo($url, constant('encode'));
			break;

		case 'bilutv':
			require_once('init/simple_html_dom.php');
			require_once('init/cURL.php');
			require_once('site/bilutv.php');
			$return = parseInfo($url, constant('encode'));
			break;

		default:
			$return = 'No action';
			break;
	}
	print_r($return);
?>