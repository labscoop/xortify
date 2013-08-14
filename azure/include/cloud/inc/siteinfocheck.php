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


if (!function_exists('check_siteinfo')){
	/*	Checks the Siteinfo Array
	 *  check_siteinfo($siteinfo)
	 *
	 *  @subpackage plugin
	 *
	 *  @param array $siteinfo	 	Xortify client siteinfo [sitename, adminmail, xoops_url]
	 */
	function check_siteinfo($siteinfo) {

		global $xoopsConfig;
		if (!isset($siteinfo)||empty($siteinfo)||!is_array($siteinfo)){
			$siteinfo = array();
			$siteinfo['sitename'] == $xoopsConfig['sitename'];
			$siteinfo['adminmail'] == $xoopsConfig['adminmail'];
			$siteinfo['xoops_url'] == XOOPS_URL;
		}
		
		if (!isset($siteinfo['sitename'])&&empty($siteinfo['sitename']))
			$siteinfo['sitename'] == $xoopsConfig['sitename'];
		

		if (!isset($siteinfo['adminmail'])&&empty($siteinfo['adminmail']))
			$siteinfo['adminmail'] == $xoopsConfig['adminmail'];
		

		if (!isset($siteinfo['xoops_url'])&&empty($siteinfo['xoops_url']))
			$siteinfo['xoops_url'] == XOOPS_URL;
		
		
		return $siteinfo;	
	}
}	
?>