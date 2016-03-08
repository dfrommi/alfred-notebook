<?php

class SearchNoteCommand {
	private $gh;
	
	function __construct($gh) {
		$this->gh = $gh;
	}
	
	public function matches($query) {
		return strlen($query) > 2;
	}
	
	public function getItems($query) {
		$items = array();
		$response = $this->gh->search($query);
		foreach($response['items'] as $item) {
			$itemUrl = "https://github.com/".$this->gh->repo."/blob/master/".$item['path'];

			array_push($items, array(
				'uid' => $item['sha'],
				'arg' => $itemUrl,
				'title' => $item['name'],
				'subtitle' => dirname($item['path']),
				'icon' => 'note-taking_icon.jpg',
				'valid' => 'yes'
			));
		}
		return $items;
	}
}

?>