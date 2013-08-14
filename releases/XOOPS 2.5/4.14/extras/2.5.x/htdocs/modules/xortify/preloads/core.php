<?php
/**
 * @package     xortify
 * @subpackage  module
 * @description	Sector Nexoork Security Drone
 * @author	    Simon Roberts WISHCRAFT <simon@chronolabs.coop>
 * @author	    Richardo Costa TRABIS 
 * @copyright	copyright (c) 2010-2013 XOOPS.org
 * @licence		GPL 2.0 - see docs/LICENCE.txt
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class XortifyCorePreload extends XoopsPreloadItem
{
	
	function eventCoreIncludeCommonStart($args)
	{
		include_once XOOPS_ROOT_PATH.'/class/cache/xoopscache.php';
		$result = XoopsCache::read('xortify_core_include_common_start');
		if ((isset($result['time'])?(float)$result['time']:0)<=microtime(true)) {
			XoopsCache::write('xortify_core_include_common_start', array('time'=>microtime(true)+600), 600);
			include_once XOOPS_ROOT_PATH . ( '/modules/xortify/include/pre.loader.mainfile.php' );
			XoopsCache::write('xortify_core_include_common_start', array('time'=>microtime(true)), -1);
		}
	}

	function eventCoreIncludeCommonEnd($args)
	{
		xoops_loadLanguage('modinfo', 'xortify');
		$module_handler = xoops_gethandler('module');
		$config_handler = xoops_gethandler('config');		
		if (!is_object($_SESSION['xortify'][XORTIFY_INSTANCE_KEY]['module']))
			$_SESSION['xortify'][XORTIFY_INSTANCE_KEY]['module'] = $module_handler->getByDirname('xortify');
		if (!isset($_SESSION['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig']))
			$_SESSION['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig'] = $config_handler->getConfigList($_SESSION['xortify'][XORTIFY_INSTANCE_KEY]['module']->getVar('mid'));
		
		include_once XOOPS_ROOT_PATH.'/class/cache/xoopscache.php';
		$result = XoopsCache::read('xortify_core_include_common_end_cron');
		if ((isset($result['time'])?(float)$result['time']:0)<=microtime(true)) {
			XoopsCache::write('xortify_core_include_common_end_cron', array('time'=>microtime(true)+$_SESSION['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig']['fault_delay']), $_SESSION['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig']['fault_delay']);
			switch ($_SESSION['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig']['crontype']) {
				case 'preloader':
					$read = XoopsCache::read('xortify_pause_preload');
					if ((isset($read['time'])?(float)$read['time']:0)<=microtime(true)) {
						XoopsCache::write('xortify_pause_preload', array('time'=>microtime(true)+$_SESSION['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig']['croninterval']));
						$_SESSION['xortify'][XORTIFY_INSTANCE_KEY]['_preloader']=true;
						ob_start();
						include(XOOPS_ROOT_PATH.'/modules/xortify/cron/serverup.php');
						ob_end_clean();
					}
					break;
			}
			XoopsCache::write('xortify_core_include_common_end_cron', array('time'=>microtime(true)), -1);
		}
		
	    $result = XoopsCache::read('xortify_core_include_common_end');
	    if ((isset($result['time'])?(float)$result['time']:0)<=microtime(true)) {
			XoopsCache::write('xortify_core_include_common_end', array('time'=>microtime(true)+$_SESSION['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig']['fault_delay']), $_SESSION['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig']['fault_delay']);
			if (XortifyCorePreload::hasAPIUserPass()) {
				include_once XOOPS_ROOT_PATH . ( '/modules/xortify/include/post.loader.mainfile.php' );
			}
			XoopsCache::write('xortify_core_include_common_end', array('time'=>microtime(true)), -1);
		}
			
	}

	function eventCoreHeaderCacheend($args)
	{
		
		include_once XOOPS_ROOT_PATH.'/class/cache/xoopscache.php';
		$result = XoopsCache::read('xortify_core_header_cache_end');
		if ((isset($result['time'])?(float)$result['time']:0)<=microtime(true)) {
			XoopsCache::write('xortify_core_header_cache_end', array('time'=>microtime(true)+$_SESSION['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig']['fault_delay']), $_SESSION['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig']['fault_delay']);
			if (XortifyCorePreload::hasAPIUserPass()) { 		
				include_once XOOPS_ROOT_PATH . ( '/modules/xortify/include/post.header.endcache.php' );
			}
			XoopsCache::write('xortify_core_header_cache_end', array('time'=>microtime(true)), -1);
		}
	}
	
	function eventCoreFooterEnd($args)
	{
		
		include_once XOOPS_ROOT_PATH.'/class/cache/xoopscache.php';
		$result = XoopsCache::read('xortify_core_header_cache_end');
		if ((isset($result['time'])?(float)$result['time']:0)<=microtime(true)) {
			XoopsCache::write('xortify_core_header_cache_end', array('time'=>microtime(true)+$_SESSION['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig']['fault_delay']), $_SESSION['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig']['fault_delay']);
			if (XortifyCorePreload::hasAPIUserPass()) { 		
				include_once XOOPS_ROOT_PATH . ( '/modules/xortify/include/post.header.endcache.php' );
			}
			XoopsCache::write('xortify_core_header_cache_end', array('time'=>microtime(true)), -1);
		}
	}
	
	function eventCoreHeaderAddmeta($args)
	{
		if (isset($_SESSION['xortify'][XORTIFY_INSTANCE_KEY]['_pass'])) {
			if ($_SESSION['xortify'][XORTIFY_INSTANCE_KEY]['_pass'] == true) {
				include_once XOOPS_ROOT_PATH.'/modules/xortify/include/functions.php';
				addmeta_googleanalytics(_XOR_MI_XOOPS_GOOGLE_ANALYTICS_ACCOUNTID_USERPASSED, $_SERVER['HTTP_HOST']);
				if (defined('_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_USERPASSED')&&strlen(constant('_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_USERPASSED'))>=13) { 
					addmeta_googleanalytics(_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_USERPASSED, $_SERVER['HTTP_HOST']);
				}	
			}
		}
	}
	
	function hasAPIUserPass()
	{
		if ($_SESSION['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig']['xortify_username']!=''&&$_SESSION['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig']['xortify_password']!='')
			return true;
		else
			return false;
	}		
}

?>