<?php

	
	include dirname(__FILE__).'/mainfile.php';
	
	$GLOBALS['xoops'] = Xoops::getInstance();
	
	if (isset($_SESSION['xortify']['lid'])) {
		$lid = $_SESSION['xortify']['lid'];
		setcookie('xortify', array('lid' => $lid), time()+3600*24*7*4*3);
	} elseif (isset($_COOKIE['xortify']['lid'])) {
		$lid = $_COOKIE['xortify']['lid'];
		$_SESSION['xortify']['lid'] = $lid;
	}
	
	$GLOBALS['xoops']->loadLanguage('ban', 'xortify');
	
	$module_handler = $GLOBALS['xoops']->getHandler('module');
	$_SESSION['xortify'][XORTIFY_INSTANCE_KEY]['module'] = $module_handler->getByDirname('xortify');

	$GLOBALS['xoops']->header('module:xortify|xortify_banning_notice.html');
	
	include_once XOOPS_ROOT_PATH.'/modules/xortify/include/functions.php';
	addmeta_googleanalytics(_XOR_MI_XOOPS_GOOGLE_ANALYTICS_ACCOUNTID_FAILEDTOPASS, $_SERVER['HTTP_HOST']);
	if (defined('_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_FAILEDTOPASS')&&strlen(constant('_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_FAILEDTOPASS'))>=13) { 
		addmeta_googleanalytics(_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_FAILEDTOPASS, $_SERVER['HTTP_HOST']);
	}
	$GLOBALS['xoops']->tpl->assign('xoops_pagetitle', _XOR_PAGETITLE);
	$GLOBALS['xoops']->tpl->assign('description', _XOR_DESCRIPTION);
	$GLOBALS['xoops']->tpl->assign('version', $_SESSION['xortify'][XORTIFY_INSTANCE_KEY]['module']->getVar('version')/100);
	$GLOBALS['xoops']->tpl->assign('platform', XOOPS_VERSION);
	
	$log_handler = $GLOBALS['xoops']->getModuleHandler('log', 'xortify');
	$log = $log_handler->get($lid);
	if (is_object($log)) {
		setcookie('xortify', array('lid' => $lid), time()+3600*24*7*4*3);
		$GLOBALS['xoops']->tpl->assign('status', $log->getVar('extra'));
		$GLOBALS['xoops']->tpl->assign('provider', $log->getVar('provider'));
		$GLOBALS['xoops']->tpl->assign('agent', $log->getVar('agent'));
	}
    $GLOBALS['xoops']->tpl->assign('xoops_lblocks', false);
    $GLOBALS['xoops']->tpl->assign('xoops_rblocks', false);
    $GLOBALS['xoops']->tpl->assign('xoops_ccblocks', false);
    $GLOBALS['xoops']->tpl->assign('xoops_clblocks', false);
    $GLOBALS['xoops']->tpl->assign('xoops_crblocks', false);
    $GLOBALS['xoops']->tpl->assign('xoops_showlblock', false);
    $GLOBALS['xoops']->tpl->assign('xoops_showrblock', false);
    $GLOBALS['xoops']->tpl->assign('xoops_showcblock', false);
		
	$GLOBALS['xoops']->footer();
	
?>
