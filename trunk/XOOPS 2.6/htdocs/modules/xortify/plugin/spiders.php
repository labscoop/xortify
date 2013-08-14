<?php
/**
 * @package     xortify
 * @subpackage  module
 * @description	Sector Network Security Drone
 * @author	    Simon Roberts WISHCRAFT <simon@chronolabs.coop>
 * @copyright	copyright (c) 2010-2013 XOOPS.org
 * @licence		GPL 2.0 - see docs/LICENCE.txt
 */

	function BannedPreHook($default, $log) {
		return $default;
	}
	
	function BlockedPreHook($default, $log) {
		return $default;
	}

	function MonitoredPreHook($default, $log) {
		return $default;
	}
	
	function BannedPostHook($log, $lid) {
		return $lid;
	}
	
	function BlockedPostHook($log, $lid) {
		return $lid;
	}

	function MonitoredPostHook($log, $lid) {
		return $lid;
	}
	
?>