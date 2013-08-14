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
 * @file:			post.loader.php		
 * @description:	Post Loader Routines for Spiders Provider
 * @date:			09/09/2012 19:34 AEST
 * @license:		GNU3 (http://web.labs.coop/public/legal/general-public-licence/13,3.html)
 * @package			xortify
 * @subpackage		security-providers
 * @author			Simon A. Roberts - wishcraft (simon@labs.coop)

 * 
 */

	$GLOBALS['xoops'] = Xoops::getInstance();	
	include_once($GLOBALS['xoops']->path("/include/common.php"));


	if (is_object($GLOBALS['xoops']->user)) {
		if (in_array($GLOBALS['xoops']->getModuleConfig('bot_group', 'spiders'), $GLOBALS['xoops']->user->getGroups())&&!empty($_POST)) {
			
			$GLOBALS['xoops']->loadLanguage('ban', 'spiders');
			
			$log_handler = $GLOBALS['xoops']->getModuleHandler('log', 'spiders');
			$log = $log_handler->create();
			$log->setVars(xortify_getIPData(false));
			$log->setVar('provider', basename(dirname(__FILE__)));
			$log->setVar('action', 'monitored');
			$log->setVar('extra', _XOR_BAN_SPIDER_TYPE.': '.print_r($_POST, true));
			$lid = $log_handler->insert($log, true);
			XoopsCache::write('xortify_core_include_common_end', array('time'=>microtime(true)), $GLOBALS['xoops']->getModuleConfig('fault_delay', 'xortify'));
			$_SESSION['xortify']['lid'] = $lid;
			setcookie('xortify', array('lid' => $lid), time()+3600*24*7*4*3);
			header('Location: '.XOOPS_URL.'/banned.php');
			exit(0);
		}
		$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['_pass'] = true;
	}	

?>