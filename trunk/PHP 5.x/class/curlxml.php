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
 * This File:	curlxml.php		
 * Description:	CURL XML Routines for XML API Packages
 * Date:		09/09/2012 19:34 AEST
 * License:		GNU3
 * 
 */

foreach (get_loaded_extensions() as $ext){
	if ($ext=="curl")
		$nativecurl=true;
	if ($ext=="SimpleXML")
		$nativexml=true;
		
}

if ($nativecurl==true&&$nativexml==true) {
	define('XOOPS_CURLXML_LIB', 'PHPCURLXML');
	
	if (!defined('XORTIFY_USER_AGENT'))
			define('XORTIFY_USER_AGENT', sprintf(_MI_XOR_USER_AGENT, _MI_XOR_NAME, _MI_XOR_VERSION, _MI_XOR_RUNTIME, _MI_XOR_MODE));
		
}

if (!defined('XORTIFY_CURLXML_API'))
	define('XORTIFY_CURLXML_API', _RUN_XORTIFY_API_XML);

include_once(_RUN_XORTIFY_ROOT_PATH . '/include/functions.php');

class CURLXMLXortifyExchange {

	var $curl_client;
	var $xml_xoops_username = '';
	var $xml_xoops_password = '';
	var $refresh = 600;
		
	function __construct()
	{
		$this->CURLXMLXortifyExchange ();
	}
	
	function CURLXMLXortifyExchange () {
		
		$module_handler = $GLOBALS['xoops']->getHandler('module');
		$config_handler = $GLOBALS['xoops']->getHandler('config');
		if (!isset($_SESSION['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'])) $_SESSION['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'] = $config_handler->getConfigList($_SESSION['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module']->mid());
		
		$this->xml_xoops_username = $_SESSION['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['xortify_username'];
		$this->xml_xoops_password = md5($_SESSION['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['xortify_password']);
		$this->refresh = $_SESSION['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['xortify_records'];

		if (!$ch = curl_init(XORTIFY_CURLXML_API)) {
			trigger_error('Could not intialise CURL file: '.XORTIFY_CURLXML_API);
			return false;
		}
		$cookies = _RUN_XORTIFY_VAR_PATH . DIRECTORY_SEPARATOR .  'data' . DIRECTORY_SEPARATOR .'  authcurl_'.md5(XORTIFY_CURLXML_API).'.cookie'; 

		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, _RUN_XORTIFY_CURL_CONNECTIONTIMEOUT);
		curl_setopt($ch, CURLOPT_TIMEOUT, _RUN_XORTIFY_CURL_TIMEOUT);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_USERAGENT, XORTIFY_USER_AGENT); 
		$this->curl_client = $ch;			
	}
	
	function training($content, $ham = false) {
		if (!empty($this->curl_client))
			switch (XOOPS_CURLXML_LIB){
				case "PHPCURLXML":
					try {
						curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
						curl_setopt($this->curl_client, CURLOPT_POSTFIELDS, 'training=' . xortify_toXml(array("username"	=> 	$this->curl_xoops_username,
							"password"	=> 	$this->curl_xoops_password,
							'op' => ($ham==true?'ham':'spam'),
							'content' => $content
						)));
						$data = curl_exec($this->curl_client);
						curl_close($this->curl_client);
						$result = xortify_elekey2numeric(xortify_xml2array($data), 'training');
					}
					catch (Exception $e) { trigger_error($e); }
					break;
		}
		return $result['training'];
	}
	
	function checkForSpam($content) {
		xoops_load('UserUtility');
		if (!empty($this->curl_client))
			switch (XOOPS_CURLXML_LIB){
			case "PHPCURLXML":
					try {
						curl_setopt($this->curl_client, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
						curl_setopt($this->curl_client, CURLOPT_POSTFIELDS, 'spamcheck='.xortify_toXml(array(      "username"	=> 	$this->xml_xoops_username,
						"password"	=> 	$this->xml_xoops_password, "poll" => XOOPS_URL.'/modules/xortify/poll/',
						'content' => $content,
						'uname' => (isset($_SESSION['xortify']['uname']) && isset($_SESSION['xortify']['uid']) && isset($_SESSION['xortify']['email'])?$_SESSION['xortify']['uname']:''),
						'name' => (isset($_SESSION['xortify']['uname']) && isset($_SESSION['xortify']['uid']) && isset($_SESSION['xortify']['email'])?$_SESSION['xortify']['name']:''),
						'email' => (isset($_SESSION['xortify']['uname']) && isset($_SESSION['xortify']['uid']) && isset($_SESSION['xortify']['email'])?$_SESSION['xortify']['email']:''),
						'ip' => (class_exists('UserUtility')?UserUtility::getIP(true):$_SERVER['REMOTE_ADDR']),
						'session' => session_id()
						)));
						$data = curl_exec($this->curl_client);
						curl_close($this->curl_client);
						$result = xortify_elekey2numeric(xortify_xml2array($data), 'spamcheck');
					}
					catch (Exception $e) { trigger_error($e); }
					break;
		}
		return $result['spamcheck'];
	
	}


	function getSpoof($type = 'comment') {
		if (!empty($this->curl_client))
			switch (XOOPS_CURLXML_LIB){
			case "PHPCURLXML":
					try {
						xoops_load('UserUtility');
						$uu = new UserUtility();
						curl_setopt($this->curl_client, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
						curl_setopt($this->curl_client, CURLOPT_POSTFIELDS, "spoof$type=" . xortify_toXml(array(      "username"	=> 	$this->curl_xoops_username,
						"password"	=> 	$this->curl_xoops_password, "uri" => (isset($_SERVER['HTTPS'])?'https://':'http://').$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
						'ip' => $uu->getIP(true),
						'language' => $GLOBALS['xoops']->getConfig('language'),
						'subject' => ''
								)));
								$data = curl_exec($this->curl_client);
								curl_close($this->curl_client);
								$result = xortify_elekey2numeric(xortify_xml2array($data), "spoof$type");
					}
					catch (Exception $e) { trigger_error($e); }
					break;
		}
		return $result["spoof$type"];
	}
	
	function getServers() {
		if (!empty($this->curl_client))
			switch (XOOPS_CURLXML_LIB){
			case "PHPCURLXML":
				try {
					curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
					curl_setopt($this->curl_client, CURLOPT_POSTFIELDS, 'servers='.xortify_toXml(array(      "username"	=> 	$this->xml_xoops_username, 
									"password"	=> 	$this->xml_xoops_password, "poll" => XOOPS_URL.'/modules/xortify/poll/', 
									'token' => $GLOBALS['xoopsSecurity']->createToken(3600, 'poll_token'),
									'agent' => $_SERVER['HTTP_USER_AGENT'],
									'session' => session_id()
								), 'servers'));
					$data = curl_exec($this->curl_client);
					curl_close($this->curl_client);
					$result = xortify_elekey2numeric(xortify_xml2array($data), 'servers');
				}
				catch (Exception $e) { trigger_error($e); }		
				break;
			}
		return $result['servers'];	
	}
	
	function sendBan($comment, $category_id = 2, $ip=false) {
		$ipData = xortify_getIPData($ip);
		if (!empty($this->curl_client))
			switch (XOOPS_CURLXML_LIB){
			case "PHPCURLXML":
				try {
					curl_setopt($this->curl_client, CURLOPT_POSTFIELDS, 'ban='.xortify_toXml(array(      "username"	=> 	$this->xml_xoops_username, 
									"password"	=> 	$this->xml_xoops_password, 
									"bans" 		=> 	array(	0 	=> 	array_merge(
																				$ipData, 
																				array('category_id' => $category_id)
																				)
													),
									"comments" 	=> 	array(	0	=>	array(	'uname'		=>		$this->xml_xoops_username, 
																			"comment" 	=> 		$comment
																	)
													 ) ), 'ban'));
					$data = curl_exec($this->curl_client);
					curl_close($this->curl_client);
					$result = xortify_elekey2numeric(xortify_xml2array($data), 'ban');
				}
				catch (Exception $e) { trigger_error($e); }		
				break;
			}
		return $result['ban'];	
	}

	function checkSFSBans($ipdata) {
		if (!empty($this->curl_client))
			switch (XOOPS_CURLXML_LIB){
			case "PHPCURLXML":
				try {
					curl_setopt($this->curl_client, CURLOPT_POSTFIELDS, 'checksfsbans='.xortify_toXml(array(      "username"	=> 	$this->xml_xoops_username, 
									"password"	=> 	$this->xml_xoops_password, 
									"ipdata" 	=> 	$ipdata
								), 'checksfsbans'));
					$data = curl_exec($this->curl_client);
					curl_close($this->curl_client);
					$result = xortify_elekey2numeric(xortify_xml2array($data), 'checksfsbans');
				}
				catch (Exception $e) { trigger_error($e); }		
				break;
			}
		return $result['checksfsbans'];	
	}

	function checkPHPBans($ipdata) {
		if (!empty($this->curl_client))
			switch (XOOPS_CURLXML_LIB){
			case "PHPCURLXML":
				try {
					curl_setopt($this->curl_client, CURLOPT_POSTFIELDS, 'checkphpbans='.xortify_toXml(array(      "username"	=> 	$this->xml_xoops_username, 
									"password"	=> 	$this->xml_xoops_password, 
									"ipdata" 	=> 	$ipdata
								), 'checkphpbans'));
					$data = curl_exec($this->curl_client);
					curl_close($this->curl_client);
					$result = xortify_elekey2numeric(xortify_xml2array($data), 'checkphpbans');
				}
				catch (Exception $e) { trigger_error($e); }		
				break;
			}
		return $result['checkphpbans'];	
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
			switch (XOOPS_CURLXML_LIB){
			case "PHPCURLXML":
				try {
					curl_setopt($this->curl_client, CURLOPT_POSTFIELDS, 'bans='.xortify_toXml(array("username"=> $this->xml_xoops_username, "password"=> $this->xml_xoops_password,  "records"=> $this->refresh), 'bans'));
					$data = curl_exec($this->curl_client);
					curl_close($this->curl_client);				
					$result = xortify_elekey2numeric(xortify_xml2array($data),'bans');
				}
				catch (Exception $e) { trigger_error($e); }		
			}
		return $result['bans'];
	}

	function checkBanned($ipdata) {
		if (!empty($this->curl_client))
			switch (XOOPS_CURLXML_LIB){
				case "PHPCURLXML":
					try {
						curl_setopt($this->curl_client, CURLOPT_POSTFIELDS, 'banned='.xortify_toXml(array("username"=> $this->xml_xoops_username, "password"=> $this->xml_xoops_password,  "ipdata"=> $ipdata), 'banned'));
						$data = curl_exec($this->curl_client);
						curl_close($this->curl_client);				
						$result = xortify_elekey2numeric(xortify_xml2array($data),'banned');
					}
					
					catch (Exception $e) { trigger_error($e); }				
				break;
			}		
		return $result;
	}
	
}

?>