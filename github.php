<?php
require_once('workflows.php');

class Github {
	public $repo;
	private $token;
	private $w;
	
	function __construct($repo, $token=null) {
		$this->repo = $repo;
		$this->token = $token;
		$this->w = new Workflows();
	}

	public function getContent() {
		return $this->query("repos/".$this->repo."/contents");
	}

	public function search($query) {
		return $this->query("search/code", array("q" => "$query repo:".$this->repo));
	}

	private function query($resource, $params = null) {
		$url = $this->getUrl($resource, $params);
		$jsonResponse = $this->w->request($url, array(CURLOPT_USERAGENT => 'alfred'));
		$response = json_decode($jsonResponse, true);
		return $response;
	}
	
	private function getUrl($resource, $params = null) {
		$allParams = is_null($params) ? array() : $params;
		if(!empty($this->token)) {
			$allParams["access_token"] = $this->token;
		}
		
		$url = sprintf("%s?%s", "https://api.github.com/".$resource, http_build_query($allParams));
		return $url;
	}
}

?>