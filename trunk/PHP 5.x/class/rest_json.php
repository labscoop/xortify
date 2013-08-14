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
 * This File:	json.php		
 * Description:	wGET routines for API JSON Packages
 * Date:		09/09/2012 19:34 AEST
 * License:		GNU3
 * 
 */
 
if (!function_exists('json_encode')){
	function json_encode($data) {
		static $json = NULL;
		if (!class_exists('Services_JSON') ) { include_once $GLOBALS['xoops']->path('/include/JSON.php'); }
		$json = new Services_JSON();
		return $json->encode($data);
	}
}

if (!function_exists('json_decode')){
	function json_decode($data) {
		static $json = NULL;
		if (!class_exists('Services_JSON') ) { include_once $GLOBALS['xoops']->path('/include/JSON.php'); }
		$json = new Services_JSON();
		return $json->decode($data);
	}
}


if (!defined('XORTIFY_REST_API'))
	define('XORTIFY_REST_API', _RUN_XORTIFY_API_REST.'%s/json/?%s');

include_once(_RUN_XORTIFY_ROOT_PATH . '/include/functions.php');


define('XOOPS_JSON_LIB', 'PHPJSON');

class REST_JSONXortifyExchange {

	var $json_client;
	var $json_xoops_username = '';
	var $json_xoops_password = '';
	var $refresh = 600;
		
	function __construct($url = 'http://xortify.com')
	{
		$this->REST_JSONXortifyExchange ($url);
	}
	
	function REST_JSONXortifyExchange ($url) {
	
		$this->json_xoops_username = _RUN_XORTIFY_USERNAME;
		$this->json_xoops_password = md5(_RUN_XORTIFY_PASSWORD);
		$this->refresh = _RUN_XORTIFY_RECORDS;
			
	}

	function training($content, $ham = false) {
		if (!empty($this->curl_client))
			switch (XOOPS_JSON_LIB){
				case "PHPJSON":
					try {
						curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
						curl_setopt($this->curl_client, CURLOPT_URL, sprintf(XORTIFY_REST_API, 'training', http_build_query(array(      "username"	=> 	$this->curl_xoops_username,
						"password"	=> 	$this->curl_xoops_password,
						'op' => ($ham==true?'ham':'spam'),
						'content' => $content
						))));
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
		switch (XOOPS_JSON_LIB){
		default:
		case "PHPJSON":
			try {
						$data = file_get_contents(sprintf(XORTIFY_REST_API, 'spamcheck', http_build_query(array(      "username"	=> 	$this->json_xoops_username,
						"password"	=> 	$this->json_xoops_password, "poll" => _RUN_XORTIFY_URL.'/poll/',
						'content' => $content,
						'uname' => (isset($_SESSION['xortify']['uname']) && isset($_SESSION['xortify']['uid']) && isset($_SESSION['xortify']['email'])?$_SESSION['xortify']['uname']:''),
						'name' => (isset($_SESSION['xortify']['uname']) && isset($_SESSION['xortify']['uid']) && isset($_SESSION['xortify']['email'])?$_SESSION['xortify']['name']:''),
						'email' => (isset($_SESSION['xortify']['uname']) && isset($_SESSION['xortify']['uid']) && isset($_SESSION['xortify']['email'])?$_SESSION['xortify']['email']:''),
						'ip' => xortify_getIP(true),
						'session' => session_id()
				))));
				$result = xortify_obj2array(json_decode($data));
			}
			catch (Exception $e) { trigger_error($e); }
			break;
		}
		return $result;
	}


	function getSpoof($type = 'comment') {
		switch (XOOPS_JSON_LIB){
		default:
		case "PHPJSON":
			try {

					$data = file_get_contents( sprintf(XORTIFY_REST_API, 'spoof'.$type, http_build_query(array(      "username"	=> 	$this->curl_xoops_username,
				"password"	=> 	$this->curl_xoops_password, "uri" => (isset($_SERVER['HTTPS'])?'https://':'http://').$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
				'ip' => xortify_getIP(true),
				'language' => $_SESSION['xortify']['language'],
				'subject' => ''
						))));
				$result = xortify_obj2array(json_decode($data));
			}
			catch (Exception $e) { trigger_error($e); }
			break;
		}
		return $result;
	}
	
	function getServers() {
		switch (XOOPS_JSON_LIB){
		default:
		case "PHPJSON":
			try {
				$data = file_get_contents(sprintf(XORTIFY_REST_API, 'servers', http_build_query( array(      "username"	=> 	$this->json_xoops_username, 
								"password"	=> 	$this->json_xoops_password, "poll" => _RUN_XORTIFY_URL.'/poll/', 
								'token' => xortify_createToken(3600, 'poll_token'),
								'agent' => $_SERVER['HTTP_USER_AGENT'],
								'session' => session_id()
						))));
				$result = xortify_obj2array(json_decode($data));
			}
			catch (Exception $e) { trigger_error($e); }
			break;
		}			
		return $result;	
	}
		
	function sendBan($comment, $category_id = 2, $ip=false) {

		$ipData = xortify_getIPData($ip);
		
		switch (XOOPS_JSON_LIB){
		default:
		case "PHPJSON":
			try {
				$data = file_get_contents(sprintf(XORTIFY_REST_API, 'ban', http_build_query( array(      "username"	=> 	$this->json_xoops_username, 
								"password"	=> 	$this->json_xoops_password, 
								"bans" 		=> 	array(	0 	=> 	array_merge(
																			$ipData, 
																			array('category_id' => $category_id)
																			)
												),
								"comments" 	=> 	array(	0	=>	array(	'uname'		=>		$this->json_xoops_username, 
																		"comment" 	=> 		$comment
																)
												 ) 
						))));
				$result = xortify_obj2array(json_decode($data));
			}
			catch (Exception $e) { trigger_error($e); }
			break;
		}			
		return $result;	
	}

	function checkSFSBans($ipdata) {
		
		switch (XOOPS_JSON_LIB){
		default:
		case "PHPJSON":
			try {
				$data = file_get_contents(sprintf(XORTIFY_REST_API, 'checksfsbans', http_build_query( 
						array(  "username"	=> 	$this->json_xoops_username, 
								"password"	=> 	$this->json_xoops_password, 
								"ipdata" 	=> 	$ipdata
						))));
				$result = xortify_obj2array(json_decode($data));
			}
			catch (Exception $e) { trigger_error($e); }
			break;
		}			
		return $result;	
	}

	function checkPHPBans($ipdata) {
		
		switch (XOOPS_JSON_LIB){
		default:
		case "PHPJSON":
			try {
				$data = file_get_contents(sprintf(XORTIFY_REST_API, 'checkphpbans', http_build_query( 
						array(  "username"	=> 	$this->json_xoops_username, 
								"password"	=> 	$this->json_xoops_password, 
								"ipdata" 	=> 	$ipdata
						))));
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
		switch (XOOPS_JSON_LIB){
		default:
		case "PHPJSON":
			try {
				$data = file_get_contents(sprintf(XORTIFY_REST_API, 'bans', http_build_query(array("username"=> $this->json_xoops_username, "password"=> $this->json_xoops_password,  "records"=> $this->refresh))));
				$result = xortify_obj2array(json_decode($data));
			}
			catch (Exception $e) { trigger_error($e); }
			break;
		}
		return $result;
	}

	function checkBanned($ipdata) {
		switch (XOOPS_JSON_LIB){
		default:
		case "PHPJSON":
			try {
				$data = file_get_contents(sprintf(XORTIFY_REST_API, 'banned', http_build_query(array("username"=> $this->json_xoops_username, "password"=> $this->json_xoops_password,  "ipdata"=> $ipdata))));
				$result = xortify_obj2array(json_decode($data));
			}
			catch (Exception $e) { trigger_error($e); }
			break;
		}
		return $result;
	}
}


?>