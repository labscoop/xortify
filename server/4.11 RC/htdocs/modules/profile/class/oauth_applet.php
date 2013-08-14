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
 * @author          Simon Roberts <phppp@users.sourceforge.net>
 * @version         $Id: oauth_applet.php 8066 2011-11-06 05:09:33Z beckmi $
 */

defined('XOOPS_ROOT_PATH') or die("XOOPS root path not defined");

/**
 * @package kernel
 * @copyright copyright &copy; 2000 XOOPS.org
 */
class ProfileOauth_applet extends XoopsObject
{
    function __construct()
    {
        $this->initVar('appid', XOBJ_DTYPE_INT);
        $this->initVar('profile_id', XOBJ_DTYPE_INT);
        $this->initVar('name', XOBJ_DTYPE_OTHER);
        $this->initVar('description', XOBJ_DTYPE_OTHER);
        $this->initVar('image', XOBJ_DTYPE_OTHER);
        $this->initVar('uri', XOBJ_DTYPE_OTHER);
        $this->initVar('client_id', XOBJ_DTYPE_OTHER);
        $this->initVar('org_name', XOBJ_DTYPE_OTHER);
        $this->initVar('org_uri', XOBJ_DTYPE_OTHER);
    }

    function ProfileOauth_applet()
    {
        $this->__construct();
    }

}

/**
 * @package kernel
 * @copyright copyright &copy; 2000 XOOPS.org
 */
class ProfileOauth_appletHandler extends XoopsPersistableObjectHandler
{
    function ProfileOauth_appletHandler(&$db)
    {
        $this->__construct($db);
    }

    function __construct(&$db)
    {
        parent::__construct($db, "profile_oauth_applet", "ProfileOauth_applet", "appid", "appid");
    }
    
    function getApplets($uid) {
    	if ($uid>0) {
    		$criteria = new Criteria('profile_id', $uid);
    	} else {
    		$criteria = new Criteria('1', '1', '=');
    		$criteria->setOrder('DESC');
    		$criteria->setSort('`profile_id`');
    	}
    	$user_handler = xoops_gethandler('user');
    	$users = array();
    	foreach($this->getObjects($criteria, true) as $key => $value) {
    		if (!isset($users[$value->getVar('profile_id')])) {
    			$users[$value->getVar('profile_id')] = $user_handler->get($value->getVar('profile_id')); 
    		}
    		$ret[$key] = $value->toArray();
    		$ret[$key]['uname'] = $users[$value->getVar('profile_id')]->getVar('uname');
    		if (file_exists(XOOPS_UPLOAD_PATH . $ret['image']))
    			$ret[$key]['image'] =  XOOPS_UPLOAD_URL . $ret['image'];
    		else
    			$ret[$key]['image'] = XOOPS_UPLOAD_URL . '/avartar/blank.gif';
    	
    	}
    	return $ret;
    }
}
?>