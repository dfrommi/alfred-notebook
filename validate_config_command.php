<?php

class ValidateConfigCommand {
	private $repo;
	
	function __construct($repo) {
		$this->repo = $repo;
	}
	
	public function matches($query) {
		return empty($this->repo);
	}
	
	public function getItems($query) {
		$items = array();
		array_push($items, array(
		  	'uid' => 'missingRepo',
			'title' => 'Repository not set',
			'subtitle' => "use nb_conf to set repository",
			'icon' => 'book-icon.png',
			'valid' => 'no'
		));
		return $items;
	}
}

?>