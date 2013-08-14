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
 * @subpackage		cURL (JSON)
 * @author         	Simon Roberts (wishcraft) - meshy@labs.coop
 */

include dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'mainfile.php';
include dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'include/cp_header.php';
include '../include/functions.php';

include_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
include_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
	
if (is_object($xoopsUser)) {
    $xoopsModule = XoopsModule::getByDirname("xcurl");
    if (!$xoopsUser->isAdmin($xoopsModule->mid())) {
        redirect_header(XOOPS_URL . "/", 3, _NOPERM);
        exit();
    } 
} else {
    redirect_header(XOOPS_URL . "/", 1, _NOPERM);
    exit();
}
$myts = &MyTextSanitizer::getInstance();
?>