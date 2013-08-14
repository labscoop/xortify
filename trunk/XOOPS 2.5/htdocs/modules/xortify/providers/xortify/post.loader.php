<?php
/*
 * Prevents Spam, Harvesting, Human Rights Abuse, Captcha Abuse etc.
* basic statistic of them in XOOPS Copyright (C) 2012 Simon Roberts
* Contact: wishcraft - simon@chronolabs.com.au
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
* Download:		http://code.google.com/p/chronolabs
* This File:	post.loader.php
* Description:	Xortify Post Loader provider for client
* Date:			09/09/2012 19:34 AEST
* License:		GNU3
*
*/
	
	$checkfields = array('uname', 'email', 'ip4', 'ip6', 'network-addy');
	
	$module_handler = xoops_gethandler('module');
	$config_handler = xoops_gethandler('config');
	if (!is_object($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module']))
		$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module'] = $module_handler->getByDirname('xortify');
	if (!isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']))
		$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'] = $config_handler->getConfigList($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module']->getVar('mid'));
	
	require_once( XOOPS_ROOT_PATH.'/modules/xortify/class/'.$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['protocol'].'.php' );
	$func = strtoupper($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['protocol']).'XortifyExchange';
	$apiExchange = new $func;
	$bans = $apiExchange->getBans();
	
	if (is_object($GLOBALS['xoopsUser'])) {
		$uid = $GLOBALS['xoopsUser']->getVar('uid');
		$uname = $GLOBALS['xoopsUser']->getVar('uname');
		$email = $GLOBALS['xoopsUser']->getVar('email');
	} else {
		$uid = 0;
		$uname = (isset($_REQUEST['uname'])?$_REQUEST['uname']:'');
		$email = (isset($_REQUEST['email'])?$_REQUEST['email']:'');
	}
	
	xoops_load('xoopscache');
	if (!class_exists('XoopsCache')) {
		// XOOPS 2.4 Compliance
		xoops_load('cache');
		if (!class_exists('XoopsCache')) {
			include_once XOOPS_ROOT_PATH.'/class/cache/xoopscache.php';
		}
	}
	
	if (!$ipdata = XoopsCache::read('xortify_php_'.sha1($_SERVER['REMOTE_ADDR'].(isset($_SERVER['HTTP_X_FORWARDED_FOR'])?$_SERVER['HTTP_X_FORWARDED_FOR']:'').$uid.$uname.$email))) {
		$ipdata = xortify_getIPData(false);
		XoopsCache::write('xortify_php_'.sha1($_SERVER['REMOTE_ADDR'].(isset($_SERVER['HTTP_X_FORWARDED_FOR'])?$_SERVER['HTTP_X_FORWARDED_FOR']:'').$uid.$uname.$email), $ipdata, $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['xortify_ip_cache']);
	}
	
	if (isset($ipdata['ip4']))
		if ($ipdata['ip4']==$GLOBALS['xoopsConfig']['my_ip'])
			return false;
			
	if (isset($ipdata['ip6']))
		if ($ipdata['ip6']==$GLOBALS['xoopsConfig']['my_ip']) 
			return false;
	
	if (is_array($bans['data'])&&count($bans['data'])>0) {
		foreach ($bans['data'] as $id => $ban) {
			foreach($ipdata as $key => $ip) {
				if (isset($ban[$key])&&!empty($ban[$key])&&!empty($ip)) {
					if (in_array($key, $checkfields)) {
						if ($ban[$key] == $ip) {
							xoops_loadLanguage('ban', 'xortify');
							
							$log_handler = xoops_getmodulehandler('log', 'xortify');
							$log = $log_handler->create();
							$log->setVars($ipdata);
							$log->setVar('provider', basename(dirname(__FILE__)));
							$log->setVar('action', 'blocked');
							$log->setVar('extra', _XOR_BAN_XORT_KEY.' '.$key.'<br/>'.
												  _XOR_BAN_XORT_MATCH.' ('.$key.') '.$ban[$key].' == '.$ip.'<br/>'.
												  _XOR_BAN_XORT_LENGTH.' '.strlen($ban[$key]).' == '.strlen($ip));
							
							$lid = $log_handler->insert($log, true);
							XoopsCache::write('xortify_core_include_common_end', array('time'=>microtime(true)), $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['fault_delay']);
							$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['lid'] = $lid;
							setcookie('xortify', array('lid' => $lid), time()+3600*24*7*4*3);
							header('Location: '.XOOPS_URL.'/banned.php');
							exit(0);
						}
					}
				}
			}
		}
		unlinkOldCachefiles('xortify_',$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['xortify_ip_cache']);
		$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['_pass'] = true;
	}
	
	if (!$checked = XoopsCache::read('xortify_xrt_'.sha1($ipdata['uname'].$ipdata['email'].(isset($ipdata['ip4'])?$ipdata['ip4']:"").(isset($ipdata['ip6'])?$ipdata['ip6']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip4']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip6']:"").$ipdata['network-addy'])))
	{
		$checked = $apiExchange->checkBanned($ipdata);
		XoopsCache::write('xortify_xrt_'.sha1($ipdata['uname'].$ipdata['email'].(isset($ipdata['ip4'])?$ipdata['ip4']:"").(isset($ipdata['ip6'])?$ipdata['ip6']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip4']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip6']:"").$ipdata['network-addy']), array_merge($checked, array('ipdata' => $ipdata)), $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['xortify_ip_cache']);
	}
	
	if (isset($checked['count'])) {
		if ($checked['count']>0) {
			foreach ($checked['bans'] as $id => $ban)
				foreach($ipdata as $key => $ip)
					if (in_array($key, $checkfields))
						if (isset($ban[$key])&&!empty($ban[$key])&&!empty($ip)) 
							if ($ban[$key] == $ip) {
								xoops_loadLanguage('ban', 'xortify');
								
								$log_handler = xoops_getmodulehandler('log', 'xortify');
								$log = $log_handler->create();
								$log->setVars($ipdata);
								$log->setVar('provider', basename(dirname(__FILE__)));
								$log->setVar('action', 'blocked');
								$log->setVar('extra', _XOR_BAN_XORT_KEY.' '.$key.'<br/>'.
													  _XOR_BAN_XORT_MATCH.' '.$ban[$key].' == '.$ip.'<br/>'.
													  _XOR_BAN_XORT_LENGTH.' '.strlen($ban[$key]).' == '.strlen($ip));
								
								include_once XOOPS_ROOT_PATH."/include/common.php";
						
								$lid = $log_handler->insert($log, true);
								XoopsCache::write('xortify_core_include_common_end', array('time'=>microtime(true)), $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['fault_delay']);
								$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['lid'] = $lid;
								setcookie('xortify', array('lid' => $lid), time()+3600*24*7*4*3);
								header('Location: '.XOOPS_URL.'/banned.php');
								exit(0);
							
							}		
		}
		unlinkOldCachefiles('xortify_',$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['xortify_ip_cache']);
		$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['_pass'] = true;
	}
	
	if (isset($_REQUEST['xortify_check'])) {
		foreach ($_REQUEST['xortify_check'] as $id => $field) {
			$field = str_replace('[]', '', $field);
			if (is_array($_REQUEST[$field])) {
				foreach ($_REQUEST[$field] as $id => $data) {
					$result = $apiExchange->checkForSpam($data);
					if ($result['spam']==true) {
					
						if (isset($_COOKIE['xortify']['spams']))
							$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['spams'] = $_COOKIE['xortify']['spams'];
						$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['spams'] = $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['spams'] + 1;
						unset($_COOKIE['xortify']['spams']);
						setcookie('xortify', array_merge($_COOKIE['xortify'],array('spams' => $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['spams'])), time()+3600*24*7*4*3);
					
						xoops_loadLanguage('ban', 'xortify');
					
						if ($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['spams']>=$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['spams_allowed']) {
					
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
							XoopsCache::write('xortify_core_include_common_end', array('time'=>microtime(true)), $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['fault_delay']);
							$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['lid'] = $lid;
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
							
								
							xoops_loadLanguage('ban', 'xortify');
								
							$module_handler = xoops_gethandler('module');
							$GLOBALS['xortifyModule'] = $module_handler->getByDirname('xortify');
								
							$xoopsOption['template_main'] = 'xortify_spamming_notice.html';
							include_once XOOPS_ROOT_PATH.'/header.php';
					
							addmeta_googleanalytics(_XOR_MI_XOOPS_GOOGLE_ANALYTICS_ACCOUNTID_FAILEDTOPASS, $_SERVER['HTTP_HOST']);
							if (defined('_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_FAILEDTOPASS')&&strlen(constant('_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_FAILEDTOPASS'))>=13) {
								addmeta_googleanalytics(_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_FAILEDTOPASS, $_SERVER['HTTP_HOST']);
							}
								
							$GLOBALS['xoopsTpl']->assign('xoops_pagetitle', _XOR_SPAM_PAGETITLE);
							$GLOBALS['xoopsTpl']->assign('description', _XOR_SPAM_DESCRIPTION);
							$GLOBALS['xoopsTpl']->assign('version', $GLOBALS['xortifyModule']->getVar('version')/100);
							$GLOBALS['xoopsTpl']->assign('platform', XOOPS_VERSION);
							$GLOBALS['xoopsTpl']->assign('provider', basename(dirname(__FILE__)));
							$GLOBALS['xoopsTpl']->assign('spam', htmlspecialchars($data));
							$GLOBALS['xoopsTpl']->assign('agent', $_SERVER['HTTP_USER_AGENT']);
								
							$GLOBALS['xoopsTpl']->assign('xoops_lblocks', false);
							$GLOBALS['xoopsTpl']->assign('xoops_rblocks', false);
							$GLOBALS['xoopsTpl']->assign('xoops_ccblocks', false);
							$GLOBALS['xoopsTpl']->assign('xoops_clblocks', false);
							$GLOBALS['xoopsTpl']->assign('xoops_crblocks', false);
							$GLOBALS['xoopsTpl']->assign('xoops_showlblock', false);
							$GLOBALS['xoopsTpl']->assign('xoops_showrblock', false);
							$GLOBALS['xoopsTpl']->assign('xoops_showcblock', false);
								
							include_once XOOPS_ROOT_PATH.'/footer.php';
						}
						exit(0);
					}
				}
			} else {
				$result = $apiExchange->checkForSpam($_REQUEST[$field], is_group(user_groups(), $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['allow_adult']));
				if ($result['spam']==true) {
					
					if (isset($_COOKIE['xortify']['spams']))
						$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['spams'] = $_COOKIE['xortify']['spams'];
					$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['spams'] = $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['spams'] + 1;
					setcookie('xortify', array('spams' => $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['spams']), time()+3600*24*7*4*3);
					
					xoops_loadLanguage('ban', 'xortify');
									
					if ($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['spams']>=$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['spams_allowed']) {
							
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
						XoopsCache::write('xortify_core_include_common_end', array('time'=>microtime(true)), $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['fault_delay']);
						$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['lid'] = $lid;
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
												
						$module_handler = xoops_gethandler('module');
						$GLOBALS['xortifyModule'] = $module_handler->getByDirname('xortify');
						
						$xoopsOption['template_main'] = 'xortify_spamming_notice.html';
						include_once XOOPS_ROOT_PATH.'/header.php';
	
						addmeta_googleanalytics(_XOR_MI_XOOPS_GOOGLE_ANALYTICS_ACCOUNTID_FAILEDTOPASS, $_SERVER['HTTP_HOST']);
						if (defined('_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_FAILEDTOPASS')&&strlen(constant('_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_FAILEDTOPASS'))>=13) {
							addmeta_googleanalytics(_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_FAILEDTOPASS, $_SERVER['HTTP_HOST']);
						}
						
						$GLOBALS['xoopsTpl']->assign('xoops_pagetitle', _XOR_SPAM_PAGETITLE);
						$GLOBALS['xoopsTpl']->assign('description', _XOR_SPAM_DESCRIPTION);
						$GLOBALS['xoopsTpl']->assign('version', $GLOBALS['xortifyModule']->getVar('version')/100);
						$GLOBALS['xoopsTpl']->assign('platform', XOOPS_VERSION);
						$GLOBALS['xoopsTpl']->assign('provider', basename(dirname(__FILE__)));
						$GLOBALS['xoopsTpl']->assign('spam', htmlspecialchars($_REQUEST[$field]));
						$GLOBALS['xoopsTpl']->assign('agent', $_SERVER['HTTP_USER_AGENT']);
						
						$GLOBALS['xoopsTpl']->assign('xoops_lblocks', false);
						$GLOBALS['xoopsTpl']->assign('xoops_rblocks', false);
						$GLOBALS['xoopsTpl']->assign('xoops_ccblocks', false);
						$GLOBALS['xoopsTpl']->assign('xoops_clblocks', false);
						$GLOBALS['xoopsTpl']->assign('xoops_crblocks', false);
						$GLOBALS['xoopsTpl']->assign('xoops_showlblock', false);
						$GLOBALS['xoopsTpl']->assign('xoops_showrblock', false);
						$GLOBALS['xoopsTpl']->assign('xoops_showcblock', false);
						
						include_once XOOPS_ROOT_PATH.'/footer.php';
					}
					exit(0);
				}
			}
		}
	}
	
?>