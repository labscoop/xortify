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
 * @Version:		3.10 Final (Stable)
 * @copyright:		Chronolabs Cooperative 2013 © Simon Roberts (www.simonaroberts.com)
 * @download:		http://sourceforge.net/projects/xortify
 * @file:			users.php		
 * @description:	Preloader Hooking Stratum for Xortify Server & Users
 * @date:			09/09/2012 19:34 AEST
 * @license:		GNU3 (http://web.labs.coop/public/legal/general-public-licence/13,3.html)
 * @package			xortify
 * @subpackage		preloaders
 * @author			Simon A. Roberts - wishcraft (simon@labs.coop)

 * 
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/*
 * Class for Xortify in Server Mode! 
 * Handlers of Users.
 */
class XortifyUsersPreload extends XoopsPreloadItem
{
	
	/*
	 * Event for loading when one of the APIs are finished being called
	 * @param array $args		Arguements passed to the API
	 */
	function eventApiServerEnd($args)
	{
		xoops_loadLanguage('modinfo', 'xortify');
		include_once dirname(dirname(__FILE__)) . '/xoops_version.php';
		if (_MI_XOR_MODE!=_MI_XOR_MODE_SERVER)
			return true;
		
		
		if ($args[1]=='xoops_create_user') {
			
			xoops_load('cache');
			
			xoops_loadLanguage('modinfo', 'xortify');
			$member_handler = $GLOBALS['xoops']->getHandler('member');
			$module_handler = $GLOBALS['xoops']->getHandler('module');
			$config_handler = $GLOBALS['xoops']->getHandler('config');		
			$GLOBALS['xortifyModule'] = $module_handler->getByDirname('xortify');
			if (is_object($GLOBALS['xortifyModule'])) {
				$GLOBALS['xortifyModuleConfig'] = $config_handler->getConfigList($GLOBALS['xortifyModule']->getVar('mid'));
			}
			
			$result = XoopsCache::read('user_bounce_last');
			if ((isset($result['when'])?(float)$result['when']:-microtime(true))+$GLOBALS['xortifyModuleConfig']['bounce']<=microtime(true)) {
				$result = array();
				$result['when'] = microtime(true);
				$servers_handler = xoops_getmodulehandler('servers', 'xortify');
				$criteria = new Criteria(true,true);
				$criteria->setSort('RAND()');
				$criteria->setOrder('ASC');
				if ($servers = $servers_handler->getObjects($criteria, true)&&$servers_handler->getCount($criteria)>0) {
					foreach($servers as $sid => $server) {
						$dbserver = parse_url($server->getVar('server'));
						if ($users = XoopsCache::read('server_users_xortify_'.$dbserver['host'])) {
							foreach($users as $uid => $user ) {
						
								$newuser = $member_handler->createUser();
								$newuser->setVars($user);
								if ($member_handler->insertUser($newuser, true)) {
									$newid = $newuser->getVar('uid');
									@$member_handler->addUserToGroup(XOOPS_GROUP_USERS, $newid);
								}
							}
							XoopsCache::delete('server_users_xortify_'.$dbserver['host']);
						}
					}
				}
				$result['took'] = microtime(true)-$result['when'];
				XoopsCache::write('user_bounce_last', $result, $GLOBALS['xortifyModuleConfig']['bounce']*2);
			}
		}
	}
}

?>