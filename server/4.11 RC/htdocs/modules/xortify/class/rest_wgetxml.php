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
 * Version:		3.10 Final (Stable)
 * Published:	Chronolabs
 * Download:	http://code.google.com/p/chronolabs
 * This File:	wgetxml.php		
 * Description:	wGET XML Packages for API Calls with Xortify Cloud
 * Date:		09/09/2012 19:34 AEST
 * License:		GNU3
 * 
 */
foreach (get_loaded_extensions() as $ext){
	if ($ext=="SimpleXML")
		$nativexml=true;
		
}

if ($nativexml==true)
	define('XOOPS_WGETXML_LIB', 'PHPXML');
	
if (!defined('XORTIFY_REST_API'))
	define('XORTIFY_REST_API', $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig']['xortify_urirest'].'%s/xml/?%s');

include_once(XOOPS_ROOT_PATH.'/modules/xortify/include/functions.php');

class REST_WGETXMLXortifyExchange {

	var $xml_xoops_username = '';
	var $xml_xoops_password = '';
	var $refresh = 600;
		
	function __construct()
	{
		$this->WGETXMLXortifyExchange ();
	}
	
	function WGETXMLXortifyExchange () {
	
		$module_handler =& xoops_gethandler('module');
		$config_handler =& xoops_gethandler('config');
		if (!is_object($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['module'])) $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['module'] = $module_handler->getByDirname('xortify');
		if (!isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig'])) $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig'] = $config_handler->getConfigList($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['module']->mid());
		
		$this->xml_xoops_username = $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig']['xortify_username'];
		$this->xml_xoops_password = md5($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig']['xortify_password']);
		$this->refresh = $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig']['xortify_records'];
			
	}

	function checkForSpam($content) {
		xoops_load('XoopsUserUtility');
		if (!empty($this->curl_client))
			switch (XOOPS_JSON_LIB){
				case "PHPJSON":
					try {
						$data = file_get_contents(sprintf(XORTIFY_REST_API, 'spamcheck', http_build_query(array(      "username"	=> 	$this->xml_xoops_username,
								"password"	=> 	$this->xml_xoops_password, "poll" => XOOPS_URL.'/modules/xortify/poll/',
								'content' => $content,
								'uname' => (is_object($GLOBALS['xoopsUser'])?$GLOBALS['xoopsUser']->getVar('uname'):''),
								'name' => (is_object($GLOBALS['xoopsUser'])?$GLOBALS['xoopsUser']->getVar('name'):''),
								'email' => (is_object($GLOBALS['xoopsUser'])?$GLOBALS['xoopsUser']->getVar('email'):''),
								'ip' => (class_exists('XoopsUserUtility')?XoopsUserUtility::getIP(true):$_SERVER['REMOTE_ADDR']),
								'session' => session_id()
						))));
						$result = xortify_elekey2numeric(xortify_xml2array($data), 'spamcheck');
					}
					catch (Exception $e) { trigger_error($e); }
					break;
		}
		return $result['spamcheck'];
	}
	
	
	function getServers() {

		$ipData = xortify_getIPData($ip);
		
		switch (XOOPS_WGETXML_LIB){
		default:
		case "PHPXML":
			try {
				$data = file_get_contents(sprintf(XORTIFY_REST_API, 'servers', http_build_query( array(      "username"	=> 	$this->xml_xoops_username, 
								"password"	=> 	$this->xml_xoops_password , "poll" => XOOPS_URL.'/modules/xortify/poll/', 
								'token' => $GLOBALS['xoopsSecurity']->createToken(3600, 'poll_token'),
								'agent' => $_SERVER['HTTP_USER_AGENT'],
								'session' => session_id()
						))));
				$result = xortify_elekey2numeric(xortify_xml2array($data), 'servers');
			}
			catch (Exception $e) { trigger_error($e); }
			break;
		}			
		return $result['servers'];	
	}
	
	function sendBan($comment, $category_id = 2, $ip=false) {

		$ipData = xortify_getIPData($ip);
		
		switch (XOOPS_WGETXML_LIB){
		default:
		case "PHPXML":
			try {
				$data = file_get_contents(sprintf(XORTIFY_REST_API, 'ban', http_build_query( array(      "username"	=> 	$this->xml_xoops_username, 
								"password"	=> 	$this->xml_xoops_password, 
								"bans" 		=> 	array(	0 	=> 	array_merge(
																			$ipData, 
																			array('category_id' => $category_id)
																			)
												),
								"comments" 	=> 	array(	0	=>	array(	'uname'		=>		$this->xml_xoops_username, 
																		"comment" 	=> 		$comment
																)
												 ) 
						))));
				$result = xortify_elekey2numeric(xortify_xml2array($data), 'ban');
			}
			catch (Exception $e) { trigger_error($e); }
			break;
		}			
		return $result['ban'];	
	}

	function checkSFSBans($ipdata) {
		switch (XOOPS_WGETXML_LIB){
		default:
		case "PHPXML":
			try {
				$data = file_get_contents(sprintf(XORTIFY_REST_API, 'checksfsbans', http_build_query( 
						array(  "username"	=> 	$this->xml_xoops_username, 
								"password"	=> 	$this->xml_xoops_password, 
								"ipdata" 	=> 	$ipdata
						))));
				$result = xortify_elekey2numeric(xortify_xml2array($data), 'checksfsbans');
			}
			catch (Exception $e) { trigger_error($e); }
			break;
		}			
		return $result['checksfsbans'];	
	}

	function checkPHPBans($ipdata) {
		
		switch (XOOPS_WGETXML_LIB){
		default:
		case "PHPXML":
			try {
				$data = file_get_contents(sprintf(XORTIFY_REST_API, 'checkphpbans', http_build_query( 
						array(  "username"	=> 	$this->xml_xoops_username, 
								"password"	=> 	$this->xml_xoops_password, 
								"ipdata" 	=> 	$ipdata
						), 'checkphpbans')));
				$result = xortify_elekey2numeric(xortify_xml2array($data), 'checkphpbans');
			}
			catch (Exception $e) { trigger_error($e); }
			break;
		}			
		return $result['checkphpbans'];	
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
			
		switch (XOOPS_WGETXML_LIB){
		default:
		case "PHPXML":
			try {
				$data = file_get_contents(sprintf(XORTIFY_REST_API, 'bans', http_build_query(array("username"=> $this->xml_xoops_username, "password"=> $this->xml_xoops_password,  "records"=> $this->refresh))));
				$result = xortify_elekey2numeric(xortify_xml2array($data), 'bans');
			}
			catch (Exception $e) { trigger_error($e); }
			break;
		}
		return $result['bans'];
	}

	function checkBanned($ipdata) {
		switch (XOOPS_WGETXML_LIB){
		default:
		case "PHPXML":
			try {
				$data = file_get_contents(sprintf(XORTIFY_REST_API, 'banned', http_build_query(array("username"=> $this->xml_xoops_username, "password"=> $this->xml_xoops_password,  "ipdata"=> $ipdata))));
				$result = xortify_elekey2numeric(xortify_xml2array($data), 'banned');
			}
			catch (Exception $e) { trigger_error($e); }
			break;
		}
		return $result;
	}
}


?>