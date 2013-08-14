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
 * @since           2.3.0
 * @author          Jan Pedersen
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id: edituser.php 8066 2011-11-06 05:09:33Z beckmi $
 */

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'header.php';

$module_handler = xoops_gethandler('module');
$config_handler = xoops_gethandler('config');
$profileModule = $module_handler->getByDirname('profile');
$configs = $config_handler->getConfigList($profileModule->getVar('mid'));
if ($configs['profile_oauth2']) {
	require_once(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'OAuth2'.DIRECTORY_SEPARATOR.'Autoloader.php');
	OAuth2_Autoloader::register();
	// register test classes
	OAuth2_Autoloader::register(dirname(__FILE__).'/class/OAuth2');
	$storage = new OAuth2_Storage_Xoops();
	$GLOBALS['OAuth2_Server'] = new OAuth2_Server($storage);
	$GLOBALS['OAuth2_Server']->addGrantType(new OAuth2_GrantType_AuthorizationCode($storage));
} else {
	header('Location: ' . XOOPS_URL);
}

?>