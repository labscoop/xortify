<?php
/**
 * @package     xortify
 * @subpackage  module
 * @description	Sector Network Security Drone
 * @author	    Simon Roberts WISHCRAFT <simon@labs.coop>
 * @copyright	copyright (c) 2010-2013 XOOPS.org
 * @licence		docs/End User Licence.pdf
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');


class SpidersCorePreload extends XoopsPreloadItem
{
	function eventCoreIncludeCommonEnd($args)
	{
		if (SpidersCorePreload::isActive()) {
			include_once XOOPS_ROOT_PATH . ( '/modules/spiders/post.loader.spiders.php' );
		}
	}
	
	function eventCoreFooterEnd($args)
	{
		if (SpidersCorePreload::isActive()) {
			include_once XOOPS_ROOT_PATH . ( '/modules/spiders/post.loader.footer.php' );
		}
	}
	
	
	function isActive()
	{
		$module_handler =& xoops_getHandler('module');
		$module = $module_handler->getByDirname('spiders');
		return ($module && $module->getVar('isactive')) ? true : false;
	}
}

?>