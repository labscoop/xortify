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
 * @file:			minimumcloud.php		
 * @description:	API Calls for JSON minimumcloud Routines in Xortify Cloud
 * @date:			09/09/2012 19:34 AEST
 * @license:		GNU3 (http://web.labs.coop/public/legal/general-public-licence/13,3.html)
 * @package			xortify
 * @subpackage		classes
 * @author			Simon A. Roberts - wishcraft (simon@labs.coop)

 * 
 */

if (!function_exists('json_encode')){
	function json_encode($data) {
		static $json = NULL;
		if (!class_exists('Services_JSON') ) { include_once $GLOBALS['xoops']->path('/modules/xortify/include/JSON.php'); }
		$json = new Services_JSON();
		return $json->encode($data);
	}
}

if (!function_exists('json_decode')){
	function json_decode($data) {
		static $json = NULL;
		if (!class_exists('Services_JSON') ) { include_once $GLOBALS['xoops']->path('/modules/xortify/include/JSON.php'); }
		$json = new Services_JSON();
		return $json->decode($data);
	}
}

foreach (get_loaded_extensions() as $ext){
	if ($ext=="curl")
		$nativecurl=true;
}

$GLOBALS['xoops'] = Xoops::getInstance();

if ($nativecurl==true) {
	if (!defined('XOOPS_MINIMUMCLOUD_LIB'))
		define('XOOPS_MINIMUMCLOUD_LIB', 'PHPCURL');
	if (!defined('XORTIFY_USER_AGENT'))
		define('XORTIFY_USER_AGENT', sprintf(_MI_XOR_USER_AGENT, _MI_XOR_NAME, _MI_XOR_VERSION, _MI_XOR_RUNTIME, _MI_XOR_MODE));
}
if (!defined('XOOPS_MINIMUMCLOUD_LIB'))
	define('XOOPS_MINIMUMCLOUD_LIB', 'PHPWGET');

if (!defined('XORTIFY_REST_API'))
	define('XORTIFY_REST_API', $GLOBALS['xoops']->getModuleConfig('xortify_urirest', 'xortify').'%s/json/?%s');

include_once(XOOPS_ROOT_PATH.'/modules/xortify/include/functions.php');

/*
 * Xortify.com connector class - as described
 */
class MinimumcloudXortifyExchange {

	/*
	 * cURL Object
	 */
	var $curl_client;
	
	/*
	 * Username on xortify.com cloud
	 */
	var $minimumcloud_xoops_username = '';
	
	/*
	 * MD5 of password
	 */
	var $minimumcloud_xoops_password = '';
	
	/*
	 * Records to Refresh
	 */
	var $refresh = 600;
		
	/*
	 * Constructor
	 */
	function __construct()
	{
		$this->MinimumcloudXortifyExchange ();
	}
	
	/*
	 * Constructor
	 */
	function MinimumcloudXortifyExchange ($url) 
	{
		error_reporting(0);
		
		$this->minimumcloud_xoops_username = $GLOBALS['xoops']->getModuleConfig('xortify_username', 'xortify');
		$this->minimumcloud_xoops_password = md5($GLOBALS['xoops']->getModuleConfig('xortify_password', 'xortify'));
		$this->refresh = $GLOBALS['xoops']->getModuleConfig('xortify_records', 'xortify');

		if (XOOPS_MINIMUMCLOUD_LIB=='PHPCURL') {
			if (!$ch = curl_init($url)) {
				trigger_error('Could not intialise minimumcloud file: '.XORTIFY_REST_API);
				return false;
			}
			$cookies = XOOPS_VAR_PATH.'/cache/xoops_cache/authminimumcloud_'.md5(XORTIFY_REST_API).'.cookie'; 
	
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $GLOBALS['xoops']->getModuleConfig('curl_connecttimeout', 'xortify'));
			curl_setopt($ch, CURLOPT_TIMEOUT, $GLOBALS['xoops']->getModuleConfig('curl_timeout', 'xortify'));
			curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($ch, CURLOPT_USERAGENT, XORTIFY_USER_AGENT); 
			$this->curl_client = $ch;
		}			
	}

	/*
	 * Checks is content is spam
	 * 
	 * @param string $content
	 * @return boolean
	 */
	 function checkForSpam($content, $adult=false) {
		if (checkWordLength($content)==false)
			return array('spam'=>true);
		if (is_group(user_groups(), $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['check_spams'])==false)
			return array('spam'=>false);
		xoops_load('XoopsUserUtility');
		try {
			$header = "Content-type: text/html; charset=utf-8";		
			$header .= "\nMIME-version: 1.0";
			$header .= "\nContent-transfer-encoding: quoted-printable";
			$header .= "\nSubject: ".$_SERVER['REQUEST_URI'];
			$header .= "\nFrom: ".(is_object($GLOBALS['xoopsUser'])?$GLOBALS['xoopsUser']->getVar('uname'):'guest')."@".$_SERVER['REMOTE_ADDR'];
			$header .= "\nTo: ".(is_object($GLOBALS['xoopsUser'])?$GLOBALS['xoopsUser']->getVar('uname'):'guest')."@".$_SERVER['HTTP_HOST'];
			$header .= "\nDate: ".date('D, d M Y H:i:s +1000');
			$header .= "\n\n";		
	
			$exe = $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['xortify_mc_spamc'] . ' -c --retry-sleep=2 ';
			if ($scores!=false) {
				$exe .= '-r ';
			}
			exec($exe . '< ' . $header . $content, $report, $code);
			$result = array('spam' => (intval($code)==0?false:true));
			if ($scores!=false) {
				$result['report'] = $report;
			}
		}
		catch (Exception $e) { trigger_error($e); }
		return $result;
	
	}
	
	/*
	 * Get a Spoof/Trick form from the cloud
	 * 
	 * @param string $type
	 * @return array
	 */
	 function getSpoof($type = 'comment') {
		return array();
	}
	
	/*
	 * Get a list of Services/Servers on the Xortify Cloud
	 * 
	 * @return array
	 */
	 function getServers() {
		return array();	
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
		if (XOOPS_MINIMUMCLOUD_LIB=='PHPCURL') {
			try {
				curl_setopt($this->curl_client, CURLOPT_URL, sprintf(XORTIFY_REST_API, 'ban', http_build_query(array(      "username"	=> 	$this->minimumcloud_xoops_username, 
								"password"	=> 	$this->minimumcloud_xoops_password, 
								"bans" 		=> 	array(	0 	=> 	array_merge(
																			$ipData, 
																			array('category_id' => $category_id)
																			)
												),
								"comments" 	=> 	array(	0	=>	array(	'uname'		=>		$this->minimumcloud_xoops_username, 
																		"comment" 	=> 		$comment
																)
												 ) ))));
				$data = curl_exec($this->curl_client);
				curl_close($this->curl_client);
				$result = xortify_obj2array(json_decode($data));
			}
			catch (Exception $e) { trigger_error($e); }		
		} else {
			try {
				$data = file_get_contents(sprintf(XORTIFY_REST_API, 'ban', http_build_query( array(      "username"	=> 	$this->minimumcloud_xoops_username,
						"password"	=> 	$this->minimumcloud_xoops_password,
						"bans" 		=> 	array(	0 	=> 	array_merge(
								$ipData,
								array('category_id' => $category_id)
						)
						),
						"comments" 	=> 	array(	0	=>	array(	'uname'		=>		$this->minimumcloud_xoops_username,
								"comment" 	=> 		$comment
						)
						)
				))));
				$result = xortify_obj2array(json_decode($data));
			}
			catch (Exception $e) { trigger_error($e); }
		}
		return $result;	
	}

	/*
	 * Retrieves the Stop Forum Spam Analysis from the cloud
	 * 
	 * @param array $ipdata
	 * @return array
	 */
	 private function checksfsbans_GetFromToHost($data) {
		if (XOOPS_MINIMUMCLOUD_LIB=='PHPCURL') {
			try {
				if (!$ch = curl_init($GLOBALS['xoops']->getModuleConfig('xortify_mc_sfs_api', 'xortify').'?'.$data)) {
					trigger_error('Could not intialise CURL file: '.$url);
					return false;
				}
				$cookies = XOOPS_VAR_PATH.'/cache/xoops_cache/authcurl_'.md5($GLOBALS['xoops']->getModuleConfig('xortify_mc_sfs_api', 'xortify')).'.cookie';
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_POST, 0);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_USERAGENT, XORTIFY_USER_AGENT);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $GLOBALS['xoops']->getModuleConfig('curl_connecttimeout', 'xortify'));
				curl_setopt($ch, CURLOPT_TIMEOUT, $GLOBALS['xoops']->getModuleConfig('curl_timeout', 'xortify'));
				$data = curl_exec($ch);
				curl_close($ch);
			}
			catch (Exception $e) { trigger_error($e); }
		} else {
			try {
				$data = file_get_contents($GLOBALS['xoops']->getModuleConfig('xortify_mc_sfs_api', 'xortify').'?'.$data);
			}
			catch (Exception $e) { trigger_error($e); }
		}
		return xortify_obj2array(json_decode($data));
	}
	
	/*
	 * Retrieves the Stop Forum Spam Analysis from the cloud
	 * 
	 * @param array $ipdata
	 * @return array
	 */
	 function checkSFSBans($ipdata) {
		try {
			$result = $this->checksfsbans_GetFromToHost('f=json&username='.$ipdata['uname'].'&email='.$ipdata['email'].'&ip='.$ipdata['ip4'].$ipdata['ip6'].'&api_key='.$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['xortify_mc_sfs_key']);
		}		
		catch (Exception $e) { trigger_error($e); }
		return $result;	
	}

		
	/*
	 * Retrieves the Project Honey Pot Analysis from the API
	 * 
	 * @param string $apikey
	 * @param string $ip
	 * @return array
	 */
	 private function checkphpbans_dolookup($apikey,$ip){
		$itman = $apikey . "." . $ip . "." . $GLOBALS['xoops']->getModuleConfig('xortify_mc_php_api', 'xortify');
		$host = gethostbyname($itman);
		return ($host);
	}
	
	/*
	 * Retrieves the Project Honey Pot Analysis from the cloud
	 * 
	 * @param array $ipdata
	 * @return array
	 */
	 function checkPHPBans($ipdata) {
		$octet = array();
		try {
			$what2lookup = implode('.', array_reverse(explode('.',$ipdata['ip4'])));
			$octet = implode('.', checkphpbans_dolookup($GLOBALS['xoops']->getModuleConfig('xortify_mc_php_key', 'xortify'), $what2lookup));
		}
		catch (Exception $e) { trigger_error($e); }		
		return array('success'	=>	($octet[0]=='127')?true:false,
					 'octeta'	=>	$octet[1],
					 'octetb'	=>	$octet[2],
					 'octetc'	=>	$octet[3]);	
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
		if (XOOPS_MINIMUMCLOUD_LIB=='PHPCURL') {
			try {
				curl_setopt($this->curl_client, CURLOPT_URL, sprintf(XORTIFY_REST_API, 'bans', http_build_query(array("username"=> $this->minimumcloud_xoops_username, "password"=> $this->minimumcloud_xoops_password,  "records"=> $this->refresh)	 ) ));
				$data = curl_exec($this->curl_client);
				curl_close($this->curl_client);				
				$result = xortify_obj2array(json_decode($data));
			}		
			catch (Exception $e) { trigger_error($e); }
		} else {
			try {
				$data = file_get_contents(sprintf(XORTIFY_REST_API, 'bans', http_build_query(array("username"=> $this->minimumcloud_xoops_username, "password"=> $this->minimumcloud_xoops_password,  "records"=> $this->refresh))));
				$result = xortify_obj2array(json_decode($data));
			}
			catch (Exception $e) { trigger_error($e); }
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
		if (XOOPS_MINIMUMCLOUD_LIB=='PHPCURL') {
			try {
				curl_setopt($this->curl_client, CURLOPT_URL, sprintf(XORTIFY_REST_API, 'banned', http_build_query(array("username"=> $this->minimumcloud_xoops_username, "password"=> $this->minimumcloud_xoops_password,  "ipdata"=> $ipdata)	 ) ));
				$data = curl_exec($this->curl_client);
				curl_close($this->curl_client);				
				$result = xortify_obj2array(json_decode($data));
			}
			catch (Exception $e) { trigger_error($e); }				
		} else {
			try {
				$data = file_get_contents(sprintf(XORTIFY_REST_API, 'banned', http_build_query(array("username"=> $this->minimumcloud_xoops_username, "password"=> $this->minimumcloud_xoops_password,  "ipdata"=> $ipdata))));
				$result = xortify_obj2array(json_decode($data));
			}
			catch (Exception $e) { trigger_error($e); }
		}		
		return $result;
	}
	
	
}

?>