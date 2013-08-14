<?php
/**
 * @package     Xortify
 * @subpackage  module
 * @description	Sector Network Security Drone for Robots
 * @author	    Simon Roberts WISHCRAFT <simon@chronolabs.coop>
 * @copyright	copyright (c) 2010-2013 XOOPS.org
 * @licence		GPL 2.0 - see docs/LICENCE.txt
 * @cron		Run at Least Once an Hour to five minutes!
 */

	include_once dirname(dirname(dirname(dirname(__FILE__)))).'/mainfile.php';
	include_once dirname(dirname(__FILE__)).'/include/functions.php';

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
					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['curl_connecttimeout']);
					curl_setopt($ch, CURLOPT_TIMEOUT, $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['curl_timeout']);
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
	
	if (!isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['_preloader']))
		echo 'Cloud Service Availablity Cron Started: '.date('Y/m/d H:i:s').(isset($_SERVER['HTTP_HOST'])?'<br/>':"\n");
	
	if (!isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['_preloader'])) {
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
	define("REST", 'rest/');
	
	include_once $xoops->path('class/cache/xoopscache.php');
	
	$module_handler = $GLOBALS['xoops']->getHandler('module');
	$config_handler = $GLOBALS['xoops']->getHandler('config');
	$configitem_handler = $GLOBALS['xoops']->getHandler('configitem');
	if (!is_object($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module']))
		$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module'] = $module_handler->getByDirname('xortify');
	
	if (!defined('XORTIFY_USER_AGENT'))
		define('XORTIFY_USER_AGENT', 'Mozilla/5.0 ('.XOOPS_VERSION.'; PHP '.PHP_VERSION.') Xortify ' . ($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module']->getVar('version')/100));
	
	$serverup=false;
	if (isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module'])) {
		if (!isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']))
			$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'] = $config_handler->getConfigList($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module']->getVar('mid'));
		if ($servers = XoopsCache::read('server_list_xortify')) {
			foreach($servers as $sid => $server) {
				$source = xortify_getURLData($server['server'], $nativecurl);
				if (strpos(strtolower($source), strtolower($server['search']))>0) {
					$serverup=true;
					echo 'Server '.$sid.' is UP - check @ '.$server['server'];
					$criteria = new CriteriaCompo(new Criteria('conf_modid', $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module']->getVar('mid')));
					$criteria->add(new Criteria('conf_name', 'xortify_urisoap'));
					$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'] = $configitem_handler->getObjects($criteria);
					if (isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'][0])) {
						$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'][0]->setVar('conf_value', str_replace($server['replace'], SOAP, $server['server']));
						$configitem_handler->insert($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'][0], true);
					}
					$criteria = new CriteriaCompo(new Criteria('conf_modid', $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module']->getVar('mid')));
					$criteria->add(new Criteria('conf_name', 'xortify_uricurl'));
					$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'] = $configitem_handler->getObjects($criteria);
					if (isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'][0])) {
						$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'][0]->setVar('conf_value', str_replace($server['replace'], CURL, $server['server']));
						$configitem_handler->insert($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'][0], true);
					}
					$criteria = new CriteriaCompo(new Criteria('conf_modid', $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module']->getVar('mid')));
					$criteria->add(new Criteria('conf_name', 'xortify_urijson'));
					$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'] = $configitem_handler->getObjects($criteria);
					if (isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'][0])) {
						$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'][0]->setVar('conf_value', str_replace($server['replace'], JSON, $server['server']));
						$configitem_handler->insert($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'][0], true);
					}
					$criteria = new CriteriaCompo(new Criteria('conf_modid', $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module']->getVar('mid')));
					$criteria->add(new Criteria('conf_name', 'xortify_uriserial'));
					$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'] = $configitem_handler->getObjects($criteria);
					if (isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'][0])) {
						$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'][0]->setVar('conf_value', str_replace($server['replace'], SERIAL, $server['server']));
						$configitem_handler->insert($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'][0], true);
					}
					$criteria = new CriteriaCompo(new Criteria('conf_modid', $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module']->getVar('mid')));
					$criteria->add(new Criteria('conf_name', 'xortify_urixml'));
					$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'] = $configitem_handler->getObjects($criteria);
					if (isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'][0])) {
						$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'][0]->setVar('conf_value', str_replace($server['replace'], XML, $server['server']));
						$configitem_handler->insert($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'][0], true);
					}
					$criteria = new CriteriaCompo(new Criteria('conf_modid', $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module']->getVar('mid')));
					$criteria->add(new Criteria('conf_name', 'xortify_urirest'));
					$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'] = $configitem_handler->getObjects($criteria);
					if (isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'][0])) {
						$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'][0]->setVar('conf_value', str_replace($server['replace'], REST, $server['server']));
						$configitem_handler->insert($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'][0], true);
					}				
				}
			}
		}
		$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module']->setVar('isactive', $serverup);
		$module_handler->insert($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module'], true);
	}
	
	unlinkOldCachefiles('xortify_',$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['xortify_ip_cache']);
	
?>