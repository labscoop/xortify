<?php
if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}
/**
 * Class for unban Profiler
 * @author Simon Roberts (simon@chronolabs.org.au)
 * @copyright copyright (c) 2000-2009 XOOPS.org
 * @package kernel
 */
class unbanMembers extends XoopsObject
{

    function unbanMembers($fid = null)
    {
        $this->initVar('member_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('category_id', XOBJ_DTYPE_INT, null, false);		
		$this->initVar('suid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('uid', XOBJ_DTYPE_INT, null, false);	
        $this->initVar('uname', XOBJ_DTYPE_TXTBOX, null, false, 64);
		$this->initVar('email', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('ip4', XOBJ_DTYPE_TXTBOX, null, false, 255);
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
    	$categories_handler = xoops_getmodulehandler('categories', 'unban');
    	$category = $categories_handler->get($this->getVar('category_id'));
    	if (is_object($category))
    		$ret['category'] = $category->getVar('category_name');
    	$comment_handler = xoops_gethandler('comment');
		$module_handler = xoops_gethandler('module');
		$GLOBALS['moduleBan'] = $module_handler->getByDirname('ban');
		$criteria = new CriteriaCompo(new Criteria('com_itemid', $this->getVar('member_id')));
		$criteria->add(new Criteria('com_modid', $GLOBALS['moduleBan']->getVar('mid')));
		$comments = $comment_handler->getObjects($criteria, true);
		if (count($comments)>0) {
			foreach($comments as $com_id => $comment);
				$ret['comments'][$com_id] = $comment->toArray();
		}
    	return $ret;
    }
    
	function ipaddy() {
		if (strlen($this->getVar('ip4'))>0)
			return $this->getVar('ip4');
		else
			return $this->getVar('ip6');
	}
	
	function story() {
		$txt .= '<img src="'.XOOPS_URL.'/modules/unban/images/unban_slogo.png"><br/>';
		$txt .= 'Ban Made on the '. date(_DATESTRING, $this->getVar('made'))." by a remote client of the Xortify Cloud this attempted security intrusions details are as follow:<br/><br/>";
		if (strlen($this->getVar('uname'))>0)
			$txt .= _UNBAN_MF_UNAME.': '. $this->getVar('uname')."<br/>";
		if (strlen($this->getVar('email'))>0)
			$txt .= _UNBAN_MF_EMAIL.': '. $this->getVar('email')."<br/>";
		if (strlen($this->getVar('ip4'))>0)
			$txt .= _UNBAN_MF_IP4.': '. $this->getVar('ip4')."<br/>";
		if (strlen($this->getVar('ip6'))>0)
			$txt .= _UNBAN_MF_IP6.': '. $this->getVar('ip6')."<br/>";
		if (strlen($this->getVar('long'))>0)
			$txt .= _UNBAN_MF_LONG.': '. $this->getVar('long')."<br/>";
		if (strlen($this->getVar('proxy-ip4'))>0)
			$txt .= _UNBAN_MF_PROXY_IP4.': '. $this->getVar('proxy-ip4')."<br/>";
		if (strlen($this->getVar('proxy-ip6'))>0)
			$txt .= _UNBAN_MF_PROXY_IP6.': '. $this->getVar('proxy-ip6')."<br/>";
		if (strlen($this->getVar('network-addy'))>0)
			$txt .= _UNBAN_MF_NETWORK_ADDY.': '. $this->getVar('network-addy')."<br/>";
		if (strlen($this->getVar('mac-addy'))>0)
			$txt .= _UNBAN_MF_MAC_ADDY.': '. $this->getVar('mac-addy')."<br/>";
		if (strlen($this->getVar('country-name'))>0)
			$txt .= _UNBAN_MF_COUNTRY_NAME.': '. $this->getVar('country-name')."(".$this->getVar('country-code').")<br/>";
		if (strlen($this->getVar('region-name'))>0)
			$txt .= _UNBAN_MF_REGION_NAME.': '. $this->getVar('region-name')."<br/>";
		if (strlen($this->getVar('city-name'))>0)
			$txt .= _UNBAN_MF_CITY_NAME.': '. $this->getVar('city-name')."<br/>";
		if (strlen($this->getVar('postcode'))>0)
			$txt .= _UNBAN_MF_POSTCODE.': '. $this->getVar('postcode')."<br/>";
		if (strlen($this->getVar('latitude'))>0)
			$txt .= _UNBAN_MF_LATITUDE.': '. $this->getVar('latitude')."<br/>";
		if (strlen($this->getVar('longitude'))>0)
			$txt .= _UNBAN_MF_LONGITUDE.': '. $this->getVar('longitude')."<br/>";
		if (strlen($this->getVar('timezone'))>0)
			$txt .= _UNBAN_MF_TIMEZONE.': '. $this->getVar('timezone')."<br/>";
		
		$comment_handler = & xoops_gethandler('comment');
		$module_handler = & xoops_gethandler('module');	
		$xoModule = $module_handler->getByDirname('unban');
		
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
}


/**
* XOOPS unban Profiler handler class.
* This class is responsible for providing data access mechanisms to the data source
* of XOOPS user class objects.
*
* @author  Simon Roberts <simon@chronolabs.org.au>
* @package kernel
*/
class unbanMembersHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db) 
    {
        parent::__construct($db, "unban_member", 'unbanMembers', "member_id", "display_name");
    }
	
}
?>
