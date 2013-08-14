<?php
/**
 * @package     xortify
 * @subpackage  module
 * @description	Sector Nexoork Security Drone
 * @author	    Simon Roberts WISHCRAFT <simon@labs.coop>
 * @author	    Richardo Costa TRABIS 
 * @copyright	copyright (c) 2010-2013 XOOPS.org
 * @licence		GPL 2.0 - see docs/LICENCE.txt
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class SystemXortifyPreload extends XoopsPreloadItem
{
	function eventCoreIncludeCommonEnd($args)
	{
		$module_handler = xoops_gethandler('module');
		$config_handler = xoops_gethandler('config');		
		$GLOBALS['xortifyModule'] = $module_handler->getByDirname('xortify');
		if (is_object($GLOBALS['xortifyModule'])) {
			$GLOBALS['xortifyModuleConfig'] = $config_handler->getConfigList($GLOBALS['xortifyModule']->getVar('mid'));
		}
		
		include_once XOOPS_ROOT_PATH.'/class/cache/xoopscache.php';
		$result = XoopsCache::read('xortify_core_include_common_end_cron');
		if ((isset($result['time'])?(float)$result['time']:0)<=microtime(true)) {
			XoopsCache::write('xortify_core_include_common_end_cron', array('time'=>microtime(true)+$GLOBALS['xortifyModuleConfig']['fault_delay']), $GLOBALS['xortifyModuleConfig']['fault_delay']);
			switch ($GLOBALS['xortifyModuleConfig']['crontype']) {
				case 'preloader':
					$read = XoopsCache::read('xortify_pause_preload');
					if ((isset($read['time'])?(float)$read['time']:0)<=microtime(true)) {
						XoopsCache::write('xortify_pause_preload', array('time'=>microtime(true)+$GLOBALS['xortifyModuleConfig']['croninterval']));
						$GLOBALS['xortify_preloader']=true;
						ob_start();
						include(XOOPS_ROOT_PATH.'/modules/xortify/cron/serverup.php');
						ob_end_clean();
					}
					break;
			}
			XoopsCache::write('xortify_core_include_common_end_cron', array('time'=>microtime(true)), -1);
		}
	}
}

?>