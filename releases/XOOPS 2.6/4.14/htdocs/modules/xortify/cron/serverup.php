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
 * @file:			serverup.php		
 * @description:	Tests to see if a service is available, otherwise disables module.
 * @date:			26/07/2013 19:40 AEST
 * @license:		GNU3 (http://web.labs.coop/public/legal/general-public-licence/13,3.html)
 * @package			xortify
 * @subpackage		xortify
 * @author			Simon A. Roberts - wishcraft (simon@labs.coop)

 * 
 */



	include_once dirname(dirname(dirname(dirname(__FILE__)))).'/mainfile.php';
	include_once dirname(dirname(__FILE__)).'/include/functions.php';

	/*
	 * Retrieve URL Source for Data and analysis
	 */
	function xortify_getURLData($URI, $curl=false) {
		$ret = '';
		try {
			switch ($curl) {
				case true:
					if (!$ch = curl_init($URI)) {
						trigger_error('Could not intialise CURL file: '.$url);
						return false;
					}
					$cookies = XOOPS_VAR_PATH.'/cache/xoops_cache/croncurl_'.md5($URI).'.cookie'; 
			
					curl_setopt($ch, CURLOPT_HEADER, 0);
					curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies); 
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $GLOBALS['xoops']->getModuleConfig('curl_connecttimeout', 'xortify'));
					curl_setopt($ch, CURLOPT_TIMEOUT, $GLOBALS['xoops']->getModuleConfig('curl_timeout', 'xortify'));
					curl_setopt($ch, CURLOPT_USERAGENT, XORTIFY_USER_AGENT);
					$ret = curl_exec($ch);
					curl_close($ch);
					break;
				case false:
					$ret = file_get_contents($uri);
					break;
				
			}
		}
		catch(Exception $e) {
			echo 'Exception: "'.$e."\n";
		}	
		return $ret;
	}
	
	if (!isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['_preloader']))
		echo 'Cloud Service Availablity Cron Started: '.date('Y/m/d H:i:s').(isset($_SERVER['HTTP_HOST'])?'<br/>':"\n");
	
	if (!isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['_preloader'])) {
		$xoopsOption["nocommon"]=true;
		require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/mainfile.php');
		defined('XOOPS_MAINFILE_INCLUDED') or die('Restricted access');
		
		if (version_compare(PHP_VERSION, '5.3.0', '<')) {
		    set_magic_quotes_runtime(0);
		}
		date_default_timezone_set('Europe/London');
		
		global $xoops;
		$GLOBALS['xoops'] = $xoops;
		
		/**
		 * YOU SHOULD NEVER USE THE FOLLOWING TO CONSTANTS, THEY WILL BE REMOVED
		 */
		defined('DS') or define('DS', DIRECTORY_SEPARATOR);
		defined('NWLINE')or define('NWLINE', "\n");
		
		/**
		 * Include files with definitions
		 */
		include_once XOOPS_ROOT_PATH . DS . 'include' . DS . 'defines.php';
		include_once XOOPS_ROOT_PATH . DS . 'include' . DS . 'version.php';
	}
	
	define('XORTIFY_USER_AGENT', 'Mozilla/5.0 (PHP - '.PHP_VERSION.') '.(defined('XOOPS_VERSION')?constant('XOOPS_VERSION').' ':'').'- Xortify 4.02');
	define("SERVER1", 'http://xortify.com/unban/?op=unban');
	define("SERVER2", 'http://xortify.xoops.org/unban/?op=unban');
	define("SERVER3", 'http://xortify.chronolabs.coop/unban/?op=unban');
	define("REPLACE", 'unban/?op=unban');
	define("SEARCHFOR", 'Solve Puzzel:');
	define("SOAP", 'soap/');
	define("CURL", 'curl/');
	define("JSON", 'json/');
	define("SERIAL", 'serial/');
	define("XML", 'xml/');
	define("REST", 'api/');
	
	include_once $xoops->path('class/cache/xoopscache.php');
	
	$module_handler = $GLOBALS['xoops']->getHandler('module');
	$config_handler = $GLOBALS['xoops']->getHandler('config');
	$configitem_handler = $GLOBALS['xoops']->getHandler('configitem');
	if (!isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['module']))
		$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['module'] = $module_handler->getByDirname('xortify');
	
	if (!defined('XORTIFY_USER_AGENT'))
		define('XORTIFY_USER_AGENT', 'Mozilla/5.0 ('.XOOPS_VERSION.'; PHP '.PHP_VERSION.') Xortify ' . ($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['module']->getVar('version')/100));
	
	$serverup=false;
	if (isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['module'])) {
		if (!isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig']))
			$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig'] = $config_handler->getConfigList($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['module']->getVar('mid'));
		if ($servers = XoopsCache::read('server_list_xortify')) {
			foreach($servers as $sid => $server) {
				$source = xortify_getURLData($server['server'], $nativecurl);
				if (strpos(strtolower($source), strtolower($server['search']))>0) {
					$serverup=true;
					echo 'Server '.$sid.' is UP - check @ '.$server['server'];
					$criteria = new CriteriaCompo(new Criteria('conf_modid', $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['module']->getVar('mid')));
					$criteria->add(new Criteria('conf_name', 'xortify_urisoap'));
					$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig'] = $configitem_handler->getObjects($criteria);
					if (isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig'][0])) {
						$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig'][0]->setVar('conf_value', str_replace($server['replace'], SOAP, $server['server']));
						$configitem_handler->insert($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig'][0], true);
					}
					$criteria = new CriteriaCompo(new Criteria('conf_modid', $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['module']->getVar('mid')));
					$criteria->add(new Criteria('conf_name', 'xortify_uricurl'));
					$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig'] = $configitem_handler->getObjects($criteria);
					if (isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig'][0])) {
						$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig'][0]->setVar('conf_value', str_replace($server['replace'], CURL, $server['server']));
						$configitem_handler->insert($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig'][0], true);
					}
					$criteria = new CriteriaCompo(new Criteria('conf_modid', $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['module']->getVar('mid')));
					$criteria->add(new Criteria('conf_name', 'xortify_urijson'));
					$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig'] = $configitem_handler->getObjects($criteria);
					if (isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig'][0])) {
						$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig'][0]->setVar('conf_value', str_replace($server['replace'], JSON, $server['server']));
						$configitem_handler->insert($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig'][0], true);
					}
					$criteria = new CriteriaCompo(new Criteria('conf_modid', $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['module']->getVar('mid')));
					$criteria->add(new Criteria('conf_name', 'xortify_uriserial'));
					$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig'] = $configitem_handler->getObjects($criteria);
					if (isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig'][0])) {
						$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig'][0]->setVar('conf_value', str_replace($server['replace'], SERIAL, $server['server']));
						$configitem_handler->insert($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig'][0], true);
					}
					$criteria = new CriteriaCompo(new Criteria('conf_modid', $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['module']->getVar('mid')));
					$criteria->add(new Criteria('conf_name', 'xortify_urixml'));
					$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig'] = $configitem_handler->getObjects($criteria);
					if (isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig'][0])) {
						$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig'][0]->setVar('conf_value', str_replace($server['replace'], XML, $server['server']));
						$configitem_handler->insert($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig'][0], true);
					}
					$criteria = new CriteriaCompo(new Criteria('conf_modid', $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['module']->getVar('mid')));
					$criteria->add(new Criteria('conf_name', 'xortify_urirest'));
					$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig'] = $configitem_handler->getObjects($criteria);
					if (isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig'][0])) {
						$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig'][0]->setVar('conf_value', str_replace($server['replace'], REST, $server['server']));
						$configitem_handler->insert($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig'][0], true);
					}				
				}
			}
		}
		$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['module']->setVar('isactive', $serverup);
		$module_handler->insert($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['module'], true);
	}
	
	unlinkOldCachefiles('xortify_',$GLOBALS['xoops']->getModuleConfig('xortify_ip_cache', 'xortify'));
	
?>