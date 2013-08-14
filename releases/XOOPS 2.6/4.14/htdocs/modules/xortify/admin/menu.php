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
 * @Version:		4.11 Final (Stable)
 * @copyright:		Chronolabs Cooperative 2013 © Simon Roberts (www.simonaroberts.com)
 * @download:		http://sourceforge.net/projects/xortify
 * @file:			menu.php		
 * @description:	Admin Menu.
 * @date:			26/07/2013 19:40 AEST
 * @license:		GNU3 (http://web.labs.coop/public/legal/general-public-licence/13,3.html)
 * @package			xortify
 * @subpackage		administration
 * @author			Simon A. Roberts - wishcraft (simon@labs.coop)

 * 
 */

global $adminmenu;
$adminmenu = array();

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

?>