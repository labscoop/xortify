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
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         profile
 * @since           2.3.0
 * @author          Jan Pedersen
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id: edituser.php 8066 2011-11-06 05:09:33Z beckmi $
 */

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'autoloader.php';
if ($GLOBALS['OAuth2_Server']->validateAuthorizeRequest(OAuth2_Request::createFromGlobals())==true) {
	if (!isset($GLOBALS['xoops_user'])) {
		$_SESSION['oauth2'] = time();
		redirect_header(XOOPS_URL.'/modules/profile/user.php?xoops_redirect='.urlencode(XOOPS_URL.'/modules/profile/oauth/authorise/?'.http_query_build($_REQUEST)));
	}
	$GLOBALS['OAuth2_Server']->handleAuthorizeRequest(OAuth2_Request::createFromGlobals(), true, $GLOBALS['xoopsUser']->getVar('uid'))->send();
}
exit;
?>