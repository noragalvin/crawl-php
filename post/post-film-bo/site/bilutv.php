<?php
	const domain = 'http://bilutv.com';
	function parseInfo($url, $encode = true) {
		$page = file_get_html($url);

		$e = (object) Array();
		$e->name = $page->find('div.film-info', 0)->find('h1.name', 0)->plaintext;
		$e->name = explode(' ', preg_replace("/\r\n|\r|\n/", '', $e->name));
		$s = Array();
		for ($i = 0; $i < count($e->name); $i++) {
			if ($e->name[$i] != '') array_push($s, $e->name[$i]);  
		}
		$e->name = implode(' ', $s);
		$e->name = trim($e->name);
		$e->name .= ' - '.$page->find('div.film-info', 0)->find('h2.real-name', 0)->plaintext;
		$e->status = '';
		$e->director = '';
		$e->actor = Array();
		$e->type = Array();
		$e->nation = Array();
		$e->release = '';
		foreach ($page->find('ul.meta-data', 0)->find('li') as $element) {
			switch ($element->find('label', 0)->plaintext) {
				case 'Đang phát:':
					$e->status = $element->find('strong', 0)->plaintext;
					break;

				case 'Đạo diễn:':
					$e->director = $element->find('a', 0)->plaintext;
					break;

				case 'Diễn viên:':
					foreach ($element->find('a') as $key) {
						array_push($e->actor, $key->plaintext);
					}
					break;

				case 'Thể loại:':
					foreach ($element->find('a') as $key) {
						array_push($e->type, $key->plaintext);
					}
					break;

				case 'Quốc gia:':
					foreach ($element->find('a') as $key) {
						array_push($e->nation, $key->plaintext);
					}
					break;

				case 'Năm xuất bản:':
					$e->release = $element->find('span', 0)->plaintext;
					break;
			}
		}
		$e->tags = Array();
		array_push($e->tags, $e->name);
		for ($i = 0; $i < count($e->nation); $i++) array_push($e->tags, $e->nation[$i]);
		for ($i = 0; $i < count($e->actor); $i++) array_push($e->tags, $e->actor[$i]);
		for ($i = 0; $i < count($e->type); $i++) array_push($e->tags, $e->type[$i]);
		$e->image = $page->find('div.film-info', 0)->find('div.poster', 0)->find('a', 0)->find('img', 0)->getAttribute('data-cfsrc');
		$e->episode = Array();
		$str = cURL(constant('domain').$page->find('a.btn-see', 0)->getAttribute('href'));
		$frl = str_get_html($str);
		if (strpos($str, 'choose-server')) {
			$cnt = 0;
			foreach ($frl->find('ul.choose-server', 0)->find('li') as $node) {
				$epsID = (object) Array();
				$epsID->name = $node->find('a', 0)->plaintext;
				$epsID->list = Array();
				$vec = file_get_html(constant('domain').$node->find('a', 0)->getAttribute('href'));
				foreach ($vec->find('ul.list-episode', 0)->find('li') as $element) {
					$tepSD = (object) Array();
					$tepSD->name = trim($element->find('a', 0)->plaintext);
					$tepSD->link = $element->find('a', 0)->getAttribute('href');
					array_push($epsID->list, $tepSD);
				}
				array_push($e->episode, $epsID);
				$cnt++;
			}
		} else {
			$cnt = 0;
			$epsID = (object) Array();
			$epsID->name = 'HD';
			$epsID->list = Array();
			foreach ($frl->find('ul.list-episode', 0)->find('li') as $element) {
				$tepSD = (object) Array();
				$tepSD->name = trim($element->find('a', 0)->plaintext);
				$tepSD->link = $element->find('a', 0)->getAttribute('href');
				array_push($epsID->list, $tepSD);
			}
			array_push($e->episode, $epsID);
		}
		$e->info = $page->find('div.film-content', 0)->find('p', 0)->plaintext;
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