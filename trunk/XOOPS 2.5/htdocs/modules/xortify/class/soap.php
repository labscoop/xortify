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
 * This File:	soap.php		
 * Description:	SOAP Package for API Routines in SOAP on Xortify
 * Date:		09/09/2012 19:34 AEST
 * License:		GNU3
 * 
 */
foreach (get_loaded_extensions() as $ext){
	if ($ext=="soap")
		$nativesoap=true;
}

if ($nativesoap==true)
	define('XOOPS_SOAP_LIB', 'PHPSOAP');
	
if (!defined('XORTIFY_API_LOCAL'))
	define('XORTIFY_API_LOCAL', $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['xortify_urisoap']);
	
if (!defined('XORTIFY_API_URI'))
	define('XORTIFY_API_URI', $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['xortify_urisoap']);

class SOAPXortifyExchange {

	var $soap_client;
	var $soap_xoops_username = '';
	var $soap_xoops_password = '';
	var $refresh = 600;
	
	function SOAPXortifyExchange () {
	
		$module_handler = $GLOBALS['xoops']->getHandler('module');
		$config_handler = $GLOBALS['xoops']->getHandler('config');
		if (!is_object($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module'])) $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module'] = $module_handler->getByDirname('xortify');
		if (!isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'])) $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'] = $config_handler->getConfigList($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module']->mid());
		
		$this->soap_xoops_username = $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['xortify_username'];
		$this->soap_xoops_password = md5($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['xortify_password']);
		$this->refresh = $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['xortify_records'];
			
		switch (XOOPS_SOAP_LIB){
		case "PHPSOAP":
			$this->soap_client = new soapclient(NULL, array('location' => XORTIFY_API_LOCAL, 'uri' => XORTIFY_API_URI));
			break;
		}
	}

	function training($content, $ham = false) {
		if (!empty($this->curl_client))
			switch (XOOPS_SOAP_LIB){
				case "PHPSOAP":
					try {
						$data = $result = $this->soap_client->__soapCall('training' , array("username"	=> 	$this->curl_xoops_username,
								"password"	=> 	$this->curl_xoops_password,
								'op' => ($ham==true?'ham':'spam'),
								'content' => $content
						));
						$result = xortify_obj2array(json_decode($data));
					}
					catch (Exception $e) { trigger_error($e); }
					break;
		}
		return $result;
	}
	
	function checkForSpam($content) {
		xoops_load('XoopsUserUtility');
		switch (XOOPS_SOAP_LIB){
			default:
			case "PHPSOAP":
				try {
					$result = $this->soap_client->__soapCall('spamcheck',
						array(      "username"	=> 	$this->soap_xoops_username,
							"password"	=> 	$this->soap_xoops_password, "poll" => XOOPS_URL.'/modules/xortify/poll/',
							'content' => $content,
							'uname' => (is_object($GLOBALS['xoopsUser'])?$GLOBALS['xoopsUser']->getVar('uname'):''),
							'name' => (is_object($GLOBALS['xoopsUser'])?$GLOBALS['xoopsUser']->getVar('name'):''),
							'email' => (is_object($GLOBALS['xoopsUser'])?$GLOBALS['xoopsUser']->getVar('email'):''),
							'ip' => (class_exists('XoopsUserUtility')?XoopsUserUtility::getIP(true):$_SERVER['REMOTE_ADDR']),
							'session' => session_id()
					));
				}
				catch (Exception $e) { trigger_error($e); }
				break;
		}
		return $result['spamcheck'];
	}
	
	function getSpoof($type = 'comment') {
			
		switch (XOOPS_SOAP_LIB){
		case "PHPSOAP":
			try {
					xoops_load('XoopsUserUtility');
					$uu = new XoopsUserUtility();
					$result = $this->soap_client->__soapCall("spoof$type" .array(      "username"	=> 	$this->curl_xoops_username,
							"password"	=> 	$this->curl_xoops_password, "uri" => (isset($_SERVER['HTTPS'])?'https://':'http://').$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
							'ip' => $uu->getIP(true),
							'language' => $GLOBALS['xoops']->getConfig('language'),
							'subject' => ''
					));
				}
				catch (Exception $e) { trigger_error($e); }
				break;
		}
		return $result;
	}
	
	function getServers() {
	
		switch (XOOPS_SOAP_LIB){
		case "PHPSOAP":
			try {
				$result = $this->soap_client->__soapCall('servers',
	 				array(      "username"		=> 	$this->soap_xoops_username, 
								"password"		=> 	$this->soap_xoops_password,
	 							"poll" 			=> 	XOOPS_URL.'/modules/xortify/poll/', 
								'token'			=> 	$GLOBALS['xoopsSecurity']->createToken(3600, 'poll_token'),
								'agent' 		=> 	$_SERVER['HTTP_USER_AGENT'],
								'session' 		=> 	session_id()
						));
			}
			catch (Exception $e) { trigger_error($e); }
						
			break;
		}
			
		return $result;	
	}
	
	function sendBan($comment, $category_id = 2, $ip=false) {

		$ipData = xortify_getIPData($ip);
		
		switch (XOOPS_SOAP_LIB){
		case "PHPSOAP":
			try {
				$result = $this->soap_client->__soapCall('ban',
	 				array(      "username"	=> 	$this->soap_xoops_username, 
								"password"	=> 	$this->soap_xoops_password, 
								"bans" 		=> 	array(	0 	=> 	array_merge(
																			$ipData, 
																			array('category_id' => $category_id)
																			)
												),
								"comments" 	=> 	array(	0	=>	array(	'uname'		=>		$this->soap_xoops_username, 
																		"comment" 	=> 		$comment
																)
												 ) 
						));
			}
			catch (Exception $e) { trigger_error($e); }
						
			break;
		}
			
		return $result;	
	}

	function checkSFSBans($ipdata) {
		
		switch (XOOPS_SOAP_LIB){
		case "PHPSOAP":
			try {
				$result = $this->soap_client->__soapCall('checksfsbans',
	 				array(      "username"	=> 	$this->soap_xoops_username, 
								"password"	=> 	$this->soap_xoops_password, 
								"ipdata" 	=> 	$ipdata
						));
			}
			catch (Exception $e) { trigger_error($e); }
						
			break;
		}
			
		return $result;	
	}

	function checkPHPBans($ipdata) {
		
		switch (XOOPS_SOAP_LIB){
		case "PHPSOAP":
			try {
				$result = $this->soap_client->__soapCall('checkphpbans',
	 				array(      "username"	=> 	$this->soap_xoops_username, 
								"password"	=> 	$this->soap_xoops_password, 
								"ipdata" 	=> 	$ipdata
						));
			}
			catch (Exception $e) { trigger_error($e); }
			break;
		}
			
		return $result;	
	}
	
	function getBans() {
		xoops_load('xoopscache');
		if (!class_exists('XoopsCache')) {
			// XOOPS 2.4 Compliance
			xoops_load('cache');
			if (!class_exists('XoopsCache')) {
				include_once XOOPS_ROOT_PATH.'/class/cache/xoopscache.php';
			}
		}
        if (! $bans = @XoopsCache::read('xortify_bans_cache')) {
				$bans = @XoopsCache::read('xortify_bans_cache_backup');
				$GLOBALS['xoDoSoap'] = true;
        }
		return $bans;
	}
	
	function retrieveBans() {
		switch (XOOPS_SOAP_LIB){
		case "PHPSOAP":
			try {
				$result = $this->soap_client->__soapCall('bans', array("username"=> $this->soap_xoops_username, "password"=> $this->soap_xoops_password,  "records"=> $this->refresh));
			}
			catch (Exception $e) { trigger_error($e); }
			break;
		}		
		return $result;
	}
	
	function checkBanned($ipdata) {
		switch (XOOPS_SOAP_LIB){
		case "PHPSOAP":
			try {
				$result = $this->soap_client->__soapCall('banned', array("username"=> $this->soap_xoops_username, "password"=> $this->soap_xoops_password,  "ipdata"=> $ipdata));
			}
			catch (Exception $e) { trigger_error($e); }
			break;
		}		
		return $result;
	}
}


?>