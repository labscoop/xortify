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
	
	require_once( _RUN_XORTIFY_ROOT_PATH . '/class/'._RUN_XORTIFY_PROTOCOL.'.php' );
	$func = strtoupper(_RUN_XORTIFY_PROTOCOL).'XortifyExchange';
	$apiExchange = new $func;
	$bans = $apiExchange->getBans();
	
	if (isset($_SESSION['xortify']['uname']) && isset($_SESSION['xortify']['uid']) && isset($_SESSION['xortify']['email'])) {
		$uid = $_SESSION['xortify']['uid'];
		$uname = $_SESSION['xortify']['uname'];
		$email = $_SESSION['xortify']['email'];
	} else {
		$uid = 0;
		$uname = (isset($_REQUEST['uname'])?$_REQUEST['uname']:'');
		$email = (isset($_REQUEST['email'])?$_REQUEST['email']:'');
	}
	
	include_once _RUN_XORTIFY_ROOT_PATH.'/class/cache/cache.php';
	
	if (!$ipdata = Cache::read('xortify_php_'.sha1(xortify_getIP(true).$uid.$uname.$email))) {
		$ipdata = xortify_getIPData();
		Cache::write('xortify_php_'.sha1(xortify_getIP(true).$uid.$uname.$email), $ipdata, _RUN_XORTIFY_IPCACHE);
	}
	
	if (isset($ipdata['ip4']))
		if ($ipdata['ip4']==_RUN_XORTIFY_MYIPv4_ADDRESS)
			return false;
			
	if (isset($ipdata['ip6']))
		if ($ipdata['ip6']==_RUN_XORTIFY_MYIPv6_ADDRESS) 
			return false;
	
	if (is_array($bans['data'])&&count($bans['data'])>0) {
		foreach ($bans['data'] as $id => $ban) {
			foreach($ipdata as $key => $ip) {
				if (isset($ban[$key])&&!empty($ban[$key])&&!empty($ip)) {
					if (in_array($key, $checkfields)) {
						if ($ban[$key] == $ip) {
							
							Cache::write('xortify_core_include_common_end', array('time'=>microtime(true)), _RUN_XORTIFY_FAULTDELAY);
							setcookie('xortify', array('lid' => $lid), time()+3600*24*7*4*3);
							header('Location: '._RUN_XORTIFY_URL.'/banned.php');
							exit(0);
						}
					}
				}
			}
		}
		unlinkOldCachefiles('xortify_',_RUN_XORTIFY_IPCACHE);
		$_SESSION['xortify']['_pass'] = true;
	}
	
	if (!$checked = Cache::read('xortify_xrt_'.sha1($ipdata['uname'].$ipdata['email'].(isset($ipdata['ip4'])?$ipdata['ip4']:"").(isset($ipdata['ip6'])?$ipdata['ip6']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip4']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip6']:"").$ipdata['network-addy'])))
	{
		$checked = $apiExchange->checkBanned($ipdata);
		Cache::write('xortify_xrt_'.sha1($ipdata['uname'].$ipdata['email'].(isset($ipdata['ip4'])?$ipdata['ip4']:"").(isset($ipdata['ip6'])?$ipdata['ip6']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip4']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip6']:"").$ipdata['network-addy']), array_merge($checked, array('ipdata' => $ipdata)), _RUN_XORTIFY_IPCACHE);
	}
	
	if (isset($checked['count'])) {
		if ($checked['count']>0) {
			foreach ($checked['bans'] as $id => $ban)
				foreach($ipdata as $key => $ip)
					if (in_array($key, $checkfields))
						if (isset($ban[$key])&&!empty($ban[$key])&&!empty($ip)) 
							if ($ban[$key] == $ip) {
								
								include_once XOOPS_ROOT_PATH."/include/common.php";
						
								Cache::write('xortify_core_include_common_end', array('time'=>microtime(true)), $_SESSION['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['fault_delay']);
								setcookie('xortify', array('banned' => true), time()+3600*24*7*4*3);
								header('Location: '._RUN_XORTIFY_URL.'/banned.php');
								exit(0);
							
							}		
		}
		unlinkOldCachefiles('xortify_',_RUN_XORTIFY_IPCACHE);
		$_SESSION['xortify']['_pass'] = true;
	}
	
	if (isset($_REQUEST['xortify_check'])) {
		foreach ($_REQUEST['xortify_check'] as $id => $field) {
			$field = str_replace('[]', '', $field);
			if (is_array($_REQUEST[$field])) {
				foreach ($_REQUEST[$field] as $id => $data) {
					$result = $apiExchange->checkForSpam($data);
					if ($result['spam']==true) {
					
						setcookie('xortify', array_merge($_COOKIE['xortify'],array('spams' => 1)), time()+3600*24*7*4*3);
					
						if (_RUN_XORTIFY_SPAMS_ALLOWED<=$_COOKIE['xortify']['spams']) {
					
							$results[] = $apiExchange->sendBan(array('comment'=>_XOR_SPAM . ' :: [' . $data . '] len('.strlen($data).')'), 2, xortify_getIPData());
					
							$_SESSION['xortify']['xoops_pagetitle'] = (_RUN_XORITIFY_SPAM_PAGETITLE);
							$_SESSION['xortify']['description'] = (_RUN_XORITIFY_SPAM_DESCRIPTION);
							$_SESSION['xortify']['version'] = (_RUN_XORTIFY_VERSION);
							$_SESSION['xortify']['provider'] = (basename(dirname(__FILE__)));
							$_SESSION['xortify']['spam'] = (htmlspecialchars($_REQUEST[$field]));
							$_SESSION['xortify']['agent'] = ($_SERVER['HTTP_USER_AGENT']);
							$_SESSION['xortify']['left'] = _RUN_XORTIFY_SPAMS_ALLOWED - $_COOKIE['xortify']['spams'];

							Cache::write('xortify_core_include_common_end', array('time'=>microtime(true)), $_SESSION['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['fault_delay']);
							setcookie('xortify', array('banned' => true), time()+3600*24*7*4*3);
							header('Location: '._RUN_XORTIFY_URL.'/banned.php');
							exit(0);
								
						} else {
							
							$_SESSION['xortify']['xoops_pagetitle'] = (_RUN_XORITIFY_SPAM_PAGETITLE);
							$_SESSION['xortify']['description'] = (_RUN_XORITIFY_SPAM_DESCRIPTION);
							$_SESSION['xortify']['version'] = (_RUN_XORTIFY_VERSION);
							$_SESSION['xortify']['provider'] = (basename(dirname(__FILE__)));
							$_SESSION['xortify']['spam'] = (htmlspecialchars($_REQUEST[$field]));
							$_SESSION['xortify']['agent'] = ($_SERVER['HTTP_USER_AGENT']);
							$_SESSION['xortify']['left'] = _RUN_XORTIFY_SPAMS_ALLOWED - $_COOKIE['xortify']['spams'];
							include_once XOOPS_ROOT_PATH.'/spam.php';
						}
						exit(0);
					}
				}
			} else {
				$result = $apiExchange->checkForSpam($_REQUEST[$field], 1, _RUN_XORTIFY_SPAMS_ALLOWADULT);
				if ($result['spam']==true) {
					
					setcookie('xortify', array('spams' => $_COOKIE['xortify']['spams']+1), time()+3600*24*7*4*3);
									
					if (_RUN_XORTIFY_SPAMS_ALLOWED<=$_COOKIE['xortify']['spams']) {
							
						$results[] = $apiExchange->sendBan(array('comment'=>__RUN_XORITFY_SPAM . ' :: [' . $_REQUEST[$field] . '] len('.strlen($_REQUEST[$field]).')'), 2, xortify_getIPData(true));
							
						$_SESSION['xortify']['xoops_pagetitle'] = (_RUN_XORITIFY_SPAM_PAGETITLE);
						$_SESSION['xortify']['description'] = (_RUN_XORITIFY_SPAM_DESCRIPTION);
						$_SESSION['xortify']['version'] = (_RUN_XORTIFY_VERSION);
						$_SESSION['xortify']['provider'] = (basename(dirname(__FILE__)));
						$_SESSION['xortify']['spam'] = (htmlspecialchars($_REQUEST[$field]));
						$_SESSION['xortify']['agent'] = ($_SERVER['HTTP_USER_AGENT']);
						$_SESSION['xortify']['left'] = _RUN_XORTIFY_SPAMS_ALLOWED - $_COOKIE['xortify']['spams'];
						
						
						Cache::write('xortify_core_include_common_end', array('time'=>microtime(true)), $_SESSION['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['fault_delay']);
						setcookie('xortify', array('banned' => true), time()+3600*24*7*4*3);
						header('Location: '._RUN_XORTIFY_URL.'/banned.php');
						exit(0);
						
					} else {

						$_SESSION['xortify']['xoops_pagetitle'] = (_RUN_XORITIFY_SPAM_PAGETITLE);
						$_SESSION['xortify']['description'] = (_RUN_XORITIFY_SPAM_DESCRIPTION);
						$_SESSION['xortify']['version'] = (_RUN_XORTIFY_VERSION);
						$_SESSION['xortify']['provider'] = (basename(dirname(__FILE__)));
						$_SESSION['xortify']['spam'] = (htmlspecialchars($_REQUEST[$field]));
						$_SESSION['xortify']['agent'] = ($_SERVER['HTTP_USER_AGENT']);
						$_SESSION['xortify']['left'] = _RUN_XORTIFY_SPAMS_ALLOWED - $_COOKIE['xortify']['spams'];
						
						header('Location: '._RUN_XORTIFY_URL.'/spam.php');
						
					}
					exit(0);
				}
			}
		}
	}
	
?>