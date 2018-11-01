<?php
	//header('Content-Type: text/plain; charset=utf-8');
	//require_once('../init/simple_html_dom.php');
	//require_once('../init/cURL.php');
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
		$link_btn = constant('domain').$page->find('a[id=btn-film-watch]', 0)->getAttribute('href');
		$data_btn = cURL($link_btn);
		$frl = str_get_html($data_btn);
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
				if ($tepSD->name != null)
					array_push($epsID->list, $tepSD);
			}
			if ($epsID->name != null)
				array_push($e->episode, $epsID);
		}
		if (count($e->episode) == 0 && strpos($data_btn, 'backup-server') > 0) {
			$cnt = 0;
			foreach ($frl->find('ul.server-list', 0)->find('li.backup-server') as $node) {
				$epsID = (object) Array();
				$epsID->name = $node->find('h3.server-title', 0)->plaintext;
				$cnt++;
				$epsID->list = Array();
				foreach ($node->find('ul.list-episode', 0)->find('li.episode', 0)->find('a') as $element) {
					$tepSD = (object) Array();
					$tepSD->name = trim($element->plaintext);
					$tepSD->id = $element->getAttribute('data-episodeid');
					$tepSD->link = constant('domain').$element->getAttribute('href');
					if ($tepSD->name != null)
						array_push($epsID->list, $tepSD);
				}
				if ($epsID->name != null)
					array_push($e->episode, $epsID);
			}
		}
		if (count($e->episode) < 1) {
			$epsID = (object) Array();
			$epsID->name = 'HD';
			$epsID->list = Array();
			$tepSD = (object) Array();
			$tepSD->name = 'FULL';
			$tepSD->link = $link_btn;
			array_push($epsID->list, $tepSD);
			array_push($e->episode, $epsID);
		} else 
		if ($e->episode[0]->list[0]->link == constant('domain')) {
			$e->episode = Array();
			$epsID = (object) Array();
			$epsID->name = 'HD';
			$epsID->list = Array();
			$tepSD = (object) Array();
			$tepSD->name = 'FULL';
			$tepSD->link = $link_btn;
			array_push($epsID->list, $tepSD);
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
	//parseInfo($_GET['url'], false);
?>