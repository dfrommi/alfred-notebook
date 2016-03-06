<?php
require_once('workflows.php');
$file = 'settings.plist';

// remove script name
array_shift($argv);

$key = array_shift($argv);
$value = join(' ', $argv);

$w = new Workflows();

$w->set($key, $value, $file);
