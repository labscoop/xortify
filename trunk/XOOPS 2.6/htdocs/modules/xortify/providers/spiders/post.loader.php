<?php
/**
 * @package     xortify
 * @subpackage  module
 * @description	Sector Network Security Drone
 * @author	    Simon Roberts WISHCRAFT <simon@chronolabs.coop>
 * @copyright	copyright (c) 2010-2013 XOOPS.org
 * @licence		GPL 2.0 - see docs/LICENCE.txt
 */
	$GLOBALS['xoops'] = Xoops::getInstance();	
	include_once($GLOBALS['xoops']->path("/include/common.php"));

	$module_handler = $GLOBALS['xoops']->getHandler('module');
	$config_handler = $GLOBALS['xoops']->getHandler('config');
	$GLOBALS['spidersModule'] = $module_handler->getByDirname('spiders');
	if (!isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module'])) $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module'] = $module_handler->getByDirname('xortify');
	if (is_object($GLOBALS['spidersModule'])) {
		$GLOBALS['spidersModuleConfig'] = $config_handler->getConfigList($GLOBALS['spidersModule']->mid());
	
		if (is_object($GLOBALS['xoops']->user)) {
			if (in_array($GLOBALS['spidersModuleConfig']['bot_group'], $GLOBALS['xoops']->user->getGroups())&&!empty($_POST)) {
				
				$GLOBALS['xoops']->loadLanguage('ban', 'spiders');
				
				$log_handler = $GLOBALS['xoops']->getModuleHandler('log', 'spiders');
				$log = $log_handler->create();
				$log->setVars(xortify_getIPData(false));
				$log->setVar('provider', basename(dirname(__FILE__)));
				$log->setVar('action', 'monitored');
				$log->setVar('extra', _XOR_BAN_SPIDER_TYPE.': '.print_r($_POST, true));
				$lid = $log_handler->insert($log, true);
				XoopsCache::write('xortify_core_include_common_end', array('time'=>microtime(true)), $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['fault_delay']);
				$GLOBALS['xortify']['lid'] = $lid;
				setcookie('xortify', array('lid' => $lid), time()+3600*24*7*4*3);
				header('Location: '.XOOPS_URL.'/banned.php');
				exit(0);
			}
			$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['_pass'] = true;
		}	
	}

?>