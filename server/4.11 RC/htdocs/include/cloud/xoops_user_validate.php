<?php
/**
 * Xortify API Function
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Chronolabs Coopertive (Australia)  http://web.labs.coop
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         api
 * @author         	Simon Roberts (wishcraft) - meshy@labs.coop
 */
include_once(dirname(__FILE__).'/inc/usercheck.php');	
include_once(dirname(__FILE__).'/inc/authcheck.php');

if (!function_exists('xoops_user_validate')) {
	
	/*	Xortify User Validation
	 *  xoops_user_validate($username, $password, $validate)
	 *  
	 *  @subpackage plugin
	 *  
	 *  @param string $username 	Xortify username for function
	 *  @param string $password 	Xortify password or md5 hash of password for function
	 *  @param array $validate 		Validation Array [uname, email, pass, vpass, time, rand]
	 *  @return array
	 */
	function xoops_user_validate($username, $password, $validate)
	{	
		global $xoopsModuleConfig, $xoopsConfig;
		if ($xoopsModuleConfig['site_user_auth']==1){
			if ($ret = check_for_lock(basename(__FILE__),$username,$password)) { return $ret; }
			if (!checkright(basename(__FILE__),$username,$password)) {
				mark_for_lock(basename(__FILE__),$username,$password);
				return array('ErrNum'=> 9, "ErrDesc" => 'No Permission for plug-in');
			}
		}

		if ($validate['passhash']!=''){
			if ($validate['passhash']!=sha1(($validate['time']-$validate['rand']).$validate['uname'].$validate['pass']))
				return array("ERRNUM" => 4, "ERRTXT" => 'No Passhash');
		} else {
			return array("ERRNUM" => 4, "ERRTXT" => 'No Passhash');
		}
		return array('ERRNUM' => 1, 'RESULT' => userCheck($validate['uname'], $validate['email'], $validate['pass'], $validate['vpass']));
	}
}
	
	
?>