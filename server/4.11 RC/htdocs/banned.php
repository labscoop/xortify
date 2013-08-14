<?php

	
	include dirname(__FILE__).'/mainfile.php';
	if (isset($GLOBALS['xortify']['lid'])) {
		$lid = $GLOBALS['xortify']['lid'];
		setcookie('xortify', array('lid' => $lid), time()+3600*24*7*4*3);
	} elseif (isset($_COOKIE['xortify']['lid'])) {
		$lid = $_COOKIE['xortify']['lid'];
		$GLOBALS['xortify']['lid'] = $lid;
	}
	
	xoops_loadLanguage('ban', 'xortify');
	
	$module_handler = xoops_gethandler('module');
	$GLOBALS['xortifyModule'] = $module_handler->getByDirname('xortify');

	$xoopsOption['template_main'] = 'xortify_banning_notice.html';
	include_once XOOPS_ROOT_PATH.'/header.php';
	include_once _RUN_XORTIFY_ROOT_PATH . '/include/functions.php';
	addmeta_googleanalytics(_XOR_MI_XOOPS_GOOGLE_ANALYTICS_ACCOUNTID_FAILEDTOPASS, $_SERVER['HTTP_HOST']);
	if (defined('_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_FAILEDTOPASS')&&strlen(constant('_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_FAILEDTOPASS'))>=13) { 
		addmeta_googleanalytics(_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_FAILEDTOPASS, $_SERVER['HTTP_HOST']);
	}
	$GLOBALS['xoopsTpl']->assign('xoops_pagetitle', _XOR_PAGETITLE);
	$GLOBALS['xoopsTpl']->assign('description', _XOR_DESCRIPTION);
	$GLOBALS['xoopsTpl']->assign('version', $GLOBALS['xortifyModule']->getVar('version')/100);
	$GLOBALS['xoopsTpl']->assign('platform', XOOPS_VERSION);
	
	$log_handler = xoops_getmodulehandler('log', 'xortify');
	$log = $log_handler->get($lid);
	if (is_object($log)) {
		setcookie('xortify', array('lid' => $lid), time()+3600*24*7*4*3);
		$GLOBALS['xoopsTpl']->assign('status', $log->getVar('extra'));
		$GLOBALS['xoopsTpl']->assign('provider', $log->getVar('provider'));
		$GLOBALS['xoopsTpl']->assign('agent', $log->getVar('agent'));
	}
    $GLOBALS['xoopsTpl']->assign('xoops_lblocks', false);
    $GLOBALS['xoopsTpl']->assign('xoops_rblocks', false);
    $GLOBALS['xoopsTpl']->assign('xoops_ccblocks', false);
    $GLOBALS['xoopsTpl']->assign('xoops_clblocks', false);
    $GLOBALS['xoopsTpl']->assign('xoops_crblocks', false);
    $GLOBALS['xoopsTpl']->assign('xoops_showlblock', false);
    $GLOBALS['xoopsTpl']->assign('xoops_showrblock', false);
    $GLOBALS['xoopsTpl']->assign('xoops_showcblock', false);
		
	include_once XOOPS_ROOT_PATH.'/footer.php';
	
?>
