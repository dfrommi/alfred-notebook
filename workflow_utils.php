<?php
require_once('workflows.php');

class WorkflowUtils {
	private $settingsFilename;
	private $w;
	
	function __construct($settingFilename = 'setting.plist') {
		$this->settingsFilename = $settingFilename;
		$this->w = new Workflows();
	}

	function initTimezone() {
		if(!ini_get( 'date.timezone')) {
			$timezone = exec( 'tz=`ls -l /etc/localtime` && echo ${tz#*/zoneinfo/}' );
		    ini_set( 'date.timezone', $timezone );
		}
	}
	
	function get($key) {
		return $this->w->get($key, 'settings.plist');
	}

	function set($key, $value) {
		$this->w->set($key, $value, 'settings.plist');
	}
	
	function printItems($items) {
		echo $this->w->toxml($items);
	}
}
?>