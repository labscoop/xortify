<?php
/*
 * Prevents Spam, Harvesting, Human Rights Abuse, Captcha Abuse etc.
 * basic statistic of them in XOOPS Copyright (C) 2012 Simon Roberts 
 * Contact: wishcraft - simon@chronolabs.com.au
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 * See /docs/license.pdf for full license.
 * 
 * Shouts:- 	Mamba (www.xoops.org), flipse (www.nlxoops.nl)
 * 				Many thanks for your additional work with version 1.01
 * 
 * Version:		3.10 Final (Stable)
 * Published:	Chronolabs
 * Download:	http://code.google.com/p/chronolabs
 * This File:	core.php		
 * Description:	Preloader Hooking Stratum for Xortify Client
 * Date:		09/09/2012 19:34 AEST
 * License:		GNU3
 * 
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

include_once XOOPS_ROOT_PATH.'/class/cache/xoopscache.php';
include_once XOOPS_ROOT_PATH.'/modules/xortify/include/functions.php';
include_once XOOPS_ROOT_PATH.'/modules/xortify/include/instance.php';

class XortifyCorePreload extends XoopsPreloadItem
{
	
	static function eventCoreIncludeCommonStart($args)
	{
		
		//$GLOBALS['xoopsLoad'] = new XoopsLoad();
		$GLOBALS['xoopsCache'] = new XoopsCache();
		// Detect if it is an internal refereer.
		$ip = xortify_getIP();
		if (isset($_SERVER['HTTP_REFERER'])&&$result = $GLOBALS['xoopsCache']->read('xortify_'.strtolower(__FUNCTION__).'_'.md5($ip))) {
			if (strtolower(XOOPS_URL)==strtolower(substr($_SERVER['HTTP_REFERER'], 0, strlen(XOOPS_URL)))&&$result['time']<microtime(true)) {
				$GLOBALS['xoopsCache']->write('xortify_'.strtolower(__FUNCTION__).'_'.md5($ip), array('time'=>microtime(true)+1800), 1800);
				return false;
			}
		}
		$GLOBALS['xoopsCache']->write('xortify_'.strtolower(__FUNCTION__).'_'.md5($ip), array('time'=>microtime(true)+1800), 1800);
		// Runs Security Preloader
		$result = $GLOBALS['xoopsCache']->read('xortify_core_include_common_start');
		if ((isset($result['time'])?(float)$result['time']:0)<=microtime(true)) {
			$GLOBALS['xoopsCache']->write('xortify_core_include_common_start', array('time'=>microtime(true)+600), 600);
			include_once XOOPS_ROOT_PATH . ( '/modules/xortify/include/pre.loader.mainfile.php' );
			$GLOBALS['xoopsCache']->write('xortify_core_include_common_start', array('time'=>microtime(true)), -1);
		}
	}

	static function eventCoreIncludeCommonEnd($args)
	{
		xoops_loadLanguage('modinfo', 'xortify');
		$module_handler = xoops_gethandler('module');
		$config_handler = xoops_gethandler('config');		
		if (!isset($GLOBALS['xortify'])||!isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module'])||!isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module']))
			$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module'] = $module_handler->getByDirname('xortify');
		if (!isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'])&&is_object($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module']))
			$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'] = $config_handler->getConfigList($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module']->getVar('mid'));
		
		$result = $GLOBALS['xoopsCache']->read('xortify_cleanup_last');
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
			$GLOBALS['xoopsCache']->write('xortify_cleanup_last', $result, $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['xortify_ip_cache']*2);
		}
		
		if (isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['lid']))
			if ($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['lid']==0)
				unset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]);
				
		if (strpos($_SERVER["PHP_SELF"], '/banned.php')>0) {
			return false;
		}
		
		if ((isset($_COOKIE['xortify_lid'])&&$_COOKIE['xortify_lid']!=0)||(isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['lid'])&&$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['lid']!=0)&&!strpos($_SERVER["PHP_SELF"], '/banned.php')) {
			header('Location: '.XOOPS_URL.'/banned.php');
			exit;
		} 

		// Detect if it is an internal refereer.
		if (isset($_SERVER['HTTP_REFERER'])&&(isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION][__FUNCTION__]))) {
			if (strtolower(XOOPS_URL)==strtolower(substr($_SERVER['HTTP_REFERER'], 0, strlen(XOOPS_URL)))&&$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION][__FUNCTION__]<microtime(true)) {
				$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION][__FUNCTION__] = microtime(true)+$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['xortify_ip_cache'];
				return false;
			}
		}
		$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION][__FUNCTION__] = microtime(true)+$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['xortify_ip_cache'];
		
		// Runs Security Preloader
	    $result = $GLOBALS['xoopsCache']->read('xortify_core_include_common_end');
	    if ((isset($result['time'])?(float)$result['time']:0)<=microtime(true)) {
			$GLOBALS['xoopsCache']->write('xortify_core_include_common_end', array('time'=>microtime(true)+$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['fault_delay']), $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['fault_delay']);
			if (XortifyCorePreload::hasAPIUserPass()) {
				include_once XOOPS_ROOT_PATH . ( '/modules/xortify/include/post.loader.mainfile.php' );
			}
			$GLOBALS['xoopsCache']->write('xortify_core_include_common_end', array('time'=>microtime(true)), $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['fault_delay']);
		}
		
		
	}

	static function eventCoreHeaderCacheEnd($args)
	{
		
		// Detect if it is an internal refereer.
		if (isset($_SERVER['HTTP_REFERER'])&&(isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION][__FUNCTION__]))) {
			if (strtolower(XOOPS_URL)==strtolower(substr($_SERVER['HTTP_REFERER'], 0, strlen(XOOPS_URL)))&&$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION][__FUNCTION__]<microtime(true)) {
				$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION][__FUNCTION__] = microtime(true)+$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['xortify_ip_cache'];
				return false;
			}
		}
		$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION][__FUNCTION__] = microtime(true)+$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['xortify_ip_cache'];
		// Runs Security Preloader
		include_once XOOPS_ROOT_PATH.'/class/cache/xoopscache.php';
		$result = $GLOBALS['xoopsCache']->read('xortify_core_header_cache_end');
		if ((isset($result['time'])?(float)$result['time']:0)<=microtime(true)) {
			$GLOBALS['xoopsCache']->write('xortify_core_header_cache_end', array('time'=>microtime(true)+$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['fault_delay']), $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['fault_delay']);
			if (XortifyCorePreload::hasAPIUserPass()) { 		
				include_once XOOPS_ROOT_PATH . ( '/modules/xortify/include/post.header.endcache.php' );
			}
			$GLOBALS['xoopsCache']->write('xortify_core_header_cache_end', array('time'=>microtime(true)), -1);
		}
		
	}

	static function eventCoreFooterEnd($args)
	{
			// Detect if it is an internal refereer.
		if (isset($_SERVER['HTTP_REFERER'])&&(isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION][__FUNCTION__]))) {
			if (strtolower(XOOPS_URL)==strtolower(substr($_SERVER['HTTP_REFERER'], 0, strlen(XOOPS_URL)))&&$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION][__FUNCTION__]<microtime(true)) {
				$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION][__FUNCTION__] = microtime(true)+$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['xortify_ip_cache'];
				return false;
			}
		}
		$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION][__FUNCTION__] = microtime(true)+$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['xortify_ip_cache'];
		// Runs Security Preloader
		include_once XOOPS_ROOT_PATH.'/class/cache/xoopscache.php';
		$result = $GLOBALS['xoopsCache']->read('xortify_core_footer_end');
		if ((isset($result['time'])?(float)$result['time']:0)<=microtime(true)) {
			$GLOBALS['xoopsCache']->write('xortify_core_footer_end', array('time'=>microtime(true)+$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['fault_delay']), $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['fault_delay']);
			if (XortifyCorePreload::hasAPIUserPass()) { 		
				include_once XOOPS_ROOT_PATH . ( '/modules/xortify/include/post.footer.end.php' );
			}
			$GLOBALS['xoopsCache']->write('xortify_core_footer_end', array('time'=>microtime(true)), -1);
		}
		
	}
	
	static function eventCoreHeaderAddmeta($args)
	{
		
		/*
		if (isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['_pass'])) {
			if ($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['_pass'] == true) {
				include_once XOOPS_ROOT_PATH.'/modules/xortify/include/functions.php';
				addmeta_googleanalytics(_XOR_MI_XOOPS_GOOGLE_ANALYTICS_ACCOUNTID_USERPASSED, $_SERVER['HTTP_HOST']);
				if (defined('_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_USERPASSED')&&strlen(constant('_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_USERPASSED'))>=13) { 
					addmeta_googleanalytics(_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_USERPASSED, $_SERVER['HTTP_HOST']);
				}	
			}
		}*/
		
		include_once XOOPS_ROOT_PATH.'/class/cache/xoopscache.php';
		$result = $GLOBALS['xoopsCache']->read('xortify_core_header_add_meta');
		if ((isset($result['time'])?(float)$result['time']:0)<=microtime(true)) {
			$GLOBALS['xoopsCache']->write('xortify_core_header_add_meta', array('time'=>microtime(true)+$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['fault_delay']), $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['fault_delay']);
			if (XortifyCorePreload::hasAPIUserPass()) {	
				include_once XOOPS_ROOT_PATH . ( '/modules/xortify/include/post.header.addmeta.php' );
			}
			$GLOBALS['xoopsCache']->write('xortify_core_header_add_meta', array('time'=>microtime(true)), -1);
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