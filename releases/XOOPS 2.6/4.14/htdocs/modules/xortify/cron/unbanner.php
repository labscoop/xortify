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
 * @file:			unbanner.php		
 * @description:	Server mode unbanner Cron.
 * @date:			26/07/2013 19:40 AEST
 * @license:		GNU3 (http://web.labs.coop/public/legal/general-public-licence/13,3.html)
 * @package			xortify
 * @subpackage		xortify
 * @author			Simon A. Roberts - wishcraft (simon@labs.coop)

 * 
 */


	include( '../../../mainfile.php' );

	$banmemberhandler = xoops_getmodulehandler('members','ban');

	$criteria = new CriteriaCompo(new Criteria('`made`', time() - (((7*(24*(60*60))))*3), '<'));
	$criteria->add(new Criteria('`made`', 0, '='), 'OR');
	
	$banned = $banmemberhandler->getObjects($criteria, true);

	$comment_handler = $GLOBALS['xoops']->getHandler('comment');
	$unbanmemberhandler = xoops_getmodulehandler('members','unban');
	$module_handler = & $GLOBALS['xoops']->getHandler('module');	
	
	$xoBanModule = $module_handler->getByDirname('ban');
	$xoUnbanModule = $module_handler->getByDirname('unban');
	
	if (is_array($banned))
	foreach( $banned as $key => $ban ) {
				
		$unban = $unbanmemberhandler->create();

		$unban->setVars($ban->toArray());
		$unban->setVar('made', time());
	
		$banmemberhandler->delete( $ban, true );
		if ($unbanmemberhandler->insert($unban)) {
			$sql = "UPDATE ".$xoopsDB->prefix('xoopscomments'). " set `com_modid` = '".$xoUnbanModule->getVar('mid')."' WHERE `com_modid` = '".$xoBanModule->getVar('mid')."' AND `com_itemid` = '".$ban->getVar('member_id')."'" ;
			$xoopsDB->queryF($sql);
		}
	} 
	
?>