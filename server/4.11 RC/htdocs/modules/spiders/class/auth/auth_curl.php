<?php
// $Author: wishcraft $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: Simon Roberts (AKA wishcraft)                                     //
// URL: http://www.chronolabs.org.au                                         //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //
 
define('XORTIFY_CURL_API', 'http://www.xortify.com/curl/');
define('XORTIFY_USER_AGENT', 'Mozilla/5.0 (X11; U; Linux i686; pl-PL; rv:1.9.0.2) XOOPS/20100101 XoopsAuth/1.xx (php)');

include_once XOOPS_ROOT_PATH . '/modules/spiders/class/auth/auth_curl_provisionning.php';

class XortifyAuthCurl extends XortifyAuth {
	
	var $curl_client;
	var $curl_xoops_username = '';
	var $curl_xoops_password = '';
	var $_dao;
	/**
	 * Authentication Service constructor
	 */
	function XortifyAuthCurl (&$dao) {
		if (!$ch = curl_init(XORTIFY_CURL_API)) {
			trigger_error('Could not intialise CURL file: '.$url);
			return false;
		}
		$cookies = XOOPS_VAR_PATH.'/cache/xoops_cache/authcurl_'.md5($url).'.cookie'; 

		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_USERAGENT, XORTIFY_USER_AGENT); 
		$this->curl_client =& $ch;
	}


	/**
	 *  Authenticate  user again curl directory (Bind)
	 *
	 * @param string $uname Username
	 * @param string $pwd Password
	 *
	 * @return bool
	 */	
	function authenticate($uname, $pwd = null) {
		$authenticated = false;
		$this->XortifyAuthCurl($GLOBALS['xoopsDB']);
		
		if (!$this->curl_client) {
			$this->setErrors(0, _AUTH_CURL_EXTENSION_NOT_LOAD);
			return $authenticated;
		}

				
		$rnd = rand(-100000, 100000000);
		curl_setopt($this->curl_client, CURLOPT_POSTFIELDS, array('xoops_authentication' => array("username"=> $this->curl_xoops_username, "password"=> $this->curl_xoops_password, "auth" => array('username' => $uname, "password" => $pwd, "time" => time(), "passhash" => sha1((time()-$rnd).$uname.$pwd), "rand"=>$rnd))));
		$data = curl_exec($this->curl_client);
		curl_close($this->curl_client);
		include_once($GLOBALS['xoops']->path('/modules/spiders/include/JSON.php'));	
		$result = Services_JSON::decode($data);
		return $result["RESULT"];		
	}
	
				  
	/**
	 *  validate a user via curl
	 *
	 * @param string $uname
	 * @param string $email
	 * @param string $pass
	 * @param string $vpass
	 *
	 * @return string
	 */		
	function validate($uname, $email, $pass, $vpass){
		$this->XortifyAuthCurl($GLOBALS['xoopsDB']);
		$rnd = rand(-100000, 100000000);
		curl_setopt($this->curl_client, CURLOPT_POSTFIELDS, array('xoops_user_validate' => array("username"=> $this->curl_xoops_username, "password"=> $this->curl_xoops_password, "validate" => array('uname' => $uname, "pass" => $pass, "vpass" => $vpass, "email" => $email, "time" => time(), "passhash" => sha1((time()-$rnd).$uname.$pass), "rand"=>$rnd))));
		$data = curl_exec($this->curl_client);
		curl_close($this->curl_client);
		include_once($GLOBALS['xoops']->path('/modules/spiders/include/JSON.php'));	
		$result = Services_JSON::decode($data);
		if ($result['ERRNUM']==1){
			return $result["RESULT"];
		} else {
			return false;
		}
	
	}

	/**
	 *  get the xoops site disclaimer via curl
	 *
	 * @return string
	 */			
	function network_disclaimer(){
		$this->XortifyAuthCurl($GLOBALS['xoopsDB']);
		curl_setopt($this->curl_client, CURLOPT_POSTFIELDS, array('xoops_network_disclaimer' => array("username"=> $this->curl_xoops_username, "password"=> $this->curl_xoops_password)));
		$data = curl_exec($this->curl_client);
		curl_close($this->curl_client);
		include_once($GLOBALS['xoops']->path('/modules/spiders/include/JSON.php'));	
		$result = Services_JSON::decode($data);		
		if ($result['ERRNUM']==1){
			return $result["RESULT"];
		} else {
			return false;
		}

	}
	
	/**
	 *  create a user
	 *
	 * @param bool $user_viewemail
	 * @param string $uname
	 * @param string $email
	 * @param string $url
	 * @param string $actkey
	 * @param string $pass
	 * @param integer $timezone_offset
	 * @param bool $user_mailok		 
	 * @param array $siteinfo
	 *
	 * @return array
	 */	
	function create_user($user_viewemail, $uname, $email, $url, $actkey, 
						 $pass, $timezone_offset, $user_mailok, $siteinfo){
						 
		$siteinfo = $this->check_siteinfo($siteinfo);

		$rnd = rand(-100000, 100000000);
		$this->XortifyAuthCurl($GLOBALS['xoopsDB']);
		curl_setopt($this->curl_client, CURLOPT_POSTFIELDS, array('xoops_create_user' => array("username"=> $this->curl_xoops_username, "password"=> $this->curl_xoops_password, "user" => array('user_viewemail' =>$user_viewemail, 'uname' => $uname, 'email' => $email, 'url' => $url, 'actkey' => $actkey, 'pass' => $pass, 'timezone_offset' => $timezone_offset, 'user_mailok' => $user_mailok, "time" => time(), "siteinfo" => $siteinfo, "passhash" => sha1((time()-$rnd).$uname.$pass), "rand"=>$rnd))));
		$data = curl_exec($this->curl_client);
		curl_close($this->curl_client);
		include_once($GLOBALS['xoops']->path('/modules/spiders/include/JSON.php'));	
		$result = Services_JSON::decode($data);		
	
		if ($result['ERRNUM']==1){

			return $result["RESULT"];
			
		} else {
			return false;
		}
	}
		
}
// end class


?>
