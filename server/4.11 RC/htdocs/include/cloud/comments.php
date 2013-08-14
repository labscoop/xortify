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
if (!function_exists('comments')) {

	/*	Provide List of Comments for ID of a module ie. Ban/Unban.
	 *  comments($username, $password, $itemid, $module='ban')
	 *
	 *  @subpackage plugin
	 *
	 *  @param string $username 	Xortify username for function
	 *  @param string $password 	Xortify password or md5 hash of password for function
	 *  @param integer $itemid 		Item ID to return comments on
	 *  @param string $module 		Module to provide bans from ie. ban/unban
	 *  @return array
	 */
	function comments($username, $password, $itemid, $module='ban') {
		global $xoopsModuleConfig, $xoopsDB;
		
		if ($xoopsModuleConfig['site_user_auth']==1){
			if ($ret = check_for_lock(basename(__FILE__),$username,$password)) { return $ret; }
			if (!checkright(basename(__FILE__),$username,$password)) {
				mark_for_lock(basename(__FILE__),$username,$password);
				return array('ErrNum'=> 9, "ErrDesc" => 'No Permission for plug-in');
			}
		}
		
		$module_handler = xoops_gethandler('module');
		$xoModule = $module_handler->getByDirname($module); 
		if (is_object($xoModule)) {
			$comment_handler = xoops_gethandler('comment');
			$user_handler = xoops_gethandler('user');
			$criteria = new CriteriaCompo(new Criteria('com_modid', $xoModule->getVar('mid')));
			$criteria->add(new Criteria('com_itemid', $itemid));
			$id=1;
			foreach($comment_handler->getObjects($criteria, true) as $com_id => $comment ) {
				$ret[$id] = $comment->toArray();
				$user = $user_handler->get($comment->getVar('com_uid'));
				if (is_object($user))
					$ret[$id]['com_uname'] = $user->getVar('uname');
				$id++;
			}	
			return array("comments" => count($ret), "made" => time(), "data" => $ret);
		}
		return array("comments" => 0, "made" => time(), "data" => array());
	}
}
?>