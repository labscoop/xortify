<?php
/**
 * Xortify API Function
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
 * @package         api
 * @subpackage		REST
 * @author         	Simon Roberts (wishcraft) - meshy@labs.coop
 */

require(dirname(dirname(dirname(dirname(__FILE__)))). 'include' . DIRECTORY_SEPARATOR . 'cp_header.php');

error_reporting(E_ERROR);

if (!defined('_CHARSET'))
	define ("_CHARSET","UTF-8");
if (!defined('_CHARSET_ISO'))
	define ("_CHARSET_ISO","ISO-8859-1");
	
$GLOBALS['myts'] = MyTextSanitizer::getInstance();

$module_handler = xoops_gethandler('module');
$config_handler = xoops_gethandler('config');
$GLOBALS['xrestModule'] = $module_handler->getByDirname('xrest');
$GLOBALS['xrestModuleConfig'] = $config_handler->getConfigList($GLOBALS['xrestModule']->getVar('mid')); 
	
xoops_load('pagenav');	
xoops_load('xoopslists');
xoops_load('xoopsformloader');

include_once $GLOBALS['xoops']->path('class'.DS.'xoopsmailer.php');
include_once $GLOBALS['xoops']->path('class'.DS.'xoopstree.php');

if ( file_exists($GLOBALS['xoops']->path('/Frameworks/moduleclasses/moduleadmin/moduleadmin.php'))){
        include_once $GLOBALS['xoops']->path('/Frameworks/moduleclasses/moduleadmin/moduleadmin.php');
        //return true;
    }else{
        echo xoops_error("Error: You don't use the Frameworks \"admin module\". Please install this Frameworks");
        //return false;
    }
$GLOBALS['xrestImageIcon'] = XOOPS_URL .'/'. $GLOBALS['xrestModule']->getInfo('icons16');
$GLOBALS['xrestImageAdmin'] = XOOPS_URL .'/'. $GLOBALS['xrestModule']->getInfo('icons32');

if ($GLOBALS['xoopsUser']) {
    $moduleperm_handler =& xoops_gethandler('groupperm');
    if (!$moduleperm_handler->checkRight('module_admin', $GLOBALS['xrestModule']->getVar( 'mid' ), $GLOBALS['xoopsUser']->getGroups())) {
        redirect_header(XOOPS_URL, 1, _NOPERM);
        exit();
    }
} else {
    redirect_header(XOOPS_URL . "/user.php", 1, _NOPERM);
    exit();
}

if (!isset($GLOBALS['xoopsTpl']) || !is_object($GLOBALS['xoopsTpl'])) {
	include_once(XOOPS_ROOT_PATH."/class/template.php");
	$GLOBALS['xoopsTpl'] = new XoopsTpl();
}

$GLOBALS['xoopsTpl']->assign('pathImageIcon', $GLOBALS['xrestImageIcon']);

include_once $GLOBALS['xoops']->path('/modules/xrest/include/common.php');
include_once $GLOBALS['xoops']->path('/modules/xrest/include/functions.php');
include_once $GLOBALS['xoops']->path('/modules/xrest/include/forms.xrest.php');

xoops_load('pagenav');	
xoops_load('xoopsmultimailer');

?>