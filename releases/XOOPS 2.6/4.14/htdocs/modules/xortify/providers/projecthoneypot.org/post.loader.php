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
 * @description:	Post Loader Routines for Project Honeypot Provider
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
		
	if (is_object($GLOBALS['xoops']->user)) {
		$uid = $GLOBALS['xoops']->user->getVar('uid');
		$uname = $GLOBALS['xoops']->user->getVar('uname');
		$email = $GLOBALS['xoops']->user->getVar('email');
	} else {
		$uid = 0;
		$uname = (isset($_REQUEST['uname'])?$_REQUEST['uname']:'');
		$email = (isset($_REQUEST['email'])?$_REQUEST['email']:'');
	}
	
	XoopsLoad::load('xoopscache');
	if (!class_exists('XoopsCache')) {
		// XOOPS 2.4 Compliance
		XoopsLoad::load('cache');
		if (!class_exists('XoopsCache')) {
			include_once $GLOBALS['xoops']->root_path.'/class/cache/xoopscache.php';
		}
	}
	
	if (!$ipdata = XoopsCache::read('xortify_php_'.sha1($_SERVER['REMOTE_ADDR'].(isset($_SERVER['HTTP_X_FORWARDED_FOR'])?$_SERVER['HTTP_X_FORWARDED_FOR']:'').$uid.$uname.$email))) {
		$ipdata = xortify_getIPData(false);
		XoopsCache::write('xortify_php_'.sha1($_SERVER['REMOTE_ADDR'].(isset($_SERVER['HTTP_X_FORWARDED_FOR'])?$_SERVER['HTTP_X_FORWARDED_FOR']:'').$uid.$uname.$email), $ipdata, $GLOBALS['xoops']->getModuleConfig('xortify_ip_cache', 'xortify'));
	}
	if ($ipdata['ip4']!=$GLOBALS['xoops']->config['my_ip']&&$ipdata['ip6']!=$GLOBALS['xoops']->config['my_ip']) {
		$checked = XoopsCache::read('xortify_php_'.sha1($ipdata['uname'].$ipdata['email'].(isset($ipdata['ip4'])?$ipdata['ip4']:"").(isset($ipdata['ip6'])?$ipdata['ip6']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip4']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip6']:"").$ipdata['network-addy']));
		
		  
		if (!is_array($checked))
		{
			require_once( $GLOBALS['xoops']->path('/modules/xortify/class/'.$GLOBALS['xoops']->getModuleConfig('protocol', 'xortify').'.php') );
			$func = strtoupper($GLOBALS['xoops']->getModuleConfig('protocol', 'xortify')).'XortifyExchange';
			ob_start();
			$soapExchg = new $func;
			$result = $soapExchg->checkPHPBans($ipdata);
			ob_end_flush();
			
			if (is_array($result)) {
				if ($result['success']==true)
					if (($result['octeta']<=$GLOBALS['xoops']->getModuleConfig('octeta', 'xortify')&&$result['octetb']>$GLOBALS['xoops']->getModuleConfig('octetb', 'xortify')&&$result['octetc']>=$GLOBALS['xoops']->getModuleConfig('octetc', 'xortify'))) {
						
						XoopsCache::write('xortify_php_'.sha1($ipdata['uname'].$ipdata['email'].(isset($ipdata['ip4'])?$ipdata['ip4']:"").(isset($ipdata['ip6'])?$ipdata['ip6']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip4']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip6']:"").$ipdata['network-addy']), array_merge($result, array('ipdata' => $ipdata)), $GLOBALS['xoops']->getModuleConfig('xortify_ip_cache', 'xortify'));					
					
						$GLOBALS['xoops']->loadLanguage('ban', 'xortify');
						
						$result = $soapExchg->sendBan(_XOR_BAN_PHP_TYPE."\n".
													  _XOR_BAN_PHP_OCTETA.' '.$result['octeta'].' <= ' . $GLOBALS['xoops']->getModuleConfig('octeta', 'xortify')."\n".
													  _XOR_BAN_PHP_OCTETB.' '.$result['octetb'].' > ' . $GLOBALS['xoops']->getModuleConfig('octetb', 'xortify')."\n".
													  _XOR_BAN_PHP_OCTETC.' '.$result['octetc'].' >= ' . $GLOBALS['xoops']->getModuleConfig('octetc', 'xortify')."\n", 5, $ipdata);
													  
						$log_handler = $GLOBALS['xoops']->getModuleHandler('log', 'xortify');
						$log = $log_handler->create();
						$log->setVars($ipdata);
						$log->setVar('provider', basename(dirname(__FILE__)));
						$log->setVar('action', 'banned');
						$log->setVar('extra', _XOR_BAN_PHP_OCTETA.' '.$result['octeta'].' <= ' . $GLOBALS['xoops']->getModuleConfig('octeta', 'xortify')."\n".
											  _XOR_BAN_PHP_OCTETB.' '.$result['octetb'].' > ' . $GLOBALS['xoops']->getModuleConfig('octetb', 'xortify')."\n".
											  _XOR_BAN_PHP_OCTETC.' '.$result['octetc'].' >= ' . $GLOBALS['xoops']->getModuleConfig('octetc', 'xortify'));
						
						$lid = $log_handler->insert($log, true);
						XoopsCache::write('xortify_core_include_common_end', array('time'=>microtime(true)), $GLOBALS['xoops']->getModuleConfig('fault_delay', 'xortify'));
						$_SESSION['xortify']['lid'] = $lid;
						setcookie('xortify_lid', $lid, time()+3600*24*7*4*3);
						header('Location: '.XOOPS_URL.'/banned.php');
						exit(0);
					
					}
			}
	
			XoopsCache::write('xortify_php_'.sha1($ipdata['uname'].$ipdata['email'].(isset($ipdata['ip4'])?$ipdata['ip4']:"").(isset($ipdata['ip6'])?$ipdata['ip6']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip4']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip6']:"").$ipdata['network-addy']), array_merge($result, array('ipdata' => $ipdata)), $GLOBALS['xoops']->getModuleConfig('xortify_seconds', 'xortify'));
			$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['_pass'] = true;
					
		} elseif (isset($checked['success'])) {
			if ($checked['success']==true) {
				if (($checked['octeta']<=$GLOBALS['xoops']->getModuleConfig('octeta', 'xortify')&&$checked['octetb']>$GLOBALS['xoops']->getModuleConfig('octetb', 'xortify')&&$checked['octetc']>=$GLOBALS['xoops']->getModuleConfig('octetc', 'xortify'))) {
					
					$GLOBALS['xoops']->loadLanguage('ban', 'xortify');
					
					$log_handler = $GLOBALS['xoops']->getModuleHandler('log', 'xortify');
					$log = $log_handler->create();
					$log->setVars($checked['ipdata']);
					$log->setVar('provider', basename(dirname(__FILE__)));
					$log->setVar('action', 'blocked');
					$log->setVar('extra', _XOR_BAN_PHP_OCTETA.' '.$checked['octeta'].' <= ' . $GLOBALS['xoops']->getModuleConfig('octeta', 'xortify')."\n".
										  _XOR_BAN_PHP_OCTETB.' '.$checked['octetb'].' > ' . $GLOBALS['xoops']->getModuleConfig('octetb', 'xortify')."\n".
										  _XOR_BAN_PHP_OCTETC.' '.$checked['octetc'].' >= ' . $GLOBALS['xoops']->getModuleConfig('octetc', 'xortify'));
					
					$lid = $log_handler->insert($log, true);
					XoopsCache::write('xortify_core_include_common_end', array('time'=>microtime(true)), $GLOBALS['xoops']->getModuleConfig('fault_delay', 'xortify'));
					$_SESSION['xortify']['lid'] = $lid;
					setcookie('xortify_lid', $lid, time()+3600*24*7*4*3);
					header('Location: '.XOOPS_URL.'/banned.php');
					exit(0);		
				}
			}
		}
	}
?>