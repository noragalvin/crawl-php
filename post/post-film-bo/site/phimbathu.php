<?php
	const domain = 'http://phimbathu.com';
	function parseInfo($url, $encode = true) {
		$page = file_get_html($url);

		$e = (object) Array();
		$movieTitle = $page->find('div.text', 0);
		$viName = $movieTitle->find('h1', 0)->find('p.title', 0)->plaintext;
		$enName = $movieTitle->find('h2', 0)->find('p.real-name', 0)->plaintext;
		$e->name = $viName.' - '.$enName;
		$e->status = $page->find('div.dinfo', 0)->find('dl', 1)->find('dd', 0)->plaintext;
		$e->director = $page->find('dl.col', 0)->find('dd', 0)->find('a', 0)->plaintext;
		$e->actor = Array();
		foreach ($page->find('dl.col', 0)->find('dd', 1)->find('a') as $element) {
			array_push($e->actor, $element->plaintext);
		}
		$e->type = Array();
		foreach ($page->find('dl.col', 0)->find('dd', 2)->find('a') as $element) {
			array_push($e->type, $element->plaintext);
		}
		$e->nation = Array();
		$vcnt = 0;
		foreach ($page->find('dl.col', 1)->find('dd') as $node) $vcnt++;
		foreach ($page->find('dl.col', 1)->find('dd', $vcnt-1)->find('a') as $element){
			array_push($e->nation, $element->plaintext);
		}
		$e->release = $page->find('dl.col', 1)->find('dd', 4)->plaintext;
		$e->tags = Array();
		array_push($e->tags, $e->name);
		for ($i = 0; $i < count($e->nation); $i++) array_push($e->tags, $e->nation[$i]);
		for ($i = 0; $i < count($e->actor); $i++) array_push($e->tags, $e->actor[$i]);
		for ($i = 0; $i < count($e->type); $i++) array_push($e->tags, $e->type[$i]);
		$e->image = $page->find('a.adspruce-streamlink', 0)->find('img', 0)->getAttribute('data-cfsrc');
		$e->episode = Array();
		$content = file_get_contents(constant('domain').$page->find('div.poster', 0)->find('ul.buttons', 0)->find('li', 0)->find('a', 0)->getAttribute('href'));
		$frl = str_get_html($content);
		if (strpos($content, 'choose-server')) {
			$cnt = 0;
			foreach ($frl->find('ul.choose-server', 0)->find('li') as $node) {
				$epsID = (object) Array(); 
				$epsID->name = $node->find('a', 0)->plaintext;
				$epsID->list = Array();
				$vec = file_get_html(constant('domain').$node->find('a', 0)->getAttribute('href'));
				foreach ($vec->find('div.list-episode', 0)->find('a') as $element) {
					$tepSD = (object) Array();
					$tepSD->name = trim($element->plaintext);
					$tepSD->link = constant('domain').$element->getAttribute('href');
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
			foreach ($frl->find('div.list-episode', 0)->find('a') as $element) {
				$tepSD = (object) Array();
				$tepSD->name = trim($element->plaintext);
				$tepSD->link = constant('domain').$element->getAttribute('href');
				array_push($epsID->list, $tepSD);
			}
			array_push($e->episode, $epsID);
		}
		$e->info = $page->find('div.tabs-content', 0)->find('div.tab', 0)->find('p', 0)->plaintext;
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