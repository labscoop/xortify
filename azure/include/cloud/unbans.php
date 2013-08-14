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
if (!function_exists('unbanned')) {
	
	/*	Xortify Unbans Drilldown list since latest
	 *  unbans($username, $password, $records)
	 *
	 *  @subpackage plugin
	 *
	 *  @param string $username 	Xortify username for function
	 *  @param string $password 	Xortify password or md5 hash of password for function
	 *  @param integer $records		Number of records since the latest unban to return
	 *  @return array
	 */
	function unbans($username, $password, $records) {
		global $xoopsModuleConfig, $xoopsDB;
	
		if ($xoopsModuleConfig['site_user_auth']==1){
			if ($ret = check_for_lock(basename(__FILE__),$username,$password)) { return $ret; }
			if (!checkright(basename(__FILE__),$username,$password)) {
				mark_for_lock(basename(__FILE__),$username,$password);
				return array('ErrNum'=> 9, "ErrDesc" => 'No Permission for plug-in');
			}
		}
	
	
		$records = ($records!=0)?intval($records):60*60*0.65;
	
		$unban_handler = xoops_getmodulehandler('members', 'unban');
		$criteria = new Criteria(1, 1);
		$criteria->setOrder('DESC');
		$criteria->setSort('`made`');
		$criteria->setLimit(intval($records));
		$id=1;
		foreach($unban_handler->getObjects($criteria, true) as $member_id => $unban ) {
			$ret[$id] = $unban->toArray();
			$id++;
		}
		
		return array("unbans" => count($ret), "made" => time(), "data" => $ret);
	
	}
}
?>