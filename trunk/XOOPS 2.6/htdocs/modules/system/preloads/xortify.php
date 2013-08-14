<?php
/**
 * System Preloads
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author      Simon Roberts (AKA wishcraft)
 * @version     $Id: xortify.php 8066 2011-11-06 05:09:33Z beckmi $
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class SystemXortifyPreload extends XoopsPreloadItem
{
	function eventCoreFooterEnd($args)
	{
		// Runs Security Preloader
		include_once XOOPS_ROOT_PATH.'/class/cache/xoopscache.php';
		$result = XoopsCache::read('xortify_system_footer_end');
		if ((isset($result['time'])?(float)$result['time']:0)<=microtime(true)) {
			XoopsCache::write('xortify_system_footer_end', array('time'=>microtime(true)+3600*24*1.75), 3600*24*1.75);
			include_once XOOPS_ROOT_PATH . ( '/modules/xortify/cron/serverup.php' );
		}
	}
}

?>