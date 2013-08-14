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
 * Description:	Stop Forum Spam Post loader provider for Xortify Client
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
	
	$ipdata = xortify_getIPData(false);
	
	if (isset($ipdata['ip4']))
		if ($ipdata['ip4']==_RUN_XORTIFY_MYIPv4_ADDRESS)
			return false;
			
	if (isset($ipdata['ip6']))
		if ($ipdata['ip6']==_RUN_XORTIFY_MYIPv6_ADDRESS) 
			return false;
	
	$checked = Cache::read('xortify_sfs_'.sha1($ipdata['uname'].$ipdata['email'].(isset($ipdata['ip4'])?$ipdata['ip4']:"").(isset($ipdata['ip6'])?$ipdata['ip6']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip4']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip6']:"").$ipdata['network-addy']));
	
	if (!is_array($checked))
	{
		
		require_once( _RUN_XORTIFY_ROOT_PATH . '/class/'._RUN_XORTIFY_PROTOCOL.'.php' );
		$func = strtoupper(_RUN_XORTIFY_PROTOCOL).'XortifyExchange';
		ob_start();
		$apiExchange = new $func;
		
		$result = $apiExchange->checkSFSBans($ipdata);
		
		ob_end_flush();
		
		if (is_array($result)) {
			if (isset($result['success']))
				if ($result['success']==true)
					if (($result['email']['frequency']>=_RUN_XORTIFY_SFS_EMAILFREQUENCY||$result['username']['frequency']>=_RUN_XORTIFY_SFS_UNAMEFREQUENCY||$result['ip']['frequency']>=_RUN_XORTIFY_SFS_IPFREQUENCY) &&
						(strtotime($result['email']['lastseen'])>time()-_RUN_XORTIFY_SFS_EMAIL_LASTSEEN||strtotime($result['username']['lastseen'])>time()-_RUN_XORTIFY_SFS_UNAME_LASTSEEN||strtotime($result['ip']['lastseen'])>time()-_RUN_XORTIFY_SFS_IP_LASTSEEN)) {
							
							include_once _RUN_XORTIFY_ROOT_PATH."/include/common.php";
							
							Cache::write('xortify_sfs_'.sha1($ipdata['uname'].$ipdata['email'].(isset($ipdata['ip4'])?$ipdata['ip4']:"").(isset($ipdata['ip6'])?$ipdata['ip6']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip4']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip6']:"").$ipdata['network-addy']), array_merge($result, array('ipdata' => $ipdata)), _RUN_XORTIFY_IPCACHE);					
									
							$result = $apiExchange->sendBan(array(_RUN_XORTIFY_PROTOCOL_BAN_SFS_TYPE."\n".
														  _RUN_XORTIFY_PROTOCOL_BAN_SFS_EMAIL_FREQ.': '.$result['email']['frequency'].' >= '._RUN_XORTIFY_SFS_EMAILFREQUENCY. "\n".
														  _RUN_XORTIFY_PROTOCOL_BAN_SFS_EMAIL_LASTSEEN.': '.date(_DATESTRING, strtotime($result['email']['lastseen'])) . ' > ' . date(_DATESTRING, time()-_RUN_XORTIFY_SFS_EMAIL_LASTSEEN) . "\n" .
														  _RUN_XORTIFY_PROTOCOL_BAN_SFS_USERNAME_FREQ.': '.$result['username']['frequency'].' >= '._RUN_XORTIFY_SFS_UNAMEFREQUENCY. "\n".
														  _RUN_XORTIFY_PROTOCOL_BAN_SFS_USERNAME_LASTSEEN.': '.date(_DATESTRING, strtotime($result['username']['lastseen'])) . ' > ' . date(_DATESTRING, time()-_RUN_XORTIFY_SFS_UNAME_LASTSEEN) . "\n" .
														  _RUN_XORTIFY_PROTOCOL_BAN_SFS_IP_FREQ.': '.$result['ip']['frequency'].' >= '._RUN_XORTIFY_SFS_IPFREQUENCY. "\n".
														  _RUN_XORTIFY_PROTOCOL_BAN_SFS_IP_LASTSEEN.': '.date(_DATESTRING, strtotime($result['ip']['lastseen'])) . ' > ' . date(_DATESTRING, time()-_RUN_XORTIFY_SFS_IP_LASTSEEN)), 4, $ipdata);
 						    
							$_SESSION['xortify']['xoops_pagetitle'] = (_RUN_XORITIFY_SPAM_PAGETITLE);
							$_SESSION['xortify']['description'] = (_RUN_XORITIFY_SPAM_DESCRIPTION);
							$_SESSION['xortify']['version'] = (_RUN_XORTIFY_VERSION);
							$_SESSION['xortify']['provider'] = (basename(dirname(__FILE__)));
							$_SESSION['xortify']['spam'] = (htmlspecialchars($_REQUEST[$field]));
							$_SESSION['xortify']['agent'] = ($_SERVER['HTTP_USER_AGENT']);
							
							Cache::write('xortify_core_include_common_end', array('time'=>microtime(true)), _RUN_XORTIFY_FAULTDELAY);
							setcookie('xortify', array('banned' => true), time()+3600*24*7*4*3);
							header('Location: '._RUN_XORTIFY_URL.'/banned.php');
							exit(0);
							
						}
			unlinkOldCachefiles('xortify_',_RUN_XORTIFY_IPCACHE);
			Cache::write('xortify_sfs_'.sha1($ipdata['uname'].$ipdata['email'].(isset($ipdata['ip4'])?$ipdata['ip4']:"").(isset($ipdata['ip6'])?$ipdata['ip6']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip4']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip6']:"").$ipdata['network-addy']), array_merge($result, array('ipdata' => $ipdata)), _RUN_XORTIFY_IPCACHE);
			$_SESSION['xortify']['_pass'] = true;
				
		}	
	} elseif (isset($checked['success'])&&$checked['success']==true) {
		if (($checked['email']['frequency']>=2||$checked['username']['frequency']>=2||$checked['ip']['frequency']>=2) &&
			(strtotime($checked['email']['lastseen'])>time()-(60*60*24*7*4*3)||strtotime($checked['username']['lastseen'])>time()-(60*60*24*7*4*3)||strtotime($checked['ip']['lastseen'])>time()-(60*60*24*7*4*3))) {
				
				include_once _RUN_XORTIFY_ROOT_PATH."/include/common.php";
				
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
	
?>