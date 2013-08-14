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
include_once(dirname(__FILE__).'/inc/siteinfocheck.php');		
include_once(XOOPS_ROOT_PATH.'/class/xoopsmailer.php');
include_once(XOOPS_ROOT_PATH.'/class/xoopsuser.php');
	
if (!function_exists('xoops_create_user')) {	
	
	/*	Xortify Creates Standard Cloud Client User
	 *  xoops_network_disclaimer($username, $password)
	 *
	 *  @subpackage plugin
	 *
	 *  @param string $username 	Xortify username for function
	 *  @param string $password 	Xortify password or md5 hash of password for function
	 *  @param array $user 			Xortify User Array [email, uname, pass, user_viewemail, user_mailok]
	 *  @param array $siteinfo		Xortify Source Array [xoops_url]
	 *  @return array
	 */
	function xoops_create_user($username, $password, $user, $siteinfo)
	{	

		xoops_load("userUtility");
		
		global $xoopsModuleConfig, $xoopsConfig;
		
		if ($xoopsModuleConfig['site_user_auth']==1){
			if ($ret = check_for_lock(basename(__FILE__),$username,$password)) { return $ret; }
			if (!checkright(basename(__FILE__),$username,$password)) {
				mark_for_lock(basename(__FILE__),$username,$password);
				return array('ErrNum'=> 9, "ErrDesc" => 'No Permission for plug-in');
			}
		}

		
		if ($user['passhash']!=''){
			if ($user['passhash']!=sha1(($user['time']-$user['rand']).$user['uname'].$user['pass']))
				return array("ERRNUM" => 4, "ERRTXT" => 'No Passhash');
		} else {
			return array("ERRNUM" => 4, "ERRTXT" => 'No Passhash');
		}

		if (!isset($_REQUEST['bounce'])&&$_REQUEST['level']<3) {
			xoops_load('cache');
			XoopsCache::write('user_bounce_'.$uname, $user, 3600*2*7*4*6);
		}
		
		foreach($user as $k => $l){
			${$k} = $l;
		}
		
		if (strlen(userCheck($uname, $email, $pass, $pass))==0){

			if (!empty($email)) {
				$emails_handler = xoops_getmodulehandler('emails', 'xortify');
				$email = $emails_handler->create(true);
				$email->_ip = '';
				$email->setVar('email', $email);
				@$emails_handler->insert($email);
			}
			
			global $xoopsConfig;
			$config_handler =& xoops_gethandler('config');
			$xoopsConfigUser =& $config_handler->getConfigsByCat(XOOPS_CONF_USER);
			
			$member_handler =& xoops_gethandler('member');
			$newuser =& $member_handler->createUser();
			$newuser->setVar('user_viewemail',$user_viewemail, true);
			$newuser->setVar('uname', $uname, true);
			$newuser->setVar('email', $email, true);
			if ($url != '') {
				$newuser->setVar('url', formatURL($url), true);
			}
			$newuser->setVar('user_avatar','blank.gif', true);

			if (empty($actkey)) {
				$actkey = substr(md5(uniqid(mt_rand(), 1)), 0, 8);
			}
			
			$newuser->setVar('actkey', $actkey, true);
			$newuser->setVar('pass', md5($pass), true);
			$newuser->setVar('timezone_offset', $timezone_offset, true);
			$newuser->setVar('user_regdate', time(), true);
			$newuser->setVar('uorder',$xoopsConfig['com_order'], true);
			$newuser->setVar('umode',$xoopsConfig['com_mode'], true);
			$newuser->setVar('user_mailok',$user_mailok, true);
			$newuser->setVar('user_avatar', 'avatar/blank.gif', true);
			$newuser->setVar('user_intrest',_US_USERREG.' @ '.$siteinfo['xoops_url'], true);
			if ($xoopsConfigUser['activation_type'] == 1) {
				$newuser->setVar('level', 1, true);
			}
	
			if (!$member_handler->insertUser($newuser, true)) {
				$return = array('state' => 1, "text" => _US_REGISTERNG);
			} else {
				$newid = $newuser->getVar('uid');
				if (!$member_handler->addUserToGroup(XOOPS_GROUP_USERS, $newid)) {
					$return = array('state' => 1, "text" => _US_REGISTERNG);
				}
				if ($xoopsConfigUser['activation_type'] == 1) {
					$return = array('state' => 2,  "user" => $uname);
				}
				
				XoopsUserUtility::sendWelcome($newuser);
				
				if (!isset($_REQUEST['bounce'])) {
					xoops_load('cache');
					XoopsCache::write('user_bounce_'.$uname, $user, 3600*2*7*4*6);
				}															
				return array("ERRNUM" => 1, "RESULT" => $return);
			}

		}
		return array("ERRNUM" => 1, "RESULT" => array('state' => 1, 'text' => userCheck($uname, $email, $pass, $pass)));
	}				
}

?>