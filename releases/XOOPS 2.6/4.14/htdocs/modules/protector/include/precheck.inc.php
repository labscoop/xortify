<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * Protector
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         protector
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id: precheck.inc.php 8391 2011-12-02 22:32:34Z trabis $
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

require_once dirname(__FILE__) . '/precheck_functions.php';

/* 
 * if (class_exists('XoopsDatabase', false)) { 
 *    require dirname(__FILE__) . '/postcheck.inc.php'; 
 *    return; 
 * } 
 */

define('PROTECTOR_PRECHECK_INCLUDED', 1);
define('PROTECTOR_VERSION', file_get_contents(dirname(__FILE__) . '/version.txt'));

// set $_SERVER['REQUEST_URI'] for IIS
if (empty($_SERVER['REQUEST_URI'])) { // Not defined by IIS
    // Under some configs, IIS makes SCRIPT_NAME point to php.exe :-(
    if (!($_SERVER['REQUEST_URI'] = @$_SERVER['PHP_SELF'])) {
        $_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'];
    }
    if (isset($_SERVER['QUERY_STRING'])) {
        $_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
    }
}

protector_precheck();