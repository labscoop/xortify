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

if (!defined('usercheck_inc')) {
 
 	global $xoopsConfig;
	
	if( file_exists(XOOPS_ROOT_PATH."/language/".$xoopsConfig['language']."/user.php") ) {
		include_once(XOOPS_ROOT_PATH."/language/".$xoopsConfig['language']."/user.php");
	} else {
		include_once(XOOPS_ROOT_PATH."/language/english/user.php");
	}
	
 	define('usercheck_inc',true);
	
	$ret= explode(" ",XOOPS_VERSION);
	$ver= explode(".",$ret[1]);
	
	if ($ret[0]>=2&&$ret[1]>=3){

		xoops_load("userUtility");
		
		/*	Xortify User Validation
		 *  userCheck($uname, $email, $pass, $vpass)
		 *
		 *  @subpackage plugin
		 *
		 *  @param string $uname	 	Xortify username for function
		 *  @param string $email	 	Xortify email for function
		 *  @param string $pass		 	Xortify password
		 *  @param string $vpass	 	Xortify password for second verify field
		 */
		function userCheck($uname, $email, $pass, $vpass)
		{
			return XoopsUserUtility::validate($uname, $email, $pass, $vpass);
		}
	
	} else {

		/*	Xortify User Validation
		 *  userCheck($uname, $email, $pass, $vpass)
		 *
		 *  @subpackage plugin
		 *
		 *  @param string $uname	 	Xortify username for function
		 *  @param string $email	 	Xortify email for function
		 *  @param string $pass		 	Xortify password
		 *  @param string $vpass	 	Xortify password for second verify field
		 */
		function userCheck($uname, $email, $pass, $vpass)
		{
			$config_handler =& xoops_gethandler('config');
			$xoopsConfigUser =& $config_handler->getConfigsByCat(XOOPS_CONF_USER);
			$xoopsDB =& Database::getInstance();
			$myts =& MyTextSanitizer::getInstance();
			$stop = '';
			if (!checkEmail($email)) {
				$stop .= _US_INVALIDMAIL.'<br />';
			}
			foreach ($xoopsConfigUser['bad_emails'] as $be) {
				if (!empty($be) && preg_match("/".$be."/i", $email)) {
					$stop .= _US_INVALIDMAIL.'<br />';
					break;
				}
			}
			if (strrpos($email,' ') > 0) {
				$stop .= _US_EMAILNOSPACES.'<br />';
			}
			$uname = xoops_trim($uname);
			switch ($xoopsConfigUser['uname_test_level']) {
			case 0:
				// strict
				$restriction = '/[^a-zA-Z0-9\_\-]/';
				break;
			case 1:
				// medium
				$restriction = '/[^a-zA-Z0-9\_\-\<\>\,\.\$\%\#\@\!\\\'\"]/';
				break;
			case 2:
				// loose
				$restriction = '/[\000-\040]/';
				break;
			}
			if (empty($uname) || preg_match($restriction, $uname)) {
				$stop .= _US_INVALIDNICKNAME."<br />";
			}
			if (strlen($uname) > $xoopsConfigUser['maxuname']) {
				$stop .= sprintf(_US_NICKNAMETOOLONG, $xoopsConfigUser['maxuname'])."<br />";
			}
			if (strlen($uname) < $xoopsConfigUser['minuname']) {
				$stop .= sprintf(_US_NICKNAMETOOSHORT, $xoopsConfigUser['minuname'])."<br />";
			}
			foreach ($xoopsConfigUser['bad_unames'] as $bu) {
				if (!empty($bu) && preg_match("/".$bu."/i", $uname)) {
					$stop .= _US_NAMERESERVED."<br />";
					break;
				}
			}
			if (strrpos($uname, ' ') > 0) {
				$stop .= _US_NICKNAMENOSPACES."<br />";
			}
			$sql = sprintf('SELECT COUNT(*) FROM %s WHERE uname = %s', $xoopsDB->prefix('users'), $xoopsDB->quoteString(addslashes($uname)));
			$result = $xoopsDB->query($sql);
			list($count) = $xoopsDB->fetchRow($result);
			if ($count > 0) {
				$stop .= _US_NICKNAMETAKEN."<br />";
			}
			$count = 0;
			if ( $email ) {
				$sql = sprintf('SELECT COUNT(*) FROM %s WHERE email = %s', $xoopsDB->prefix('users'), $xoopsDB->quoteString(addslashes($email)));
				$result = $xoopsDB->query($sql);
				list($count) = $xoopsDB->fetchRow($result);
				if ( $count > 0 ) {
					$stop .= _US_EMAILTAKEN."<br />";
				}
			}
			if ( !isset($pass) || $pass == '' || !isset($vpass) || $vpass == '' ) {
				$stop .= _US_ENTERPWD.'<br />';
			}
			if ( (isset($pass)) && ($pass != $vpass) ) {
				$stop .= _US_PASSNOTSAME.'<br />';
			} elseif ( ($pass != '') && (strlen($pass) < $xoopsConfigUser['minpass']) ) {
				$stop .= sprintf(_US_PWDTOOSHORT,$xoopsConfigUser['minpass'])."<br />";
			}
			return $stop;
		}
	}
	
  }