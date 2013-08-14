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
 * This File:	auth_curlserialised_provisionning.php		
 * Description:	Auth Provisionning Library for CURL Serialised Package
 * Date:		09/09/2012 19:34 AEST
 * License:		GNU3
 * 
 */

class XortifyAuthCurlserialisedProvisionning {

	var $_auth_instance;
	
	function &getInstance(&$auth_instance)
	{
		static $provis_instance;				
		if (!isset($provis_instance)) {
			$provis_instance = new XortifyAuthWgetserialisedProvisionning($auth_instance);
		}
		return $provis_instance;
	}

	/**
	 * Authentication Service constructor
	 */
	function __construct (&$auth_instance) {
		$this->_auth_instance = &$auth_instance;        
		/**
		 * Syronise User to Database/Storage Mechanism
		 */        
	}

	/**
	 *  Return a Xortify User Object 
	 *
	 * @return XortifyUser or false
	 */	
	function getXortifyUser($uname) {
		/**
		 * Get a USer Object based on $uname
		 */		
	}
	
	/**
	 *  Launch the synchronisation process 
	 *
	 * @return bool
	 */		
	function sync($datas, $uname, $pwd = null) {
		/**
		 * Syronise User to Database/Storage Mechanism
		 */
	}

	/**
	 *  Add a new user to the system
	 *
	 * @return bool
	 */		
	function add($datas, $uname, $pwd = null) {
		/**
		 * Add User to Database/Storage Mechanism
		 */
		return $newuser;
	}
	
	/**
	 *  Modify user information
	 *
	 * @return bool
	 */		
	function change(&$xoopsUser, $datas, $uname, $pwd = null) {	
		/**
		 * Update & Change User to Database/Storage Mechanism
		 */
	}
	
	function change_json(&$xoopsUser, $datas, $uname, $pwd = null) {	
		/**
		 * Update & Change User to Database/Storage Mechanism
		 */
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
