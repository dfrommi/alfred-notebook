<?php

class NewNoteCommand {
	private $gh;
	
	function __construct($gh) {
		$this->gh = $gh;
	}
	
	public function matches($query) {
		return strpos($query, "+") === 0;
	}
	
	public function getItems($query) {
		$searchCategory = trim($query, "+\t ");
		$template = "---\ncreated: ".date("Y-m-d")."\ntags: []\n---\n\n";

		$response = $this->gh->getContent();
	
		$items = array();
		foreach($response as $item) {
			if($item['type'] == "dir" && $item['name'] != 'resources'
			 && (empty($searchCategory) || stripos($item['name'], $searchCategory) > -1)) {
				$category = $item['name'];
				$params = array("value" => $template);
				$itemUrl = sprintf("%s?%s", "https://github.com/$gh->repo/new/master/".$category, http_build_query($params));

				array_push($items, array(
					'uid' => $item['sha'],
					'arg' => $itemUrl,
					'title' => $category,
					'subtitle' => "Add note to ".$category,
					'icon' => 'note-taking_icon.jpg',
					'valid' => 'yes'
				));
			}
		}
		return $items;
	}
}

?>