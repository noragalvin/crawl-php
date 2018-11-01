<?php
	const domain = 'http://banhtv.com';
	function parseInfo($url, $encode = true) {
		$page = file_get_html($url);

		$e = (object) Array();
		$e->name = $page->find('div.film-info', 0)->find('div.image', 0)->find('div.text', 0)->find('h1', 0)->plaintext.' - '.$page->find('div.film-info', 0)->find('div.image', 0)->find('div.text', 0)->find('h2', 0)->plaintext;
		$e->status = '';
		$e->director = '';
		$e->actor = Array();
		$e->type = Array();
		$e->nation = Array();
		$e->release = '';
		foreach ($page->find('ul.entry-meta', 0)->find('li') as $element) {
			switch ($element->find('label', 0)->plaintext) {
				case 'Đang phát: ':
					$e->status = $element->find('span', 0)->plaintext;
					break;
				
				case 'Quốc gia: ':
					foreach ($element->find('a') as $key) {
						array_push($e->nation, $key->plaintext);
					}
					break;
				
				case 'Thể loại: ':
					foreach ($element->find('a') as $key) {
						array_push($e->type, $key->plaintext);
					}
					break;

				case 'Đạo diễn: ':
					$e->director = $element->find('a', 0)->plaintext;
					break;
				
				case 'Diễn viên: ':
					foreach ($element->find('a') as $key) {
						array_push($e->actor, $key->plaintext);
					}
					break;
			}
		}
		$e->tags = Array();
		array_push($e->tags, $e->name);
		for ($i = 0; $i < count($e->nation); $i++) array_push($e->tags, $e->nation[$i]);
		for ($i = 0; $i < count($e->actor); $i++) array_push($e->tags, $e->actor[$i]);
		for ($i = 0; $i < count($e->type); $i++) array_push($e->tags, $e->type[$i]);
		$e->image = $page->find('img.poster', 0)->getAttribute('data-cfsrc');
		$link_btn = constant('domain').$page->find('ul.list-button', 0)->find('li', 0)->find('a', 0)->getAttribute('href');
		$str = cURL($link_btn);
		$frl = str_get_html($str);
		$e->episode = Array();

		if (strpos($str, '<div class="list-server" id="list-server">') < 1) {
			$epsID = (object) Array(); 
			$epsID->name = 'VIP';
			$epsID->list = Array();
			foreach ($frl->find('ul.episodes', 0)->find('li') as $element) {
				$tepSD = (object) Array();
				$tepSD->name = trim($element->find('a', 0)->plaintext);
				$tepSD->link = constant('domain').$element->find('a', 0)->getAttribute('href');
				array_push($epsID->list, $tepSD);
			}
			array_push($e->episode, $epsID);
		} else {
			$epsID = (object) Array();
			$epsID->name = 'HD';
			$epsID->list = Array();
			$tepSD = (object) Array();
			$tepSD->name = 'FULL';
			$tepSD->link = $link_btn;
			array_push($epsID->list, $tepSD);
			array_push($e->episode, $epsID);
		}

		$e->info = '';
		foreach ($page->find('div.film-content', 0)->find('p') as $element) {
			if ($element->getAttribute('class') != 'heading')
				$e->info .= $element->plaintext;
		}
		$e->info = explode(' ', preg_replace("/\r\n|\r|\n/", '', $e->info));
		$s = Array();
		for ($i = 0; $i < count($e->info); $i++) {
			if ($e->info[$i] != '') array_push($s, $e->info[$i]);  
		}
		$e->info = implode(' ', $s);

		if ($encode) $e = json_encode($e, JSON_PRETTY_PRINT);
		return $e;
	}
?>