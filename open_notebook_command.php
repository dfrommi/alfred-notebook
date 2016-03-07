<?php

class OpenNotebookCommand {
	private $repo;
	
	function __construct($repo) {
		$this->repo = $repo;
	}
	
	public function matches($query) {
		return true;
	}
	
	public function getItems($query) {
		$items = array();
		$openRepo = array(
		  	'uid' => 'openRepo',
			'arg' => 'https://github.com/'.$this->repo,
			'title' => 'Open notebook',
			'subtitle' => $this->gh->repo,
			'icon' => 'book-icon.png',
			'valid' => 'yes'
		);
		array_push($items, $openRepo);
		return $items;
	}
}

?>