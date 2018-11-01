<?php
	const domain = 'http://www.phimmoi.net/';
	function parseInfo($url, $encode = true) {
		$page = str_get_html(cURL($url));

		$e = (object) Array();
		$movieTitle = $page->find('div.movie-detail', 0)->find('h1.movie-title', 0);
		$viName = $movieTitle->find('span.title-1', 0)->find('a.title-1', 0)->plaintext;
		$enName = $movieTitle->find('span.title-2', 0)->plaintext;
		$e->name = $viName.' - '.$enName;
		$e->status = trim($page->find('dd.status', 0)->plaintext);
		$e->director = $page->find('dd.dd-director', 0)->find('a.director', 0)->plaintext;
		$e->actor = Array();
		foreach ($page->find('div.actor-name') as $element) {
			array_push($e->actor, $element->find('span.actor-name-a', 0)->plaintext);
		}
		$e->type = Array();
		foreach ($page->find('dd.dd-cat', 0)->find('a') as $element) {
			array_push($e->type, $element->plaintext);
		}
		$e->nation = Array();
		foreach ($page->find('dd.dd-country', 0)->find('a') as $element) {
			array_push($e->nation, $element->plaintext);
		}
		$e->release = $page->find('dd.movie-dd', 3)->plaintext;
		$e->tags = Array();
		array_push($e->tags, $e->name);
		for ($i = 0; $i < count($e->nation); $i++) array_push($e->tags, $e->nation[$i]);
		for ($i = 0; $i < count($e->actor); $i++) array_push($e->tags, $e->actor[$i]);
		for ($i = 0; $i < count($e->type); $i++) array_push($e->tags, $e->type[$i]);
		$e->image = $page->find('div.movie-l-img', 0)->find('img', 0)->getAttribute('src');
		$e->episode = Array();
		$frl = str_get_html(cURL(constant('domain').$page->find('a[id=btn-film-watch]', 0)->getAttribute('href')));
		$cnt = 0;
		foreach ($frl->find('ul.list-episode') as $node) {
			$epsID = (object) Array();
			$epsID->name = $frl->find('h3.server-name', $cnt)->plaintext;
			$cnt++;
			$epsID->list = Array();
			foreach ($node->find('li.episode') as $element) {
				$tepSD = (object) Array();
				$tepSD->name = trim($element->find('a', 0)->plaintext);
				$tepSD->id = $element->find('a', 0)->getAttribute('data-episodeid');
				$tepSD->link = constant('domain').$element->find('a', 0)->getAttribute('href');
				array_push($epsID->list, $tepSD);
			}
			array_push($e->episode, $epsID);
		}
		$e->info = $page->find('div[id=film-content]', 0)->find('p', 0)->plaintext;
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