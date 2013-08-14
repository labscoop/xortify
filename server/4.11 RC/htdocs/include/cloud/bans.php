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
if (!function_exists('bans')) {

	/*	Gets from Xortify Cloud Ban List Starting at the most recent from start limitation
	 *  bans($username, $password, $records, $start=0)
	 *
	 *  @subpackage plugin
	 *
	 *  @param string $username 	Xortify username for function
	 *  @param string $password 	Xortify password or md5 hash of password for function
	 *  @param integer $records 	Number of Records to Return
	 *  @param integer $start 		Start list from this index number starting at the most recent
	 *  @return array
	 */
	function bans($username, $password, $records, $start=0) {
		global $xoopsModuleConfig, $xoopsDB;
	
		if ($xoopsModuleConfig['site_user_auth']==1){
			if ($ret = check_for_lock(basename(__FILE__),$username,$password)) { return $ret; }
			if (!checkright(basename(__FILE__),$username,$password)) {
				mark_for_lock(basename(__FILE__),$username,$password);
				return array('ErrNum'=> 9, "ErrDesc" => 'No Permission for plug-in');
			}
		}
	
	
		$records = ($records!=0)?intval($records):60*60*0.65;
	
	
		$ban_handler = xoops_getmodulehandler('members', 'ban');
		$criteria = new Criteria(1, 1);
		$criteria->setOrder('DESC');
		$criteria->setSort('`made`');
		$criteria->setLimit(intval($records));
		$criteria->setStart(intval($start));
		
		$id=1;
		foreach($ban_handler->getObjects($criteria, true) as $member_id => $ban ) {
			$ret[$id] = $ban->toArray();
			$id++;
		}	
		return array("bans" => count($ret), "made" => time(), "data" => $ret);
	
	}
}
?>