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
if (!function_exists('cloud_users')) {

	/*	Provide List of Users on the cloud based from the first to latest.
	 *  cloud_users($username, $password, $records, $start=0)
	 *
	 *  @subpackage plugin
	 *
	 *  @param string $username 	Xortify username for function
	 *  @param string $password 	Xortify password or md5 hash of password for function
	 *  @param integer $records 	Number of Records to Return
	 *  @param integer $start 		Point to start from
	 *  @return array
	 */
	function cloud_users($username, $password, $records, $start=0) {
		global $xoopsModuleConfig, $xoopsDB;
	
		if ($xoopsModuleConfig['site_user_auth']==1){
			if ($ret = check_for_lock(basename(__FILE__),$username,$password)) { return $ret; }
			if (!checkright(basename(__FILE__),$username,$password)) {
				mark_for_lock(basename(__FILE__),$username,$password);
				return array('ErrNum'=> 9, "ErrDesc" => 'No Permission for plug-in');
			}
		}
	
	
		$records = ($records!=0)?intval($records):50;
	
	
		$user_handler = xoops_gethandler('user');
		$criteria = new Criteria(1, 1);
		$criteria->setOrder('DESC');
		$criteria->setSort('`uid`');
		$criteria->setLimit(intval($records));
		$criteria->setStart(intval($start));
		
		$id=1;
		foreach($user_handler->getObjects($criteria, true) as $uid => $user ) {
			$ret[$uid] = $user->toArray();
		}
		
		return array("cloud_users" => count($ret), "made" => time(), "data" => $ret);
	
	}
}

?>