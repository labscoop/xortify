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

$xoopsOption['pagetype'] = 'user';
include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'header.php';
include_once $GLOBALS['xoops']->path('class/xoopsformloader.php');

// If not a user, redirect
if ( !is_object($GLOBALS['xoopsUser'])  ) {
    redirect_header(XOOPS_URL, 3, _US_NOEDITRIGHT);
    exit();
}

$myts =& MyTextSanitizer::getInstance();
$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'list';
$xoBreadcrumbs[] = array('title' => _US_EDITAPPLET);

if ($op == 'list') {
	$xoopsOption['template_main'] = 'profile_applet.html';
	$applets_handler = xoops_getmodulehandler('oauth_applet', 'profile');
	include_once $GLOBALS['xoops']->path('header.php');
	$GLOBALS['xoopsTpl']->assign('applets', $applets_handler->getApplets($GLOBALS['xoopsUser']->getVar('uid')));
}


if ($op == 'save') {
    if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header(XOOPS_URL . "/modules/" . $GLOBALS['xoopsModule']->getVar('dirname', 'n') . "/", 3, _US_NOEDITRIGHT . "<br />" . implode('<br />', $GLOBALS['xoopsSecurity']->getErrors() ));
        exit();
    }

    $applets_handler = xoops_getmodulehandler('oauth_applet', 'profile');
    if (intval($_POST['appid'])>0) {
    	$applet = $applets_handler->get($_POST['appid']);
		if ($applet->getVar('profile_id')!=$GLOBALS['xoopsUser']->getVar('uid')) {
			redirect_header(XOOPS_URL . "/modules/" . $GLOBALS['xoopsModule']->getVar('dirname', 'n') . "/", 3, _US_NOEDITRIGHT . "<br />" . implode('<br />', $GLOBALS['xoopsSecurity']->getErrors() ));
			exit();
		}
	} else
    	$applet = $applets_handler->create();

    foreach($_POST as $key => $value) {
    	$applet->setVar($key, $value);
    	
    }
    include_once $GLOBALS['xoops']->path('class/uploader.php');
    $uploader = new XoopsMediaUploader( XOOPS_UPLOAD_PATH . '/avatars', array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png'), $GLOBALS['xoopsConfigUser']['avatar_maxsize'], $GLOBALS['xoopsConfigUser']['avatar_width'], $GLOBALS['xoopsConfigUser']['avatar_height']);
    if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
    	$uploader->setPrefix('applet');
    	if ($uploader->upload()) {
			$applet->setVar('image', '/avatars/' . $uploader->getSavedFileName() );
    	}
    }
    if ($applet->isNew()) {
    	$clients_handler = xoops_getmodulehandler('oauth_clients', 'profile');
    	$client = $clients_handler->create();
    	$client->setVar('client_id', $_POST['client_id']);
    	$client->setVar('client_secret', $_POST['client_secret']);
    	$clients_handler->insert($client);
    }
    $appid = $applets_handler->insert($applet);
    
    redirect_header(XOOPS_URL . "/modules/" . $GLOBALS['xoopsModule']->getVar('dirname', 'n') . "/applet.php?op=list",3,_PROFILE_MA_SAVEOK);
    exit;
}


if ($op == 'editapplet'||$op == 'edit') {

	$applets_handler = xoops_getmodulehandler('oauth_applet', 'profile');
	if (intval($_REQUEST['appid'])>0) {
		$applet = $applets_handler->get($_REQUEST['appid']);
		if ($applet->getVar('profile_id')!=$GLOBALS['xoopsUser']->getVar('uid')) {
			redirect_header(XOOPS_URL . "/modules/" . $GLOBALS['xoopsModule']->getVar('dirname', 'n') . "/", 3, _US_NOEDITRIGHT . "<br />" . implode('<br />', $GLOBALS['xoopsSecurity']->getErrors() ));
			exit();
		}
	} else
		$applet = $applets_handler->create();
	
	
	$xoopsOption['template_main'] = 'profile_editapplet.html';
    include_once $GLOBALS['xoops']->path('header.php');
    include_once dirname(__FILE__) . '/include/forms.php';
    $form = profile_getAppletForm($applet, false);
    $form->assign($GLOBALS['xoopsTpl']);
    $xoBreadcrumbs[] = array('title' => _US_EDITAPPLET);
}


if ($op == 'delete') {

	$applets_handler = xoops_getmodulehandler('oauth_applet', 'profile');
	if (intval($_REQUEST['appid'])>0) {
		$applet = $applets_handler->get($_REQUEST['appid']);
		if ($applet->getVar('profile_id')!=$GLOBALS['xoopsUser']->getVar('uid')) {
			redirect_header(XOOPS_URL . "/modules/" . $GLOBALS['xoopsModule']->getVar('dirname', 'n') . "/", 3, _US_NOEDITRIGHT . "<br />" . implode('<br />', $GLOBALS['xoopsSecurity']->getErrors() ));
			exit();
		}
	} 
	
	$criteria = new Criteria('client_id', $applet->getVar('client_id'));
	$oauth_clients_handler = xoops_getmodulehandler('oauth_clients', 'profile');
	$oauth_authorization_codes_handler = xoops_getmodulehandler('oauth_authorization_codes', 'profile');
	$oauth_access_tokens_handler = xoops_getmodulehandler('oauth_access_tokens', 'profile');
	$oauth_refresh_tokens_handler = xoops_getmodulehandler('oauth_refresh_tokens', 'profile');
	
	$applets_handler->deleteAll($criteria);
	$oauth_clients_handler->deleteAll($criteria);
	$oauth_authorization_codes_handler->deleteAll($criteria);
	$oauth_access_tokens_handler->deleteAll($criteria);
	$oauth_refresh_tokens_handler->deleteAll($criteria);
	
    redirect_header(XOOPS_URL . "/modules/" . $GLOBALS['xoopsModule']->getVar('dirname', 'n') . "/applet.php?op=list",3,_PROFILE_MA_DELETEOK);
    exit;
}

if ($op == 'create') {

	$applets_handler = xoops_getmodulehandler('oauth_applet', 'profile');
	$applet = $applets_handler->create();

	$xoopsOption['template_main'] = 'profile_editapplet.html';
	include_once $GLOBALS['xoops']->path('header.php');
	include_once dirname(__FILE__) . '/include/forms.php';
	$form = profile_getAppletForm($applet, false);
	$form->assign($GLOBALS['xoopsTpl']);
	
}

include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'footer.php';
?>