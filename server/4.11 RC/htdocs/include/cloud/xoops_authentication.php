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
if (!function_exists('xoops_authentication')) {
	
	/*	Xortify Authenticates a Standard Cloud Client User
	 *  xoops_authentication($username, $password, $auth)
	 *
	 *  @subpackage plugin
	 *
	 *  @param string $username 	Xortify username for function
	 *  @param string $password 	Xortify password or md5 hash of password for function
	 *  @param array $auth 			Xortify User Array [username, password(md5)]
	 *  @return array
 	 */
	function xoops_authentication($username, $password, $auth)
	{	

		global $xoopsModuleConfig, $xoopsConfig;
		
		if ($xoopsModuleConfig['site_user_auth']==1){
			if ($ret = check_for_lock(basename(__FILE__),$username,$password)) { return $ret; }
			if (!checkright(basename(__FILE__),$username,$password)) {
				mark_for_lock(basename(__FILE__),$username,$password);
				return array('ErrNum'=> 9, "ErrDesc" => 'No Permission for plug-in');
			}
		}


		if ($auth['passhash']!=''){
			if ($auth['passhash']!=sha1(($auth['time']-$auth['rand']).$auth['username'].$auth['password']))
				return array("ERRNUM" => 4, "ERRTXT" => 'No Passhash');
		} else {
			return array("ERRNUM" => 4, "ERRTXT" => 'No Passhash');
		}

		require_once XOOPS_ROOT_PATH.'/class/auth/authfactory.php';
		require_once XOOPS_ROOT_PATH.'/language/'.$xoopsConfig['language'].'/auth.php';
		$xoopsAuth =& XoopsAuthFactory::getAuthConnection(addslashes($auth['username']));
		$user = $xoopsAuth->authenticate(addslashes($auth['username']), addslashes($auth['password']));
		
		if(is_object($user))
			$row =array("uid" => $user->getVar('uid'),"uname" => $user->getVar('uname'),"email" => $user->getVar('email'), "user_from" => $user->getVar('user_from'), "name" => $user->getVar('name'), "url" => $user->getVar('url'), "user_icq" => $user->getVar('user_icq'), "user_sig" => $user->getVar('user_sig'), "user_viewemail" => $user->getVar('user_viewemail'), "user_aim" => $user->getVar('user_aim'), "user_yim" => $user->getVar('user_yim'), "user_msnm" => $user->getVar('user_msnm'), "attachsig" => $user->getVar('attachsig'), "timezone_offset" => $user->getVar('timezone_offset'), "notify_method" => $user->getVar('notify_method'), "user_occ" => $user->getVar('user_occ'), "bio" => $user->getVar('bio'), "user_intrest" => $user->getVar('user_intrest'), "user_mailok" => $user->getVar('user_mailok'));
						
		
		if (!empty($row)){
			return array("ERRNUM" => 1, "RESULT" => $row);
		} else {
			return array("ERRNUM" => 3, "ERRTXT" => _ERR_FUNCTION_FAIL);
		}				

	}
}
?>