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

class XortifyAuth {

	var	$_dao;

	var	$_errors;
	/**
	 * Authentication Service constructor
	 */
	function XortifyAuth (&$dao){
		$this->_dao = $dao;
	}

	/**
	 * @abstract need to be write in the dervied class
	 */	
	function authenticate() {
		$authenticated = false;
				
		return $authenticated;
	}		
	
	/**
	 * add an error 
	 * 
	 * @param string $value error to add
	 * @access public
	 */
	function setErrors($err_no, $err_str)
	{
		$this->_errors[$err_no] = trim($err_str);
	}

	/**
	 * return the errors for this object as an array
	 * 
	 * @return array an array of errors
	 * @access public
	 */
	function getErrors()
	{
		return $this->_errors;
	}

	/**
	 * return the errors for this object as html
	 * 
	 * @return string html listing the errors
	 * @access public
	 */
	function getHtmlErrors()
	{
		global $xoopsConfig;
		$ret = '<br>';
		if ( $xoopsConfig['debug_mode'] == 1 || $xoopsConfig['debug_mode'] == 2 ) 
		{	       
			if (!empty($this->_errors)) {
				foreach ($this->_errors as $errno => $errstr) {            	
					$ret .=  $errstr . '<br/>';
				}
			} else {
				$ret .= _NONE.'<br />';
			}        
			$ret .= sprintf(_AUTH_MSG_AUTH_METHOD, $this->auth_method);
		}
		else {
			$ret .= _US_INCORRECTLOGIN;
		}
		return $ret;
	}	

	/**
	 * checks for variables require in siteinfo package in the auth library
	 * 
	 * @param array $siteinfo 
	 *
	 * @return array $siteinfo
	 * @access public
	 */
	function check_siteinfo($siteinfo){

		global $xoopsConfig;
		if (!isset($siteinfo)||empty($siteinfo)||!is_array($siteinfo)){
			$siteinfo = array();
			$siteinfo['sitename'] = $xoopsConfig['sitename'];
			$siteinfo['adminmail'] = $xoopsConfig['adminmail'];
			$siteinfo['systemkey'] = $xoopsConfig['systemkey'];
			$siteinfo['xoops_url'] = XOOPS_URL;
		}
		
		if (!isset($siteinfo['sitename'])||empty($siteinfo['sitename']))
			$siteinfo['sitename'] = $xoopsConfig['sitename'];

		if (!isset($siteinfo['adminmail'])||empty($siteinfo['adminmail']))
			$siteinfo['adminmail'] = $xoopsConfig['adminmail'];
		
		if (!isset($siteinfo['xoops_url'])||empty($siteinfo['xoops_url']))
			$siteinfo['xoops_url'] = XOOPS_URL;

		if (!isset($siteinfo['systemkey'])||empty($siteinfo['systemkey']))
			$siteinfo['systemkey'] = $xoopsConfig['systemkey'];
		
		return $siteinfo;	
	}
}


?>
