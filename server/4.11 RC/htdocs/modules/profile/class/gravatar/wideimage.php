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

if (!class_exists('WideImage'))
	include_once dirname(__FILE__) . DS . 'wideimage' . DS . 'WideImage.php';
include_once dirname(dirname(__FILE__)) . DS . 'gravatar.php';

/**
 * @package profile
 * @copyright copyright &copy; 2013 XOOPS.org
 */
class ProfileGravatarWideimage extends ProfileGravatarHandler {
	
	var $image = '';
	
	function ProfileGravatarWideimage(XoopsUser $user, $url, $config) {
		$this->__construct($user, $url, $config);
	}
	
	function __construct(XoopsUser $user, $url, $config) {
		if (parent::_updatable($user)==true) {
			$img = WideImage::load($url);
			$resize = $img->resize($GLOBALS['xoopsConfigUser']['avatar_width'], $GLOBALS['xoopsConfigUser']['avatar_height'], false, true);
			ob_start();
			$resize->output('jpg');
			$this->image = ob_get_clean();
			ob_end_clean();
			if (md5_file(parent::_getAvatarPath($user))!=md5($this->image)) {
				return parent::_saveAvatar($user, $this->image);
			}
			
		}
	}
}
