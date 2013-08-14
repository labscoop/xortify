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

	static function init() {
		$GLOBALS['xoops'] = Xoops::getInstance();
		$GLOBALS['xoops']->loadLanguage('modinfo', 'xortify');
		
		$module_handler = $GLOBALS['xoops']->getHandler('module');
		$config_handler = $GLOBALS['xoops']->getHandler('config');
		$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module'] = $module_handler->getByDirname('xortify');
		if (is_object($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module'])) {
			$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'] = $config_handler->getConfigList($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module']->getVar('mid'));
		} else {
			$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['fault_delay'] = 600;
		}
		XoopsLoad::load('xoopscache');
	}
	
	static function eventCoreIncludeCommonStart($args)
	{
		XortifyCorePreload::init();
		$result = XoopsCache::read('xortify_core_include_common_start');
		if ((isset($result['time'])?(float)$result['time']:0)<=microtime(true)) {
			XoopsCache::write('xortify_core_include_common_start', array('time'=>microtime(true)), $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['fault_delay']);
			include_once XOOPS_ROOT_PATH . ( '/modules/xortify/include/pre.loader.mainfile.php' );
			XoopsCache::write('xortify_core_include_common_start', array('time'=>microtime(true)), -1);
		}
	}

	static function eventCoreIncludeCommonEnd($args)
	{
  		
		$result = XoopsCache::read('xortify_cleanup_last');
		if ((isset($result['when'])?(float)$result['when']:-microtime(true))+$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['xortify_ip_cache']<=microtime(true)) {
			$result = array();
			$result['when'] = microtime(true);
			$result['files'] = 0;
			$result['size'] = 0;
			foreach(XortifyCorePreload::getFileListAsArray(XOOPS_VAR_PATH.'/caches/xoops_cache/', 'xortify') as $id => $file) {
				if (file_exists(XOOPS_VAR_PATH.'/caches/xoops_data/'.$file)&&!empty($file)) {
					if (@filectime(XOOPS_VAR_PATH.'/caches/xoops_data/'.$file)<time()-$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['xortify_ip_cache']) {
						$result['files']++;
						$result['size'] = $result['size'] + filesize(XOOPS_VAR_PATH.'/caches/xoops_data/'.$file);
						@unlink(XOOPS_VAR_PATH.'/caches/xoops_data/'.$file);
					}
				}
			}
			$result['took'] = microtime(true)-$result['when'];
			XoopsCache::write('xortify_cleanup_last', $result, $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['xortify_ip_cache']*2);
		}
		if (isset($GLOBALS['xortify']['lid']))
			if ($GLOBALS['xortify']['lid']==0)
				unset($GLOBALS['xortify']);
	
		if (strpos($_SERVER["PHP_SELF"], '/banned.php')>0) {
			return false;
		}
		if ((isset($_COOKIE['xortify']['lid'])&&$_COOKIE['xortify']['lid']!=0)||(isset($GLOBALS['xortify']['lid'])&&$GLOBALS['xortify']['lid']!=0)&&!strpos($_SERVER["PHP_SELF"], '/banned.php')) {
			header('Location: '.XOOPS_URL.'/banned.php');
			exit;
		} 
		$result = XoopsCache::read('xortify_core_include_common_end');
	    if ((isset($result['time'])?(float)$result['time']:0)<=microtime(true)) {
	    	XoopsCache::write('xortify_core_include_common_end', array('time'=>microtime(true)+$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['fault_delay']), $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['fault_delay']);
			if (XortifyCorePreload::hasAPIUserPass()) {
				include_once XOOPS_ROOT_PATH . ( '/modules/xortify/include/post.loader.mainfile.php' );
			}
			XoopsCache::write('xortify_core_include_common_end', array('time'=>microtime(true)), -1);
		}
					
	}

	static function eventCoreHeaderCacheEnd($args)
	{
		$result = XoopsCache::read('xortify_core_header_cache_end');
		if ((isset($result['time'])?(float)$result['time']:0)<=microtime(true)) {
			XoopsCache::write('xortify_core_header_cache_end', array('time'=>microtime(true)+$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['fault_delay']), $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['fault_delay']);
			if (XortifyCorePreload::hasAPIUserPass()) { 		
				include_once XOOPS_ROOT_PATH . ( '/modules/xortify/include/post.header.endcache.php' );
			}
			XoopsCache::write('xortify_core_header_cache_end', array('time'=>microtime(true)), -1);
		}
	}
	
	static function eventCoreHeaderAddmeta($args)
	{
		if (isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['_pass'])) {
			if ($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['_pass'] == true) {
				include_once XOOPS_ROOT_PATH.'/modules/xortify/include/functions.php';
				addmeta_googleanalytics(_XOR_MI_XOOPS_GOOGLE_ANALYTICS_ACCOUNTID_USERPASSED, $_SERVER['HTTP_HOST']);
				if (defined('_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_USERPASSED')&&strlen(constant('_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_USERPASSED'))>=13) { 
					addmeta_googleanalytics(_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_USERPASSED, $_SERVER['HTTP_HOST']);
				}
			}
		}
		$result = XoopsCache::read('xortify_core_header_add_meta');
		if ((isset($result['time'])?(float)$result['time']:0)<=microtime(true)) {
			XoopsCache::write('xortify_core_header_add_meta', array('time'=>microtime(true)+$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['fault_delay']), $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['fault_delay']);
			if (XortifyCorePreload::hasAPIUserPass()) { 		
				include_once XOOPS_ROOT_PATH . ( '/modules/xortify/include/post.header.addmeta.php' );
			}
			XoopsCache::write('xortify_core_header_add_meta', array('time'=>microtime(true)), -1);
		}
	}
		
	static function hasAPIUserPass()
	{
		if ($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['xortify_username']!=''&&$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['xortify_password']!='')
			return true;
		else
			return false;
	}	
	
	static function getFileListAsArray($dirname, $prefix="xortify")
	{
		$filelist = array();
		if (substr($dirname, -1) == '/') {
			$dirname = substr($dirname, 0, -1);
		}
		if (is_dir($dirname) && $handle = opendir($dirname)) {
			while (false !== ($file = readdir($handle))) {
				if (!preg_match("/^[\.]{1,2}$/",$file) && is_file($dirname.'/'.$file)) {
					if (!empty($prefix)&&strpos(' '.$file, $prefix)>0) {
						$filelist[$file] = $file;
					} elseif (empty($prefix)) {
						$filelist[$file] = $file;
					}
				}
			}
			closedir($handle);
			asort($filelist);
			reset($filelist);
		}
		return $filelist;
	}
}

?>