<?php
/**
 * Xortify Bans & Unbans Function
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Chronolabs Coopertive (Australia)  http://web.labs.coop
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         bans
 * @subpackage		ban
 * @author         	Simon Roberts (wishcraft) - meshy@labs.coop
 */

	include_once $GLOBALS['xoops']->path("/class/xoopsformloader.php");
	
	// Include Internal Form Objects
	include_once $GLOBALS['xoops']->path("/modules/ban/class/formselectcategory.php");
	include_once $GLOBALS['xoops']->path("/modules/ban/class/formselectmember.php");
	
	// Include Forms
	include_once $GLOBALS['xoops']->path("/modules/ban/include/members.forms.php");
	include_once $GLOBALS['xoops']->path("/modules/ban/include/categories.forms.php");
	$module_handler =& xoops_gethandler( 'module' );
	$config_handler =& xoops_gethandler( 'config' );
	$xoModule =& $module_handler->getByDirname('ban');
	$GLOBALS['xoopsModuleConfig'] = $config_handler->getConfigList( $xoModule->getVar( 'mid' ) );
	
?>