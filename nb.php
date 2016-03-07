<?php
if(!ini_get( 'date.timezone')) {
	$timezone = exec( 'tz=`ls -l /etc/localtime` && echo ${tz#*/zoneinfo/}' );
    ini_set( 'date.timezone', $timezone );
}

array_shift($argv);
$query = trim($argv[0]);

require_once('workflows.php');
$w = new Workflows();

$access_token = $w->get('token', 'settings.plist');
$repo = $w->get('repo', 'settings.plist');

$items = array();

//Configuration error
if(empty($repo)) {
	$errorItem = array(
	  	'uid' => 'missingRepo',
		'title' => 'Repository not set',
		'subtitle' => "use nb_conf to set repository",
		'icon' => 'book-icon.png',
		'valid' => 'no'
	);
	array_push($items, $errorItem);	

	$w = new Workflows();
	echo $w->toxml($items);
		
	exit(1);
}

if(strpos($query, "+") === 0) {
	$searchCategory = trim($query, "+\t ");

	$template = "---\ncreated: ".date("Y-m-d")."\ntags: []\n---\n\n";

	$params = array();
	if(!empty($access_token)) {
		$params["access_token"] = $access_token;
	}

	$url = sprintf("%s?%s", "https://api.github.com/repos/".$repo."/contents", http_build_query($params));
	$jsonResponse = $w->request($url, array(CURLOPT_USERAGENT => 'alfred'));
	$response = json_decode($jsonResponse, true);

	foreach($response as $item) {
		if($item['type'] == "dir" && $item['name'] != 'resources'
		 && (empty($searchCategory) || stripos($item['name'], $searchCategory) > -1)) {
			$category = $item['name'];
			$params = array("value" => $template);
			$itemUrl = sprintf("%s?%s", "https://github.com/$repo/new/master/".$category, http_build_query($params));

			$temp = array(
				'uid' => $item['sha'],
				'arg' => $itemUrl,
				'title' => $category,
				'subtitle' => "Add note to ".$category,
				'icon' => 'note-taking_icon.jpg',
				'valid' => 'yes'
			);
			array_push($items, $temp);
		}
	}
	
	$w = new Workflows();
	echo $w->toxml($items);

	exit(0);
}

if(strlen($query) > 2) {
	$query = "$query repo:$repo";
	$params = array("q" => $query);
	if(!empty($access_token)) {
		$params["access_token"] = $access_token;
	}

	$url = sprintf("%s?%s", "https://api.github.com/search/code", http_build_query($params));

	$jsonResponse = $w->request($url, array(CURLOPT_USERAGENT => 'alfred'));
	$response = json_decode($jsonResponse, true);

	foreach($response['items'] as $item) {
		$itemUrl = "https://github.com/$repo/blob/master/".$item['path'];

		$temp = array(
			'uid' => $item['sha'],
			'arg' => $itemUrl,
			'title' => $item['name'],
			'subtitle' => dirname($item['path']),
			'icon' => 'note-taking_icon.jpg',
			'valid' => 'yes'
		);
		array_push($items, $temp);
	}
}

if(empty($item)) {
	$openRepo = array(
	  	'uid' => 'openRepo',
		'arg' => 'https://github.com/'.$repo,
		'title' => 'Open notebook',
		'subtitle' => $repo,
		'icon' => 'book-icon.png',
		'valid' => 'yes'
	);
	array_push($items, $openRepo);	
}

$w = new Workflows();
echo $w->toxml($items);

?>
