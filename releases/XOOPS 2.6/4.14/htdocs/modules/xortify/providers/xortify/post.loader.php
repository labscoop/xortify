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
 * @description:	Post Loader Routines for Xortify Provider
 * @date:			09/09/2012 19:34 AEST
 * @license:		GNU3 (http://web.labs.coop/public/legal/general-public-licence/13,3.html)
 * @package			xortify
 * @subpackage		security-providers
 * @author			Simon A. Roberts - wishcraft (simon@labs.coop)

 * 
 */


	
	$GLOBALS['xoops'] = Xoops::getInstance();	
	include_once($GLOBALS['xoops']->path("/include/common.php"));

	$checkfields = array('uname', 'email', 'ip4', 'ip6', 'network-addy');
	
	require_once( $GLOBALS['xoops']->path('/modules/xortify/class/'.$GLOBALS['xoops']->getModuleConfig('protocol', 'xortify').'.php') );
	$func = strtoupper($GLOBALS['xoops']->getModuleConfig('protocol', 'xortify')).'XortifyExchange';
	$soapExchg = new $func;
	$bans = $soapExchg->getBans();
	
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
		
	if (is_array($bans['data'])&&count($bans['data'])>0) {
		foreach ($bans['data'] as $id => $ban)
			foreach($ipdata as $key => $ip)
				echo "$ban[$key] == $ip";
				if (!empty($ip)&&isset($ban[$key]))
					if (in_array($key, $checkfields))
						if ($ban[$key] == $ip) {
		
							$GLOBALS['xoops']->loadLanguage('ban', 'xortify');
							
							$log_handler = $GLOBALS['xoops']->getModuleHandler('log', 'xortify');
							$log = $log_handler->create();
							$log->setVars($checked['ipdata']);
							$log->setVar('provider', basename(dirname(__FILE__)));
							$log->setVar('action', 'blocked');
							$log->setVar('extra', _XOR_BAN_XORT_KEY.' '.$key.'<br/>'.
												  _XOR_BAN_XORT_MATCH.' ('.$key.') '.$ban[$key].' == '.$ip.'<br/>'.
												  _XOR_BAN_XORT_LENGTH.' '.strlen($ban[$key]).' == '.strlen($ip));
							
							$lid = $log_handler->insert($log, true);
							XoopsCache::write('xortify_core_include_common_end', array('time'=>microtime(true)), $GLOBALS['xoops']->getModuleConfig('fault_delay', 'xortify'));
							$_SESSION['xortify']['lid'] = $lid;
							setcookie('xortify', array('lid' => $lid), time()+3600*24*7*4*3);
							header('Location: '.XOOPS_URL.'/banned.php');
							exit(0);			
						}
		$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['_pass'] = true;
	}
	if (!$checked = XoopsCache::read('xortify_xrt_'.sha1($ipdata['uname'].$ipdata['email'].(isset($ipdata['ip4'])?$ipdata['ip4']:"").(isset($ipdata['ip6'])?$ipdata['ip6']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip4']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip6']:"").$ipdata['network-addy'])))
	{
		$checked = $soapExchg->checkBanned($ipdata);
		XoopsCache::write('xortify_xrt_'.sha1($ipdata['uname'].$ipdata['email'].(isset($ipdata['ip4'])?$ipdata['ip4']:"").(isset($ipdata['ip6'])?$ipdata['ip6']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip4']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip6']:"").$ipdata['network-addy']), array_merge($checked, array('ipdata' => $ipdata)), $GLOBALS['xoops']->getModuleConfig('xortify_ip_cache', 'xortify'));
	}

	if (isset($checked['count'])) {
		if ($checked['count']>0) {
			foreach ($checked['bans'] as $id => $ban)
				foreach($ipdata as $key => $ip)
					if (!empty($ip)&&isset($ban[$key]))
						if (in_array($key, $checkfields))
							if ($ban[$key] == $ip) {
								
								$GLOBALS['xoops']->loadLanguage('ban', 'xortify');
								
								$log_handler = $GLOBALS['xoops']->getModuleHandler('log', 'xortify');
								$log = $log_handler->create();
								$log->setVars($checked['ipdata']);
								$log->setVar('provider', basename(dirname(__FILE__)));
								$log->setVar('action', 'blocked');
								$log->setVar('extra', _XOR_BAN_XORT_KEY.' '.$key.'<br/>'.
													  _XOR_BAN_XORT_MATCH.' '.$ban[$key].' == '.$ip.'<br/>'.
													  _XOR_BAN_XORT_LENGTH.' '.strlen($ban[$key]).' == '.strlen($ip));
								
								$lid = $log_handler->insert($log, true);
								XoopsCache::write('xortify_core_include_common_end', array('time'=>microtime(true)), $GLOBALS['xoops']->getModuleConfig('fault_delay', 'xortify'));
								$_SESSION['xortify']['lid'] = $lid;
								setcookie('xortify', array('lid' => $lid), time()+3600*24*7*4*3);
								header('Location: '.XOOPS_URL.'/banned.php');
								exit(0);
							}		
		}
	}
	
	$GLOBALS['xoops'] = Xoops::getInstance();
	
	if (isset($_REQUEST['xortify_check'])) {
		foreach ($_REQUEST['xortify_check'] as $id => $field) {
			$field = str_replace('[]', '', $field);
			if (is_array($_REQUEST[$field])) {
				foreach ($_REQUEST[$field] as $id => $data) {
					$result = $apiExchange->checkForSpam($data);
					if ($result['spam']==true) {
							
						if (isset($_COOKIE['xortify']['spams']))
							$_SESSION['xortify'][XORTIFY_INSTANCE_KEY]['spams'] = $_COOKIE['xortify']['spams'];
						$_SESSION['xortify'][XORTIFY_INSTANCE_KEY]['spams'] = $_SESSION['xortify'][XORTIFY_INSTANCE_KEY]['spams'] + 1;
						unset($_COOKIE['xortify']['spams']);
						setcookie('xortify', array_merge($_COOKIE['xortify'],array('spams' => $_SESSION['xortify'][XORTIFY_INSTANCE_KEY]['spams'])), time()+3600*24*7*4*3);
							
						$GLOBALS['xoops']->loadLanguage('ban', 'xortify');
							
						if ($_SESSION['xortify'][XORTIFY_INSTANCE_KEY]['spams']>=$GLOBALS['xoops']->getModuleConfig('spams_allowed', 'xortify')) {
								
							$results[] = $apiExchange->sendBan(array('comment'=>_XOR_SPAM . ' :: [' . $data . '] len('.strlen($data).')'), 2, xortify_getIPData());
								
							$log_handler = xoops_getmodulehandler('log', 'xortify');
							$log = $log_handler->create();
							$log->setVars(xortify_getIPData($ip));
							$log->setVar('provider', basename(dirname(__FILE__)));
							$log->setVar('action', 'banned');
							$log->setVar('extra', _XOR_SPAM . ' :: [' . $data . '] len('.strlen($data).')');
							$log->setVar('agent', $_SERVER['HTTP_USER_AGENT']);
							if (isset($GLOBALS['xoopsUser'])) {
								$log->setVar('email', $GLOBALS['xoopsUser']->getVar('email'));
								$log->setVar('uname', $GLOBALS['xoopsUser']->getVar('uname'));
							}
	
							$lid = $log_handler->insert($log, true);
							XoopsCache::write('xortify_core_include_common_end', array('time'=>microtime(true)), $GLOBALS['xoops']->getModuleConfig('fault_delay', 'xortify'));
							$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY] = $lid;
							setcookie('xortify', array('lid' => $lid), time()+3600*24*7*4*3);
							header('Location: '.XOOPS_URL.'/banned.php');
							exit(0);
	
						} else {
							$log_handler = xoops_getmodulehandler('log', 'xortify');
							$log = $log_handler->create();
							$log->setVars($ipdata);
							$log->setVar('provider', basename(dirname(__FILE__)));
							$log->setVar('action', 'blocked');
							$log->setVar('extra', _XOR_SPAM . ' :: [' . $_REQUEST[$field] . '] len('.strlen($_REQUEST[$field]).')');
							if (isset($GLOBALS['xoopsUser'])) {
								$log->setVar('email', $GLOBALS['xoopsUser']->getVar('email'));
								$log->setVar('uname', $GLOBALS['xoopsUser']->getVar('uname'));
							}
							$lid = $log_handler->insert($log, true);
	
							$GLOBALS['xoops']->loadLanguage('ban', 'xortify');
	
							$module_handler = $xoops->getHandler('module');
							$GLOBALS['xortifyModule'] = $module_handler->getByDirname('xortify');
	
							$GLOBALS['xoops']->header('module:xortify|xortify_spamming_notice.html');
														
							addmeta_googleanalytics(_XOR_MI_XOOPS_GOOGLE_ANALYTICS_ACCOUNTID_FAILEDTOPASS, $_SERVER['HTTP_HOST']);
							if (defined('_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_FAILEDTOPASS')&&strlen(constant('_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_FAILEDTOPASS'))>=13) {
								addmeta_googleanalytics(_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_FAILEDTOPASS, $_SERVER['HTTP_HOST']);
							}
	
							$GLOBALS['xoops']->tpl->assign('xoops_pagetitle', _XOR_SPAM_PAGETITLE);
							$GLOBALS['xoops']->tpl->assign('description', _XOR_SPAM_DESCRIPTION);
							$GLOBALS['xoops']->tpl->assign('version', $GLOBALS['xortifyModule']->getVar('version')/100);
							$GLOBALS['xoops']->tpl->assign('platform', XOOPS_VERSION);
							$GLOBALS['xoops']->tpl->assign('provider', basename(dirname(__FILE__)));
							$GLOBALS['xoops']->tpl->assign('spam', htmlspecialchars($data));
							$GLOBALS['xoops']->tpl->assign('agent', $_SERVER['HTTP_USER_AGENT']);
	
							$GLOBALS['xoops']->tpl->assign('xoops_lblocks', false);
							$GLOBALS['xoops']->tpl->assign('xoops_rblocks', false);
							$GLOBALS['xoops']->tpl->assign('xoops_ccblocks', false);
							$GLOBALS['xoops']->tpl->assign('xoops_clblocks', false);
							$GLOBALS['xoops']->tpl->assign('xoops_crblocks', false);
							$GLOBALS['xoops']->tpl->assign('xoops_showlblock', false);
							$GLOBALS['xoops']->tpl->assign('xoops_showrblock', false);
							$GLOBALS['xoops']->tpl->assign('xoops_showcblock', false);
	
							$GLOBALS['xoops']->footer();
						}
						exit(0);
					}
				}
			} else {
				$result = $apiExchange->checkForSpam($_REQUEST[$field]);
				if ($result['spam']==true) {
						
					if (isset($_COOKIE['xortify']['spams']))
						$_SESSION['xortify'][XORTIFY_INSTANCE_KEY]['spams'] = $_COOKIE['xortify']['spams'];
					$_SESSION['xortify'][XORTIFY_INSTANCE_KEY]['spams'] = $_SESSION['xortify'][XORTIFY_INSTANCE_KEY]['spams'] + 1;
					setcookie('xortify', array('spams' => $_SESSION['xortify'][XORTIFY_INSTANCE_KEY]['spams']), time()+3600*24*7*4*3);
						
					$GLOBALS['xoops']->loadLanguage('ban', 'xortify');
						
					if ($_SESSION['xortify'][XORTIFY_INSTANCE_KEY]['spams']>=$GLOBALS['xoops']->getModuleConfig('spams_allowed', 'xortify')) {
							
						$results[] = $apiExchange->sendBan(array('comment'=>_XOR_SPAM . ' :: [' . $_REQUEST[$field] . '] len('.strlen($_REQUEST[$field]).')'), 2, xortify_getIPData());
							
						$log_handler = xoops_getmodulehandler('log', 'xortify');
						$log = $log_handler->create();
						$log->setVars(xortify_getIPData($ip));
						$log->setVar('provider', basename(dirname(__FILE__)));
						$log->setVar('action', 'banned');
						$log->setVar('extra', _XOR_SPAM . ' :: [' . $_REQUEST[$field] . '] len('.strlen($_REQUEST[$field]).')');
						$log->setVar('agent', $_SERVER['HTTP_USER_AGENT']);
						if (isset($GLOBALS['xoopsUser'])) {
							$log->setVar('email', $GLOBALS['xoopsUser']->getVar('email'));
							$log->setVar('uname', $GLOBALS['xoopsUser']->getVar('uname'));
						}
	
						$lid = $log_handler->insert($log, true);
						XoopsCache::write('xortify_core_include_common_end', array('time'=>microtime(true)), $GLOBALS['xoops']->getModuleConfig('fault_delay', 'xortify'));
						$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY] = $lid;
						setcookie('xortify', array('lid' => $lid), time()+3600*24*7*4*3);
						header('Location: '.XOOPS_URL.'/banned.php');
						exit(0);
	
					} else {
						$log_handler = xoops_getmodulehandler('log', 'xortify');
						$log = $log_handler->create();
						$log->setVars($ipdata);
						$log->setVar('provider', basename(dirname(__FILE__)));
						$log->setVar('action', 'blocked');
						$log->setVar('extra', _XOR_SPAM . ' :: [' . $_REQUEST[$field] . '] len('.strlen($_REQUEST[$field]).')');
						if (isset($GLOBALS['xoopsUser'])) {
							$log->setVar('email', $GLOBALS['xoopsUser']->getVar('email'));
							$log->setVar('uname', $GLOBALS['xoopsUser']->getVar('uname'));
						}
						$lid = $log_handler->insert($log, true);
							
						$GLOBALS['xoops']->loadLanguage('ban', 'xortify');
	
						$module_handler = $xoops->getHandler('module');
						$GLOBALS['xortifyModule'] = $module_handler->getByDirname('xortify');
	
						$GLOBALS['xoops']->header('xortify|xortify_spamming_notice.html');
							
						addmeta_googleanalytics(_XOR_MI_XOOPS_GOOGLE_ANALYTICS_ACCOUNTID_FAILEDTOPASS, $_SERVER['HTTP_HOST']);
						if (defined('_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_FAILEDTOPASS')&&strlen(constant('_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_FAILEDTOPASS'))>=13) {
							addmeta_googleanalytics(_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_FAILEDTOPASS, $_SERVER['HTTP_HOST']);
						}
	
						$GLOBALS['xoops']->tpl->assign('xoops_pagetitle', _XOR_SPAM_PAGETITLE);
						$GLOBALS['xoops']->tpl->assign('description', _XOR_SPAM_DESCRIPTION);
						$GLOBALS['xoops']->tpl->assign('version', $GLOBALS['xortifyModule']->getVar('version')/100);
						$GLOBALS['xoops']->tpl->assign('platform', XOOPS_VERSION);
						$GLOBALS['xoops']->tpl->assign('provider', basename(dirname(__FILE__)));
						$GLOBALS['xoops']->tpl->assign('spam', htmlspecialchars($_REQUEST[$field]));
						$GLOBALS['xoops']->tpl->assign('agent', $_SERVER['HTTP_USER_AGENT']);
	
						$GLOBALS['xoops']->tpl->assign('xoops_lblocks', false);
						$GLOBALS['xoops']->tpl->assign('xoops_rblocks', false);
						$GLOBALS['xoops']->tpl->assign('xoops_ccblocks', false);
						$GLOBALS['xoops']->tpl->assign('xoops_clblocks', false);
						$GLOBALS['xoops']->tpl->assign('xoops_crblocks', false);
						$GLOBALS['xoops']->tpl->assign('xoops_showlblock', false);
						$GLOBALS['xoops']->tpl->assign('xoops_showrblock', false);
						$GLOBALS['xoops']->tpl->assign('xoops_showcblock', false);
	
						$GLOBALS['xoops']->footer();
					}
					exit(0);
				}
			}
		}
	}
?>