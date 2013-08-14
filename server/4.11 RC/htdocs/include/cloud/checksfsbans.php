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
xoops_loadLanguage('server', 'global');

if (!function_exists('obj2array')) {
	
	/*	Converts Object to Array.
	 *  obj2array($objects)
	 *
	 *  @subpackage functions
	 *
	 *  @param object $objects 		Object to be convert to an array
	 *  @return array
	 */
	function obj2array($objects) {
		$ret = array();
		foreach($objects as $key => $value) {
			if (is_a($value, 'stdClass')) {
				$ret[$key] = (array)obj2array($value);
			} elseif (is_array($value)) {
				$ret[$key] = obj2array($value);
			} else {
				$ret[$key] = $value;
			}
		}
		return $ret;
	}
}


if (!function_exists('checksfsbans_GetFromToHost')) {
	
	/*	Gets from Stop forum spam the current ban information for the data provided
	 *  checksfsbans_GetFromToHost($data)
	 *
	 *  @subpackage plugin
	 *
	 *  @param string $data 		Stop forum spam Request Query for checking for ban
	 */
	function checksfsbans_GetFromToHost($data) {
		if (!$ch = curl_init('http://www.stopforumspam.com/api?'.$data)) {
			trigger_error('Could not intialise CURL file: '.$url);
			return false;
		}
		$cookies = XOOPS_VAR_PATH.'/cache/xoops_cache/authcurl_'.md5('http://www.stopforumspam.com/api').'.cookie'; 
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, 0);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_USERAGENT, _MI_SERVER_USER_AGENT);
		$data = curl_exec($ch);
		curl_close($ch);
		$json = new Services_JSON();
		$result = obj2array($json->decode($data));
	}
}

if (!function_exists('checksfsbans')) {
	
	/*	Gets from Stop forum spam the current ban information for the IP Data and User Data provided
	 *  checksfsbans($username, $password, $ipdata)
	 *
	 *  @subpackage plugin
	 *
	 *  @param string $username 	Xortify username for function
	 *  @param string $password 	Xortify password or md5 hash of password for function
	 *  @param array $ipdata 		Associative Array [uname, email, ip4 or ip6]
	 *  @return array
	 */
	function checksfsbans($username, $password, $ipdata) {
		
		if ($xoopsModuleConfig['site_user_auth']==1){
			if ($ret = check_for_lock(basename(__FILE__),$username,$password)) { return $ret; }
			if (!checkright(basename(__FILE__),$username,$password)) {
				mark_for_lock(basename(__FILE__),$username,$password);
				return array('ErrNum'=> 9, "ErrDesc" => 'No Permission for plug-in');
			}
		}
					
		if (!$result = XoopsCache::read('xortify_isfs_'.md5(serialize($ipdata)))) {
			/*if (!empty($ipdata['email'])) {
				$emails_handler = xoops_getmodulehandler('emails', 'xortify');
				$email = $emails_handler->create(true);
				$email->_ip = $ipdata['ip4'].$ipdata['ip6'];
				$email->setVar('email', $ipdata['email']);
				@$emails_handler->insert($email);
			}*/
		 	$result = checksfsbans_GetFromToHost('f=json&username='.$ipdata['uname'].'&email='.$ipdata['email'].'&ip='.$ipdata['ip4'].$ipdata['ip6'].'&api_key='._MI_SERVER_STOPFORUMSPAM_KEY);
		 	XoopsCache::write('xortify_isfs_'.md5(serialize($ipdata)), $result, 60*20);
		}
		return $result;
	}
}
?>