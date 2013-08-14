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
 * Download:	http://code.google.com/p/chronolabs
 * This File:	post.loader.php		
 * Description:	Project Honeypot Provider Post Loader
 * Date:		09/09/2012 19:34 AEST
 * License:		GNU3
 * 
 */
	
	
	include_once((_RUN_XORTIFY_ROOT_PATH . '/include/functions.php'));
	
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
	
	if (!$ipdata = Cache::read('xortify_php_'.sha1($_SERVER['REMOTE_ADDR'].(isset($_SERVER['HTTP_X_FORWARDED_FOR'])?$_SERVER['HTTP_X_FORWARDED_FOR']:'').$uid.$uname.$email))) {
		$ipdata = xortify_getIPData(false);
		Cache::write('xortify_php_'.sha1($_SERVER['REMOTE_ADDR'].(isset($_SERVER['HTTP_X_FORWARDED_FOR'])?$_SERVER['HTTP_X_FORWARDED_FOR']:'').$uid.$uname.$email), $ipdata, $_SESSION['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['xortify_ip_cache']);
	}
	
	if (isset($ipdata['ip4']))
		if ($ipdata['ip4']==_RUN_XORTIFY_MYIPv4_ADDRESS)
			return false;
			
	if (isset($ipdata['ip6']))
		if ($ipdata['ip6']==_RUN_XORTIFY_MYIPv6_ADDRESS) 
			return false;
	
	$checked = Cache::read('xortify_php_'.sha1($ipdata['uname'].$ipdata['email'].(isset($ipdata['ip4'])?$ipdata['ip4']:"").(isset($ipdata['ip6'])?$ipdata['ip6']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip4']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip6']:"").$ipdata['network-addy']));
		
  	
	if (!is_array($checked))
	{
		require_once( _RUN_XORTIFY_ROOT_PATH . '/class/'._RUN_XORTIFY_PROTOCOL.'.php' );
		$func = strtoupper(_RUN_XORTIFY_PROTOCOL).'XortifyExchange';
		ob_start();
		$apiExchange = new $func;
		$result = $apiExchange->checkPHPBans($ipdata);
		ob_end_flush();
		
		if (is_array($result)) {
			if ($result['success']==true)
				if (($result['octeta'] <= _RUN_XORTIFY_PROJECTHONEYPOT_OCTETA && $result['octetb'] > _RUN_XORTIFY_PROJECTHONEYPOT_OCTETB && $result['octetc'] >= _RUN_XORTIFY_PROJECTHONEYPOT_OCTETC)) {
					
					$result = $apiExchange->sendBan(array("Project Honeypot\n".
												  _XOR_BAN_PHP_OCTETA.' '.$result['octeta'].' <= ' . _RUN_XORTIFY_PROJECTHONEYPOT_OCTETA."\n".
												  _XOR_BAN_PHP_OCTETB.' '.$result['octetb'].' > ' . _RUN_XORTIFY_PROJECTHONEYPOT_OCTETB."\n".
												  _XOR_BAN_PHP_OCTETC.' '.$result['octetc'].' >= ' . _RUN_XORTIFY_PROJECTHONEYPOT_OCTETC."\n"), 5, $ipdata);

					$_SESSION['xortify']['xoops_pagetitle'] = (_RUN_XORITIFY_SPAM_PAGETITLE);
					$_SESSION['xortify']['description'] = (_RUN_XORITIFY_SPAM_DESCRIPTION);
					$_SESSION['xortify']['version'] = (_RUN_XORTIFY_VERSION);
					$_SESSION['xortify']['provider'] = (basename(dirname(__FILE__)));
					$_SESSION['xortify']['spam'] = (htmlspecialchars($_REQUEST[$field]));
					$_SESSION['xortify']['agent'] = ($_SERVER['HTTP_USER_AGENT']);
																	  
					setcookie('xortify', array('banned' => true), time()+3600*24*7*4*3);
					header('Location: '._RUN_XORTIFY_URL.'/banned.php');
					exit(0);
				
				}
			
		}
		unlinkOldCachefiles('xortify_',_RUN_XORTIFY_IPCACHE);
		$_SESSION['xortify']['_pass'] = true;
		Cache::write('xortify_php_'.sha1($ipdata['uname'].$ipdata['email'].(isset($ipdata['ip4'])?$ipdata['ip4']:"").(isset($ipdata['ip6'])?$ipdata['ip6']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip4']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip6']:"").$ipdata['network-addy']), array_merge($result, array('ipdata' => $ipdata)), _RUN_XORTIFY_IPCACHE);
	} elseif (isset($checked['success'])) {
		if ($checked['success']==true) {
			if (($checked['octeta'] <= _RUN_XORTIFY_PROJECTHONEYPOT_OCTETA && $checked['octetb'] > _RUN_XORTIFY_PROJECTHONEYPOT_OCTETB && $checked['octetc'] >= _RUN_XORTIFY_PROJECTHONEYPOT_OCTETC)) {		
				$_SESSION['xortify']['xoops_pagetitle'] = (_RUN_XORITIFY_SPAM_PAGETITLE);
				$_SESSION['xortify']['description'] = (_RUN_XORITIFY_SPAM_DESCRIPTION);
				$_SESSION['xortify']['version'] = (_RUN_XORTIFY_VERSION);
				$_SESSION['xortify']['provider'] = (basename(dirname(__FILE__)));
				$_SESSION['xortify']['spam'] = (htmlspecialchars($_REQUEST[$field]));
				$_SESSION['xortify']['agent'] = ($_SERVER['HTTP_USER_AGENT']);
				
				setcookie('xortify', array('banned' => true), time()+3600*24*7*4*3);
				header('Location: '._RUN_XORTIFY_URL.'/banned.php');
				exit(0);
			}
		}
	}
	
?>