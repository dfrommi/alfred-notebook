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
		$nbCategory = preg_replace('/^\+(\w+)?\s*(.*)\s*$/i', '$1', $query);
		$filename = preg_replace('/^\+(\w+)?\s*(.*)\s*$/i', '$2', $query);

		$template = "---\ncreated: ".date("Y-m-d")."\ntags: []\n---\n\n";

		$response = $this->gh->getContent();
	
		$items = array();
		foreach($response as $item) {
			if($item['type'] == "dir" && $item['name'] != 'resources'
			 && (empty($nbCategory) || stripos($item['name'], $nbCategory) > -1)) {
				$category = $item['name'];
				$params = array("value" => $template);
				if(!empty($filename)) {
					$params['filename'] = $filename.".md";
				}
				$itemUrl = sprintf("%s?%s", "https://github.com/".$this->gh->repo."/new/master/".$category, http_build_query($params));

				array_push($items, array(
					'uid' => $item['sha'],
					'arg' => $itemUrl,
					'title' => $category,
					'subtitle' => "Add note '".$filename."' to ".$category,
					'icon' => 'note-taking_icon.jpg',
					'valid' => 'yes',
					'autocomplete' => '+'.$category." ".$filename
				));
			}
		}
		return $items;
	}
}

?>