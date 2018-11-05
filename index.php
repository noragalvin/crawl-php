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
		

		// $html = str_replace($weburl,HTTP_SERVER, $html);	
		// $isMobile = (bool)preg_match('#\b(ip(hone|od|ad)|android|opera m(ob|in)i|windows (phone|ce)|blackberry|tablet'.
        //     '|s(ymbian|eries60|amsung)|p(laybook|alm|rofile/midp|laystation portable)|nokia|fennec|htc[\-_]'.
        //     '|mobile|up\.browser|[1-4][0-9]{2}x[1-4][0-9]{2})\b#i', $_SERVER['HTTP_USER_AGENT'] );
		// if($isMobile) {
		//    $html = str_replace('<script type="text/javascript" src="http://media.bilutv.com/js/main.js"></script>', '<script type="text/javascript" src="http://media.bilutv.com/js/main.js"></script><link rel="stylesheet" type="text/css" href="http://hotfilm.site/css/mobile.css" />', $html);
		//    $html = str_replace('<div id="header">', '<div id="header" style="display:none;">', $html);
		//    $html = str_replace('<div id="main-menu" class="desktop">', '<div id="header"><div class="btn-humber"><i class="fa fa-bars"></i></div><a class="logo" href="/" title="Trang chủ"><img data-cfsrc="http://ginmovies.me/logo.png" alt="ginmovies.me" style="display:none;visibility:hidden;" /><noscript><img src="http://ginmovies.me/logo.png" alt="ginmovies.me" /></noscript></a><i class="fa fa-search btn-search" onclick="$(\'.mobile-search-bar\').removeClass(\'hide\');$(\'#keyword\').focus();"></i><div class="mobile-search-bar hide"><form action="/tim-kiem.html" method="GET" id="form_search"><input name="q" id="keyword" autocomplete="off"  type="text" placeholder="Nhập tên phim, diễn viên,..." /><div style="display: block;" id="suggestions" class="msuggestions top-search-box"></div></form><i class="fa fa-search mobile-search-submit" onclick="$(\'#form_search_mobile\').submit()"></i><i class="fa fa-times close-button" onclick="$(\'.mobile-search-bar\').addClass(\'hide\')"></i></div></div><div id="bswrapper_inhead"></div><div id="main-menu">', $html);
		//    $html = preg_replace_callback("#<div id=\"main-menu\"(.*?)>([^`]*?)<\/div>#", function(){	
		// 		return '<div id="main-menu"><div class="container"><ul><li class="parent-menu"><a href="#" title="Thể loại"><i class="fa fa-clone"></i><span>Thể loại</span><i class="fa fa-expand fa-angle-down"></i></a><ul class="sub-menu"><li class="sub-menu-item"><a title="Cổ Trang - Thần Thoại" href="/the-loai/co-trang-than-thoai-6.html">Cổ Trang - Thần Thoại</a></li><li class="sub-menu-item"><a title="Võ Thuật - Kiếm Hiệp" href="/the-loai/vo-thuat-kiem-hiep-4.html">Võ Thuật - Kiếm Hiệp</a></li><li class="sub-menu-item"><a title="Phiêu lưu -  Hành động" href="/the-loai/phieu-luu-hanh-dong-1.html">Phiêu lưu -  Hành động</a></li><li class="sub-menu-item"><a title="Tâm Lý - Tình Cảm" href="/the-loai/tam-ly-tinh-cam-51.html">Tâm Lý - Tình Cảm</a></li><li class="sub-menu-item"><a title="Phim Hoạt Hình" href="/the-loai/phim-hoat-hinh-55.html">Phim Hoạt Hình</a></li><li class="sub-menu-item"><a title="Khoa Học - Viễn Tưởng" href="/the-loai/khoa-hoc-vien-tuong-2.html">Khoa Học - Viễn Tưởng</a></li><li class="sub-menu-item"><a title="Hình Sự - Chiến Tranh" href="/the-loai/hinh-su-chien-tranh-7.html">Hình Sự - Chiến Tranh</a></li><li class="sub-menu-item"><a title="Tài Liệu - Khám Phá" href="/the-loai/tai-lieu-kham-pha-3.html">Tài Liệu - Khám Phá</a></li><li class="sub-menu-item"><a title="Văn Hóa - Tâm Linh" href="/the-loai/van-hoa-tam-linh-53.html">Văn Hóa - Tâm Linh</a></li><li class="sub-menu-item"><a title="Hài Hước" href="/the-loai/hai-huoc-52.html">Hài Hước</a></li><li class="sub-menu-item"><a title="Thể Thao - Âm Nhạc" href="/the-loai/the-thao-am-nhac-54.html">Thể Thao - Âm Nhạc</a></li><li class="sub-menu-item"><a title="Kinh Dị - Ma" href="/the-loai/kinh-di-ma-5.html">Kinh Dị - Ma</a></li><li class="sub-menu-item"><a title="Gia Đình - Học Đường" href="/the-loai/gia-dinh-hoc-duong-58.html">Gia Đình - Học Đường</a></li><li class="sub-menu-item"><a title="TV Show" href="/the-loai/tv-show-57.html">TV Show</a></li><li class="sub-menu-item"><a title="Chiếu Rạp" href="/the-loai/chieu-rap-56.html">Chiếu Rạp</a></li><li class="sub-menu-item"><a title="Xuyên Không" href="/the-loai/xuyen-khong-61.html">Xuyên Không</a></li><li class="sub-menu-item"><a title="Phim Thuyết Minh" href="/the-loai/phim-thuyet-minh-59.html">Phim Thuyết Minh</a></li><li class="sub-menu-item"><a title="Phim Lồng Tiếng" href="/the-loai/phim-long-tieng-60.html">Phim Lồng Tiếng</a></li><li class="sub-menu-item"><a title="Trinh Thám" href="/the-loai/trinh-tham-62.html">Trinh Thám</a></li></ul></li><li class="parent-menu"><a href="javascript:void(0)" title="Quốc gia"><i class="fa fa-globe"></i><span>Quốc gia</span><i class="fa fa-expand fa-angle-down"></i></a><ul class="sub-menu absolute"><li class="sub-menu-item"><a title="Việt Nam" href="/quoc-gia/viet-nam-7.html">Việt Nam</a></li><li class="sub-menu-item"><a title="Trung Quốc" href="/quoc-gia/trung-quoc-2.html">Trung Quốc</a></li><li class="sub-menu-item"><a title="Hàn Quốc" href="/quoc-gia/han-quoc-5.html">Hàn Quốc</a></li><li class="sub-menu-item"><a title="Thái Lan" href="/quoc-gia/thai-lan-6.html">Thái Lan</a></li><li class="sub-menu-item"><a title="Hồng Kông" href="/quoc-gia/hong-kong-10.html">Hồng Kông</a></li><li class="sub-menu-item"><a title="Âu - Mỹ" href="/quoc-gia/au-my-3.html">Âu - Mỹ</a></li><li class="sub-menu-item"><a title="Đài Loan" href="/quoc-gia/dai-loan-1.html">Đài Loan</a></li><li class="sub-menu-item"><a title="Nhật Bản" href="/quoc-gia/nhat-ban-8.html">Nhật Bản</a></li><li class="sub-menu-item"><a title="Ấn Độ" href="/quoc-gia/an-do-11.html">Ấn Độ</a></li><li class="sub-menu-item"><a title="Philippines" href="/quoc-gia/philippines-12.html">Philippines</a></li><li class="sub-menu-item"><a title="Quốc gia khác" href="/quoc-gia/quoc-gia-khac-9.html">Quốc gia khác</a></li></ul></li><li class="parent-menu"><a href="javascript:void(0)" title="Phim lẻ"><i class="fa fa-film"></i><span>Phim lẻ</span><i class="fa fa-expand fa-angle-down"></i></a><ul class="sub-menu absolute"><li class="sub-menu-item"><a title="Phim lẻ Việt Nam" href="/danh-sach/phim-le-viet-nam-7.html">Phim lẻ Việt Nam</a></li><li class="sub-menu-item"><a title="Phim lẻ Trung Quốc" href="/danh-sach/phim-le-trung-quoc-2.html">Phim lẻ Trung Quốc</a></li><li class="sub-menu-item"><a title="Phim lẻ Hàn Quốc" href="/danh-sach/phim-le-han-quoc-5.html">Phim lẻ Hàn Quốc</a></li><li class="sub-menu-item"><a title="Phim lẻ Thái Lan" href="/danh-sach/phim-le-thai-lan-6.html">Phim lẻ Thái Lan</a></li><li class="sub-menu-item"><a title="Phim lẻ Hồng Kông" href="/danh-sach/phim-le-hong-kong-10.html">Phim lẻ Hồng Kông</a></li><li class="sub-menu-item"><a title="Phim lẻ Âu - Mỹ" href="/danh-sach/phim-le-au-my-3.html">Phim lẻ Âu - Mỹ</a></li><li class="sub-menu-item"><a title="Phim lẻ Đài Loan" href="/danh-sach/phim-le-dai-loan-1.html">Phim lẻ Đài Loan</a></li><li class="sub-menu-item"><a title="Phim lẻ Nhật Bản" href="/danh-sach/phim-le-nhat-ban-8.html">Phim lẻ Nhật Bản</a></li><li class="sub-menu-item"><a title="Phim lẻ Ấn Độ" href="/danh-sach/phim-le-an-do-11.html">Phim lẻ Ấn Độ</a></li><li class="sub-menu-item"><a title="Phim lẻ Philippines" href="/danh-sach/phim-le-philippines-12.html">Phim lẻ Philippines</a></li><li class="sub-menu-item"><a title="Phim lẻ Quốc gia khác" href="/danh-sach/phim-le-quoc-gia-khac-9.html">Phim lẻ Quốc gia khác</a></li></ul></li><li class="parent-menu"><a href="javascript:void(0)" title="Phim bộ"><i class="fa fa-th-list"></i><span>Phim bộ</span><i class="fa fa-expand fa-angle-down"></i></a><ul class="sub-menu absolute"><li class="sub-menu-item"><a title="Phim bộ Việt Nam" href="/danh-sach/phim-bo-viet-nam-7.html">Phim bộ Việt Nam</a></li><li class="sub-menu-item"><a title="Phim bộ Trung Quốc" href="/danh-sach/phim-bo-trung-quoc-2.html">Phim bộ Trung Quốc</a></li><li class="sub-menu-item"><a title="Phim bộ Hàn Quốc" href="/danh-sach/phim-bo-han-quoc-5.html">Phim bộ Hàn Quốc</a></li><li class="sub-menu-item"><a title="Phim bộ Thái Lan" href="/danh-sach/phim-bo-thai-lan-6.html">Phim bộ Thái Lan</a></li><li class="sub-menu-item"><a title="Phim bộ Hồng Kông" href="/danh-sach/phim-bo-hong-kong-10.html">Phim bộ Hồng Kông</a></li><li class="sub-menu-item"><a title="Phim bộ Âu - Mỹ" href="/danh-sach/phim-bo-au-my-3.html">Phim bộ Âu - Mỹ</a></li><li class="sub-menu-item"><a title="Phim bộ Đài Loan" href="/danh-sach/phim-bo-dai-loan-1.html">Phim bộ Đài Loan</a></li><li class="sub-menu-item"><a title="Phim bộ Nhật Bản" href="/danh-sach/phim-bo-nhat-ban-8.html">Phim bộ Nhật Bản</a></li><li class="sub-menu-item"><a title="Phim bộ Ấn Độ" href="/danh-sach/phim-bo-an-do-11.html">Phim bộ Ấn Độ</a></li><li class="sub-menu-item"><a title="Phim bộ Philippines" href="/danh-sach/phim-bo-philippines-12.html">Phim bộ Philippines</a></li><li class="sub-menu-item"><a title="Phim bộ Quốc gia khác" href="/danh-sach/phim-bo-quoc-gia-khac-9.html">Phim bộ Quốc gia khác</a></li></ul></li><li><a href="/the-loai/hoat-hinh-55.html" title="Phim hoạt hình"><i class="fa fa-bolt"></i><span>Phim hoạt hình</span></a></li><li><a href="/phim-xem-nhieu" title=""><i class="fa fa-eye"></i><span>Phim xem nhiều</span></a></li><li><a href="/phim-chieu-rap" title=""><i class="fa fa-tasks"></i><span>Phim chiếu rạp</span></a></li></ul></div></div><script>var $menu = $("#main-menu");var $over_lay = $(\'#overlay_menu\');var hw = $(window).height();function set_height_menu(){var w_scroll_top = $(window).scrollTop();if(w_scroll_top >= 50){pos_top_menu = 0;}else{pos_top_menu = 50-w_scroll_top;}$menu.css(\'top\',pos_top_menu+\'px\');$("#overlay_menu").css(\'top\',pos_top_menu+\'px\');}function open_menu(){$menu.height(hw);$menu.addClass(\'expanded\');set_height_menu();$("#overlay_menu").removeClass(\'hide\');$(\'body,html\').addClass(\'overlow-hidden\');$(".btn-humber").addClass(\'active\');}function close_menu(){$menu.removeClass(\'expanded\');var w_scroll_top = $(window).scrollTop();if(w_scroll_top >= 50){pos_top_menu = 0;}else{pos_top_menu = w_scroll_top;}set_height_menu();$("#overlay_menu").addClass(\'hide\');$(\'body,html\').removeClass(\'overlow-hidden\');$(".btn-humber").removeClass(\'active\');}$(document).ready(function(){$(".btn-humber").click(function(){if($menu.hasClass(\'expanded\')){close_menu();}else{open_menu();}});$(window).scroll(function(){set_height_menu();});$(".parent-menu").click(function(){$this = $(this);$arrow = $this.find(\'.fa-expand\');if($arrow.hasClass(\'fa-angle-down\')){$arrow.removeClass(\'fa-angle-down\').addClass(\'fa-angle-up\');}else{$arrow.addClass(\'fa-angle-down\').removeClass(\'fa-angle-up\');}$this.find(\'.sub-menu\').toggle();});});</script>';
		// 	}, $html);
		//    $html = str_replace('<div class="right-content">', '<div class="right-content" style="display:none;">', $html);
		//    $html = str_replace('<div class="social">', '<div class="social" style="display:none;">', $html);
		//    $html = str_replace('<div class="views-row views-row-2">', '<div class="views-row views-row-2" style="display:none;">', $html);
		//    $html = str_replace('<div class="views-row views-row-3">', '<div class="views-row views-row-3" style="display:none;">', $html);
		//    $html = str_replace('<div class="views-row views-row-4">', '<div class="views-row views-row-4" style="display:none;">', $html);
		//    $html = str_replace('<div class="views-row views-row-5">', '<div class="views-row views-row-5" style="display:none;">', $html);
		//    $html = str_replace('<div class="block-film" id="film-trailer" >', '<div class="block-film" id="film-trailer" style="display:none;">', $html);
		//    $html = str_replace('<div class="tags">', '<div class="tags" style="display:none;">', $html);
		//    $html = str_replace('<div class="control-box">', '<div class="control-box" style="display:none;">', $html);
		//    $html = str_replace('<div class="text-center">', '<div class="text-center" style="display:none;">', $html);
		// }	
		// if(preg_match('#xml#',$url) || preg_match('#/rss/#',$url) ||  preg_match('#xsl#',$url)){
		// 	header('Content-Type: text/xml');
		// 	$html = str_replace($weburl,HTTP_SERVER, $html);
		// 	$html = str_replace('bilutv.com',"ginmovies.me",$html);
		// 	$html = str_replace('Bilutv sitemap',"GinMovies Sitemap",$html);
		// 	$html = str_replace('BILUTV GROUP',"GinMovies Group",$html);
		// 	$html = str_replace('Bilutv Group',"GinMovies Group",$html);
		// 	return $html;
		// }

		// $html = preg_replace_callback('/<script[^>]*src="(.*?)"><\/script>/',function($m){
		// 	if(strpos($m[1],'adsx_v4') !== false){
		// 		return '<script type="text/javascript" src="'.HTTP_SERVER.'/js/gibberish-aes-1.0.0.js"></script>';
		// 	}
		// 	if(strpos($m[1],'hadarone') !== false || strpos($m[1],'genieesspv') !== false || strpos($m[1],'genieessp.com') !== false || strpos($m[1],'media1.admicro.vn') !== false || strpos($m[1],'bilutv_balloon.js') !== false || strpos($m[1],'static.gammaplatform.com') !== false || strpos($m[1],'campaigns-assets-vp.daumcdn.net') !== false || strpos($m[1],'blueserving.com') !== false || strpos($m[1],'ads.exdynsrv.com') !== false || strpos($m[1],'ad-exchange.js') !== false){
		// 		return '';
		// 	}else{
		// 		return $m[0];
		// 	}
		// }, $html);
		// if($isMobile) {}
		// else{
		// 	if($url){
		// 		$html = preg_replace_callback("#<div id=\"bswrapper_inhead\"(.*?)>([^`]*?)<\/div>#", function(){	
		// 			return '<script type="text/javascript">if(!window.BB_ind) { BB_ind = 0; }if(!window.BB_r) { BB_r = Math.floor(Math.random()*1000000000)}BB_ind++;window.BB_skin = {centerWidth: 1000,centerDomId: \'\',leftOffset: 0,rightOffset: 0,topPos: 0,deferLoading: false,fixed: true,fixedStickTopOnScroll: false,fixedScrollSecondaryTop: 0,adjustSkinOnDynamicCenter: true,zIndex: 0,leftFrameId: \'\',rightFrameId: \'\',pl: 44773,index: BB_ind};</script><script type="text/javascript" data-cfasync="false" async src="//st.bebi.com/bebi_v3.js"></script><div style="margin-bottom: 5px; text-align: center; border: 1px dotted #FFF;"><center><script type="text/javascript">if(!window.BB_a) { BB_a = [];} if(!window.BB_ind) { BB_ind = 0; } if(!window.BB_r) { BB_r = Math.floor(Math.random()*1000000000)} BB_ind++; BB_a.push({ "pl" : 44772, "index": BB_ind});</script><script type="text/javascript" src="//st.bebi.com/bebi_v3.js"></script></center></div>';	
		// 		}, $html);

		// 		$html = preg_replace_callback("#<div class=\"ads-under-player\"(.*?)>([^`]*?)<\/div>#", function(){	
		// 			return '<div style="margin-bottom: 5px; text-align: center; border: 1px dotted #FFF;"><center><script type="text/javascript">if(!window.BB_a) { BB_a = [];} if(!window.BB_ind) { BB_ind = 0; } if(!window.BB_vrsa) { BB_vrsa = "v3"; }if(!window.BB_r) { BB_r = Math.floor(Math.random()*1000000000)} BB_ind++; BB_a.push({ "pl" : 44772, "index": BB_ind});</script><script type="text/javascript">document.write("<scr"+"ipt async data-cfasync="false" id="BB_SLOT_"+BB_r+"_"+BB_ind+"" src="//st.bebi.com/bebi_"+BB_vrsa+".js"></scr"+"ipt>");</script></center></div>';	
		// 		}, $html);

		// 		$html = preg_replace_callback("#<div class=\"most-view block\">#", function(){	
		// 			return '<div style="margin-bottom: 5px; text-align: center; border: 1px dotted #FFF;"><script type="text/javascript">if(!window.BB_a) { BB_a = [];} if(!window.BB_ind) { BB_ind = 0; } if(!window.BB_r) { BB_r = Math.floor(Math.random()*1000000000)} BB_ind++; BB_a.push({ "pl" : 44769, "index": BB_ind});</script><script type="text/javascript" src="//st.bebi.com/bebi_v3.js"></script><script type="text/javascript">if(!window.BB_a) { BB_a = [];} if(!window.BB_ind) { BB_ind = 0; } if(!window.BB_r) { BB_r = Math.floor(Math.random()*1000000000)} BB_ind++; BB_a.push({ "pl" : 44770, "index": BB_ind});</script><script type="text/javascript" src="//st.bebi.com/bebi_v3.js"></script><script type="text/javascript">if(!window.BB_a) { BB_a = [];} if(!window.BB_ind) { BB_ind = 0; } if(!window.BB_r) { BB_r = Math.floor(Math.random()*1000000000)} BB_ind++; BB_a.push({ "pl" : 44771, "index": BB_ind});</script><script type="text/javascript" src="//st.bebi.com/bebi_v3.js"></script></div><div class="most-view block">';	
		// 		}, $html);
		// 	}
		// }

		// $html = preg_replace_callback('#<script type="text/javascript" src="(.*?)bplayer.js(.*?)"></script>#', function(){return '<script type="text/javascript" src="'.HTTP_SERVER.'/js/bplayer.js"></script>';}, $html);
		$header = file_get_contents('views/header.php');
		$html = str_replace('</head>',$header.'</head>',$html);
		$footer = file_get_contents('views/footer.php');
		$html = str_replace('</body>',$footer.'</body>',$html);
	}
	
	return $html;
}

require_once('system/response.php');