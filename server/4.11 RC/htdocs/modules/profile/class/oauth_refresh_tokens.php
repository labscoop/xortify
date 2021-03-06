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
 * @version         $Id: oauth_refresh_tokens.php 8066 2011-11-06 05:09:33Z beckmi $
 */

defined('XOOPS_ROOT_PATH') or die("XOOPS root path not defined");

/**
 * @package kernel
 * @copyright copyright &copy; 2000 XOOPS.org
 */
class ProfileOauth_refresh_tokens extends XoopsObject
{
    function __construct()
    {
        $this->initVar('refresh_token', XOBJ_DTYPE_OTHER);
        $this->initVar('client_id', XOBJ_DTYPE_OTHER);
        $this->initVar('user_id', XOBJ_DTYPE_OTHER);
        $this->initVar('expires', XOBJ_DTYPE_OTHER);
        $this->initVar('scope', XOBJ_DTYPE_OTHER);
    }

    function ProfileOauth_refresh_tokens()
    {
        $this->__construct();
    }

}

/**
 * @package kernel
 * @copyright copyright &copy; 2000 XOOPS.org
 */
class ProfileOauth_refresh_tokensHandler extends XoopsPersistableObjectHandler
{
    function ProfileOauth_refresh_tokensHandler(&$db)
    {
        $this->__construct($db);
    }

    function __construct(&$db)
    {
        parent::__construct($db, "profile_oauth_refresh_tokens", "ProfileOauth_refresh_tokens");
    }
}
?>