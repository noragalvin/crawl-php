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
		$html = str_replace("https://readmanhua.net/uploads/logo.png", "https://i.imgur.com/mbvZlzu.png", $html);
		$html = str_replace(`<a href="https://readmanhua.net">Vip Manga</a>`, `<a href="`.HTTP_SERVER.`">Vip Manga</a>`, $html);
		$html = str_replace("Read Manhua", "Vip Manga", $html);
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
		$html = str_replace('<div class="alert alert-success">','<div class="alert alert-success" style="display:none;">',$html);
		$html = str_replace("google-auto-placed", "", $html);
		$html = str_replace("//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js", "", $html);

		

		//Ads
		$html = str_replace("<h2 class=\"hotmanga-header\">", "<h2><i class=\"fa fa-credit-card-alt\" aria-hidden=\"true\"></i> Advertisement</h2><hr><div style=\"width: 728px;height: 90px; overflow:hidden;max-width:100%;\"><script type=\"text/javascript\">if(!window.BB_a) { BB_a = [];} if(!window.BB_ind) { BB_ind = 0; } if(!window.BB_r) { BB_r = Math.floor(Math.random()*1000000000)} BB_ind++; BB_a.push({ \"pl\" : 1000056, \"index\": BB_ind});</script><script type=\"text/javascript\" src=\"//st.bebi.com/bebi_v3.js\"></script></div><h2 class=\"hotmanga-header\">", $html); // ads 728x90

		$html = str_replace('<div class="col-sm-4 col-sm-push-8">', '<div class="col-sm-4 col-sm-push-8"><h2>Advertisement</h2><div style="width:300px; height:750px;margin-bottom: 5px; text-align: center; border: 1px dotted #FFF;"><script type="text/javascript">if(!window.BB_a) { BB_a = [];} if(!window.BB_ind) { BB_ind = 0; } if(!window.BB_r) { BB_r = Math.floor(Math.random()*1000000000)} BB_ind++; BB_a.push({ "pl" : 1000053, "index": BB_ind});</script><script type="text/javascript" src="//st.bebi.com/bebi_v3.js"></script><script type="text/javascript">if(!window.BB_a) { BB_a = [];} if(!window.BB_ind) { BB_ind = 0; } if(!window.BB_r) { BB_r = Math.floor(Math.random()*1000000000)} BB_ind++; BB_a.push({ "pl" : 1000054, "index": BB_ind});</script><script type="text/javascript" src="//st.bebi.com/bebi_v3.js"></script><script type="text/javascript">if(!window.BB_a) { BB_a = [];} if(!window.BB_ind) { BB_ind = 0; } if(!window.BB_r) { BB_r = Math.floor(Math.random()*1000000000)} BB_ind++; BB_a.push({ "pl" : 1000055, "index": BB_ind});</script><script type="text/javascript" src="//st.bebi.com/bebi_v3.js"></script></div>', $html); // ads 300x250

		$html = str_replace("</body>", '<script type="text/javascript">if(!window.BB_ind) { BB_ind = 0; }if(!window.BB_r) { BB_r = Math.floor(Math.random()*1000000000)}BB_ind++;window.BB_skin = {centerWidth: 1000,centerDomId: \'\',leftOffset: 0,rightOffset: 0,topPos: 0,deferLoading: false,fixed: true,fixedStickTopOnScroll: false,fixedScrollSecondaryTop: 0,adjustSkinOnDynamicCenter: true,zIndex: 0,leftFrameId: \'\',rightFrameId: \'\',pl: 1000057,index: BB_ind};</script><script type="text/javascript" data-cfasync="false" async src="//st.bebi.com/bebi_v3.js"></script></body>', $html); // ads skin

		$html = str_replace('<div class="panel panel-default">', '<div class="panel panel-default" style="display:none;">', $html);

		$html = str_replace("<script async src=\"\/\/pagead2.googlesyndication.com/pagead/js/adsbygoogle.js\"></script>", "", $html);
		

		$header = file_get_contents('views/header.php');
		$html = str_replace('</head>',$header.'</head>',$html);
		$footer = file_get_contents('views/footer.php');
		$html = str_replace('</body>',$footer.'</body>',$html);
	}
	return $html;
}
require_once('system/response.php');