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
if (!function_exists('spiders')) {

	/*	Xortify Provides a list of Spiders since first one - Limited
	 *  spiders($username, $password, $records)
	 *
	 *  @subpackage plugin
	 *
	 *  @param string $username 	Xortify username for function
	 *  @param string $password 	Xortify password or md5 hash of password for function
	 *  @param integer $records 	Number of Records to return
	 *  @return array
	 */
	function spiders($username, $password, $records) {
		global $xoopsModuleConfig, $xoopsDB;
	
		if ($xoopsModuleConfig['site_user_auth']==1){
			if ($ret = check_for_lock(basename(__FILE__),$username,$password)) { return $ret; }
			if (!checkright(basename(__FILE__),$username,$password)) {
				mark_for_lock(basename(__FILE__),$username,$password);
				return array('ErrNum'=> 9, "ErrDesc" => 'No Permission for plug-in');
			}
		}
	
	
		$records = ($records!=0)?intval($records):600;
	
		$sql = "SELECT * FROM ".$xoopsDB->prefix('spiders'). ' limit '.intval($records);
		$result = $xoopsDB->query($sql);
		$ret = array();
		while($robot = $xoopsDB->fetchArray($result) ){
			$id++;
			foreach(array(	'robot-id','robot-name','robot-cover-url','robot-details-url','robot-owner-name','robot-owner-url','robot-owner-email',
							'robot-status','robot-purpose','robot-type','robot-platform','robot-availability','robot-exclusion','robot-exclusion-useragent',
							'robot-noindex','robot-host','robot-from','robot-useragent','robot-language','robot-description','robot-history',
							'robot-environment','modified-date','modified-by','robot-safeuseragent','robot-handlesession' ) as $field)
				$ret[$id][$field] = $robot[$field];
			
		}
		return array("spiders" => count($ret), "made" => time(), "robots" => $ret);
	
	}
}

?>