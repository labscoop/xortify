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
 * @subpackage		xml
 * @author         	Simon Roberts (wishcraft) - meshy@labs.coop
 */

$adminmenu = array();
$adminmenu[1]['title'] = _XXMLC_ADMINMENU_1;
$adminmenu[1]['link'] = "admin/index.php?op=tables";
$adminmenu[1]['icon'] = "images/dbtables.png";
$adminmenu[2]['title'] = _XXMLC_ADMINMENU_2;
$adminmenu[2]['link'] = "admin/index.php?op=fields";
$adminmenu[2]['icon'] = "images/dbfields.png";
$adminmenu[3]['title'] = _XXMLC_ADMINMENU_3;
$adminmenu[3]['link'] = "admin/index.php?op=views";
$adminmenu[3]['icon'] = "images/dbviews.png";
$adminmenu[4]['title'] = _XXMLC_ADMINMENU_4;
$adminmenu[4]['link'] = "admin/index.php?op=plugins";
$adminmenu[4]['icon'] = "images/plugins.png";
$adminmenu[5]['title'] = _XXMLC_ADMINMENU_5;
$adminmenu[5]['link'] = "admin/permissions.php";
$adminmenu[5]['icon'] = "images/permissions.png";
?>