<?php
array_shift($argv);
$query = $argv[0];

require_once('workflows.php');
$w = new Workflows();

$access_token = $w->get('token', 'settings.plist');
$repo = $w->get('repo', 'settings.plist');

$items = array();

if(empty($repo)) {
	$errorItem = array(
	  	'uid' => 'missingRepo',
		'title' => 'Repository not set',
		'subtitle' => "use nb_conf to set repository",
		'icon' => 'book-icon.png',
		'valid' => 'no',
	);
	array_push($items, $errorItem);	

	$w = new Workflows();
	echo $w->toxml($items);
		
	exit(1);
}

if(strlen($query) > 2) {
	$query = "$query repo:$repo";
	$params = array(
		"q" => $query,
		"access_token" => $access_token
	);

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
			'valid' => 'yes',
			//	'autocomplete' => 'autocomplete'
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
		'valid' => 'yes',
	//	'autocomplete' => 'autocomplete'
	);
	array_push($items, $openRepo);	
}

$w = new Workflows();
echo $w->toxml($items);

?>
