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
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'translator.php';

if (!function_exists('languages')) {
	
	/*	Provide List of Available Languages for Spoof forms etc.
	 *  languages($username, $password)
	 *
	 *  @subpackage plugin
	 *
	 *  @param string $username 	Xortify username for function
	 *  @param string $password 	Xortify password or md5 hash of password for function
	 *  @return array
	 */
	function languages($username, $password) {
		global $xoopsModuleConfig, $xoopsDB;
	
		if ($xoopsModuleConfig['site_user_auth']==1){
			if ($ret = check_for_lock(basename(__FILE__),$username,$password)) { return $ret; }
			if (!checkright(basename(__FILE__),$username,$password)) {
				mark_for_lock(basename(__FILE__),$username,$password);
				return array('ErrNum'=> 9, "ErrDesc" => 'No Permission for plug-in');
			}
		}
	
		$ret = array();
		foreach(GooglosityTranslator::_languages as $iso => $folder) {
			if (!in_array($iso, array_keys($ret)))
				if (!in_array(strtolower($folder), $ret))
					$ret[$iso] = strtolower($folder);
		}
		foreach(BingTranslator::_languages as $iso => $folder) {
			if (!in_array($iso, array_keys($ret)))
				if (!in_array(strtolower($folder), $ret))
					$ret[$iso] = strtolower($folder);
		}
		return array("languages" => count($ret), "made" => time(), "data" => $ret);
	
	}
}
?>