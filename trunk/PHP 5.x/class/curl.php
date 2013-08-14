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
 * This File:	curl.php		
 * Description:	API Calls for JSON Curl Routines in Xortify Cloud
 * Date:		09/09/2012 19:34 AEST
 * License:		GNU3
 * 
 */
if (!function_exists('json_encode')){
	function json_encode($data) {
		static $json = NULL;
		if (!class_exists('Services_JSON') ) { include_once (_RUN_XORTIFY_ROOT_PATH . '/include/JSON.php'); }
		$json = new Services_JSON();
		return $json->encode($data);
	}
}

if (!function_exists('json_decode')){
	function json_decode($data) {
		static $json = NULL;
		if (!class_exists('Services_JSON') ) { include_once (_RUN_XORTIFY_ROOT_PATH . '/include/JSON.php'); }
		$json = new Services_JSON();
		return $json->decode($data);
	}
}

foreach (get_loaded_extensions() as $ext){
	if ($ext=="curl")
		$nativecurl=true;
}

if ($nativecurl==true) {
	define('XOOPS_CURL_LIB', 'PHPCURL');
	
	if (!defined('XORTIFY_USER_AGENT'))
			define('XORTIFY_USER_AGENT', sprintf(_RUN_XORTIFY_USER_AGENT, _RUN_XORTIFY_NAME, _RUN_XORTIFY_VERSION, _RUN_XORTIFY_RUNTIME, _RUN_XORTIFY_MODE));
		
}

if (!defined('XORTIFY_CURL_API'))
	define('XORTIFY_CURL_API', _RUN_XORTIFY_API_CURL);

include_once(_RUN_XORTIFY_ROOT_PATH . '/include/functions.php');

class CURLXortifyExchange {

	var $curl_client;
	var $curl_xoops_username = '';
	var $curl_xoops_password = '';
	var $refresh = 600;
		
	function __construct()
	{
		$this->CURLXortifyExchange ();
	}
	
	function CURLXortifyExchange () {
		
		$module_handler = $GLOBALS['xoops']->getHandler('module');
		$config_handler = $GLOBALS['xoops']->getHandler('config');
		if (!is_object($_SESSION['xortify'][XORTIFY_INSTANCE_KEY][_RUN_XORTIFY_VERSION]['module'])) $_SESSION['xortify'][XORTIFY_INSTANCE_KEY][_RUN_XORTIFY_VERSION]['module'] = $module_handler->getByDirname('xortify');
		if (!isset($_SESSION['xortify'][XORTIFY_INSTANCE_KEY][_RUN_XORTIFY_VERSION]['moduleConfig'])) $_SESSION['xortify'][XORTIFY_INSTANCE_KEY][_RUN_XORTIFY_VERSION]['moduleConfig'] = $config_handler->getConfigList($_SESSION['xortify'][XORTIFY_INSTANCE_KEY][_RUN_XORTIFY_VERSION]['module']->mid());
		
		$this->curl_xoops_username = $_SESSION['xortify'][XORTIFY_INSTANCE_KEY][_RUN_XORTIFY_VERSION]['moduleConfig']['xortify_username'];
		$this->curl_xoops_password = md5($_SESSION['xortify'][XORTIFY_INSTANCE_KEY][_RUN_XORTIFY_VERSION]['moduleConfig']['xortify_password']);
		$this->refresh = $_SESSION['xortify'][XORTIFY_INSTANCE_KEY][_RUN_XORTIFY_VERSION]['moduleConfig']['xortify_records'];

		if (!$ch = curl_init(XORTIFY_CURL_API)) {
			trigger_error('Could not intialise CURL file: '.XORTIFY_CURL_API);
			return false;
		}
		$cookies = _RUN_XORTIFY_VAR_PATH.'/authcurl_'.md5(XORTIFY_CURL_API).'.cookie'; 

		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, _RUN_XORTIFY_CURL_TIMEOUT);
		curl_setopt($ch, CURLOPT_TIMEOUT, _RUN_XORTIFY_CURL_TIMEOUT);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_USERAGENT, XORTIFY_USER_AGENT); 
		$this->curl_client = $ch;			
	}

	function training($content, $ham = false) {
		if (!empty($this->curl_client))
			switch (XOOPS_CURL_LIB){
				case "PHPCURL":
					try {
						curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
						curl_setopt($this->curl_client, CURLOPT_POSTFIELDS, 'training=' . json_encode(array(      "username"	=> 	$this->curl_xoops_username,
							"password"	=> 	$this->curl_xoops_password,
							'op' => ($ham==true?'ham':'spam'),
							'content' => $content
						)));
						$data = curl_exec($this->curl_client);
						curl_close($this->curl_client);
						$result = xortify_obj2array(json_decode($data));
					}
					catch (Exception $e) { trigger_error($e); }
					break;
		}
		return $result;
	}
	
	function checkForSpam($content) {
		xoops_load('UserUtility');
		if (!empty($this->curl_client))
			switch (XOOPS_CURL_LIB){
				case "PHPCURL":
					try {
						curl_setopt($this->curl_client, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
						curl_setopt($this->curl_client, CURLOPT_POSTFIELDS, 'spamcheck='.json_encode(array(      "username"	=> 	$this->curl_xoops_username,
						"password"	=> 	$this->curl_xoops_password, "poll" => XOOPS_URL.'/modules/xortify/poll/',
						'content' => $content,
						'uname' => (isset($_SESSION['xortify']['uname']) && isset($_SESSION['xortify']['uid']) && isset($_SESSION['xortify']['email'])?$_SESSION['xortify']['uname']:''),
						'name' => (isset($_SESSION['xortify']['uname']) && isset($_SESSION['xortify']['uid']) && isset($_SESSION['xortify']['email'])?$_SESSION['xortify']['name']:''),
						'email' => (isset($_SESSION['xortify']['uname']) && isset($_SESSION['xortify']['uid']) && isset($_SESSION['xortify']['email'])?$_SESSION['xortify']['email']:''),
						'ip' => (class_exists('UserUtility')?UserUtility::getIP(true):$_SERVER['REMOTE_ADDR']),
						'session' => session_id()
						)));
						$data = curl_exec($this->curl_client);
						curl_close($this->curl_client);
						$result = xortify_obj2array(json_decode($data));
					}
					catch (Exception $e) { trigger_error($e); }
					break;
		}
		return $result;
		
	}
	
	function getSpoof($type = 'comment') {
		if (!empty($this->curl_client))
			switch (XOOPS_CURL_LIB){
				case "PHPCURL":
					try {
						xoops_load('UserUtility');
						$uu = new UserUtility();
						curl_setopt($this->curl_client, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
						curl_setopt($this->curl_client, CURLOPT_POSTFIELDS, "spoof$type=" . json_encode(array(      "username"	=> 	$this->curl_xoops_username,
						"password"	=> 	$this->curl_xoops_password, "uri" => (isset($_SERVER['HTTPS'])?'https://':'http://').$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
						'ip' => $uu->getIP(true),
						'language' => $GLOBALS['xoops']->getConfig('language'),
						'subject' => ''
								)));
								$data = curl_exec($this->curl_client);
								curl_close($this->curl_client);
								$result = xortify_obj2array(json_decode($data));
					}
					catch (Exception $e) { trigger_error($e); }
					break;
			}
		return $result;
	}
	
	function getServers() {
		if (!empty($this->curl_client)) 
			switch (XOOPS_CURL_LIB){
			case "PHPCURL":
				try {
					curl_setopt($this->curl_client, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
					curl_setopt($this->curl_client, CURLOPT_POSTFIELDS, 'servers='.json_encode(array(      "username"	=> 	$this->curl_xoops_username, 
									"password"	=> 	$this->curl_xoops_password, "poll" => XOOPS_URL.'/modules/xortify/poll/', 
									'token' => $GLOBALS['xoopsSecurity']->createToken(3600, 'poll_token'),
									'agent' => $_SERVER['HTTP_USER_AGENT'],
									'session' => session_id()
								)));
					$data = curl_exec($this->curl_client);
					curl_close($this->curl_client);
					$result = xortify_obj2array(json_decode($data));
				}
				catch (Exception $e) { trigger_error($e); }		
				break;
			}
		return $result;	
	}

	function sendBan($comment, $category_id = 2, $ip=false) {
		$ipData = xortify_getIPData($ip);
		if (!empty($this->curl_client)) 
			switch (XOOPS_CURL_LIB){
			case "PHPCURL":
				try {
					curl_setopt($this->curl_client, CURLOPT_POSTFIELDS, 'ban='.json_encode(array(      "username"	=> 	$this->curl_xoops_username, 
									"password"	=> 	$this->curl_xoops_password, 
									"bans" 		=> 	array(	0 	=> 	array_merge(
																				$ipData, 
																				array('category_id' => $category_id)
																				)
													),
									"comments" 	=> 	array(	0	=>	array(	'uname'		=>		$this->curl_xoops_username, 
																			"comment" 	=> 		$comment
																	)
													 ) )));
					$data = curl_exec($this->curl_client);
					curl_close($this->curl_client);
					$result = xortify_obj2array(json_decode($data));
				}
				catch (Exception $e) { trigger_error($e); }		
				break;
			}
		return $result;	
	}

	function checkSFSBans($ipdata) {
		if (!empty($this->curl_client))
			switch (XOOPS_CURL_LIB){
			case "PHPCURL":
				try {
					curl_setopt($this->curl_client, CURLOPT_POSTFIELDS, 'checksfsbans='.json_encode(array(      "username"	=> 	$this->curl_xoops_username, 
									"password"	=> 	$this->curl_xoops_password, 
									"ipdata" 	=> 	$ipdata
								)));
					$data = curl_exec($this->curl_client);
					curl_close($this->curl_client);
					$result = xortify_obj2array(json_decode($data));
				}		
				catch (Exception $e) { trigger_error($e); }
				break;
			}
		return $result;	
	}

	function checkPHPBans($ipdata) {
		if (!empty($this->curl_client))
			switch (XOOPS_CURL_LIB){
			case "PHPCURL":
				try {
					curl_setopt($this->curl_client, CURLOPT_POSTFIELDS, 'checkphpbans='.json_encode(array(      "username"	=> 	$this->curl_xoops_username, 
									"password"	=> 	$this->curl_xoops_password, 
									"ipdata" 	=> 	$ipdata
								)));
					$data = curl_exec($this->curl_client);
					curl_close($this->curl_client);
					$result = xortify_obj2array(json_decode($data));
				}
				catch (Exception $e) { trigger_error($e); }		
				break;
			}
		return $result;	
	}
	function getBans() {
		xoops_load('xoopscache');
		if (!class_exists('Cache')) {
			// XOOPS 2.4 Compliance
			xoops_load('cache');
			if (!class_exists('Cache')) {
				include_once XOOPS_ROOT_PATH.'/class/cache/xoopscache.php';
			}
		}
        if (! $bans = @Cache::read('xortify_bans_cache')) {
				$bans = @Cache::read('xortify_bans_cache_backup');
				$GLOBALS['xoDoSoap'] = true;
        }
		return $bans;
	}	
	
	function retrieveBans() {
		if (!empty($this->curl_client))
			switch (XOOPS_CURL_LIB){
			case "PHPCURL":
				try {
					curl_setopt($this->curl_client, CURLOPT_POSTFIELDS, 'bans='.json_encode(array("username"=> $this->curl_xoops_username, "password"=> $this->curl_xoops_password,  "records"=> $this->refresh)	 ) );
					$data = curl_exec($this->curl_client);
					curl_close($this->curl_client);				
					$result = xortify_obj2array(json_decode($data));
				}		
				catch (Exception $e) { trigger_error($e); }
			}
		return $result;
	}

	function checkBanned($ipdata) {
		switch (XOOPS_CURL_LIB){
			case "PHPCURL":
				try {
					curl_setopt($this->curl_client, CURLOPT_POSTFIELDS, 'banned='.json_encode(array("username"=> $this->curl_xoops_username, "password"=> $this->curl_xoops_password,  "ipdata"=> $ipdata)	 ) );
					$data = curl_exec($this->curl_client);
					curl_close($this->curl_client);				
					$result = xortify_obj2array(json_decode($data));
				}
				catch (Exception $e) { trigger_error($e); }				
			break;
		}		
		return $result;
	}
	
	
}

?>