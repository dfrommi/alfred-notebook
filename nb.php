<?php
require_once('github.php');
require_once('workflows.php');

if(!ini_get( 'date.timezone')) {
	$timezone = exec( 'tz=`ls -l /etc/localtime` && echo ${tz#*/zoneinfo/}' );
    ini_set( 'date.timezone', $timezone );
}

array_shift($argv);
$query = trim($argv[0]);

$w = new Workflows();

$access_token = $w->get('token', 'settings.plist');
$repo = $w->get('repo', 'settings.plist');

$gh = new Github($repo, $access_token);

//Configuration error
if(empty($repo)) {
	echo $w->toxml(array(
	  	'uid' => 'missingRepo',
		'title' => 'Repository not set',
		'subtitle' => "use nb_conf to set repository",
		'icon' => 'book-icon.png',
		'valid' => 'no'
	));
	exit(1);
}

$items = array();

//Create new note
if(strpos($query, "+") === 0) {
	$searchCategory = trim($query, "+\t ");
	$template = "---\ncreated: ".date("Y-m-d")."\ntags: []\n---\n\n";

	$response = $gh->getContent();
	
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
	
	echo $w->toxml($items);
	exit(0);
}

if(strlen($query) > 2) {
	$response = $gh->search($query);
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

echo $w->toxml($items);

?>
