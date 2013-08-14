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
 * @since           2.5.0
 * @author          Simon Roberts <simon@labs.coop>
 * @version         $Id: cron.php 8066 2011-11-06 05:09:33Z beckmi $
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Profile core preloads
 *
 * @copyright       Chronolabs Coopertive (Australia)  http://web.labs.coop
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author          Simon Roberts <simon@labs.coop>
 */
class ProfileCronPreload extends XoopsPreloadItem
{
    function eventCoreFooterEnd($args)
    {
    	$module_handler = xoops_gethandler('module');
    	$config_handler = xoops_gethandler('config');
    	$profileModule = $module_handler->getByDirname('profile');
    	$configs = $config_handler->getConfigList($profileModule->getVar('mid'));
		if ($configs['profile_crontype']=='preloader') {
	    	xoops_load('cache');
			$result = XoopsCache::read('profile_core_footer_end_gravatar');
			if ((isset($result['time'])?(float)$result['time']:0)>=microtime(true)) {
				XoopsCache::write('profile_core_footer_end_gravatar', array('time'=>microtime(true)+$config['profile_fault_delay']), $config['profile_fault_delay']);
				include dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'cron' . DIRECTORY_SEPARATOR . 'gravatar.php';
				XoopsCache::write('profile_core_footer_end_gravatar', array('time'=>microtime(true)+$config['profile_croninterval']), $config['profile_croninterval']);
			}
		}
    }
}
?>