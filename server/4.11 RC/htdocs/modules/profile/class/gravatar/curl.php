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

include_once dirname(dirname(__FILE__)) . DS . 'gravatar.php';

/**
 * @package profile
 * @copyright copyright &copy; 2013 XOOPS.org
 */
class ProfileGravatarCurl extends ProfileGravatarHandler {
	
	/**
	 * @var String
	 */
	var $image = '';

	/**
	 * @var Object
	 */
	var $curl_client = '';
	
	/**
	 * Constructor
	 * @param XoopsUser $user
	 * @param String $url
	 * @param Array $config
	 * @return boolean
	 */
	public function __construct(XoopsUser $user, $url, $config) {
		if (parent::_updatable($user)==true) {
			if (!$ch = curl_init($url)) {
				trigger_error('Could not intialise CURL file: '.XORTIFY_CURL_API);
				return false;
			}
			$cookies = XOOPS_VAR_PATH.'/cache/xoops_cache/gravatar_curl.cookie';
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $config['profile_curl_connect']);
			curl_setopt($ch, CURLOPT_TIMEOUT, $config['profile_curl_timeout']);
			curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_USERAGENT, $config['profile_curl_user_agent']);
			$this->curl_client =& $ch;
			$this->image = curl_exec($this->curl_client);
			curl_close($this->curl_client);
			if (md5_file(parent::_getAvatarPath($user))!=md5($this->image)) {
				return parent::_saveAvatar($user, $this->image);
			}
		}
	}
}
?>