<?php
/*
 * Prevents Spam, Harvesting, Human Rights Abuse, Captcha Abuse etc.
 * basic statistic of them in XOOPS Copyright (C) 2012 Simon Roberts 
 * Contact: wishcraft - simon@labs.coop
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
 * @Version:		4.11 Final (Stable)
 * @copyright:		Chronolabs Cooperative 2013 © Simon Roberts (www.simonaroberts.com)
 * @download:		http://sourceforge.net/projects/xortify
 * @file:			core.php		
 * @description:	Mode: Client - Preloader for handling bans.
 * @date:			26/07/2013 19:40 AEST
 * @license:		GNU3 (http://web.labs.coop/public/legal/general-public-licence/13,3.html)
 * @package			xortify
 * @subpackage		preloaders
 * @author			Simon A. Roberts - wishcraft (simon@labs.coop)

 * 
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');


/*
 * Class for Xortify in Client Mode!
 * Preloader of Client Security & Security Devices.
 */
class XortifyCorePreload extends XoopsPreloadItem
{

	/*
	 * Event for intializing Preloader and Xortify
	 */
	static function init() {
		$GLOBALS['xoops'] = Xoops::getInstance();
		$GLOBALS['xoops']->loadLanguage('modinfo', 'xortify');
		
		$module_handler = $GLOBALS['xoops']->getHandler('module');
		$config_handler = $GLOBALS['xoops']->getHandler('config');
		$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['module'] = $module_handler->getByDirname('xortify');
		$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig'] = $config_handler->getConfigList($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['module']->getVar('mid'));
		XoopsLoad::load('xoopscache');
	}

	/*
	 * Event for calling security sentry for preloaders in provider - precheck
	 * @param array $args		
	 */
	static function eventCoreIncludeAuthSuccess($args)
	{
		XortifyCorePreload::init();
		$result = XoopsCache::read('xortify_core_include_common_start');
		if ((isset($result['time'])?(float)$result['time']:0)<=microtime(true)) {
			XoopsCache::write('xortify_core_include_common_start', array('time'=>microtime(true)), $GLOBALS['xoops']->getModuleConfig('fault_delay', 'xortify'));
			include_once XOOPS_ROOT_PATH . ( '/modules/xortify/include/pre.loader.mainfile.php' );
			XoopsCache::write('xortify_core_include_common_start', array('time'=>microtime(true)), -1);
		}
	}

	/*
	 * Event for calling security sentry for preloaders in provider - postcheck
	 * @param array $args
	 */
	static function eventCoreIncludeCommonEnd($args)
	{
  		
		$result = XoopsCache::read('xortify_cleanup_last');
		if ((isset($result['when'])?(float)$result['when']:-microtime(true))+$GLOBALS['xoops']->getModuleConfig('xortify_ip_cache', 'xortify')<=microtime(true)) {
			$result = array();
			$result['when'] = microtime(true);
			$result['files'] = 0;
			$result['size'] = 0;
			foreach(XortifyCorePreload::getFileListAsArray(XOOPS_VAR_PATH.'/caches/xoops_cache/', 'xortify') as $id => $file) {
				if (file_exists(XOOPS_VAR_PATH.'/caches/xoops_data/'.$file)&&!empty($file)) {
					if (@filectime(XOOPS_VAR_PATH.'/caches/xoops_data/'.$file)<time()-$GLOBALS['xoops']->getModuleConfig('xortify_ip_cache', 'xortify')) {
						$result['files']++;
						$result['size'] = $result['size'] + filesize(XOOPS_VAR_PATH.'/caches/xoops_data/'.$file);
						@unlink(XOOPS_VAR_PATH.'/caches/xoops_data/'.$file);
					}
				}
			}
			$result['took'] = microtime(true)-$result['when'];
			XoopsCache::write('xortify_cleanup_last', $result, $GLOBALS['xoops']->getModuleConfig('xortify_ip_cache', 'xortify')*2);
		}
		if (isset($_SESSION['xortify']['lid']))
			if ($_SESSION['xortify']['lid']==0)
				unset($GLOBALS['xortify']);
	
		if (strpos($_SERVER["PHP_SELF"], '/banned.php')>0) {
			return false;
		}
		if ((isset($_COOKIE['xortify_lid'])&&$_COOKIE['xortify_lid']!=0)||(isset($_SESSION['xortify']['lid'])&&$_SESSION['xortify']['lid']!=0)&&!strpos($_SERVER["PHP_SELF"], '/banned.php')) {
			header('Location: '.XOOPS_URL.'/banned.php');
			exit;
		} 
		$result = XoopsCache::read('xortify_core_include_common_end');
	    if ((isset($result['time'])?(float)$result['time']:0)<=microtime(true)) {
	    	XoopsCache::write('xortify_core_include_common_end', array('time'=>microtime(true)+$GLOBALS['xoops']->getModuleConfig('fault_delay', 'xortify')), $GLOBALS['xoops']->getModuleConfig('fault_delay', 'xortify'));
			if (XortifyCorePreload::hasAPIUserPass()) {
				include_once XOOPS_ROOT_PATH . ( '/modules/xortify/include/post.loader.mainfile.php' );
			}
			XoopsCache::write('xortify_core_include_common_end', array('time'=>microtime(true)), -1);
		}
					
	}

	/*
	 * Event for calling security sentry for preloaders in provider - headerpostcheck
	 * @param array $args
	 */
	static function eventCoreHeaderCacheEnd($args)
	{
		$result = XoopsCache::read('xortify_core_header_cache_end');
		if ((isset($result['time'])?(float)$result['time']:0)<=microtime(true)) {
			XoopsCache::write('xortify_core_header_cache_end', array('time'=>microtime(true)+$GLOBALS['xoops']->getModuleConfig('fault_delay', 'xortify')), $GLOBALS['xoops']->getModuleConfig('fault_delay', 'xortify'));
			if (XortifyCorePreload::hasAPIUserPass()) { 		
				include_once XOOPS_ROOT_PATH . ( '/modules/xortify/include/post.header.endcache.php' );
			}
			XoopsCache::write('xortify_core_header_cache_end', array('time'=>microtime(true)), -1);
		}
	}
	
	/*
	 * Event for calling security sentry for preloaders in provider - headerpostcheck
	 * @param array $args
	 */
	static function eventCoreHeaderAddmeta($args)
	{
		if (isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['_pass'])) {
			if ($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['_pass'] == true) {
				include_once XOOPS_ROOT_PATH.'/modules/xortify/include/functions.php';
				addmeta_googleanalytics(_XOR_MI_XOOPS_GOOGLE_ANALYTICS_ACCOUNTID_USERPASSED, $_SERVER['HTTP_HOST']);
				if (defined('_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_USERPASSED')&&strlen(constant('_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_USERPASSED'))>=13) { 
					addmeta_googleanalytics(_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_USERPASSED, $_SERVER['HTTP_HOST']);
				}
			}
		}
		$result = XoopsCache::read('xortify_core_header_add_meta');
		if ((isset($result['time'])?(float)$result['time']:0)<=microtime(true)) {
			XoopsCache::write('xortify_core_header_add_meta', array('time'=>microtime(true)+$GLOBALS['xoops']->getModuleConfig('fault_delay', 'xortify')), $GLOBALS['xoops']->getModuleConfig('fault_delay', 'xortify'));
			if (XortifyCorePreload::hasAPIUserPass()) { 		
				include_once XOOPS_ROOT_PATH . ( '/modules/xortify/include/post.header.addmeta.php' );
			}
			XoopsCache::write('xortify_core_header_add_meta', array('time'=>microtime(true)), -1);
		}
	}

	/*
	 * Function for checking if API has Username and Password for xortify.com
	 * @return boolean
	 */
	static function hasAPIUserPass()
	{
		if ($GLOBALS['xoops']->getModuleConfig('xortify_username', 'xortify')!=''&&$GLOBALS['xoops']->getModuleConfig('xortify_password', 'xortify')!='')
			return true;
		else
			return false;
	}	
	

	/*
	 * Function for retrieveing file list from Path with prefix
	 * @return array
	 */
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