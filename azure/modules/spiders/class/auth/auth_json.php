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
 
define('XORTIFY_JSON_API', 'http://www.xortify.com/json/');

include_once XOOPS_ROOT_PATH . '/modules/xortify/class/auth/auth_json_provisionning.php';

class XortifyAuthJson extends XortifyAuth {
	
	var $json_client;
	var $json_xoops_username = '';
	var $json_xoops_password = '';
	var $_dao;
	/**
	 * Authentication Service constructor
	 */
	function XortifyAuthJson (&$dao) {
		ini_set('allow_furl_open', true);
	}


	/**
	 *  Authenticate  user again json directory (Bind)
	 *
	 * @param string $uname Username
	 * @param string $pwd Password
	 *
	 * @return bool
	 */	
	function authenticate($uname, $pwd = null) {
		$authenticated = false;
		include_once($GLOBALS['xoops']->path('/modules/spiders/include/JSON.php'));
		$rnd = rand(-100000, 100000000);		
		$data = file_get_contents(XORTIFY_JSON_API.'?xoops_authentication='.urlencode(Services_JSON::encode(array("username"=> $this->json_xoops_username, "password"=> $this->json_xoops_password, "auth" => array('username' => $uname, "password" => $pwd, "time" => time(), "passhash" => sha1((time()-$rnd).$uname.$pwd), "rand"=>$rnd)))));
		$result = Services_JSON::decode($data);
		return $result["RESULT"];		
	}
	
				  
	/**
	 *  validate a user via json
	 *
	 * @param string $uname
	 * @param string $email
	 * @param string $pass
	 * @param string $vpass
	 *
	 * @return string
	 */		
	function validate($uname, $email, $pass, $vpass){
	
		$rnd = rand(-100000, 100000000);
		$data = file_get_contents(XORTIFY_JSON_API.'?xoops_user_validate='.urlencode(Services_JSON::encode(array("username"=> $this->json_xoops_username, "password"=> $this->json_xoops_password, "validate" => array('uname' => $uname, "pass" => $pass, "vpass" => $vpass, "email" => $email, "time" => time(), "passhash" => sha1((time()-$rnd).$uname.$pass), "rand"=>$rnd)))));
		$result = Services_JSON::decode($data);
		if ($result['ERRNUM']==1){
			return $result["RESULT"];
		} else {
			return false;
		}
	
	}

	/**
	 *  get the xoops site disclaimer via json
	 *
	 * @return string
	 */			
	function network_disclaimer(){

		$data = file_get_contents(XORTIFY_JSON_API.'?xoops_network_disclaimer='.urlencode(Services_JSON::encode(array("username"=> $this->json_xoops_username, "password"=> $this->json_xoops_password))));
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

		$data = file_get_contents(XORTIFY_JSON_API.'?xoops_create_user='.urlencode(Services_JSON::encode(array("username"=> $this->json_xoops_username, "password"=> $this->json_xoops_password, "user" => array('user_viewemail' =>$user_viewemail, 'uname' => $uname, 'email' => $email, 'url' => $url, 'actkey' => $actkey, 'pass' => $pass, 'timezone_offset' => $timezone_offset, 'user_mailok' => $user_mailok, "time" => time(), "siteinfo" => $siteinfo, "passhash" => sha1((time()-$rnd).$uname.$pass), "rand"=>$rnd)))));
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
