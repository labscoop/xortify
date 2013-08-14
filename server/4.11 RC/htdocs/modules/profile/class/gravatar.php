<?php
/**
 * Extended User Profile
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
 * @package         profile
 * @since           2.5.5
 * @author          Simon Roberts <simon@labs.coop>
 * @version         $Id: functions.php 8066 2011-11-06 05:09:33Z beckmi $
 */

/**
 * @package profile
 * @copyright copyright &copy; 2013 XOOPS.org
 */
class ProfileGravatarHandler {

	/**
	 * @var String
	 */
	var $prefix = 'grv_';
	
	/**
	 * Checks if Image is Updatable
	 * @param XoopsUser $user
	 * @return boolean
	 */
	function _updatable(XoopsUser $user) {
		if (!file_exists(XOOPS_UPLOAD_PATH . '/' . $user->getVar('user_avatar')) || $user->getVar('user_avatar') == '' || strpos($user->getVar('user_avatar'), 'blank') != 0 || strpos($user->getVar('user_avatar'), $this->prefix) != 0 ) {
			return true;
		}
		return false;
	}
	
	/**
	 * Get the Avarta Path
	 * @param XoopsUser $user
	 * @return string
	 */
	function _getAvatarPath(XoopsUser $user) {
		return XOOPS_UPLOAD_PATH . '/' . $user->getVar('user_avatar');
	}
	
	/**
	 * Saves the Raw Data of the Avarta and Updates it in the database
	 * @param XoopsUser $user
	 * @param string $rawdata
	 * @return boolean
	 */
	function _saveAvatar(XoopsUser $user, $rawdata = '') {
		
		// Writes file
		$filename = $this->prefix . substr(sha1($rawdata), mt_rand(mt_rand(0,2),42-8-2), 7) . '.jpg';
		$rf = fopen(XOOPS_UPLOAD_PATH . '/avatars/' . $filename, 'w');
		fwrite($rf, $rawdata, strlen($rawdata));
		fclose($rf);

		// Saves Avartar
		$avt_handler =& xoops_gethandler('avatar');
		$avatar =& $avt_handler->create(true);
		$avatar->setVar('avatar_file', 'avatars/' . $filename);
		$avatar->setVar('avatar_name', $user->getVar('uname'));
		$avatar->setVar('avatar_mimetype', 'image/jpeg');
		$avatar->setVar('avatar_display', 1);
		$avatar->setVar('avatar_type', 'C');
		if (!$avt_handler->insert($avatar)) {
			$sql = sprintf("UPDATE %s SET user_avatar = %s WHERE uid = %u", $GLOBALS['xoopsDB']->prefix('users'), $GLOBALS['xoopsDB']->quoteString( 'avatars/' . $filename ), $GLOBALS['xoopsUser']->getVar('uid'));
			$GLOBALS['xoopsDB']->queryF($sql);
		} else {
			$oldavatar = $user->getVar('user_avatar');
			if (!empty($oldavatar) && false !== strpos(strtolower($oldavatar), "cavt")) {
				$avatars = $avt_handler->getObjects(new Criteria('avatar_file', $oldavatar));
				if (!empty($avatars) && count($avatars) == 1 && is_object($avatars[0])) {
					$avt_handler->delete($avatars[0]);
					$oldavatar_path = realpath(XOOPS_UPLOAD_PATH . '/' . $oldavatar);
					if (0 === strpos($oldavatar_path, XOOPS_UPLOAD_PATH) && is_file($oldavatar_path)) {
						unlink($oldavatar_path);
					}
				}
			}
			$sql = sprintf("UPDATE %s SET user_avatar = %s WHERE uid = %u", $GLOBALS['xoopsDB']->prefix('users'), $GLOBALS['xoopsDB']->quoteString( 'avatars/' . $filename ), $GLOBALS['xoopsUser']->getVar('uid'));
			$GLOBALS['xoopsDB']->queryF($sql);
			$avt_handler->addUser($avatar->getVar('avatar_id'), $user->getVar('uid'));
			return true;
		}
	}
}