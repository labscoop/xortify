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
include_once(dirname(__FILE__).'/inc/authcheck.php');
	
if (!function_exists('xoops_network_disclaimer')) {	

	/*	Xortify Network Disclaimer
	 *  xoops_network_disclaimer($username, $password)
	 *
	 *  @subpackage plugin
	 *
	 *  @param string $username 	Xortify username for function
	 *  @param string $password 	Xortify password or md5 hash of password for function
	 *  @return array
	 */
	function xoops_network_disclaimer($username, $password)
	{	

		global $xoopsModuleConfig, $xoopsConfig;
		
		if ($xoopsModuleConfig['site_user_auth']==1){
			if ($ret = check_for_lock(basename(__FILE__),$username,$password)) { return $ret; }
			if (!checkright(basename(__FILE__),$username,$password)) {
				mark_for_lock(basename(__FILE__),$username,$password);
				return array('ErrNum'=> 9, "ErrDesc" => 'No Permission for plug-in');
			}
		}


		include_once XOOPS_ROOT_PATH.'/class/auth/authfactory.php';
		include_once XOOPS_ROOT_PATH.'/language/'.$xoopsConfig['language'].'/auth.php';
		$xoopsAuth =& XoopsAuthFactory::getAuthConnection();

		if (check_auth_class($xoopsAuth)==true){
			
			$result = $xoopsAuth->network_disclaimer();
			return $result;
			
		} else {
		
			$config_handler =& xoops_gethandler('config');
			$xoopsConfigUser =& $config_handler->getConfigsByCat(XOOPS_CONF_USER);
	
			return array("ERRNUM" => 1, "RESULT" => $xoopsConfigUser['reg_disclaimer']);
		}
		
	}
}
?>