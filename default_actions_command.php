<?php

class DefaultActionsCommand {
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

		$createNote = array(
		  	'uid' => 'searchRepo',
			'title' => 'Create new note',
			'icon' => 'book-icon.png',
			'valid' => 'no',
			'autocomplete' => '+'
		);
		array_push($items, $createNote);

		return $items;
	}
}

?>