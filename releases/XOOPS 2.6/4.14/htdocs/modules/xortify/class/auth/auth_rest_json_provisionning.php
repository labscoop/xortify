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
 * @copyright:		Chronolabs Cooperative 2013 � Simon Roberts (www.simonaroberts.com)
 * @download:		http://sourceforge.net/projects/xortify
 * @file:			auth_json_provisionning.php		
 * @description:	Auth Provisionning Library for wGET JSON Package
 * @date:			09/09/2012 19:34 AEST
 * @license:		GNU3 (http://web.labs.coop/public/legal/general-public-licence/13,3.html)
 * @package			xortify
 * @subpackage		classes
 * @author			Simon A. Roberts - wishcraft (simon@labs.coop)

 * 
 */

/*
 * Xortify Auth Provisioning Class
*/
class XortifyAuthRest_JsonProvisionning {

	/*
	 * Authentication Factory Instance
	 */
	var $_auth_instance;
	
	/*
	 * Get Instance of this Provisioning Class 
	 * 
	 * @param object $auth_instance
	 * @return object
	 */
	function &getInstance(&$auth_instance)
	{
		static $provis_instance;				
		if (!isset($provis_instance)) {
			$provis_instance = new XortifyAuthRest_JsonProvisionning($auth_instance);
		}
		return $provis_instance;
	}

	/**
	 * Authentication Service constructor
	 */
	function XortifyAuthRest_JsonProvisionning (&$auth_instance) {
		$this->_auth_instance = &$auth_instance;        
		$config_handler = $GLOBALS['xoops']->getHandler('config');    
		$config = $config_handler->getConfigsByCat(XOOPS_CONF_AUTH);
		foreach ($config as $key => $val) {
			$this->$key = $val;
		}
		$config_gen = $config_handler->getConfigsByCat(XOOPS_CONF);
		$this->default_TZ = $config_gen['default_TZ'];
		$this->theme_set = $config_gen['theme_set'];
		$this->com_mode = $config_gen['com_mode'];
		$this->com_order = $config_gen['com_order'];        
	}

	/**
	 *  Return a Xortify User Object 
	 *
	 * @return XortifyUser or false
	 */	
	function getXortifyUser($uname) {
		$member_handler = $GLOBALS['xoops']->getHandler('member');
		$criteria = new Criteria('uname', $uname);
		$getuser = $member_handler->getUsers($criteria);
		if (count($getuser) == 1)
			return $getuser[0];
		else return false;		
	}
	
	/**
	 *  Launch the synchronisation process 
	 *
	 * @return bool
	 */		
	function sync($datas, $uname, $pwd = null) {
		$xoopsUser = $this->getXortifyUser($uname);		
		if (!$xoopsUser) { // Xortify User Database not exists
			if ($this->json_provisionning) { 
				$xoopsUser = $this->add($datas, $uname, $pwd);
			} else $this->_auth_instance->setErrors(0, sprintf(_AUTH_LDAP_XOOPS_USER_NOTFOUND, $uname));
		} else { // Xortify User Database exists
			
		}
		return $xoopsUser;
	}

	/**
	 *  Add a new user to the system
	 *
	 * @return bool
	 */		
	function add($datas, $uname, $pwd = null) {
		$ret = false;
		$member_handler = $GLOBALS['xoops']->getHandler('member');
		// Create XOOPS Database User
		$newuser = $member_handler->createUser();
		$newuser->setVar('uname', $uname);
		$newuser->setVar('pass', md5(stripslashes($pwd)));
		$newuser->setVar('email', $datas['email']);
		$newuser->setVar('rank', 0);
		$newuser->setVar('level', 1);
		$newuser->setVar('timezone_offset', $this->default_TZ);
		$newuser->setVar('theme', 	$this->theme_set);
		$newuser->setVar('umode', 	$this->com_mode);
		$newuser->setVar('uorder', 	$this->com_order);
		if ($this->json_provisionning)
			$tab_mapping = explode('|', $this->json_field_mapping);
		else 
			$tab_mapping = explode('|', $this->ldap_field_mapping);
			
		foreach ($tab_mapping as $mapping) {
			$fields = explode('=', trim($mapping));
			if ($fields[0] && $fields[1])
				$newuser->setVar(trim($fields[0]), utf8_decode($datas[trim($fields[1])]));
		}        
		if ($member_handler->insertUser($newuser)) {
		} 
		if ($member_handler->insertUser($newuser)) {
			foreach ($this->json_provisionning_group as $groupid)
				$member_handler->addUserToGroup($groupid, $newuser->getVar('uid'));
			$newuser->unsetNew();
			return $newuser;
		} else redirect_header(XOOPS_URL.'/user.php', 5, $newuser->getHtmlErrors());      
		
		$newuser->unsetNew();
		return $newuser;
		//else redirect_header(XOOPS_URL.'/user.php', 5, $newuser->getHtmlErrors());         
		return $ret;	
	}
	
	/**
	 *  Modify user information
	 *
	 * @return bool
	 */		
	function change(&$xoopsUser, $datas, $uname, $pwd = null) {	
		$ret = false;
		$member_handler = $GLOBALS['xoops']->getHandler('member');
		$xoopsUser->setVar('pass', md5(stripslashes($pwd)));
		$tab_mapping = explode('|', $this->ldap_field_mapping);
		foreach ($tab_mapping as $mapping) {
			$fields = explode('=', trim($mapping));
			if ($fields[0] && $fields[1])
				$xoopsUser->setVar(trim($fields[0]), utf8_decode($datas[trim($fields[1])][0]));
		}
		if ($member_handler->insertUser($xoopsUser)) {
			return $xoopsUser;
		} else redirect_header(XOOPS_URL.'/user.php', 5, $xoopsUser->getHtmlErrors());         
		return $ret;
	}
	
	function change_json(&$xoopsUser, $datas, $uname, $pwd = null) {	
		$ret = false;
		$member_handler = $GLOBALS['xoops']->getHandler('member');
		$xoopsUser->setVar('pass', md5(stripslashes($pwd)));
		$tab_mapping = explode('|', $this->json_field_mapping);
		foreach ($tab_mapping as $mapping) {
			$fields = explode('=', trim($mapping));
			if ($fields[0] && $fields[1])
				$xoopsUser->setVar(trim($fields[0]), utf8_decode($datas[trim($fields[1])][0]));
		}
		if ($member_handler->insertUser($xoopsUser)) {
			return $xoopsUser;
		} else redirect_header(XOOPS_URL.'/user.php', 5, $xoopsUser->getHtmlErrors());         
		return $ret;
	}

	/**
	 *  Modify a user
	 *
	 * @return bool
	 */		
	function delete() {
	}

	/**
	 *  Suspend a user
	 *
	 * @return bool
	 */		
	function suspend() {
	}

	/**
	 *  Restore a user
	 *
	 * @return bool
	 */		
	function restore() {
	}

	/**
	 *  Add a new user to the system
	 *
	 * @return bool
	 */		
	function resetpwd() {
	}
	
	
}
// end class
 
?>
