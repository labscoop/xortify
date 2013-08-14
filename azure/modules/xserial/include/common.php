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
 * @subpackage		Serialised
 * @author         	Simon Roberts (wishcraft) - meshy@labs.coop
 */

/*	Validates that User Credientials Exist
 *  validateuser($username, $password)
 *
 *  @param string $username			Username of the User on Xortify Cloud
 *  @param string $password			Open text or MD5 of password for User
 *  @return boolean
 */
function validateuser($username, $password) {
	global $xoopsDB;
	$sql = "select * from ".$xoopsDB->prefix('users'). " WHERE uname = '$username' and pass = ".(strlen($password)==32&&strtolower($password)==$password?"'$password'":"md5('$password')");
	$ret = $xoopsDB->query($sql);
	if (!$xoopsDB->getRowsNum($ret)) {
		return false;
	} else {
		return true;
	}
}

/*	Validates that User Credientials and returned the UID
 *  user_uid($username, $password)
 *
 *  @param string $username			Username of the User on Xortify Cloud
 *  @param string $password			Open text or MD5 of password for User
 *  @return integer
 */
function user_uid($username, $password) {
	global $xoopsDB;
	$sql = "select uid from ".$xoopsDB->prefix('users'). " WHERE uname = '$username' and pass = ".(strlen($password)==32&&strtolower($password)==$password?"'$password'":"md5('$password')");
	$ret = $xoopsDB->query($sql);
	if (!$xoopsDB->getRowsNum($ret)) {
		return false;
	} else {
		$row = $xoopsDB->fetchArray($ret);
		return $row['uid'];
	}
}

/*	Validates that User Credientials and returned the UID
 *  validate($tbl_id, $data, $function)
 *
 *  @param integer $tbl_id			Identifier for Table Name in Database
 *  @param array $data				Data for validation
 *  @param string $function			Function name on table validation
 *  @return string
 */
function validate($tbl_id, $data, $function) {
	global $xoopsDB;
	$sql = "select * from ".$xoopsDB->prefix('xml_tables'). " WHERE tablename = '".get_tablename($tbl_id)."' and $function = 1";
	$ret = $xoopsDB->query($sql);
	$pass=true;
	if (!$xoopsDB->getRowsNum($ret)) {
		$pass=false;	
	} else {
		foreach($data as $row){
			$sql = "select * from ".$xoopsDB->prefix('xml_fields'). " WHERE tbl_id = '$tbl_id' and $function = 1 and fieldname = '".$row['field']."'";
			$ret = $xoopsDB->query($sql);
			if (!$xoopsDB->getRowsNum($ret)&&!is_fieldkey($row['field'],$tbl_id)) {
				$pass=false;
			}
		}
	}
	
	return $pass;
}



/*	Check User has the right to fire function on API
 *  checkright($function_file, $username, $password)
 *
 *  @param string $function_file	Function name of file
 *  @param string $username			Username of the User on Xortify Cloud
 *  @param string $password			Open text or MD5 of password for User
 *  @return string
 */
function checkright($function_file, $username, $password) {
	$uid = user_uid($username,$password);
	$module_handler = xoops_gethandler('module');
	$xoModule = $module_handler->getByDirname('xxml');
	if ($uid <> 0){
		global $xoopsDB, $xoopsModule;
		$rUser = new XoopsUser($uid);
		$gperm_handler =& xoops_gethandler('groupperm');
		$groups = is_object($rUser) ? $rUser->getGroups() : array(XOOPS_GROUP_ANONYMOUS);
		$sql = "select plugin_id from ".$xoopsDB->prefix('xml_plugins')." where plugin_file = '".addslashes($function_file)."'";
		$ret = $xoopsDB->queryF($sql);
		$row = $xoopsDB->fetchArray($ret);
		$item_id = $row['plugin_id'];
		$modid = $xoModule->getVar('mid');
		$online_handler =& xoops_gethandler('online');
		$online_handler->write($uid, $username, time(), $modid, (string)$_SERVER["REMOTE_ADDR"]);
		$member_handler =& xoops_gethandler('member');
		@ini_set( 'session.gc_maxlifetime', $xoopsConfig['session_expire'] * 60 );
		session_set_save_handler( array( &$sess_handler, 'open' ), array( &$sess_handler, 'close' ), array( &$sess_handler, 'read' ), array( &$sess_handler, 'write' ), array( &$sess_handler, 'destroy' ), array( &$sess_handler, 'gc' ) );
		session_start();
		$_SESSION['xoopsUserId'] = $uid;
		$GLOBALS['xoopsUser'] = &$member_handler->getUser( $uid );
		$_SESSION['xoopsUserGroups'] = $GLOBALS['xoopsUser']->getGroups();
		$GLOBALS['sess_handler']->update_cookie();		
		
		return $gperm_handler->checkRight('plugin_call',$item_id,$groups, $modid);
	} else {
		global $xoopsDB, $xoopsModule;
		$gperm_handler =& xoops_gethandler('groupperm');
		$groups = array(XOOPS_GROUP_ANONYMOUS);
		$sql = "select plugin_id from ".$xoopsDB->prefix('xml_plugins')." where plugin_file = '".addslashes($function_file)."'";
		$ret = $xoopsDB->queryF($sql);
		$row = $xoopsDB->fetchArray($ret);
		$item_id = $row['plugin_id'];
		$modid = $xoModule->getVar('mid');
		return $gperm_handler->checkRight('plugin_call',$item_id,$groups, $modid);
	}
}


/*	Gets a Database Table Identifier
 *  get_tableid($tablename)
 *
 *  @param string $tablename		Table Name in Database
 *  @return integer
 */
function get_tableid($tablename) {
	global $xoopsDB;
	$sql = "SELECT * FROM ".$xoopsDB->prefix('xml_tables')." WHERE tablename = '$tablename'";
	$ret = $xoopsDB->query($sql);
	$row = $xoopsDB->fetchArray($ret);
	return $row['tbl_id'];
}

/*	Gets a Database Tablename from Identifier
 *  get_tablename($tableid)
 * 
 *  @param integer $tableid		Table Name Idenifier 
 *  @return string
 */
function get_tablename($tableid) {
	global $xoopsDB;
	$sql = "SELECT * FROM ".$xoopsDB->prefix('xml_tables')." WHERE tbl_id = '$tableid'";
	$ret = $xoopsDB->query($sql);
	$row = $xoopsDB->fetchArray($ret);
	return $row['tablename'];
}

/*	Gets a Field name with table and field identifier
 *  get_fieldname($fld_id, $tbl_id)
 *
 *  @param integer $fld_id		Field Name Idenifier
 *  @param integer $tbl_id		Table Name Idenifier
 *  @return string
 */
function get_fieldname($fld_id, $tbl_id) {
	global $xoopsDB;
	$sql = "SELECT * FROM ".$xoopsDB->prefix('xml_fields')." WHERE tbl_id = '$tbl_id' and fld_id = '$fld_id'";
	$ret = $xoopsDB->query($sql);
	$row = $xoopsDB->fetchArray($ret);
	return $row['fieldname'];

}

/*	Gets a True/False if field is part of primary or secondary key
 *  get_fieldname($fld_id, $tbl_id)
 *
 *  @param string $fieldname	Field Name 
 *  @param integer $tbl_id		Table Name Idenifier
 *  @return boolean
 */
function is_fieldkey($fieldname, $tbl_id){
	global $xoopsDB;
	$sql = "SELECT * FROM ".$xoopsDB->prefix('xml_fields')." WHERE tbl_id = '$tbl_id' and fieldname = '$fieldname' and `key` = 1";
	//echo $sql."\n";
	$ret = $xoopsDB->query($sql);
	if (!$xoopsDB->getRowsNum($ret)){
		return false;
	} else {
		return true;
	}

}


if (!function_exists('xoops_isIPv6')) {

	/*	Gets a True/False if IP Address is IPv6
	 *  xoops_isIPv6($ip = "")
	 *
	 *  @param string $ip			IP Address
	 *  @return boolean
	 */
	function xoops_isIPv6($ip = "")  
	{  
		if ($ip == "")  
			return false; 
			 
		if (substr_count($ip,":") > 0){  
			return true;  
		} else {  
			return false;  
		}  
	} 
}

if (!function_exists('xoops_getUserIP')) {
	
	/*	Gets a Associative Array on networking and user information
	 *  xoops_getUserIP($ip=false)
	 *
	 *  @param string $ip			IP Address
	 *  @return array
	 */
	function xoops_getUserIP($ip=false) { 
		$ret = array(); 
		if (is_object($GLOBALS['xoopsUser'])) { 
			$ret['uid'] = $GLOBALS['xoopsUser']->getVar('uid'); 
			$ret['uname'] = $GLOBALS['xoopsUser']->getVar('uname'); 
		} else { 
			$ret['uid'] = 0; 
			$ret['uname'] = $GLOBALS['xoopsConfig']['anonymous']; 
		} 
		$ret['sessionid'] = session_id(); 
		if (!$ip) { 
			if ($_SERVER["HTTP_X_FORWARDED_FOR"] != ""){  
				$ip = (string)$_SERVER["HTTP_X_FORWARDED_FOR"];  
				$ret['is_proxied'] = true; 
				$proxy_ip = $_SERVER["REMOTE_ADDR"];  
				$ret['network-addy'] = @gethostbyaddr($ip);  
				$ret['long'] = @ip2long($ip); 
				if (xoops_isipv6($ip)) { 
					$ret['ip6'] = $ip; 
					$ret['proxy-ip6'] = $proxy_ip; 
				} else { 
					$ret['ip4'] = $ip; 
					$ret['proxy-ip4'] = $proxy_ip; 
				} 
			}else{  
				$ret['is_proxied'] = false; 
				$ip = (string)$_SERVER["REMOTE_ADDR"];  
				$ret['network-addy'] = @gethostbyaddr($ip);  
				$ret['long'] = @ip2long($ip); 
				if (xoops_isipv6($ip)) { 
					$ret['ip6'] = $ip; 
				} else { 
					$ret['ip4'] = $ip; 
				} 
			}  
		} else { 
			$ret['is_proxied'] = false; 
			$ret['network-addy'] = @gethostbyaddr($ip);  
			$ret['long'] = @ip2long($ip); 
			if (xoops_isipv6($ip)) { 
				$ret['ip6'] = $ip; 
			} else { 
				$ret['ip4'] = $ip; 
			} 
		} 
		$ret['md5'] = md5($ip.$ret['long'].$ret['network-addy'].$ret['is_proxied']); 
		$ret['sha1'] = sha1($ip.$ret['long'].$ret['network-addy'].$ret['is_proxied'].$ret['uid']. $ret['uname']);     
		$ret['made'] = time();                 
		return $ret; 
	}
}


/*	Checks if the function is locked
 *  check_for_lock($function_file, $username, $password)
 *
 *  @param string $function_file	Function name of file
 *  @param string $username			Username of the User on Xortify Cloud
 *  @param string $password			Open text or MD5 of password for User
 *  @return array
 */
function check_for_lock($function_file, $username, $password) {
	xoops_load('cache');
	$userip = xoops_getUserIP();
	$retn = false;
	if ($result = XoopsCache::read('lock_'.$function_file.'_'.$username)) {
		foreach($result as $id => $ret) {
			if ($ret['made']<time()-$GLOBALS['xoopsModuleConfig']['lock_seconds'] || 
				$ret['made']<((time()-$GLOBALS['xoopsModuleConfig']['lock_seconds'])+mt_rand(1, $GLOBALS['xoopsModuleConfig']['lock_random_seed']))) {
				unset($result[$id]);
			} elseif ($ret['md5']==$userip['md5']) {
				$retn = array('ErrNum'=> 9, "ErrDesc" => 'No Permission for plug-in');
			}
		}
		XoopsCache::delete('lock_'.$function_file.'_'.$username);
		XoopsCache::write('lock_'.$function_file.'_'.$username, $result, $GLOBALS['cache_seconds']);
		return $retn;
	}
}

/*	MArks a function lock based on username and password
 *  check_for_lock($function_file, $username, $password)
 *
 *  @param string $function_file	Function name of file
 *  @param string $username			Username of the User on Xortify Cloud
 *  @param string $password			Open text or MD5 of password for User
 *  @return array
 */
function mark_for_lock($function_file, $username, $password) {
	xoops_load('cache');
	$userip = xoops_getUserIP();
	$result = array();
	if ($result = XoopsCache::read('lock_'.$function_file.'_'.$username)) {
		$result[] = $userip;
		XoopsCache::delete('lock_'.$function_file.'_'.$username);
		XoopsCache::write('lock_'.$function_file.'_'.$username, $result, $GLOBALS['cache_seconds']);
		return array('ErrNum'=> 9, "ErrDesc" => 'No Permission for plug-in');
	} else {
		$result[] = $userip;
		XoopsCache::delete('lock_'.$function_file.'_'.$username);
		XoopsCache::write('lock_'.$function_file.'_'.$username, $result, $GLOBALS['cache_seconds']);
		return array('ErrNum'=> 9, "ErrDesc" => 'No Permission for plug-in');
	}
}
?>