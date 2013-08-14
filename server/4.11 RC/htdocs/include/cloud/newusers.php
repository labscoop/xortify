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
if (!function_exists('newusers')) {
	
	/*	Provide Push of New Users to a Xortify Cloud Server
	 *  newusers($username, $password, $poll, $unames, $token, $agent, $session)
	 *
	 *  @subpackage plugin
	 *
	 *  @param string $username 	Xortify username for function
	 *  @param string $password 	Xortify password or md5 hash of password for function
	 *  @param string $poll 		URI to Push Server Lists to (Polling API)
	 *  @param array $uname 		Array of associative that list Username to skip
	 *  @param string $token 		XOOPS Service Security Token
	 *  @param string $agent 		User Agent for Generated Security Token
	 *  @param string $session 		Session ID for Client Session on call!
	 *  @return array
	 */
	function newusers($username, $password, $poll, $unames, $token, $agent, $session) {
		global $xoopsModuleConfig, $xoopsDB;
	
		if ($xoopsModuleConfig['site_user_auth']==1){
			if ($ret = check_for_lock(basename(__FILE__),$username,$password)) { return $ret; }
			if (!checkright(basename(__FILE__),$username,$password)) {
				mark_for_lock(basename(__FILE__),$username,$password);
				return array('ErrNum'=> 9, "ErrDesc" => 'No Permission for plug-in');
			}
		}
	
	
		$module_handler =& xoops_gethandler('module');
		$config_handler =& xoops_gethandler('config');
		if (!isset($GLOBALS['xortifyModule'])) $GLOBALS['xortifyModule'] = $module_handler->getByDirname('xortify');
		if (!isset($GLOBALS['xortifyModuleConfig'])) $GLOBALS['xortifyModuleConfig'] = $config_handler->getConfigList($GLOBALS['xortifyModule']->mid());
		
		if (!$ch = curl_init($poll)) {
			trigger_error('Could not intialise CURL file: '.$poll);
			return false;
		}
		$cookies = XOOPS_VAR_PATH.'/cache/xoops_cache/authcurl_'.md5($poll).'.cookie'; 
	
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 45);
		curl_setopt($ch, CURLOPT_TIMEOUT, 45);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_USERAGENT, $agent); 
		
		$uri = parse_url(XOOPS_URL);
		$ret = array();
		$ret['session'] = $session;
		$ret['token'] = $token;
		$ret['carriage'] = 'server_users_xortify_'.$uri['host'];
		$ret['config'] = 'server_cache';
		$ret['bounce'] = true;
		$ret['noemail'] = true;
		$user_handler = xoops_gethandler('user');
		$criteria = new Criteria('`uname`', "('".implode("','", $unames)."')", 'NOT IN');
		if ($users = $user_handler->getObjects($criteria, true)&&$user_handler->getCount($criteria)>0) {
			foreach($users as $uid => $user)
				$ret['data'][$uid] = $user->toArray();
		}
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($ret));
	
		return unserialize(curl_exec($ch));
		
	}
}
?>