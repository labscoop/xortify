<?php
/**
 * @package     xortify
 * @subpackage  module
 * @description	Sector Network Security Drone
 * @author	    Simon Roberts WISHCRAFT <simon@chronolabs.coop>
 * @copyright	copyright (c) 2010-2013 XOOPS.org
 * @licence		GPL 2.0 - see docs/LICENCE.txt
 */

	set_time_limit(1800);
	include_once (XOOPS_ROOT_PATH.'/modules/xortify/providers/providers.php');
	$check = new Providers('footerpostcheck');

?>