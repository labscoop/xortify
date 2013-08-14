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
* @copyright:		Chronolabs Cooperative 2013 © Simon Roberts (www.simonaroberts.com)
* Download:		http://sourceforge.net/projects/xortify
* This File:	index.php
* Description:	Data Push recieving service from Xortify Cloud.
* @date:				09/09/2012 19:34 AEST
* @license:		GNU3 (http://web.labs.coop/public/legal/general-public-licence/13,3.html)
*
*/
	
	global $xoops, $xoopsPreload, $xoopsLogger, $xoopsErrorHandler, $xoopsSecurity, $sess_handler;

	session_id($_REQUEST['session']);
	session_start();
	
	$xoopsOption["nocommon"]=false;
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/mainfile.php');
	require_once(dirname(dirname(__FILE__)).'/language/english/modinfo.php');
	require_once(dirname(dirname(__FILE__)).'/xoops_version.php');
	
	if (_MI_XOR_MODE!=_MI_XOR_MODE_CLIENT)
		die(_MI_XOR_NOT_MODE_CLIENT);
	
	include_once $GLOBALS['xoops']->path('class'.DS.'cache'.DS.'xoopscache.php');
	
	if ($GLOBALS['xoopsSecurity']->validateToken($_REQUEST['token'], true, 'poll_token')==true) {
		XoopsCache::write($_REQUEST['carriage'], $_REQUEST['data'], (integer)$GLOBALS['xoops']->getModuleConfig($_REQUEST['config'], 'xortify'));
		echo serialize(array('success'=>true));
		exit;
	}
	echo serialize(array('success'=>false));
	exit;
	
?>