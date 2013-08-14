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
 * @Version:		3.10 Final (Stable)
 * @copyright:		Chronolabs Cooperative 2013 © Simon Roberts (www.simonaroberts.com)
 * @download:		http://sourceforge.net/projects/xortify
 * @file:			soap.php		
 * @description:	SOAP Package for API Routines in SOAP on Xortify
 * @date:			09/09/2012 19:34 AEST
 * @license:		GNU3 (http://web.labs.coop/public/legal/general-public-licence/13,3.html)
 * @package			xortify
 * @subpackage		classes
 * @author			Simon A. Roberts - wishcraft (simon@labs.coop)

 * 
 */
foreach (get_loaded_extensions() as $ext){
	if ($ext=="soap")
		$nativesoap=true;
}

if ($nativesoap==true)
	define('XOOPS_SOAP_LIB', 'PHPSOAP');

$GLOBALS['xoops'] = Xoops::getInstance();

if (!defined('XORTIFY_API_LOCAL'))
	define('XORTIFY_API_LOCAL', $GLOBALS['xoops']->getModuleConfig('xortify_urisoap', 'xortify'));
	
if (!defined('XORTIFY_API_URI'))
	define('XORTIFY_API_URI', $GLOBALS['xoops']->getModuleConfig('xortify_urisoap', 'xortify'));


/*
 * Xortify.com connector class - as described
 */
class SOAPXortifyExchange {

	/*
	 * SOAP Object
	 */
	var $soap_client;
	
	/*
	 * Username on xortify.com cloud
	 */
	var $soap_xoops_username = '';
	
	/*
	 * MD5 of password
	 */
	var $soap_xoops_password = '';
	
	/*
	 * Records to Refresh
	 */
	var $refresh = 600;
	
	/*
	 * Constructor
	 */
	function SOAPXortifyExchange () {
		error_reporting(0);
		
		$this->soap_xoops_username = $GLOBALS['xoops']->getModuleConfig('xortify_username', 'xortify');
		$this->soap_xoops_password = md5($GLOBALS['xoops']->getModuleConfig('xortify_password', 'xortify'));
		$this->refresh = $GLOBALS['xoops']->getModuleConfig('xortify_records', 'xortify');
			
		switch (XOOPS_SOAP_LIB){
		case "PHPSOAP":
			$this->soap_client = new soapclient(NULL, array('location' => XORTIFY_API_LOCAL, 'uri' => XORTIFY_API_URI));
			break;
		}
	}

	/*
	 * Checks is content is spam
	 * 
	 * @param string $content
	 * @return boolean
	 */
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
	
	/*
	 * Get a Spoof/Trick form from the cloud
	 * 
	 * @param string $type
	 * @return array
	 */
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
	
	/*
	 * Get a list of Services/Servers on the Xortify Cloud
	 * 
	 * @return array
	 */
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
	
	/*
	 * Creates Ban on the cloud
	 * 
	 * @param array $comment
	 * @param integer $category_id
	 * @param string $ip
	 * @return mixed
	 */
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

	/*
	 * Retrieves the Stop Forum Spam Analysis from the cloud
	 * 
	 * @param array $ipdata
	 * @return array
	 */
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

	/*
	 * Retrieves the Project Honey Pot Analysis from the cloud
	 * 
	 * @param array $ipdata
	 * @return array
	 */
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
	
	/*
	 * Retrieves the Bans from the File Cache
	 * 
	 * @return array
	 */
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
	
	/*
	 * Retrieves the Bans from the Cloud per Record Number
	 * 
	 * @return array
	 */
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
	
	/*
	 * Checks if IP Data PAckages banned uin anyway
	 * 
	 * @param array $ipdata
	 * @return mixed
	 */
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