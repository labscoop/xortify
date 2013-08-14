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
 * @file:			usercrawl.php		
 * @description:	Preloader Hooking Stratum for Xortify Client
 * @date:			09/09/2012 19:34 AEST
 * @license:		GNU3 (http://web.labs.coop/public/legal/general-public-licence/13,3.html)
 * @package			xortify
 * @subpackage		preloaders
 * @author			Simon A. Roberts - wishcraft (simon@labs.coop)

 * 
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

include_once XOOPS_ROOT_PATH.'/class/cache/xoopscache.php';
include_once XOOPS_ROOT_PATH.'/modules/xortify/include/functions.php';
include_once XOOPS_ROOT_PATH.'/modules/xortify/include/instance.php';

/*
 * Class for Xortify in Server Mode!
 * Handlers of Users Crawling.
 */
class XortifyUsercrawlPreload extends XoopsPreloadItem
{
	
	/*
	 * Event for loading when one cron is finished crawling users
	 * @param array $args		Arguements passed to the API
	 */
	function eventUsercrawlHeaderCacheEnd($args)
	{
		self::eventUsercrawlFooterEnd($args);
	}
	
	/*
	 * Event for loading when one cron is finished crawling users
	 * @param array $args		Arguements passed to the API
	 */
	function eventUsercrawlFooterEnd($args)
	{
		if (self::hasAPIUserPass()==false)
			return false;
		
		xoops_loadLanguage('modinfo', 'xortify');
		
		if ($GLOBALS['xoops']->getModuleConfig('users_to_check', 'xortify')==0)
			return false;
		
		$usercrawl = XoopsCache::read('xortify_usercrawl_last');
		if ((isset($usercrawl['when'])?(float)$usercrawl['when']:-microtime(true))+$GLOBALS['xoops']->getModuleConfig('xortify_ip_cache', 'xortify')<=microtime(true)) {
			$usercrawl['when'] = microtime(true);
			$user_handler = $GLOBALS['xoops']->getHandler('user');
			if (count($usercrawl['users'])) {
				$criteria = new Criteria('uid', 'NOT IN', '('.implode(',', $usercrawl['users']) .')');
				$criteria->setSort('RAND()');
				$criteria->setLimit($GLOBALS['xoops']->getModuleConfig('users_to_check', 'xortify'));
			} elseif (count($result['users'])==0) {
				$criteria = new Criteria('uid', '<>', '0');
				$criteria->setSort('RAND()');
				$criteria->setLimit($GLOBALS['xoops']->getModuleConfig('users_to_check', 'xortify'));
			}
			$users = $user_handler->getObjects($criteria, true, true);
			
			if (count($users)<$GLOBALS['xoops']->getModuleConfig('users_to_check', 'xortify')) {
				$usercrawl['users'] = array();
			} else {
				$usercrawl['users'] = array_merge($usercrawl['users'], array_keys($users));
			}
			
			require_once( XOOPS_ROOT_PATH.'/modules/xortify/class/'.$GLOBALS['xoops']->getModuleConfig('protocol', 'xortify').'.php' );
			$func = strtoupper($GLOBALS['xoops']->getModuleConfig('protocol', 'xortify')).'XortifyExchange';
			$apiExchange = new $func;
			
			$sfs = array();
			$seconds = 120;
			foreach($users as $uid => $user) {
				$seconds = $seconds + microtime(true) - $end;
				set_time_limit($seconds);
				$sfs[$uid] = $apiExchange->checkSFSBans($user);
				$end = microtime(true);
			}
			
			$uids = array();
			foreach($sfs as $uid => $result) {
				$seconds = $seconds + microtime(true) - $end;
				set_time_limit($seconds);				
				if (is_array($result)) {
					if (isset($result['success']))
						if ($result['success']==true)
						if (($result['email']['frequency']>=$GLOBALS['xoops']->getModuleConfig('email_freq', 'xortify')||$result['username']['frequency']>=$GLOBALS['xoops']->getModuleConfig('uname_freq', 'xortify')||$result['ip']['frequency']>=$GLOBALS['xoops']->getModuleConfig('ip_freq', 'xortify')) &&
								(strtotime($result['email']['lastseen'])>time()-$GLOBALS['xoops']->getModuleConfig('email_lastseen', 'xortify')||strtotime($result['username']['lastseen'])>time()-$GLOBALS['xoops']->getModuleConfig('uname_lastseen', 'xortify')||strtotime($result['ip']['lastseen'])>time()-$GLOBALS['xoops']->getModuleConfig('ip_lastseen', 'xortify'))) {
							
						include_once XOOPS_ROOT_PATH."/include/common.php";
							
						XoopsCache::write('xortify_sfs_'.sha1($users[$uid]['uname'].$users[$uid]['email'].(isset($users[$uid]['ip4'])?$users[$uid]['ip4']:"").(isset($users[$uid]['ip6'])?$users[$uid]['ip6']:"").(isset($users[$uid]['proxy-ip4'])?$users[$uid]['proxy-ip4']:"").(isset($users[$uid]['proxy-ip4'])?$users[$uid]['proxy-ip6']:"").$users[$uid]['network-addy']), array_merge($result, array('ipdata' => $users[$uid])), $GLOBALS['xoops']->getModuleConfig('xortify_ip_cache', 'xortify'));
							
						xoops_loadLanguage('ban', 'xortify');
							
						$log_handler = xoops_getmodulehandler('log', 'xortify');
						$log = $log_handler->create();
						$log->setVars($ipdata);
						$log->setVar('provider', 'stopforumspam.com');
						$log->setVar('action', 'banned');
						$log->setVar('extra', _XOR_BAN_SFS_TYPE."\n".
								_XOR_BAN_SFS_EMAIL_FREQ.': '.$result['email']['frequency'].' >= '.$GLOBALS['xoops']->getModuleConfig('email_freq', 'xortify'). "\n".
								_XOR_BAN_SFS_EMAIL_LASTSEEN.': '.date(_DATESTRING, strtotime($result['email']['lastseen'])) . ' > ' . date(_DATESTRING, time()-$GLOBALS['xoops']->getModuleConfig('email_lastseen', 'xortify')) . "\n" .
								_XOR_BAN_SFS_USERNAME_FREQ.': '.$result['username']['frequency'].' >= '.$GLOBALS['xoops']->getModuleConfig('uname_freq', 'xortify'). "\n".
								_XOR_BAN_SFS_USERNAME_LASTSEEN.': '.date(_DATESTRING, strtotime($result['username']['lastseen'])) . ' > ' . date(_DATESTRING, time()-$GLOBALS['xoops']->getModuleConfig('uname_lastseen', 'xortify')) . "\n" .
								_XOR_BAN_SFS_IP_FREQ.': '.$result['ip']['frequency'].' >= '.$GLOBALS['xoops']->getModuleConfig('ip_freq', 'xortify'). "\n".
								_XOR_BAN_SFS_IP_LASTSEEN.': '.date(_DATESTRING, strtotime($result['ip']['lastseen'])) . ' > ' . date(_DATESTRING, time()-$GLOBALS['xoops']->getModuleConfig('ip_lastseen', 'xortify')));

						$uids[$uid] = $uid;
					}
				}
				$end = microtime(true);
			}

			if (count($uids)>0) {
				$criteria = criteria('uid', 'IN', '('.implode(', ', array_keys($uids)).')');
				$user_handler->deleteAll($criteria);
			}
			$usercrawl['deleted'] = $usercrawl['deleted'] + count($uids);
			
			$usercrawl['took'] = microtime(true)-$usercrawl['when'];
			XoopsCache::write('xortify_usercrawl_last', $usercrawl, $GLOBALS['xoops']->getModuleConfig('xortify_ip_cache', 'xortify')*2);
		}
		
		return true;
	}
	
	function hasAPIUserPass()
	{
		if ($GLOBALS['xoops']->getModuleConfig('xortify_username', 'xortify')!=''&&$GLOBALS['xoops']->getModuleConfig('xortify_password', 'xortify')!='')
			return true;
		else
			return false;
	}		
	
}

?>