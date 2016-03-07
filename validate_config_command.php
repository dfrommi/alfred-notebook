<?php

class ValidateConfigCommand {
	private $gh;
	
	function __construct($gh) {
		$this->gh = $gh;
	}
	
	public function matches($query) {
		return empty($this->gh->repo);
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