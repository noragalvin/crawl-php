<?php
require_once('system/start.php');

$webdomain = 'readmanhua.net';
$weburl = 'https://' . $webdomain;
$url = $weburl . $act;

function progess($html)
{ 
	global $url,$webdomain,$weburl,$db,$md5,$curl;
	if($html){
		$current_url = $_SERVER['REQUEST_URI'];
		if(strpos($current_url, "manga-list") > -1){
			//Manga list
			// $html = str_replace("filterList\",", "filterList\", crossDomain: true, dataType: 'jsonp',", $html);
			$html = str_replace("https://readmanhua.net/filterList", "ajax/filterList.php", $html);
			$html = str_replace("https://readmanhua.net/changeMangaList", "ajax/changeMangaList.php", $html);
			

		} else if(strpos($current_url, "latest-release") > -1) {
			//Latest

		} else {
			//Index
			
		}

		//All pages
		$html = str_replace("https://readmanhua.net/search", "ajax/search.php", $html);
		$html = str_replace("https://readmanhua.net/uploads/logo.png", "https://i.imgur.com/LqoUncp.png", $html);
		$html = str_replace(`<a href="https://readmanhua.net">Gin Manga</a>`, `<a href="`.HTTP_SERVER.`">Gin Manga</a>`, $html);
		$html = str_replace("Read Manhua", "Gin Manga", $html);
		$html = str_replace($weburl . '/manga', HTTP_SERVER . '/manga', $html);
		$html = str_replace($weburl . '/latest-release', HTTP_SERVER . '/latest-release', $html);
		$html = str_replace($weburl . '/privacy-policy', HTTP_SERVER . '/privacy-policy', $html);
		$html = str_replace("uploads/atr/kofi.jpg", "", $html);
		$html = str_replace("uploads/atr/rss.png", "", $html);
		$html = str_replace("uploads/atr/rmpatreon.jpg", "", $html);
		$html = str_replace("uploads/atr/twitter.png", "", $html);
		$html = str_replace("uploads/atr/rmdiscord.jpg", "", $html);
		$html = str_replace("uploads/atr/yt.png", "", $html);
		$html = str_replace("uploads/atr/fb.png", "", $html);
		$html = str_replace("https://readmanhua.net/fonts/fontawesome-webfont.ttf?v=4.2.0", "", $html);	
		

		//Ads

		//Top ads
		// $html = str_replace("<h2 class=\"hotmanga-header\">", "<h2><i class=\"fa fa-credit-card-alt\" aria-hidden=\"true\"></i>Advertisement</h2><hr><div style=\"width: 728px;height: 90px;\"></div><h2 class=\"hotmanga-header\">", $html);
		//Right ads
		// $html = str_replace("<div class=\"col-sm-4 col-sm-push-8\">", "<div class=\"col-sm-4 col-sm-push-8\"><h2>Advertisement</h2>", $html);
		//Left ads
		$html = str_replace("</body>", "<div style=\"width: 100%;
		height: 768px;
		position: fixed;
		left: 0;
		top: 60px;
		overflow: hidden;
		visibility: hidden;
		z-index: 0;\"><div style=\"visibility: visible;
		height: 768px;
		width: 336px;
		position: absolute;
		left: 50%;
		margin-left: -836px;
		top: 0;
		z-index: 0;\"></div></div></body>", $html);

		//Remove old Ads
		$html = str_replace("<script async src=\"\/\/pagead2.googlesyndication.com/pagead/js/adsbygoogle.js\"></script>", "", $html);

		$header = file_get_contents('views/header.php');
		$html = str_replace('</head>',$header.'</head>',$html);
		$footer = file_get_contents('views/footer.php');
		$html = str_replace('</body>',$footer.'</body>',$html);
	}
	
	return $html;
}

require_once('system/response.php');