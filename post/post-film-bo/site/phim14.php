<?php
	function parseInfo($url, $encode = true) {
		$page = file_get_html($url);

		$e = (object) Array();
		$e->name = $page->find('div.alt2', 0)->find('font', 0)->plaintext;
		$e->status = trim($page->find('div.alt2', 4)->find('font', 0)->plaintext);
		$e->director = $page->find('div.alt1', 0)->find('a', 0)->plaintext;
		$e->actor = Array();
		foreach ($page->find('div.alt2', 1)->find('a') as $element) {
			if ($element->plaintext != '') array_push($e->actor, $element->plaintext);
		}
		$e->type = Array();
		foreach ($page->find('div.alt1', 1)->find('a') as $element) {
			if ($element->plaintext != '') array_push($e->type, $element->plaintext);
		}
		$e->nation = Array();
		foreach ($page->find('div.alt2', 2)->find('a') as $element) {
			array_push($e->nation, $element->plaintext);
		}
		$e->release = $page->find('div.alt2', 3)->find('a', 0)->plaintext;
		$e->tags = Array();
		array_push($e->tags, $e->name);
		for ($i = 0; $i < count($e->nation); $i++) array_push($e->tags, $e->nation[$i]);
		for ($i = 0; $i < count($e->actor); $i++) array_push($e->tags, $e->actor[$i]);
		for ($i = 0; $i < count($e->type); $i++) array_push($e->tags, $e->type[$i]);
		$e->image = $page->find('div.thumbnail', 0)->find('img', 0)->getAttribute('src');
		$e->episode = Array();
		$frl = file_get_html($page->find('div.phimbtn', 0)->find('a', 0)->getAttribute('href'));
		$cnt = 0;
		foreach ($frl->find('ul[id=server_list]', 0)->find('ul.episode_list') as $node) {
			$epsID = (object) Array();
			$epsID->name = str_replace(':', '', $frl->find('li.server_item', $cnt)->find('strong', 0)->plaintext);
			$epsID->name = str_replace(' ', '', $epsID->name);
			$cnt++;
			$epsID->list = Array();
			foreach ($node->find('li') as $element) {
				$tepSD = (object) Array();
				$tepSD->name = str_replace(' ', '', $element->find('a', 0)->plaintext);
				$tepSD->id = $element->find('a', 0)->getAttribute('id');
				$tepSD->link = $element->find('a', 0)->getAttribute('href');
				array_push($epsID->list, $tepSD);
			}
			array_push($e->episode, $epsID);
		}
		$e->info = $page->find('div.message', 0)->plaintext;
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