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
	
if (!defined('XORTIFY_WGETXML_API'))
	define('XORTIFY_WGETXML_API', _RUN_XORTIFY_API_XML);

include_once(_RUN_XORTIFY_ROOT_PATH . '/include/functions.php');

class WGETXMLXortifyExchange {

	var $xml_xoops_username = '';
	var $xml_xoops_password = '';
	var $refresh = 600;
		
	function __construct()
	{
		$this->WGETXMLXortifyExchange ();
	}
	
	function WGETXMLXortifyExchange () {

		$this->xml_xoops_username = _RUN_XORTIFY_USERNAME;
		$this->xml_xoops_password = md5(_RUN_XORTIFY_PASSWORD);
		$this->refresh = _RUN_XORTIFY_RECORDS;
			
	}


	function training($content, $ham = false) {
		if (!empty($this->curl_client))
			switch (XOOPS_WGETXML_LIB){
				case "PHPXML":
					try {
						$data = file_get_contents(XORTIFY_WGETXML_API . '?training=' . urlencode(xortify_toXml(array("username"	=> 	$this->curl_xoops_username,
								"password"	=> 	$this->curl_xoops_password,
								'op' 		=> ($ham==true?'ham':'spam'),
								'content' 	=> $content
							))));
						$result = xortify_elekey2numeric(xortify_xml2array($data), 'training');
					}
					catch (Exception $e) { trigger_error($e); }
					break;
		}
		return $result['training'];
	}
	
	function checkForSpam($content) {
		xoops_load('UserUtility');
		switch (XOOPS_WGETXML_LIB){
		default:
		case "PHPXML":
			try {
				$data = file_get_contents(XORTIFY_WGETXML_API.'?spamcheck='.urlencode(xortify_toXml(array(      "username"	=> 	$this->xml_xoops_username,
						"password"	=> 	$this->xml_xoops_password, "poll" => _RUN_XORTIFY_URL.'/poll/',
						'content' => $content,
						'uname' => (isset($_SESSION['xortify']['uname']) && isset($_SESSION['xortify']['uid']) && isset($_SESSION['xortify']['email'])?$_SESSION['xortify']['uname']:''),
						'name' => (isset($_SESSION['xortify']['uname']) && isset($_SESSION['xortify']['uid']) && isset($_SESSION['xortify']['email'])?$_SESSION['xortify']['name']:''),
						'email' => (isset($_SESSION['xortify']['uname']) && isset($_SESSION['xortify']['uid']) && isset($_SESSION['xortify']['email'])?$_SESSION['xortify']['email']:''),
						'ip' => xortify_getIP(true),
						'session' => session_id()
				))));
				$result = xortify_elekey2numeric(xortify_xml2array($data), 'spamcheck');
			}
			catch (Exception $e) { trigger_error($e); }
			break;
		}
		return $result['spamcheck'];
	}

	function getSpoof($type = 'comment') {
		switch (XOOPS_WGETXML_LIB){
		default:
		case "PHPXML":
			try {
					$data = file_get_contents(XORTIFY_SERIAL_API."?spoof$type=" . urlencode(xortify_toXml(array(      "username"	=> 	$this->curl_xoops_username,
							"password"	=> 	$this->curl_xoops_password, "uri" => (isset($_SERVER['HTTPS'])?'https://':'http://').$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
							'ip' => xortify_getIP(true),
							'language' => $_SESSION['xortify']['language'],
							'subject' => ''
					))));
					$result = xortify_elekey2numeric(xortify_xml2array($data), "spoof$type");
				}
				catch (Exception $e) { trigger_error($e); }
				break;
		}
		return $result["spoof$type"];
	}
	
	function getServers() {

		$ipData = xortify_getIPData($ip);
		
		switch (XOOPS_WGETXML_LIB){
		default:
		case "PHPXML":
			try {
				$data = file_get_contents(XORTIFY_WGETXML_API.'?servers='.urlencode(xortify_toXml( array(      "username"	=> 	$this->xml_xoops_username, 
								"password"	=> 	$this->xml_xoops_password , "poll" => _RUN_XORTIFY_URL.'/poll/', 
								'token' => xortify_createToken(3600, 'poll_token'),
								'agent' => $_SERVER['HTTP_USER_AGENT'],
								'session' => session_id()
						), 'servers')));
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
				$data = file_get_contents(XORTIFY_WGETXML_API.'?ban='.urlencode(xortify_toXml( array(      "username"	=> 	$this->xml_xoops_username, 
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
						), 'ban')));
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
				$data = file_get_contents(XORTIFY_WGETXML_API.'?checksfsbans='.urlencode(xortify_toXml( 
						array(  "username"	=> 	$this->xml_xoops_username, 
								"password"	=> 	$this->xml_xoops_password, 
								"ipdata" 	=> 	$ipdata
						), 'checksfsbans')));
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
				$data = file_get_contents(XORTIFY_WGETXML_API.'?checkphpbans='.urlencode(xortify_toXml( 
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
			
		switch (XOOPS_WGETXML_LIB){
		default:
		case "PHPXML":
			try {
				$data = file_get_contents(XORTIFY_WGETXML_API.'?bans='.urlencode(xortify_toXml(array("username"=> $this->xml_xoops_username, "password"=> $this->xml_xoops_password,  "records"=> $this->refresh), 'bans')));
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
				$data = file_get_contents(XORTIFY_WGETXML_API.'?banned='.urlencode(xortify_toXml(array("username"=> $this->xml_xoops_username, "password"=> $this->xml_xoops_password,  "ipdata"=> $ipdata), 'banned')));
				$result = xortify_elekey2numeric(xortify_xml2array($data), 'banned');
			}
			catch (Exception $e) { trigger_error($e); }
			break;
		}
		return $result;
	}
}


?>