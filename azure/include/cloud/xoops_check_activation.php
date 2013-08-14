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
		
if (!function_exists('xoops_check_activation')) {

	/*	Xortify Checks Activation on a Standard Cloud Client User
	 *  xoops_check_activation($username, $password, $user)
	 *
	 *  @subpackage plugin
	 *
	 *  @param string $username 	Xortify username for function
	 *  @param string $password 	Xortify password or md5 hash of password for function
	 *  @param array $user 			Xortify User Array [uname, actkey]
	 *  @return array
	 */
	function xoops_check_activation($username, $password, $user)
	{	

		global $xoopsModuleConfig, $xoopsConfig;

		if ($xoopsModuleConfig['site_user_auth']==1){
			if ($ret = check_for_lock(basename(__FILE__),$username,$password)) { return $ret; }
			if (!checkright(basename(__FILE__),$username,$password)) {
				mark_for_lock(basename(__FILE__),$username,$password);
				return array('ErrNum'=> 9, "ErrDesc" => 'No Permission for plug-in');
			}
		}


		if ($user['passhash']!=''){
			if ($user['passhash']!=sha1(($user['time']-$user['rand']).$user['uname'].$user['actkey']))
				return array("ERRNUM" => 4, "ERRTXT" => 'No Passhash');
		} else {
			return array("ERRNUM" => 4, "ERRTXT" => 'No Passhash');
		}
		
		foreach($user as $k => $l){
			${$k} = $l;
		}
		
		$siteinfo = check_siteinfo($siteinfo);
		
		include_once XOOPS_ROOT_PATH.'/class/auth/authfactory.php';
		include_once XOOPS_ROOT_PATH.'/language/'.$xoopsConfig['language'].'/auth.php';
		$xoopsAuth =& XoopsAuthFactory::getAuthConnection(addslashes($uname));

		if (check_auth_class($xoopsAuth)==true){
			
			$result = $xoopsAuth->check_activation($uname, $actkey, $siteinfo);
			return $result;
			
		} else {
	
			global $xoopsConfig, $xoopsConfigUser;

			global $xoopsDB;
			$sql = "SELECT uid FROM ".$xoopsDB->prefix('users')." WHERE uname = '$uname'";
			$ret = $xoopsDB->query($sql);
			$row = $xoopsDB->fetchArray($ret);
			
		    $member_handler =& xoops_gethandler('member');
			$thisuser =& $member_handler->getUser($row['uid']);
			if (!is_object($thisuser)) {
				exit();
			}
			if ($thisuser->getVar('actkey') != $actkey) {
				$return = array("state" => _US_STATE_ONE, "action" => "redirect_header", "url" => 'index.php', "opt" => 5, "text" => _US_ACTKEYNOT);
			} else {
				if ($thisuser->getVar('level') > 0 ) {
					$return = array("state" => _US_STATE_ONE, "action" => "redirect_header", "url" => 'user.php', "opt" => 5, "text" => _US_ACONTACT, "set" => false);
				} else {
					if (false != $member_handler->activateUser($thisuser)) {
						$config_handler =& xoops_gethandler('config');
						$xoopsConfigUser = $config_handler->getConfigsByCat(XOOPS_CONF_USER);
						if ($xoopsConfigUser['activation_type'] == 2) {
							$myts =& MyTextSanitizer::getInstance();
							$xoopsMailer =& xoops_getMailer();
							$xoopsMailer->useMail();
							$xoopsMailer->setTemplate('activated.tpl');
							$xoopsMailer->assign('SITENAME', $siteinfo['sitename']);
							$xoopsMailer->assign('ADMINMAIL', $siteinfo['adminmail']);
							$xoopsMailer->assign('SITEURL', $siteinfo['xoops_url']."/");
							$xoopsMailer->setToUsers($thisuser);
							$xoopsMailer->setFromEmail($siteinfo['adminmail']);
							$xoopsMailer->setFromName($siteinfo['sitename']);
							$xoopsMailer->setSubject(sprintf(_US_YOURACCOUNT,$siteinfo['sitename']));			
							if ( !$xoopsMailer->send() ) {
								$return = array("state" => _US_STATE_TWO, "text" => sprintf(_US_ACTVMAILNG, $thisuser->getVar('uname')));
							} else {
								$return = array("state" => _US_STATE_TWO, "text" => sprintf(_US_ACTVMAILOK, $thisuser->getVar('uname')));
							}
					
						} else {
							$local = explode(' @ ',$thisuser->getVar('user_intrest'));
							if ($local[0] == _US_USERREG){ 
								$return = array("state" => _US_STATE_ONE, "action" => "redirect_header", "url" => $local[1].'/user.php', "opt" => 5, "text" => _US_ACTLOGIN, "set" => false);
							} else {
								$return = array("state" => _US_STATE_ONE, "action" => "redirect_header", "url" => 'user.php', "opt" => 5, "text" => _US_ACTLOGIN, "set" => false);
							}
						}
					} else {
						$return = array("state" => _US_STATE_ONE, "action" => "redirect_header", "url" => 'index.php', "opt" => 5, "text" => 'Activation failed!');
					}
				}
			}
			
			return $return;	

		}
	}
}

	
?>