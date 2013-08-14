<?php
/**
 * Extended User Profile
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Chronolabs Coopertive (Australia)  http://web.labs.coop
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         profile
 * @since           2.4.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id: core.php 3333 2009-08-27 10:46:15Z trabis $
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Profile core preloads
 *
 * @copyright       Chronolabs Coopertive (Australia)  http://web.labs.coop
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author          trabis <lusopoemas@gmail.com>
 */
class ProfileCorePreload extends XoopsPreloadItem
{
	
	function eventCoreHeaderStart($args)
    {
    	header('X-XRDS-Location: '.XOOPS_URL . '/modules/profile/services/');
    }
    
	function eventCoreFooterStart($args)
    {
    	if (isset($_SESSION['profileDoSpeedtest'])) {
    		if (strpos($_SERVER['PHP_SELF'], 'speedtest/results.php')>0) {
    			unset($_SESSION['profileDoSpeedtest']);
    			redirect_header(XOOPS_URL.'/modules/profile/register.php', 10);
    			exit;
    		}
    	}
    	
    }
    
	function eventCoreUserStart($args)
    {
        $op = 'main';
        if (isset($_POST['op'])) {
            $op = trim($_POST['op']);
        } else if (isset($_GET['op'])) {
            $op = trim($_GET['op']);
        }
        if ($op != 'login' && (empty($_GET['from']) || 'profile' != $_GET['from'])) {
            header("location: ./modules/profile/user.php" . (empty($_SERVER['QUERY_STRING']) ? "" : "?" . $_SERVER['QUERY_STRING']));
            exit();
        }
    }

    function eventCoreEdituserStart($args)
    {
        header("location: ./modules/profile/edituser.php" . (empty($_SERVER['QUERY_STRING']) ? "" : "?" . $_SERVER['QUERY_STRING']));
        exit();
    }

    function eventCoreLostpassStart($args)
    {
        $email = isset($_GET['email']) ? trim($_GET['email']) : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : $email;
        header("location: ./modules/profile/lostpass.php?email={$email}" . (empty($_GET['code']) ? "" : "&" . $_GET['code']));
        exit();
    }

    function eventCoreRegisterStart($args)
    {
        header("location: ./modules/profile/register.php" . (empty($_SERVER['QUERY_STRING']) ? "" : "?" . $_SERVER['QUERY_STRING']) );
        exit();
    }

    function eventCoreUserinfoStart($args)
    {
        header("location: ./modules/profile/userinfo.php" . (empty($_SERVER['QUERY_STRING']) ? "" : "?" . $_SERVER['QUERY_STRING']) );
        exit();
    }

}
?>