<?php
require_once('workflow_utils.php');
require_once('github.php');
require_once('workflows.php');
require_once('validate_config_command.php');
require_once('new_note_command.php');
require_once('search_note_command.php');
require_once('default_actions_command.php');

array_shift($argv);
$query = trim($argv[0]);

$wfUtils = new WorkflowUtils();
$wfUtils->initTimezone();

$access_token = $wfUtils->get('token');
$repo = $wfUtils->get('repo');

$gh = new Github($repo, $access_token);

$commands = array(
	new ValidateConfigCommand($gh->repo),
	new NewNoteCommand($gh),
	new SearchNoteCommand($gh),
	new DefaultActionsCommand($gh->repo)
);

foreach($commands as $command) {
	if($command->matches($query)) {
		$items = $command->getItems($query);
		if(count($items) > 0) {
			$wfUtils->printItems($items);
			exit(0);
		}
	}
}

?>
