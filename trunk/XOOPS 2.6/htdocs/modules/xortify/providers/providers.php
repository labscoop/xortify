<?php
/**
 * @package     xortify
 * @subpackage  module
 * @description	Sector Network Security Drone
 * @author	    Simon Roberts WISHCRAFT <simon@chronolabs.coop>
 * @copyright	copyright (c) 2010-2013 XOOPS.org
 * @licence		GPL 2.0 - see docs/LICENCE.txt
 */

if (!defined('XOOPS_ROOT_PATH')) die ('Restricted Access');

$GLOBALS['xoops'] = Xoops::getInstance();
$GLOBALS['xoops']->loadLocale();

class Providers 
{
	var $providers = array();

	function init($check) {
		$xoops = Xoops::getInstance();
		$module_handler = $xoops->getHandler('module');
		$config_handler = $xoops->getHandler('config');
		$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module'] = $module_handler->getByDirname('xortify');
		if (is_object($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module'])) {
			$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'] = $config_handler->getConfigList($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module']->getVar('mid'));
		} else {
			$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['fault_delay'] = 600;
		}	 		
	}
		
	function Providers($check = 'precheck')
	{
		$this->init($check);
		if (isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['xortify_providers']))
			$this->providers = $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['xortify_providers'];
		else
			$this->providers = array();
		$this->$check();
	}
	
	private function precheck()
	{
		if (!is_object($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module']))
			return false;
		if ($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module']->getVar('version')<302)
			return false;
		ob_start();
		foreach($this->providers as $id => $key)
		switch ($key) {
		default:
			
			if (file_exists(XOOPS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/precheck.inc.php')) 
				include_once(XOOPS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/precheck.inc.php');
			if (file_exists(XOOPS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/pre.loader.php')) 
				include_once(XOOPS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/pre.loader.php');
			
		}
		ob_end_clean();
	}
	
	private function postcheck()
	{
		if (!is_object($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module']))
			return false;
		if ($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module']->getVar('version')<302)
			return false;
		ob_start();
		foreach($this->providers as $id => $key)
		switch ($key) {
		default:
			
			if (file_exists(XOOPS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/postcheck.inc.php'))
				include_once(XOOPS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/postcheck.inc.php');
			if (file_exists(XOOPS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/post.loader.php'))
				include_once(XOOPS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/post.loader.php');
		}
		ob_end_clean();
	}

	private function headerpostcheck()
	{
		if (!is_object($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module']))
			return false;
		if ($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module']->getVar('version')<302)
			return false;
		ob_start();
		foreach($this->providers as $id => $key)
		switch ($key) {
		default:
			
			if (file_exists(XOOPS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/headerpostcheck.inc.php'))
				include_once(XOOPS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/headerpostcheck.inc.php');
			if (file_exists(XOOPS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/header.post.loader.php'))
				include_once(XOOPS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/header.post.loader.php');
			
		}
		ob_end_clean();
	}
	
	private function footerpostcheck()
	{
		if ($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module']->getVar('version')<302)
			return false;
		ob_start();
		foreach($this->providers as $id => $key)
		switch ($key) {
		default:
			
			if (file_exists(XOOPS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/footerpostcheck.inc.php'))
				include_once(XOOPS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/footerpostcheck.inc.php');
			if (file_exists(XOOPS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/footer.post.loader.php'))
				include_once(XOOPS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/footer.post.loader.php');
			
		}
		ob_end_clean();
	}
}

?>