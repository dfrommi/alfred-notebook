<?php

class OpenNotebookCommand {
	private $gh;
	
	function __construct($gh) {
		$this->gh = $gh;
	}
	
	public function matches($query) {
		return true;
	}
	
	public function getItems($query) {
		$items = array();
		$openRepo = array(
		  	'uid' => 'openRepo',
			'arg' => 'https://github.com/'.$this->gh->repo,
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