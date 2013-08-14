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
 * @file:			wgetserialised.php		
 * @description:	wGET Serialised API Package Routines for Xortify Cloud
 * @date:			09/09/2012 19:34 AEST
 * @license:		GNU3 (http://web.labs.coop/public/legal/general-public-licence/13,3.html)
 * @package			xortify
 * @subpackage		classes
 * @author			Simon A. Roberts - wishcraft (simon@labs.coop)

 * 
 */

$GLOBALS['xoops'] = Xoops::getInstance();

if (!defined('XORTIFY_REST_API'))
	define('XORTIFY_REST_API', $GLOBALS['xoops']->getModuleConfig('xortify_urirest', 'xortify').'%s/serial/?%s');

include_once(XOOPS_ROOT_PATH.'/modules/xortify/include/functions.php');

define('XOOPS_SERIAL_LIB', 'PHPSERIAL');

/*
 * Xortify.com connector class - as described
 */
class REST_WGETSERIALISEDXortifyExchange {

	/*
	 * Username on xortify.com cloud
	 */
	var $serial_xoops_username = '';
	
	/*
	 * MD5 of password
	 */
	var $serial_xoops_password = '';
	
	/*
	 * Records to Refresh
	 */
	var $refresh = 600;
	
	/*
	 * Serialisation string
	 */
	var $serial = '';
	
	/*
	 * Constructor
	 */
	function __construct()
	{
		$this->REST_WGETSERIALISEDXortifyExchange ();
	}
	
	/*
	 * Constructor
	 */
	function REST_WGETSERIALISEDXortifyExchange () {
		error_reporting(0);
		
		$this->serial_xoops_username = $GLOBALS['xoops']->getModuleConfig('xortify_username', 'xortify');
		$this->serial_xoops_password = md5($GLOBALS['xoops']->getModuleConfig('xortify_password', 'xortify'));
		$this->refresh = $GLOBALS['xoops']->getModuleConfig('xortify_records', 'xortify');
			
	}

	/*
	 * Provides training document to API
	 * 
	 * @param string $content
	 * @param boolean $ham
	 * @return boolean
	 */
	 function training($content, $ham = false) {
		if (!empty($this->curl_client))
			switch (XOOPS_SERIAL_LIB){
				case "PHPSERIAL":
					try {
						curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
						curl_setopt($this->curl_client, CURLOPT_URL, sprintf(XORTIFY_REST_API, 'training', http_build_query(array(      "username"	=> 	$this->curl_xoops_username,
							"password"	=> 	$this->curl_xoops_password,
							'op' => ($ham==true?'ham':'spam'),
							'content' => $content
						))));
						$data = curl_exec($this->curl_client);
						curl_close($this->curl_client);
						$result = (unserialize($data));
					}
					catch (Exception $e) { trigger_error($e); }
					break;
		}
		return $result;
	}
	
	/*
	 * Checks is content is spam
	 * 
	 * @param string $content
	 * @return boolean
	 */
	 function checkForSpam($content) {
		switch (XOOPS_SERIAL_LIB){
			default:
			case "PHPSERIAL":
				try {
					$data = file_get_contents(sprintf(XORTIFY_REST_API, 'spamcheck', http_build_query(array(      "username"	=> 	$this->serial_xoops_username,
							"password"	=> 	$this->serial_xoops_password, "poll" => XOOPS_URL.'/modules/xortify/poll/',
							'content' => $content,
							'uname' => (is_object($GLOBALS['xoopsUser'])?$GLOBALS['xoopsUser']->getVar('uname'):''),
							'name' => (is_object($GLOBALS['xoopsUser'])?$GLOBALS['xoopsUser']->getVar('name'):''),
							'email' => (is_object($GLOBALS['xoopsUser'])?$GLOBALS['xoopsUser']->getVar('email'):''),
							'ip' => (class_exists('XoopsUserUtility')?XoopsUserUtility::getIP(true):$_SERVER['REMOTE_ADDR']),
							'session' => session_id()
					))));
					$result = (unserialize($data));
				}
				catch (Exception $e) { trigger_error($e); }
				break;
		}
		return $result;
	}


	/*
	 * Get a Spoof/Trick form from the cloud
	 * 
	 * @param string $type
	 * @return array
	 */
	 function getSpoof($type = 'comment') {
		switch (XOOPS_SERIAL_LIB){
			default:
			case "PHPSERIAL":
				try {
					xoops_load('XoopsUserUtility');
					$uu = new XoopsUserUtility();
					$data = file_get_contents( sprintf(XORTIFY_REST_API, 'spoof'.$type, http_build_query(array(      "username"	=> 	$this->curl_xoops_username,
							"password"	=> 	$this->curl_xoops_password, "uri" => (isset($_SERVER['HTTPS'])?'https://':'http://').$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
							'ip' => $uu->getIP(true),
							'language' => $GLOBALS['xoops']->getConfig('language'),
							'subject' => ''
					))));
					$result = (unserialize($data));
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
		switch (XOOPS_SERIAL_LIB){
		default:
		case "PHPSERIAL":
			try {
				$data = file_get_contents(sprintf(XORTIFY_REST_API, 'ban', http_build_query( array(      "username"	=> 	$this->serial_xoops_username, 
								"password"	=> 	$this->serial_xoops_password, "poll" => XOOPS_URL.'/modules/xortify/poll/', 
								'token' => $GLOBALS['xoopsSecurity']->createToken(3600, 'poll_token'),
								'agent' => $_SERVER['HTTP_USER_AGENT'],
								'session' => session_id()
						))));
				$result = (unserialize($data));
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
		
		switch (XOOPS_SERIAL_LIB){
		default:
		case "PHPSERIAL":
			try {
				$data = file_get_contents(sprintf(XORTIFY_REST_API, 'ban', http_build_query( array(      "username"	=> 	$this->serial_xoops_username, 
								"password"	=> 	$this->serial_xoops_password, 
								"bans" 		=> 	array(	0 	=> 	array_merge(
																			$ipData, 
																			array('category_id' => $category_id)
																			)
												),
								"comments" 	=> 	array(	0	=>	array(	'uname'		=>		$this->serial_xoops_username, 
																		"comment" 	=> 		$comment
																)
												 ) 
						))));
				$result = (unserialize($data));
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
		
		switch (XOOPS_SERIAL_LIB){
		default:
		case "PHPSERIAL":
			try {
				$data = file_get_contents(sprintf(XORTIFY_REST_API, 'checksfsbans', http_build_query( 
						array(  "username"	=> 	$this->serial_xoops_username, 
								"password"	=> 	$this->serial_xoops_password, 
								"ipdata" 	=> 	$ipdata
						))));
				$result = (unserialize($data));
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
		
		switch (XOOPS_SERIAL_LIB){
		default:
		case "PHPSERIAL":
			try {
				$data = file_get_contents(sprintf(XORTIFY_REST_API, 'checkphpbans', http_build_query( 
						array(  "username"	=> 	$this->serial_xoops_username, 
								"password"	=> 	$this->serial_xoops_password, 
								"ipdata" 	=> 	$ipdata
						))));
				$result = (unserialize($data));
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
		switch (XOOPS_SERIAL_LIB){
		default:
		case "PHPSERIAL":
			try {
				$data = file_get_contents(sprintf(XORTIFY_REST_API, 'bans', http_build_query(array("username"=> $this->serial_xoops_username, "password"=> $this->serial_xoops_password,  "records"=> $this->refresh))));
				$result = (unserialize($data));
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
		switch (XOOPS_SERIAL_LIB){
		default:
		case "PHPSERIAL":
			try {
				$data = file_get_contents(sprintf(XORTIFY_REST_API, 'banned', http_build_query(array("username"=> $this->serial_xoops_username, "password"=> $this->serial_xoops_password,  "ipdata"=> $ipdata))));
				$result = (unserialize($data));
			}
			catch (Exception $e) { trigger_error($e); }
			break;
		}
		return $result;
	}
}


?>