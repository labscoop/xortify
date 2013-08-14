<?php
/**
 * @package     xortify
 * @subpackage  module
 * @description	Sector Network Security Drone
 * @author	    Simon Roberts WISHCRAFT <simon@chronolabs.coop>
 * @copyright	copyright (c) 2010-2013 XOOPS.org
 * @licence		GPL 2.0 - see docs/LICENCE.txt
 */
global $adminmenu;
$adminmenu = array();
if (is_object($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module'])) {
	$xortifyImageAdmin = '../../../../../modules/xortify/images/icons/32';
	$adminmenu[1]['title'] = _XOR_ADMENU4;
	$adminmenu[1]['icon'] = 'home.png';
	$adminmenu[1]['image'] = 'home.png';
	$adminmenu[1]['link'] = "admin/index.php?op=dashboard";
	$adminmenu[2]['title'] = _XOR_ADMENU1;
	$adminmenu[2]['icon'] = $xortifyImageAdmin.'/current.bans.png';
	$adminmenu[2]['image'] = $xortifyImageAdmin.'/current.bans.png';
	$adminmenu[2]['link'] = "admin/index.php?op=list&fct=bans";
	$adminmenu[3]['title'] = _XOR_ADMENU3;
	$adminmenu[3]['icon'] = $xortifyImageAdmin.'/xortify.log.png';
	$adminmenu[3]['image'] = $xortifyImageAdmin.'/xortify.log.png';
	$adminmenu[3]['link'] = "admin/index.php?op=log";
	$adminmenu[4]['title'] = _XOR_ADMENU2;
	$adminmenu[4]['icon'] = $xortifyImageAdmin.'/access.list.png';
	$adminmenu[4]['image'] = $xortifyImageAdmin.'/access.list.png';
	$adminmenu[4]['link'] = "admin/index.php?op=signup&fct=signup";
	$adminmenu[5]['title'] = _XOR_ADMENU5;
	$adminmenu[5]['icon'] = 'about.png';
	$adminmenu[5]['image'] = 'about.png';
	$adminmenu[5]['link'] = "admin/index.php?op=about";
}
?>