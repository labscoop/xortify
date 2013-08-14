<?php
/**
 * Authentification class factory
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         kernel
 * @subpackage      auth
 * @since           2.0
 * @author          Pierre-Eric MENUET <pemphp@free.fr>
 * @version         $Id: authfactory.php 8066 2011-11-06 05:09:33Z beckmi $
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 *
 * @package kernel
 * @subpackage auth
 * @description Authentification class factory
 * @author Pierre-Eric MENUET <pemphp@free.fr>
 * @copyright copyright (c) 2000-2005 XOOPS.org
 */
class XoopsAuthFactory
{
    /**
     * Get a reference to the only instance of authentication class
     *
     * if the class has not been instantiated yet, this will also take
     * care of that
     *
     * @static
     * @return object Reference to the only instance of authentication class
     */
    function &getAuthConnection($uname)
    {
        static $auth_instance;
        if (!isset($auth_instance)) {
            $config_handler =& xoops_gethandler('config');
            $authConfig = $config_handler->getConfigsByCat(XOOPS_CONF_AUTH);

            include_once $GLOBALS['xoops']->path('class/auth/auth.php');

            if (empty($authConfig['auth_method'])) { // If there is a config error, we use xoops
                $xoops_auth_method = 'xoops';
            } else {
                $xoops_auth_method = $authConfig['auth_method'];
            }
            // Verify if uname allow to bypass LDAP auth
            if (in_array($uname, $authConfig['ldap_users_bypass'])) {
                $xoops_auth_method = 'xoops';
            }

            $ret = include_once $GLOBALS['xoops']->path('class/auth/auth_' . $xoops_auth_method . '.php');
            if ($ret == false) {
                return false;
            }

            $class = 'XoopsAuth' . ucfirst($xoops_auth_method);
            if (!class_exists($class)) {
                $GLOBALS['xoopsLogger']->triggerError($class, _XO_ER_CLASSNOTFOUND, __FILE__, __LINE__, E_USER_ERROR );
                return false;
            }
            switch ($xoops_auth_method) {
                case 'xoops':
                    $dao =& XoopsDatabaseFactory::getDatabaseConnection();
                    break;
                case 'ldap':
                    $dao = null;
                    break;
                case 'ads':
                    $dao = null;
                    break;
            }
            $auth_instance = new $class($dao);
        }
        return $auth_instance;
    }
}

?>