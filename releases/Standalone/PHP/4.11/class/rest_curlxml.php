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
		define('XORTIFY_USER_AGENT', 'Mozilla/5.0 (X11; U; Linux i686; pl-PL; rv:1.9.0.2) XOOPS/20100101 Auth/1.xx (php)');
}

if (!defined('XORTIFY_REST_API'))
	define('XORTIFY_REST_API', $_SESSION['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['xortify_urirest'].'%s/xml/?%s');

include_once(_RUN_XORTIFY_ROOT_PATH . '/include/functions.php');

class REST_CURLXMLXortifyExchange {

	var $curl_client;
	var $xml_xoops_username = '';
	var $xml_xoops_password = '';
	var $refresh = 600;
		
	function __construct($url = 'http://xortify.com')
	{
		$this->REST_CURLXMLXortifyExchange ($url);
	}
	
	function REST_CURLXMLXortifyExchange ($url) {

		$this->xml_xoops_username = _RUN_XORTIFY_USERNAME;
		$this->xml_xoops_password = md5(_RUN_XORTIFY_PASSWORD);
		$this->refresh = _RUN_XORTIFY_RECORDS;

		if (!$ch = curl_init($url)) {
			trigger_error('Could not intialise CURL file: '.XORTIFY_REST_API);
			return false;
		}
		$cookies = _RUN_XORTIFY_VAR_PATH . '/data/authcurl_'.md5(XORTIFY_REST_API).'.cookie'; 

		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $_SESSION['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['curl_connecttimeout']);
		curl_setopt($ch, CURLOPT_TIMEOUT, $_SESSION['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['curl_timeout']);
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
						curl_setopt($this->curl_client, CURLOPT_URL, sprintf(XORTIFY_REST_API, 'training', http_build_query(array(      "username"	=> 	$this->curl_xoops_username,
						"password"	=> 	$this->curl_xoops_password,
						'op' => ($ham==true?'ham':'spam'),
						'content' => $content
						))));
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
		if (!empty($this->curl_client))
			switch (XOOPS_CURLXML_LIB){
				case "PHPCURLXML":
					try {
						curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
						curl_setopt($this->curl_client, CURLOPT_URL, sprintf(XORTIFY_REST_API, 'spamcheck', http_build_query(array(      "username"	=> 	$this->xml_xoops_username,
									"password"	=> 	$this->xml_xoops_password,
									"poll" => _RUN_XORTIFY_URL.'/poll/',
									'content' => $content,
									'uname' => (isset($_SESSION['xortify']['uname']) && isset($_SESSION['xortify']['uid']) && isset($_SESSION['xortify']['email'])?$_SESSION['xortify']['uname']:''),
									'name' => (isset($_SESSION['xortify']['uname']) && isset($_SESSION['xortify']['uid']) && isset($_SESSION['xortify']['email'])?$_SESSION['xortify']['name']:''),
									'email' => (isset($_SESSION['xortify']['uname']) && isset($_SESSION['xortify']['uid']) && isset($_SESSION['xortify']['email'])?$_SESSION['xortify']['email']:''),
									'ip' => xortify_getIP(true),
									'session' => session_id()
						))));
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

						curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
						curl_setopt($this->curl_client, CURLOPT_URL, sprintf(XORTIFY_REST_API, 'spoof'.$type, http_build_query(array(      "username"	=> 	$this->curl_xoops_username,
						"password"	=> 	$this->curl_xoops_password, "uri" => (isset($_SERVER['HTTPS'])?'https://':'http://').$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
						'ip' => xortify_getIP(true),
						'language' => $_SESSION['xortify']['language'],
						'subject' => ''
								))));
								$data = curl_exec($this->curl_client);
								curl_close($this->curl_client);
								$result = xortify_elekey2numeric(xortify_xml2array($data), 'spoof'.$type);
					}
					catch (Exception $e) { trigger_error($e); }
					break;
		}
		return $result['spoof'.$type];
	}
	
	function getServers() {
		if (!empty($this->curl_client))
			switch (XOOPS_CURLXML_LIB){
			case "PHPCURLXML":
				try {
					curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
					curl_setopt($this->curl_client, CURLOPT_URL, sprintf(XORTIFY_REST_API, 'servers', http_build_query(array(      "username"	=> 	$this->xml_xoops_username, 
									"password"	=> 	$this->xml_xoops_password, "poll" => _RUN_XORTIFY_URL.'/poll/', 
									'token' => xortify_createToken(3600, 'poll_token'),
									'agent' => $_SERVER['HTTP_USER_AGENT'],
									'session' => session_id()
								))));
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
					curl_setopt($this->curl_client, CURLOPT_URL, sprintf(XORTIFY_REST_API, 'ban', http_build_query(array(      "username"	=> 	$this->xml_xoops_username, 
									"password"	=> 	$this->xml_xoops_password, 
									"bans" 		=> 	array(	0 	=> 	array_merge(
																				$ipData, 
																				array('category_id' => $category_id)
																				)
													),
									"comments" 	=> 	array(	0	=>	array(	'uname'		=>		$this->xml_xoops_username, 
																			"comment" 	=> 		$comment
																	)
													 ) ))));
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
					curl_setopt($this->curl_client, CURLOPT_URL, sprintf(XORTIFY_REST_API, 'checksfsbans', http_build_query(array(      "username"	=> 	$this->xml_xoops_username, 
									"password"	=> 	$this->xml_xoops_password, 
									"ipdata" 	=> 	$ipdata
								))));
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
					curl_setopt($this->curl_client, CURLOPT_URL, sprintf(XORTIFY_REST_API, 'checkphpbans', http_build_query(array(      "username"	=> 	$this->xml_xoops_username, 
									"password"	=> 	$this->xml_xoops_password, 
									"ipdata" 	=> 	$ipdata
								))));
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
					curl_setopt($this->curl_client, CURLOPT_URL, sprintf(XORTIFY_REST_API, 'bans', http_build_query(array("username"=> $this->xml_xoops_username, "password"=> $this->xml_xoops_password,  "records"=> $this->refresh))));
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
						curl_setopt($this->curl_client, CURLOPT_URL, sprintf(XORTIFY_REST_API, 'banned', http_build_query(array("username"=> $this->xml_xoops_username, "password"=> $this->xml_xoops_password,  "ipdata"=> $ipdata))));
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