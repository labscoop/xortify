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
 * This File:	footer.post.loader.php		
 * Description:	Protector Footer Post Loader Provider
 * Date:		09/09/2012 19:34 AEST
 * License:		GNU3
 * 
 */
	
	if (class_exists('Protector')) {
		
		xoops_load('xoopscache');
		if (!class_exists('XoopsCache')) {
			// XOOPS 2.4 Compliance
			xoops_load('cache');
			if (!class_exists('XoopsCache')) {
				include_once XOOPS_ROOT_PATH.'/class/cache/xoopscache.php';
			}
		}
			
		
		$bad_ips = Protector::get_bad_ips(false);
		$cache_bad_ips = XoopsCache::read('xortify_bans_protector');
		if (empty($cache_bad_ips))
			$cache_bad_ips = array();
	
		$module_handler = xoops_gethandler('module');
		$config_handler = xoops_gethandler('config');
		if (!is_object($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['module'])) $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['module'] = $module_handler->getByDirname('xortify');
		if (!isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig'])) $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig'] = $config_handler->getConfigList($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['module']->getVar('mid'));
	
		require_once( XOOPS_ROOT_PATH.'/modules/xortify/class/'.$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig']['protocol'].'.php' ); 	
		$func = strtoupper($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig']['protocol']).'XortifyExchange';
		$apiExchange = new $func;
		
		if (is_array($cache_bad_ips)) {
			foreach($bad_ips as $id => $ip) {
				if (!in_array($ip, $cache_bad_ips)) { 
					if ($ip!=$GLOBALS['xoopsConfig']['my_ip']) {    
						$sql = 'SELECT `timestamp`, `type`, `agent`, `description` FROM '.$GLOBALS['xoopsDB']->prefix('protector_log').' WHERE ip = "'.$ip.'" ORDER BY `timestamp`';
						$result = $GLOBALS['xoopsDB']->queryF($sql);
						$comment = '';
						while($row = $GLOBALS['xoopsDB']->fetchArray($result)) {
							$comment .= (strlen($comment)>0?"\n":'').$row['timestamp']. ' - ' . $row['type'] . ' - ' . $row['agent'] . ' - ' . $row['description'];
							$agent[] = $row['agent'];
						} 
						$results[] = $apiExchange->sendBan($comment, 1, $ip);
						
						$log_handler = xoops_getmodulehandler('log', 'xortify');
						$log = $log_handler->create();
						$log->setVars(xortify_getIPData($ip));
						$log->setVar('provider', basename(dirname(__FILE__)));
						$log->setVar('action', 'banned');
						$log->setVar('extra', $comment);
						$log->setVar('agent', implode("\n", array_unique($agent)));
						$log->setVar('email', '');
						$log->setVar('uname', '');
						$log_handler->insert($log, true);
						
					}
				}
			}
		}		
		unlinkOldCachefiles('xortify_',$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig']['xortify_ip_cache']);
		XoopsCache::delete('xortify_bans_protector');
		XoopsCache::write('xortify_bans_protector', $bad_ips);			
	}
?>