<?php
/**
 * @package     xortify
 * @subpackage  module
 * @description	Sector Network Security Drone
 * @author	    Simon Roberts WISHCRAFT <simon@chronolabs.coop>
 * @copyright	copyright (c) 2010-2013 XOOPS.org
 * @licence		GPL 2.0 - see docs/LICENCE.txt
 */
echo __LINE__ . ' :: '. basename(dirname(__FILE__)). '/' . basename(__FILE__);
	$xoops = Xoops::getInstance();
	
	if (isset($GLOBALS['xoDoSoap']))
	{
		
		$module_handler = $xoops->getHandler('module');
		$config_handler = $xoops->getHandler('config');
		if (!isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module'])) $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module'] = $module_handler->getByDirname('xortify');
		if (!isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'])) $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'] = $config_handler->getConfigList($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module']->getVar('mid'));
		
		require_once( $xoops->path('/modules/xortify/class/'.$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['protocol'].'.php') );
		$func = strtoupper($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['protocol']).'XortifyExchange';
		$soapExchg = new $func;
		$result = $soapExchg->retrieveBans();
				
		if (is_array($result)) {
		
			XoopsLoad::load('xoopscache');
			if (!class_exists('XoopsCache')) {
				// XOOPS 2.4 Compliance
				XoopsLoad::load('cache');
				if (!class_exists('XoopsCache')) {
					include_once $xoops->root_path.'/class/cache/xoopscache.php';
				}
			}
					
			XoopsCache::delete('xortify_bans_cache');
			XoopsCache::delete('xortify_bans_cache_backup');			
			XoopsCache::write('xortify_bans_cache', $result, $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['xortify_seconds']);
			XoopsCache::write('xortify_bans_cache_backup', $result, ($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['xortify_seconds'] * 1.45));			
		}		
	}
	
?>