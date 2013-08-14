<?php
if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}
/**
 * Class for Spiders
 * @author Simon Roberts (simon@xoops.org)
 * @copyright copyright (c) 2010-2013 labs.coop
 * @package kernel
 */
class SpidersSpiders extends XoopsObject
{

    function SpidersSpiders($fid = null)
    {
        $this->initVar('id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('robot-id', XOBJ_DTYPE_TXTBOX, null, false, 128);
        $this->initVar('robot-name', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('robot-cover-url', XOBJ_DTYPE_OTHER, null, false, 255);
        $this->initVar('robot-details-url', XOBJ_DTYPE_OTHER, null, false, 255);
        $this->initVar('robot-owner-name', XOBJ_DTYPE_TXTBOX, null, false, 128);
        $this->initVar('robot-owner-url', XOBJ_DTYPE_OTHER, null, false, 255);
        $this->initVar('robot-owner-email', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('robot-status', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('robot-purpose', XOBJ_DTYPE_TXTBOX, null, false, 128);
		$this->initVar('robot-type', XOBJ_DTYPE_TXTBOX, null, false, 64);
		$this->initVar('robot-platform', XOBJ_DTYPE_TXTBOX, null, false, 64);
		$this->initVar('robot-availability', XOBJ_DTYPE_TXTBOX, null, false, 128);
		$this->initVar('robot-exclusion', XOBJ_DTYPE_TXTBOX, null, false, 32);
		$this->initVar('robot-exclusion-useragent', XOBJ_DTYPE_TXTBOX, null, false, 255);
		$this->initVar('robot-noindex', XOBJ_DTYPE_TXTBOX, null, false, 32);
		$this->initVar('robot-host', XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar('robot-from', XOBJ_DTYPE_TXTBOX, null, false, 32);
		$this->initVar('robot-useragent', XOBJ_DTYPE_TXTBOX, null, false, 255);
		$this->initVar('robot-language', XOBJ_DTYPE_TXTBOX, null, false, 64);
		$this->initVar('robot-description', XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar('robot-history', XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar('robot-environment', XOBJ_DTYPE_TXTBOX, null, false, 128);
		$this->initVar('modified-date', XOBJ_DTYPE_TXTBOX, null, false, 64);										
		$this->initVar('modified-by', XOBJ_DTYPE_TXTBOX, null, false, 64);
		$this->initVar('robot-safeuseragent', XOBJ_DTYPE_TXTBOX, null, false, 255);	
		$this->initVar('robot-handlesession', XOBJ_DTYPE_TXTBOX, null, false, 3);		
    }


}


/**
* XOOPS Spider handler class.
* This class is responsible for providing data access mechanisms to the data source
* of XOOPS user class objects.
*
* @author  Simon Roberts <simon@xoops.org>
* @package kernel
*/
class SpidersSpidersHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db) 
    {
        parent::__construct($db, "spiders", 'SpidersSpiders', "id", "robot-name");
    }
	
	function banDetails($details_url) {
		if (!$this->isSpiderHost(@gethostbyaddr($details_url))) {
			$ipData = $this->getReverseNetaddyIPData($details_url);
			$ban_members =& xoops_getmodulehandler('members', 'ban');
			$ban = $ban_members->create();
			$ban->setVars($ipData);
			$ban_members->insert($ban);
			return true;
		}
		return false;
	}
	
	function getReverseNetaddyIPData($addr=false){
		return $this->getIPData(@gethostbyaddr($addr)); 
	}
	
	function getIPData($ip=false){
		$ret = array();
		if (is_object($GLOBALS['xoopsUser'])) {
			$ret['uid'] = $GLOBALS['xoopsUser']->getVar('uid');
			$ret['uname'] = $GLOBALS['xoopsUser']->getVar('uname');
		} else {
			$ret['uid'] = 0;
			$ret['uname'] = '';
		}
		if (!$ip) {
			if ($_SERVER["HTTP_X_FORWARDED_FOR"] != ""){ 
				$ip = (string)$_SERVER["HTTP_X_FORWARDED_FOR"]; 
				$ret['is_proxied'] = true;
				$proxy_ip = $_SERVER["REMOTE_ADDR"]; 
				$ret['network-addy'] = @gethostbyaddr($ip); 
				$ret['long'] = @ip2long($ip);
				if (is_ipv6($ip)) {
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
				if (is_ipv6($ip)) {
					$ret['ip6'] = $ip;
				} else {
					$ret['ip4'] = $ip;
				}
			} 
		} else {
			$ret['is_proxied'] = false;
			$ret['network-addy'] = @gethostbyaddr($ip); 
			$ret['long'] = @ip2long($ip);
			if (is_ipv6($ip)) {
				$ret['ip6'] = $ip;
			} else {
				$ret['ip4'] = $ip;
			}
		}
		$ret['made'] = time();				
		return $ret;
	}


	function is_ipv6($ip = "") 
	{ 
		if ($ip == "") 
			return false;
			
		if (substr_count($ip,":") > 0){ 
			return true; 
		} else { 
			return false; 
		} 
	} 

	
	
	function safeAgent($useragent) {

		$part = explode('/', $useragent);
		foreach($part as $key => $value) {
			if (strpos(strtolower($value), '.x'))
				unset($part[$key]);
		}
		$useragent = implode('/', $part);
		
		$ver_char = array('x.x', '*.*', 'X.X', 'x.xxx', 'x.y', 'xxx', 'xxxx', 'xxxxx', 'xxxxxx', 'vX.X.X', 'X.X.X', 'X.xx');
		foreach($ver_char as $vc)
			if (strpos($useragent, $vc))
				$useragent = str_replace($vc, '', $useragent);

		$modulehandler =& xoops_gethandler('module');
		$confighandler =& xoops_gethandler('config');
		$xoModule = $modulehandler->getByDirname('spiders');
		$xoConfig = $confighandler->getConfigList($xoModule->getVar('mid'),false);
		$reservedphrases = explode('|', $xoConfig['reserved_prases']);
		foreach($reservedphrases as $id => $phrase)
			$useragent = str_replace($phrase, '', $useragent);

		return $useragent;
	}
	
	function import_insert($spider)
	{
		if (!is_a($spider, 'SpidersSpiders'))
			return false;
		
		$group_handler = &xoops_gethandler( 'group' );
		$suser_handler = &xoops_getmodulehandler( 'spiders_user', _MI_SPIDERS_DIRNAME );
		$criteria = new Criteria('group_type', _MI_SPIDERS_GROUP_TYPE);
		$groups = $group_handler->getObjects($criteria);
		if (is_object($groups[0]))
		{
			$groupid = $groups[0]->getVar('groupid');
		}
		
		$sql = "SELECT count(*) as rc FROM ".$GLOBALS['xoopsDB']->prefix('spiders')." WHERE `robot-id` = '".$spider->getVar('robot-id')."'";
		list($rc) = $GLOBALS['xoopsDB']->fetchRow($GLOBALS['xoopsDB']->query($sql));
		if ($rc==0&&strlen($spider->getVar('robot-id'))>0)
		{
		
			$sid = $this->insert($spider);
			if ($sid>0) {
				$member_handler =& xoops_gethandler('member');
				$criteria = new CriteriaCompo(new Criteria('uname', ucfirst($spider->getVar('robot-id'))));
				if ($member_handler->getUserCount($criteria)==0){
					$user = $member_handler->createUser();
					$user->setVar('name', $spider->getVar('robot-name'));
					$user->setVar('uname', ucfirst($spider->getVar('robot-id')));
					$user->setVar('email', str_replace('+', '@', $spider->getVar('robot-owner-email')));
					if (strlen($spider->getVar('robot-details-url')))
						$user->setVar('url', $spider->getVar('robot-details-url'));
					else
						$user->setVar('url', $spider->getVar('robot-cover-url'));
					$user->setVar('pass', md5(XOOPS_URL.XOOPS_ROOT_PATH.$spider->getVar('robot-id').rand(1,30000)));
					$user->setVar('level', 1);
					$user->setVar('user_mailok', 0);
					$user->setVar('user_occ', _MI_SPIDERS_GROUP_NAME);
					$user->setVar('bio', $spider->getVar('robot-description'));
					$uid = $member_handler->insertUser($user, true);
					@$member_handler->addUserToGroup($groupid, $user->getVar('uid'));
				} else {
					$user = $member_handler->createUser();
					$user->setVar('name', $spider->getVar('robot-name'));
					$user->setVar('uname', ucfirst($spider->getVar('robot-id').'-'.rand(1,9)));
					$user->setVar('email', str_replace('+', '@', $spider->getVar('robot-owner-email')));
					if (strlen($spider->getVar('robot-details-url')))
						$user->setVar('url', $spider->getVar('robot-details-url'));
					else
						$user->setVar('url', $spider->getVar('robot-cover-url'));
					$user->setVar('pass', md5(XOOPS_URL.XOOPS_ROOT_PATH.$spider->getVar('robot-id').rand(1,30000)));
					$user->setVar('level', 1);
					$user->setVar('user_mailok', 0);
					$user->setVar('user_occ', _MI_SPIDERS_GROUP_NAME);
					$user->setVar('bio', $spider->getVar('robot-description'));
					$uid = $member_handler->insertUser($user, true);
					@$member_handler->addUserToGroup($groupid, $user->getVar('uid'));
				}
				$suser = $suser_handler->create();
				$suser->setVar('spider_id', $spider->getVar('id'));
				$suser->setVar('uid', $user->getVar('uid'));
				$suser_handler->insert($suser);
			}				
		} else {
			return false;
		}
	}
	
	function getRobotHostandIP() {
		xoops_load('cache');
		if (!$ips = XoopsCache::read('spiders_robot_host_ips')||!$hosts = XoopsCache::read('spiders_robot_host_addresses')) {
			$criteria = new Criteria('`robot-host`', '*', 'NOT LIKE');
			$robots = $this->getObjects($criteria, true);
			foreach($robots as $id => $robot) {
				foreach(explode(',', $robot->getVar('robot-host')) as $hostmask) {
					$hostmask = trim($hostmask);
					$components = explode('.', $hostmask); 
					if (count($components)==4 && is_numeric($components[0])) {
						$hostname=false;
						$range = array();
						for($c=0; $c<=3; $c++) {
							if (is_numeric($components[$c])) {
									$range[$c]['start'] = $components[$c];
									$range[$c]['end'] = $components[$c];
							} elseif (is_string($components[$c])&&strpos($components[$c], '-')>0) {
								$rt = explode('-', $components[$c]);
								if (count($rt)==2&&is_numeric($rt[0])&&is_numeric($rt[1])) {
									$range[$c]['start'] = $rt[0];
									$range[$c]['end'] = $rt[1];
								} else {
									$hostname=true;
								}
							} else {
								$hostname=true;
							}
						}			
						if ($hostname==false) {
							for($octa=$range[0]['start']; $octa<=$range[0]['end']; $octa++) {
								for($octb=$range[1]['start']; $octb<=$range[1]['end']; $octb++) {
									for($octc=$range[2]['start']; $octc<=$range[2]['end']; $octc++) {
										for($octd=$range[3]['start']; $octd<=$range[3]['end']; $octd++) {
											$ips[$octa.'.'.$octb.'.'.$octc.'.'.$octd] = $octa.'.'.$octb.'.'.$octc.'.'.$octd;
										}
									}
								}
							}
						} else {
							if ($hostmask!='*') {
								if ($this->is_ipv6($hostmask)==false) {					
							 		$hosts[$hostmask] = $hostmask;
								} else { 
							 		$ips[$hostmask] = $hostmask;
								}
							}
						}
					} elseif ($hostmask!='*') {					
						$hosts[$hostmask] = $hostmask;
					}
				}
			}
			XoopsCache::write('spiders_robot_host_ips', $ips, 3600*24*7);
			XoopsCache::write('spiders_robot_host_addresses', $hosts, 3600*24*7);
		}
		return array('ips'=>$ips, 'hosts'=>$hosts);
	}

	function isSpiderHost($ip) {
		$resource = $this->getRobotHostandIP();
		if (in_array($ip, $resource['ips'])) {
			return true;
		} else {
			$hostname = "";
			foreach(array_reverse(explode('.', @gethostbyaddr($ip))) as $id => $component) {
				$hostname = $component . (strlen($hostname)!=0?'.'.$hostname:'');
				if (in_array($hostname, $resource['hosts'])) {
					return true;
				}
			}
		}
		return false;
	}
	
	function isSpiderAgent($useragent, $return_object = false) {
	
		$modulehandler =& xoops_gethandler('module');
		$confighandler =& xoops_gethandler('config');
		$xoModule = $modulehandler->getByDirname('spiders');
		$xoConfig = $confighandler->getConfigList($xoModule->getVar('mid'),false);
		
		$criteria = new CriteriaCompo();
		$part = $this->safeAgent($useragent);
		foreach(array(';','/',',','\\','(',')',' ') as $split) {
			$ret= array();
			foreach(explode($split, $part) as $value) {
				$ret[] = $value;
			}
			$part = implode(' ',$ret);
		}
	
		foreach($ret as $value) 
			if (!empty($value)&&!strpos($value, 'www.')) {
				$criteria->add(new Criteria('`robot-safeuseragent`', '%'.$value.'%', 'LIKE'), 'OR');
				$uagereg[] = $value;
			}
	
		
		$spiders = $this->getObjects($criteria, true);
		foreach($spiders as $spider) {
			$part = $this->safeAgent($spider->getVar('robot-useragent'));
			foreach(array(';','/',',','\\','(',')',' ') as $split) {
				$usersafeagent = array();
				foreach(explode($split, $part) as $value) {
					if (strlen(trim($value))!=0)
						$usersafeagent[] = $value;
				}
				$part = implode(' ',$usersafeagent);
			}
			$usersafeagent = explode(' ', $part);
			if (((intval($match/count($uagereg)*100)+intval($match/count($usersafeagent)*100))/2)>intval($xoConfig['match_percentile'])) {
				return ($return_object==true?$spider:true);			
			}
		}
		
		return false;
	}
	
}
?>