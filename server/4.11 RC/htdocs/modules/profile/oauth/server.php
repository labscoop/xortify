<?php
$xoopsOption['pagetype'] = "user";
require_once(dirname(dirname(dirname(__FILE__))).'/mainfile.php');

require_once $GLOBALS['xoops']->path('/modules/profile/include/functions.php');
require_once $GLOBALS['xoops']->path('/modules/profile/include/forms.php');

$_SESSION['oAuthFunction'] = basename(dirname(__FILE__));

$oauth_handler = xoops_getmodulehandler('oauth', 'profile');
$oauth_handler->initialiseServer();
	
switch($GLOBALS['op'])
{
case 'request_token':
	$oauth_handler->server->requestToken();
	exit;

case 'access_token':
	$oauth_handler->server->accessToken();
	exit;

case 'authorize':
	$authorised = false;
	if (!is_object($GLOBALS['xoopsUser'])) {
		if (!isset($_POST['op'])) {
			$xoopsOption['template_main'] = 'profile_oauth_authorize_login.html';
			include($GLOBALS['xoops']->path('/header.php'));
			$GLOBALS['xoopsTpl']->assign('form', profile_oauth_login($_SERVER['REQUEST_URI']));
			include($GLOBALS['xoops']->path('/footer.php'));
			exit(0);
		} elseif ($_POST['op']='login') {

			$uname = !isset($_POST['uname']) ? '' : trim($_POST['uname']);
			$pass = !isset($_POST['pass']) ? '' : trim($_POST['pass']);
			if ($uname == '' || $pass == '') {
				redirect_header($_POST['url'], 1, _US_INCORRECTLOGIN);
				exit();
			}
			
			$member_handler =& xoops_gethandler('member');
			$myts =& MyTextsanitizer::getInstance();
			
			include_once $GLOBALS['xoops']->path('class/auth/authfactory.php');
			
			xoops_loadLanguage('auth');
			
			$xoopsAuth =& XoopsAuthFactory::getAuthConnection($myts->addSlashes($uname));
			$user = $xoopsAuth->authenticate($myts->addSlashes($uname), $myts->addSlashes($pass));
			
			if (false != $user) {
				if (0 == $user->getVar('level')) {
					redirect_header($_POST['url'], 5, _US_NOACTTPADM);
					exit();
				}
				if ($xoopsConfig['closesite'] == 1) {
					$allowed = false;
					foreach ($user->getGroups() as $group) {
						if (in_array($group, $xoopsConfig['closesite_okgrp']) || XOOPS_GROUP_ADMIN == $group) {
							$allowed = true;
							break;
						}
					}
					if (!$allowed) {
						redirect_header($_POST['url'], 1, _NOPERM);
						exit();
					}
				}
				$user->setVar('last_login', time());
				if (!$member_handler->insertUser($user)) {
				}
				// Regenrate a new session id and destroy old session
				$GLOBALS["sess_handler"]->regenerate_id(true);
				$_SESSION = array();
				$_SESSION['xoopsUserId'] = $user->getVar('uid');
				$_SESSION['xoopsUserGroups'] = $user->getGroups();
				$user_theme = $user->getVar('theme');
				if (in_array($user_theme, $xoopsConfig['theme_set_allowed'])) {
					$_SESSION['xoopsUserTheme'] = $user_theme;
				}
			
				// Set cookie for rememberme
				if (!empty($xoopsConfig['usercookie'])) {
					if (!empty($_POST["rememberme"])) {
						setcookie($xoopsConfig['usercookie'], $_SESSION['xoopsUserId'] . '-' . md5($user->getVar('pass') . XOOPS_DB_NAME . XOOPS_DB_PASS . XOOPS_DB_PREFIX), time() + 31536000, '/', XOOPS_COOKIE_DOMAIN, 0);
					} else {
						setcookie($xoopsConfig['usercookie'], 0, -1, '/', XOOPS_COOKIE_DOMAIN, 0);
					}
				}
			
				// RMV-NOTIFY
				// Perform some maintenance of notification records
				$notification_handler =& xoops_gethandler('notification');
				$notification_handler->doLoginMaintenance($user->getVar('uid'));
			
			} else if (empty($_POST['xoops_redirect'])) {
				redirect_header($_POST['url'], 5, $xoopsAuth->getHtmlErrors());
			} else {
				redirect_header($_POST['url'], 5, $xoopsAuth->getHtmlErrors(), false);
			}
			$authorised = isset($_POST['approve']);
		}
	} else {
		if (!isset($_POST['op'])) {
			$xoopsOption['template_main'] = 'profile_oauth_authorize_approve.html';
			include($GLOBALS['xoops']->path('/header.php'));
			$GLOBALS['xoopsTpl']->assign('form', profile_oauth_approve($_SERVER['REQUEST_URI']));
			include($GLOBALS['xoops']->path('/footer.php'));
			exit(0);
		} elseif ($_POST['op']=='login') {
			$authorised = isset($_POST['approve']);
		}
	}
	try
	{
		$oauth_handler->server->authorizeVerify();
		$oauth_handler->server->authorizeFinish($authorised, $_SESSION['xoopsUserId']);
	}
	catch (OAuthException2 $e)
	{
		header('HTTP/1.1 400 Bad Request');
		header('Content-Type: text/plain');
		
		echo "Failed OAuth Request: " . $e->getMessage();
	}
	exit;
default:
	header('HTTP/1.1 500 Internal Server Error');
	header('Content-Type: text/plain');
	echo "Unknown request";
}