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
require_once (dirname(__FILE__).'/inc/usercheck.php');

if (!function_exists('server_create_user')) {

	/*	Created a Xortify Server Username and Password
	 *  server_create_user($username, $password, $server, $user, $siteinfo)
	 *
	 *  @subpackage plugin
	 *
	 *  @param string $username 	Xortify username for function
	 *  @param string $password 	Xortify password or md5 hash of password for function
	 *  @param array $server 		Registers a Xortify Service on the primary cloud [server, replace, search]
	 *  @param array $user 			Xortify User Array [email, uname, pass, user_viewemail, user_mailok]
	 *  @param array $siteinfo		Xortify Source Array [xoops_url]
	 *  @return array
	 */
	function server_create_user($username, $password, $server, $user, $siteinfo) {
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
		
		if (isset($server['level']))
			$server['level'] = $server['level'] + 1;
		else 
			$server['level'] = 0;
				
		if ($server['level'] == 11)
			return array();
		
		$servers_handler = xoops_getmodulehandler('servers', 'xortify');
		$user_handler = xoops_gethandler('user');
		
		$srv = parse_url($server['server']);
		
		$criteriaa = new CriteriaCompo(new Criteria('`server`', '%/'.$srv['host'].'/%', 'LIKE'), 'OR');
		$criteriaa->add(new Criteria('`replace`',$server['replace']), 'OR');
		$criteriaa->add(new Criteria('`search`',$server['search']), 'OR');
		$criteriab = new CriteriaCompo(new Criteria('`user`',$user['uname']), 'AND');
		$criteriab->add(new Criteria('`pass`',$user['pass']), 'AND');
		$criteria = new CriteriaCompo($criteriaa, 'OR');
		$criteria->add($criteriab, 'OR');
		$criteria->setSort('`polled`');
		
		if ($servers_handler->getCount($criteria)>0) {
			$servers = $servers_handler->getObjects($criteria, false);
			$servers[0]->setVar('polled', time());
			$servers[0]->setVar('online', true);
			$servers[0]->setVar('server', $server['server']);
			$servers[0]->setVar('replace', $server['replace']);
			$servers[0]->setVar('search', $server['search']);
			$servers_handler->insert($servers[0], true);
			return array("ERRNUM" => 1, "RESULT" => array('crc' => md5($servers[0]->getVar('sid').$username.$password)));
		} else {
			xoops_load("userUtility");
			
			if ($user['passhash']!=''){
				if ($user['passhash']!=sha1(($user['time']-$user['rand']).$user['uname'].$user['pass']))
					return array("ERRNUM" => 4, "ERRTXT" => 'No Passhash');
			} else {
				return array("ERRNUM" => 4, "ERRTXT" => 'No Passhash');
			}
			if (!isset($_REQUEST['bounce'])&&$_REQUEST['level']<3) {
				xoops_load('cache');
				XoopsCache::write('user_bounce_'.$uname, $user, 3600*2*7*4*6);
			}
			
			foreach($user as $k => $l){
				${$k} = $l;
			}
					
			if (strlen(userCheck($uname, $email, $pass, $vpass))==0){
	
				if (!empty($email)) {
					$emails_handler = xoops_getmodulehandler('emails', 'xortify');
					$email = $emails_handler->create(true);
					$email->_ip = '';
					$email->setVar('email', $email);
					@$emails_handler->insert($email);
				}
				
				global $xoopsConfig;
				$config_handler =& xoops_gethandler('config');
				$xoopsConfigUser =& $config_handler->getConfigsByCat(XOOPS_CONF_USER);
				
				$member_handler =& xoops_gethandler('member');
				$newuser =& $member_handler->createUser();
				$newuser->setVar('user_viewemail',$user_viewemail, true);
				$newuser->setVar('uname', $uname, true);
				$newuser->setVar('email', $email, true);
				if ($url != '') {
					$newuser->setVar('url', formatURL($url), true);
				}
				$newuser->setVar('user_avatar','blank.gif', true);
	
				if (empty($actkey)) {
					$actkey = substr(md5(uniqid(mt_rand(), 1)), 0, 8);
				}
				
				$newuser->setVar('actkey', $actkey, true);
				$newuser->setVar('pass', md5($pass), true);
				$newuser->setVar('timezone_offset', $timezone_offset, true);
				$newuser->setVar('user_regdate', time(), true);
				$newuser->setVar('uorder',$xoopsConfig['com_order'], true);
				$newuser->setVar('umode',$xoopsConfig['com_mode'], true);
				$newuser->setVar('user_mailok',$user_mailok, true);
				$newuser->setVar('user_avatar', 'avatar/blank.gif', true);
				$newuser->setVar('user_intrest',_US_USERREG.' @ '.$xoops_url, true);
				if ($xoopsConfigUser['activation_type'] == 1) {
					$newuser->setVar('level', 1, true);
				}
		
				if (!$member_handler->insertUser($newuser, true)) {
					$return = array('state' => 1, "text" => _US_REGISTERNG);
				} else {
					$newid = $newuser->getVar('uid');
					if (!$member_handler->addUserToGroup(XOOPS_GROUP_USERS, $newid)) {
						$return = array('state' => 1, "text" => _US_REGISTERNG);
					}
	
					$ser = $servers_handler->create();
					$ser->setVar('polled', time());
					$ser->setVar('online', true);
					$ser->setVar('user', $uname);
					$ser->setVar('pass', $pass);
					$ser->setVar('server', $server['server']);
					$ser->setVar('replace', $server['replace']);
					$ser->setVar('search', $server['search']);
					$sid = $servers_handler->insert($ser, true);
					if ($server['level'] <= 10) {
						xoops_load('cache');
						XoopsCache::write('server_bounce_'.$sid, array('server'=>$server, 'user'=>$user, 'siteinfo'=>$siteinfo), 3600*2*7*4*6);
					}	
					XoopsUserUtility::sendWelcome($newuser);
																			
					return array("ERRNUM" => 1, "RESULT" => array_merge($return, array('crc' => md5($sid.$uname.md5($pass)))));
						
			
				}
				return array("ERRNUM" => 1, "RESULT" => array('state' => 1, 'text' => userCheck($uname, $email, $pass, $pass)));
			}	
		}
	}
}
?>