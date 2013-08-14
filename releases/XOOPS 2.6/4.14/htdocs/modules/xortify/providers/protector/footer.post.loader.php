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
 * @description:	Post Loader Routines for Protector Provider
 * @date:			09/09/2012 19:34 AEST
 * @license:		GNU3 (http://web.labs.coop/public/legal/general-public-licence/13,3.html)
 * @package			xortify
 * @subpackage		security-providers
 * @author			Simon A. Roberts - wishcraft (simon@labs.coop)

 * 
 */

	$GLOBALS['xoops'] = Xoops::getInstance();	
	include_once($GLOBALS['xoops']->path("/include/common.php"));

	
	if (class_exists('Protector')) {
		
		XoopsLoad::load('xoopscache');
		if (!class_exists('XoopsCache')) {
			// XOOPS 2.4 Compliance
			XoopsLoad::load('cache');
			if (!class_exists('XoopsCache')) {
				include_once $GLOBALS['xoops']->root_path.'/class/cache/xoopscache.php';
			}
		}
			
		
		$bad_ips = Protector::get_bad_ips(false);
		$cache_bad_ips = XoopsCache::read('xortify_bans_protector');
		if (empty($cache_bad_ips))
			$cache_bad_ips = array();
	
		require_once( $GLOBALS['xoops']->path('/modules/xortify/class/'.$GLOBALS['xoops']->getModuleConfig('protocol', 'xortify').'.php') ); 	
		$func = strtoupper($GLOBALS['xoops']->getModuleConfig('protocol', 'xortify')).'XortifyExchange';
		$soapExchg = new $func;
		
		if (is_array($cache_bad_ips)) {
			foreach($bad_ips as $id => $ip) {
				if (!in_array($ip, $cache_bad_ips)) { 
					if ($ip!=$GLOBALS['xoops']->config['my_ip']) {    
						$sql = 'SELECT `lid`, `timestamp`, `type`, `agent`, `description` FROM '.$GLOBALS['xoops']->db->prefix('protector_log').' WHERE ip = "'.$ip.'" ORDER BY `timestamp`';
						$delete = 'DELECT FROM '.$GLOBALS['xoops']->db->prefix('protector_log')." WHERE `lid` = '%s'";
						$result = $GLOBALS['xoops']->db->queryF($sql);
						$comment = '';
						while($row = $GLOBALS['xoops']->db->fetchArray($result)) {
							$comment .= (strlen($comment)>0?"\n":'').$row['timestamp']. ' - ' . $row['type'] . ' - ' . $row['agent'] . ' - ' . $row['description'];
							$agent[] = $row['agent'];
							$GLOBALS['xoops']->db->queryF(sprintf($delete, $row['lid']));
						} 
						$results[] = $soapExchg->sendBan($comment, 1, $ip);
						
						$log_handler = $GLOBALS['xoops']->getModuleHandler('log', 'xortify');
						$log = $log_handler->create();
						$log->setVars(xortify_getIPData($ip));
						$log->setVar('provider', basename(dirname(__FILE__)));
						$log->setVar('action', 'banned');
						$log->setVar('extra', $comment);
						$log->setVar('agent', implode("\n", array_unique(array_unique($agent))));
						$log->setVar('email', '');
						$log->setVar('uname', '');
						$log_handler->insert($log, true);
						
					}
				}
			}
		}		
		XoopsCache::delete('xortify_bans_protector');
		XoopsCache::write('xortify_bans_protector', $bad_ips);			
	}
?>