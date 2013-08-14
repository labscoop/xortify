<?php
/*
 * Prevents Spam, Harvesting, Human Rights Abuse, Captcha Abuse etc.
 * basic statistic of them in XOOPS Copyright (C) 2012 Simon Roberts 
 * Contact: wishcraft - simon@labs.coop
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 * See /docs/license.pdf for full license.
 * 
 * Shouts:- 	Mamba (www.xoops.org), flipse (www.nlxoops.nl)
 * 				Many thanks for your additional work with version 1.01
 * 
 * Version:		3.10 Final (Stable)
 * Published:	Chronolabs
 * Download:	http://code.google.com/p/chronolabs
 * This File:	menu.php		
 * Description:	menu defines for admin
 * Date:		09/09/2012 19:34 AEST
 * License:		GNU3
 * 
 */
$module_handler =& xoops_gethandler('module');
if (!is_object($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['module']))
	$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['module'] = $module_handler->getByDirname('xortify');
$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['ImageAdmin'] = $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['module']->getInfo('icons32');
$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['XortifyImageAdmin'] = $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['module']->getInfo('xortify_icons32');

global $adminmenu;
$adminmenu = array();
$adminmenu[1]['title'] = _XOR_ADMENU4;
$adminmenu[1]['icon'] = '../../'.$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['ImageAdmin'].'/home.png';
$adminmenu[1]['image'] = '../../'.$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['ImageAdmin'].'/home.png';
$adminmenu[1]['link'] = "admin/index.php?op=dashboard";
$adminmenu[2]['title'] = _XOR_ADMENU1;
$adminmenu[2]['icon'] = '../../'.$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['XortifyImageAdmin'].'/current.bans.png';
$adminmenu[2]['image'] = '../../'.$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['XortifyImageAdmin'].'/current.bans.png';
$adminmenu[2]['link'] = "admin/index.php?op=list&fct=bans";
$adminmenu[3]['title'] = _XOR_ADMENU3;
$adminmenu[3]['icon'] = '../../'.$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['XortifyImageAdmin'].'/xortify.log.png';
$adminmenu[3]['image'] = '../../'.$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['XortifyImageAdmin'].'/xortify.log.png';
$adminmenu[3]['link'] = "admin/index.php?op=log";
$adminmenu[4]['title'] = _XOR_ADMENU2;
$adminmenu[4]['icon'] = '../../'.$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['XortifyImageAdmin'].'/access.list.png';
$adminmenu[4]['image'] = '../../'.$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['XortifyImageAdmin'].'/access.list.png';
$adminmenu[4]['link'] = "admin/index.php?op=signup&fct=signup";
$adminmenu[5]['title'] = _XOR_ADMENU5;
$adminmenu[5]['icon'] = '../../'.$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['ImageAdmin'].'/about.png';
$adminmenu[5]['image'] = '../../'.$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['ImageAdmin'].'/about.png';
$adminmenu[5]['link'] = "admin/index.php?op=about";
?>