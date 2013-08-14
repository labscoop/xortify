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
 * @description:	Post Loader Routines for Stop Forum Spam Provider
 * @date:			09/09/2012 19:34 AEST
 * @license:		GNU3 (http://web.labs.coop/public/legal/general-public-licence/13,3.html)
 * @package			xortify
 * @subpackage		security-providers
 * @author			Simon A. Roberts - wishcraft (simon@labs.coop)

 * 
 */


		
	$GLOBALS['xoops'] = Xoops::getInstance();	
	include_once($GLOBALS['xoops']->path("/include/common.php"));
	include_once($GLOBALS['xoops']->path('/modules/xortify/include/functions.php'));
	
	$GLOBALS['xoops']->load('xoopscache');
	if (!class_exists('XoopsCache')) {
		// XOOPS 2.4 Compliance
		$GLOBALS['xoops']->load('cache');
		if (!class_exists('XoopsCache')) {
			include_once $GLOBALS['xoops']->root_path.'/class/cache/xoopscache.php';
		}
	}
	
	
	
	$ipdata = xortify_getIPData(false);
	if ($ipdata['ip4']!=$GLOBALS['xoops']->config['my_ip']&&$ipdata['ip6']!=$GLOBALS['xoops']->config['my_ip']) {
		$checked = XoopsCache::read('xortify_sfs_'.sha1($ipdata['uname'].$ipdata['email'].(isset($ipdata['ip4'])?$ipdata['ip4']:"").(isset($ipdata['ip6'])?$ipdata['ip6']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip4']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip6']:"").$ipdata['network-addy']));
		
		$module_handler = $GLOBALS['xoops']->getHandler('module');
		$config_handler = $GLOBALS['xoops']->getHandler('config');
		if (!isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['module'])) $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['module'] = $module_handler->getByDirname('xortify');
		if (!isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig'])) $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig'] = $config_handler->getConfigList($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['module']->getVar('mid'));
			
		if (!is_array($checked))
		{
			
			require_once( $GLOBALS['xoops']->path('/modules/xortify/class/'.$GLOBALS['xoops']->getModuleConfig('protocol', 'xortify').'.php') );
			$func = strtoupper($GLOBALS['xoops']->getModuleConfig('protocol', 'xortify')).'XortifyExchange';
			ob_start();
			$soapExchg = new $func;
			
			$result = $soapExchg->checkSFSBans($ipdata);
			
			ob_end_flush();
			
			if (is_array($result)) {
				if (isset($result['success']))
					if ($result['success']==true)
						if (($result['email']['frequency']>=$GLOBALS['xoops']->getModuleConfig('email_freq', 'xortify')||$result['username']['frequency']>=$GLOBALS['xoops']->getModuleConfig('uname_freq', 'xortify')||$result['ip']['frequency']>=$GLOBALS['xoops']->getModuleConfig('ip_freq', 'xortify')) &&
							(strtotime($result['email']['lastseen'])>time()-$GLOBALS['xoops']->getModuleConfig('email_lastseen', 'xortify')||strtotime($result['username']['lastseen'])>time()-$GLOBALS['xoops']->getModuleConfig('uname_lastseen', 'xortify')||strtotime($result['ip']['lastseen'])>time()-$GLOBALS['xoops']->getModuleConfig('ip_lastseen', 'xortify'))) {
								
								XoopsCache::write('xortify_sfs_'.sha1($ipdata['uname'].$ipdata['email'].(isset($ipdata['ip4'])?$ipdata['ip4']:"").(isset($ipdata['ip6'])?$ipdata['ip6']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip4']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip6']:"").$ipdata['network-addy']), array_merge($result, array('ipdata' => $ipdata)), $GLOBALS['xoops']->getModuleConfig('xortify_ip_cache', 'xortify'));					
								
								$GLOBALS['xoops']->loadLanguage('ban', 'xortify');
								
								$result = $soapExchg->sendBan(_XOR_BAN_SFS_TYPE."\n".
															  _XOR_BAN_SFS_EMAIL_FREQ.': '.$result['email']['frequency'].' >= '.$GLOBALS['xoops']->getModuleConfig('email_freq', 'xortify'). "\n".
															  _XOR_BAN_SFS_EMAIL_LASTSEEN.': '.date(_DATESTRING, strtotime($result['email']['lastseen'])) . ' > ' . date(_DATESTRING, time()-$GLOBALS['xoops']->getModuleConfig('email_lastseen', 'xortify')) . "\n" .
															  _XOR_BAN_SFS_USERNAME_FREQ.': '.$result['username']['frequency'].' >= '.$GLOBALS['xoops']->getModuleConfig('uname_freq', 'xortify'). "\n".
															  _XOR_BAN_SFS_USERNAME_LASTSEEN.': '.date(_DATESTRING, strtotime($result['username']['lastseen'])) . ' > ' . date(_DATESTRING, time()-$GLOBALS['xoops']->getModuleConfig('uname_lastseen', 'xortify')) . "\n" .
															  _XOR_BAN_SFS_IP_FREQ.': '.$result['ip']['frequency'].' >= '.$GLOBALS['xoops']->getModuleConfig('ip_freq', 'xortify'). "\n".
															  _XOR_BAN_SFS_IP_LASTSEEN.': '.date(_DATESTRING, strtotime($result['ip']['lastseen'])) . ' > ' . date(_DATESTRING, time()-$GLOBALS['xoops']->getModuleConfig('ip_lastseen', 'xortify')), 4, $ipdata);
	 						    
								$log_handler = $GLOBALS['xoops']->getModuleHandler('log', 'xortify');
								$log = $log_handler->create();
								$log->setVars($ipdata);
								$log->setVar('provider', basename(dirname(__FILE__)));
								$log->setVar('action', 'banned');
								$log->setVar('extra', _XOR_BAN_SFS_TYPE."\n".
													  _XOR_BAN_SFS_EMAIL_FREQ.': '.$result['email']['frequency'].' >= '.$GLOBALS['xoops']->getModuleConfig('email_freq', 'xortify'). "\n".
													  _XOR_BAN_SFS_EMAIL_LASTSEEN.': '.date(_DATESTRING, strtotime($result['email']['lastseen'])) . ' > ' . date(_DATESTRING, time()-$GLOBALS['xoops']->getModuleConfig('email_lastseen', 'xortify')) . "\n" .
													  _XOR_BAN_SFS_USERNAME_FREQ.': '.$result['username']['frequency'].' >= '.$GLOBALS['xoops']->getModuleConfig('uname_freq', 'xortify'). "\n".
													  _XOR_BAN_SFS_USERNAME_LASTSEEN.': '.date(_DATESTRING, strtotime($result['username']['lastseen'])) . ' > ' . date(_DATESTRING, time()-$GLOBALS['xoops']->getModuleConfig('uname_lastseen', 'xortify')) . "\n" .
													  _XOR_BAN_SFS_IP_FREQ.': '.$result['ip']['frequency'].' >= '.$GLOBALS['xoops']->getModuleConfig('ip_freq', 'xortify'). "\n".
													  _XOR_BAN_SFS_IP_LASTSEEN.': '.date(_DATESTRING, strtotime($result['ip']['lastseen'])) . ' > ' . date(_DATESTRING, time()-$GLOBALS['xoops']->getModuleConfig('ip_lastseen', 'xortify')));
								$lid = $log_handler->insert($log, true);
								XoopsCache::write('xortify_core_include_common_end', array('time'=>microtime(true)), $GLOBALS['xoops']->getModuleConfig('fault_delay', 'xortify'));
								$_SESSION['xortify']['lid'] = $lid;
								setcookie('xortify', array('lid' => $lid), time()+3600*24*7*4*3);
								header('Location: '.XOOPS_URL.'/banned.php');
								exit(0);
								
							}
				XoopsCache::write('xortify_sfs_'.sha1($ipdata['uname'].$ipdata['email'].(isset($ipdata['ip4'])?$ipdata['ip4']:"").(isset($ipdata['ip6'])?$ipdata['ip6']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip4']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip6']:"").$ipdata['network-addy']), array_merge($result, array('ipdata' => $ipdata)), $GLOBALS['xoops']->getModuleConfig('xortify_ip_cache', 'xortify'));
				$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['_pass'] = true;
			}		
		} elseif (isset($checked['success'])&&$checked['success']==true) {
			if (($checked['email']['frequency']>=2||$checked['username']['frequency']>=2||$checked['ip']['frequency']>=2) &&
				(strtotime($checked['email']['lastseen'])>time()-(60*60*24*7*4*3)||strtotime($checked['username']['lastseen'])>time()-(60*60*24*7*4*3)||strtotime($checked['ip']['lastseen'])>time()-(60*60*24*7*4*3))) {
									
					$GLOBALS['xoops']->loadLanguage('ban', 'xortify');
					
					$log_handler = $GLOBALS['xoops']->getModuleHandler('log', 'xortify');
					$log = $log_handler->create();
					$log->setVars($checked['ipdata']);
					$log->setVar('provider', basename(dirname(__FILE__)));
					$log->setVar('action', 'blocked');
					$log->setVar('extra', _XOR_BAN_SFS_TYPE."\n".
										  _XOR_BAN_SFS_EMAIL_FREQ.': '.$checked['email']['frequency'].' >= '.$GLOBALS['xoops']->getModuleConfig('email_freq', 'xortify'). "\n".
										  _XOR_BAN_SFS_EMAIL_LASTSEEN.': '.date(_DATESTRING, strtotime($checked['email']['lastseen'])) . ' > ' . date(_DATESTRING, time()-$GLOBALS['xoops']->getModuleConfig('email_lastseen', 'xortify')) . "\n" .
										  _XOR_BAN_SFS_USERNAME_FREQ.': '.$checked['username']['frequency'].' >= '.$GLOBALS['xoops']->getModuleConfig('uname_freq', 'xortify'). "\n".
										  _XOR_BAN_SFS_USERNAME_LASTSEEN.': '.date(_DATESTRING, strtotime($checked['username']['lastseen'])) . ' > ' . date(_DATESTRING, time()-$GLOBALS['xoops']->getModuleConfig('uname_lastseen', 'xortify')) . "\n" .
										  _XOR_BAN_SFS_IP_FREQ.': '.$checked['ip']['frequency'].' >= '.$GLOBALS['xoops']->getModuleConfig('ip_freq', 'xortify'). "\n".
										  _XOR_BAN_SFS_IP_LASTSEEN.': '.date(_DATESTRING, strtotime($checked['ip']['lastseen'])) . ' > ' . date(_DATESTRING, time()-$GLOBALS['xoops']->getModuleConfig('ip_lastseen', 'xortify')));
					$lid = $log_handler->insert($log, true);
					XoopsCache::write('xortify_core_include_common_end', array('time'=>microtime(true)), $GLOBALS['xoops']->getModuleConfig('fault_delay', 'xortify'));
					$_SESSION['xortify']['lid'] = $lid;
					setcookie('xortify', array('lid' => $lid), time()+3600*24*7*4*3);
					header('Location: '.XOOPS_URL.'/banned.php');
					exit(0);
					
			}
		}
	}
?>