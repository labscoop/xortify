<?php
/**
 * Xortify Bans & Unbans Function
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
 * @package         bans
 * @subpackage		ban
 * @author         	Simon Roberts (wishcraft) - meshy@labs.coop
 */


if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}

xoops_loadLanguage('keys', 'global');

require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'ip2locationlite.class.php');
require_once(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'functions.php');
/**
 * Class for Ban Profiler
 * @author Simon Roberts (simon@labs.coop)
 * @copyright copyright (c) 2010-2013 labs.coop
 */
class banMembers extends XoopsObject
{
	
	var $_whois_url = 'http://whois.labs.coop/v1/%s/html.api';
	
    function banMembers($fid = null)
    {
        $this->initVar('member_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('category_id', XOBJ_DTYPE_INT, null, false);		
		$this->initVar('suid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('uid', XOBJ_DTYPE_INT, null, false);	
        $this->initVar('uname', XOBJ_DTYPE_TXTBOX, null, false, 64);
        $this->initVar('email', XOBJ_DTYPE_TXTBOX, null, false, 255);
		$this->initVar('ip4', XOBJ_DTYPE_TXTBOX, null, true, 255);
		$this->initVar('ip6', XOBJ_DTYPE_TXTBOX, null, false, 65535);
		$this->initVar('long', XOBJ_DTYPE_TXTBOX, null, false, 120);
		$this->initVar('proxy-ip4', XOBJ_DTYPE_TXTBOX, null, false, 255);
		$this->initVar('proxy-ip6', XOBJ_DTYPE_TXTBOX, null, false, 65535);						
		$this->initVar('network-addy', XOBJ_DTYPE_TXTBOX, null, false, 255);
		$this->initVar('mac-addy', XOBJ_DTYPE_TXTBOX, null, false, 255);	
		$this->initVar('country-code', XOBJ_DTYPE_TXTBOX, null, false, 3);
		$this->initVar('country-name', XOBJ_DTYPE_TXTBOX, null, false, 128);
		$this->initVar('region-name', XOBJ_DTYPE_TXTBOX, null, false, 128);
		$this->initVar('city-name', XOBJ_DTYPE_TXTBOX, null, false, 128);
		$this->initVar('postcode', XOBJ_DTYPE_TXTBOX, null, false, 15);
		$this->initVar('latitude', XOBJ_DTYPE_DECIMAL, null, false);
		$this->initVar('longitude', XOBJ_DTYPE_DECIMAL, null, false);
		$this->initVar('timezone', XOBJ_DTYPE_TXTBOX, null, false, 6);
		$this->initVar('made', XOBJ_DTYPE_INT, null, false);			
    }
	
    function toArray() {
    	$ret = parent::toArray();
    	$ret['made'] = date(_DATESTRING, $this->getVar('made'));
    	$categories_handler = xoops_getmodulehandler('categories', 'ban');
    	$category = $categories_handler->get($this->getVar('category_id'));
    	if (is_object($category))
    		$ret['category'] = $category->getVar('category_name');
    	return $ret;
    }
    
	function ipaddy() {
		if (strlen($this->getVar('ip4'))>0)
			return $this->getVar('ip4');
		else
			return $this->getVar('ip6');
	}
	
	function story() {
		$txt .= '<img src="'.XOOPS_URL.'/modules/ban/images/ban_slogo.png"><br/>';
		$txt .= 'Ban Made on the '. date(_DATESTRING, $this->getVar('made'))." by a remote client of the Xortify Cloud this attempted security intrusions details are as follow:<br/><br/>";
		if (strlen($this->getVar('uname'))>0)
			$txt .= _BAN_MF_UNAME.': '. $this->getVar('uname')."<br/>";
		if (strlen($this->getVar('email'))>0)
			$txt .= _BAN_MF_EMAIL.': '. $this->getVar('email')."<br/>";
		if (strlen($this->getVar('ip4'))>0)
			$txt .= _BAN_MF_IP4.': '. $this->getVar('ip4')."<br/>";
		if (strlen($this->getVar('ip6'))>0)
			$txt .= _BAN_MF_IP6.': '. $this->getVar('ip6')."<br/>";
		if (strlen($this->getVar('long'))>0)
			$txt .= _BAN_MF_LONG.': '. $this->getVar('long')."<br/>";
		if (strlen($this->getVar('proxy-ip4'))>0)
			$txt .= _BAN_MF_PROXY_IP4.': '. $this->getVar('proxy-ip4')."<br/>";
		if (strlen($this->getVar('proxy-ip6'))>0)
			$txt .= _BAN_MF_PROXY_IP6.': '. $this->getVar('proxy-ip6')."<br/>";
		if (strlen($this->getVar('network-addy'))>0)
			$txt .= _BAN_MF_NETWORK_ADDY.': '. $this->getVar('network-addy')."<br/>";
		if (strlen($this->getVar('mac-addy'))>0)
			$txt .= _BAN_MF_MAC_ADDY.': '. $this->getVar('mac-addy')."<br/>";
		if (strlen($this->getVar('country-name'))>0)
			$txt .= _BAN_MF_COUNTRY_NAME.': '. $this->getVar('country-name')."(".$this->getVar('country-code').")<br/>";
		if (strlen($this->getVar('region-name'))>0)
			$txt .= _BAN_MF_REGION_NAME.': '. $this->getVar('region-name')."<br/>";
		if (strlen($this->getVar('city-name'))>0)
			$txt .= _BAN_MF_CITY_NAME.': '. $this->getVar('city-name')."<br/>";
		if (strlen($this->getVar('postcode'))>0)
			$txt .= _BAN_MF_POSTCODE.': '. $this->getVar('postcode')."<br/>";
		if (strlen($this->getVar('latitude'))>0)
			$txt .= _BAN_MF_LATITUDE.': '. $this->getVar('latitude')."<br/>";
		if (strlen($this->getVar('longitude'))>0)
			$txt .= _BAN_MF_LONGITUDE.': '. $this->getVar('longitude')."<br/>";
		if (strlen($this->getVar('timezone'))>0)
			$txt .= _BAN_MF_TIMEZONE.': '. $this->getVar('timezone')."<br/>";
			
		$comment_handler = & xoops_gethandler('comment');
		$module_handler = & xoops_gethandler('module');	
		$xoModule = $module_handler->getByDirname('ban');
		
		$criteria = new CriteriaCompo(new Criteria('com_modid', $xoModule->getVar('mid')));
		$criteria->add(new Criteria('com_itemid', $this->getVar('member_id')));
		$comments = $comment_handler->getObjects($criteria);
		if (count($comments)>0) {
			$txt .= "<br/>";
			foreach($comments as $id => $comment) {
				$txt .= str_replace(chr(0), '', str_replace('\n', '<br/>', stripslashes($comment->getVar('com_text'))));
			}
		}
		return $txt;
	}

	function getDomains() {
		$ret = $this->lookupDomain();
		$ret .= " ".$this->lookupIP();
		$ret = str_replace(array("\n", "\t", '(', ')', '[', ']', ':'), " ", $ret);
		preg_match_all("/((https:\/\/|http:\/\/)*(([a-zA-Z0-9-]+)|(www.|@)))*(([a-zA-Z0-9-]+)|(\.[a-zA-Z0-9-]+)*(\.([0-9]{1,3})|([a-zA-Z0-9]{2,3})|(aero|coop|info|mobi|asia|museum|name)))/s", $ret, $matches);
		$ret = '';
		foreach($matches as $key => $values) {
			foreach($values as $keyb => $value) {
				if (substr($value, 0, 1)=='@') { 
					$value = 'www.' . substr($value, 1, strlen($value)-1);
				}
				if (substr($value, 0, 4)=='www.') {  
					$value = substr($value, 4, strlen($value)-4);
				}
				if ($this->validateDomain($value)!=false) {
					if (substr($value, 0, 4)!='http') {
						$value = 'http://'.$value;
					}
					$ret[$this->getBaseDomain($value)] = $this->getBaseDomain($value); 
				}
			}
		}
		return $ret;
	}
	
	function getEmailAddresses() {
		$ret = $this->lookupDomain($this->getDomains());
		$ret .= " ".$this->lookupIP();
		$ret .= " ".$this->getVar('email');
		$ret = str_replace(array("\n", "\t", '(', ')', '[', ']', ':'), " ", $ret);
		preg_match_all("/[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.([0-9]{1,3})|([a-zA-Z]{2,3})|(aero|coop|info|mobi|asia|museum|name))/i", $ret, $matches);
		$ret = array();
		foreach($matches as $values) {
			foreach($values as $email) {
				if ($this->validateEmail($email))
					$ret[$email] = $email;
			}
		}
		return $ret;
	}
	
	function sendBanNotice() {
		xoops_load('xoopsmailer');
		xoops_loadLanguage('main','ban');
		
		$xoopsMailer =& getMailer();
		$xoopsMailer->setHTML(true);
		$xoopsMailer->setTemplateDir($GLOBALS['xoops']->path('/modules/ban/language/'.$GLOBALS['xoopsConfig']['language'].'/mail_templates/'));
		$xoopsMailer->setTemplate('ban_notice.tpl');
		$xoopsMailer->setSubject(sprintf(_BAN_MF_EMAIL_BANNOTICE, $this->ipaddy()));
		
		$xoopsMailer->assign("SITEURL", XOOPS_URL);
		$xoopsMailer->assign("SITENAME", $GLOBALS['xoopsConfig']['sitename']);
		$xoopsMailer->assign("URL", XOOPS_URL.'/ban/index.php?op=member&id='.$this->getVar('member_id'));
		
		foreach($this->toArray() as $key => $value) {
			$xoopsMailer->assign(strtoupper(str_replace('-', '_', $key)), $value);
		}
		
		$xoopsMailer->assign("IP", $this->ipaddy());
		$xoopsMailer->assign("IP_WHOIS", $this->lookupIP());
		$xoopsMailer->assign("DOMAIN_WHOIS", $this->lookupDomain());
		
		foreach($this->getEmailAddresses() as $key=>$email) {
			$xoopsMailer->setToEmails($email);
		}
		
		if (!$xoopsMailer->send())
			return false;
		else 
			return true;
	}
	
	function lookupDomain($domain = ''){
		if (empty($domain))
			$domain = $this->getVar('network-addy');
		xoops_load('cache');
		if (is_array($domain)) {
			if (!$whois = XoopsCache::read('whois_'.md5(implode('|', $domain)))) {
				foreach($domain as $value) {
					if (!$ret = XoopsCache::read('whois_'.$value)) {
						$ret = ban_get_curl(sprintf($this->_whois_url, $value));					
						XoopsCache::write('whois_'.$domain, $ret, 14400);
					}	
					if (!strpos($ret, 'Error'))
						$whois .= $ret;
				}
				XoopsCache::write('whois_'.md5(implode('|', $domain)), $whois, 14400);
			}
		} else {
			if (!$whois = XoopsCache::read('whois_'.$domain)) {
				$whois = ban_get_curl(sprintf($this->_whois_url, $domain));
				XoopsCache::write('whois_'.$domain, $whois, 14400);
			}
		}
		return $whois;
	}

	function lookupIP() {
		$ip = $this->ipaddy();
		xoops_load('cache');
		if (!$whois = XoopsCache::read('whois_'.$ip)) {
			$whois = ban_get_curl(sprintf($this->_whois_url, $ip));
			XoopsCache::write('whois_'.$ip, $whois, 14400);
		}
		return $whois;
	}
	
	function validateEmail($email) {
		if(preg_match("/[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.([0-9]{1,3})|([a-zA-Z]{2,3})|(aero|coop|info|mobi|asia|museum|name))/i", $email)) {
			return true;
		} else {
			return false;
		}
	}
	
	function validateDomain($domain) {
		return (preg_match("/^([a-zA-Z0-9-]+)*(\.([a-z0-9]{1,3})|(\.([a-zA-Z]{2,3})|(aero|coop|info|mobi|asia|museum|name)))/i", $domain));
	}
		
	function getBaseDomain($url, $debug = 0)
	{
		$url = strtolower($url);
		$full_domain = parse_url($url, PHP_URL_HOST);
	
		// break up domain, reverse
		$domain = explode('.', $full_domain);
		$domain = array_reverse($domain);
		// first check for ip address
		if (count($domain) == 4 && is_numeric($domain[0]) && is_numeric($domain[1]) && is_numeric($domain[2]) && is_numeric($domain[3])) {
			return $full_domain;
		}
	
		// if only 2 domain parts, that must be our domain
		if (count($domain) <= 2) {
			return $full_domain;
		}
	
		if (in_array($domain[0], $this->c_tld) && in_array($domain[1], $this->g_tld) && $domain[2] != 'www') {
			$full_domain = $domain[2] . '.' . $domain[1] . '.' . $domain[0];
		} else {
			$full_domain = $domain[1] . '.' . $domain[0];
		}
		// did we succeed?
		return $full_domain;
	}
				
}


/**
* XOOPS Ban Profiler handler class.
* This class is responsible for providing data access mechanisms to the data source
* of XOOPS user class objects.
*
* @author  Simon Roberts <simon@labs.coop>
*/
class banMembersHandler extends XoopsPersistableObjectHandler
{
	var $_ipinfodb_key = _MI_SERVER_IPINFODB_KEY;
	
    function __construct(&$db) 
    {
        parent::__construct($db, "ban_member", 'banMembers', "member_id", "display_name");
    }
	
    function insert($obj, $force = true) {
    	
    	if ($obj->isNew()) {
    		if (strlen($obj->getVar('long'))==0)
    			$obj->setVar('long', @ip2long($obj->ipaddy()));

    		if (strlen($obj->getVar('network-addy'))<strlen(strlen($obj->ipaddy())))
    			$obj->setVar('network-addy', @gethostbyaddr($obj->ipaddy()));
    		
    		if (strlen($obj->getVar('ip4'))<>0) {
	    		if (strlen($obj->getVar('ip4'))<7) {
	    			return false;
	    		} elseif (substr($obj->getVar('ip4'), strlen($obj->getVar('ip4'))-1, 1) == '.') {
	    			return false;
	    		} else {
	    			$count = count(explode('.', $obj->getVar('ip4')));
	    			if ($count!=4)
	    				return false;
	    		}
    		} elseif (strlen($obj->getVar('ip6'))<>0) {
    			if (strlen($obj->getVar('ip6'))<15) {
	    			return false;
	    		} elseif (substr($obj->getVar('ip6'), strlen($obj->getVar('ip6'))-1, 1) == ':') {
	    			return false;
	    		} else {
	    			$count = count(explode(':',$obj->getVar('ip6')));
	    			if ($count<5)
	    				return false;
	    		}
    		}

			$spiders_handler =& xoops_getmodulehandler('spiders','spiders');
			$criteria = new Criteria('robot-host', '*', 'NOT LIKE');
			$spiders = $spiders_handler->getObjects($criteria);
			foreach($spiders as $id => $sobj) {
				foreach(explode('|', $sobj->getVar('robot-host')) as $spider) {
					if (xoops_getBaseDomain('http://'.$obj->getVar('network-addy'))==xoops_getBaseDomain('http://'.$spider)) {
						return false;
					}
				}
			} 
			    		
    		$criteriaa = new CriteriaCompo(new Criteria('`ip4`', $obj->getVar('ip4')));
	    	$criteriaa->add(new Criteria('`proxy-ip4`', $obj->getVar('proxy-ip4')), 'AND');
	    	$criteriab = new CriteriaCompo(new Criteria('`ip6`', $obj->getVar('ip6')));
	    	$criteriab->add(new Criteria('`proxy-ip6`', $obj->getVar('proxy-ip6')), 'AND');
	    	$criteriac = new CriteriaCompo(new Criteria('`long`', $obj->getVar('long')));
	    	$criteriac->add(new Criteria('`network-addy`', $obj->getVar('network-addy')), 'AND');
	    	$criteria = new CriteriaCompo($criteriaa, 'OR');
	    	$criteria->add($criteriab, 'OR');
	    	$criteria->add($criteriac, 'OR');
			if ($this->getCount($criteria)>0)
				return false;
						
    		$ipLite = new ip2location_lite;
			$ipLite->setKey($this->_ipinfodb_key);
 			//Get errors and locations
			$locations = $ipLite->getCity($obj->ipaddy());
    		$obj->setVar('country-code', strtoupper($locations['countryCode']));
    		$obj->setVar('country-name', ucfirst($locations['countryName']));
    		$obj->setVar('region-name', ucfirst($locations['regionName']));
    		$obj->setVar('city-name', ucfirst($locations['cityName']));
    		$obj->setVar('postcode', $locations['zipCode']);
    		$obj->setVar('latitude', $locations['latitude']);
    		$obj->setVar('longitude', $locations['longitude']);
    		$obj->setVar('timezone', $locations['timeZone']);
    		    			
    	}
    	

    	$ret = parent::insert($obj, $force);
    	// Send Abuse email
    	$obj = parent::get($ret);
    	@$obj->sendBanNotice();
    	
    	return $ret;
    	
    }
    
    function get($id, $force = true) {
    	$obj = parent::get($id, $force);
    	if (strlen($obj->getVar('country-code'))==0) {
    		$ipLite = new ip2location_lite;
    		$ipLite->setKey($this->_ipinfodb_key);
    		//Get errors and locations
    		$locations = $ipLite->getCity($obj->ipaddy());
    		$obj->setVar('country-code', strtoupper($locations['countryCode']));
    		$obj->setVar('country-name', ucfirst($locations['countryName']));
    		$obj->setVar('region-name', ucfirst($locations['regionName']));
    		$obj->setVar('city-name', ucfirst($locations['cityName']));
    		$obj->setVar('postcode', $locations['zipCode']);
    		$obj->setVar('latitude', $locations['latitude']);
    		$obj->setVar('longitude', $locations['longitude']);
    		$obj->setVar('timezone', $locations['timeZone']);
    		parent::insert($obj, $force);
    	}
    	return $obj;
    }
    
    function getObjects($criteria, $id_as_key = false, $as_object = true) {
    	    	$objs = parent::getObjects($criteria, $id_as_key, $as_object);
    	return $objs;
    }
}
?>
