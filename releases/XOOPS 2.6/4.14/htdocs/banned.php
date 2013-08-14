<?php

	
	include dirname(__FILE__).'/mainfile.php';
	
	$GLOBALS['xoops'] = Xoops::getInstance();
	
	if (isset($_SESSION['xortify']['lid'])) {
		$lid = $_SESSION['xortify']['lid'];
		setcookie('xortify_lid', $lid, time()+3600*24*7*4*3);
	} elseif (isset($_COOKIE['xortify_lid'])) {
		$lid = $_COOKIE['xortify_lid'];
		$_SESSION['xortify']['lid'] = $lid;
	} else {
		$lid = 0;
	}
	
	$GLOBALS['xoops']->loadLanguage('ban', 'xortify');
	
	$module_handler = $GLOBALS['xoops']->getHandler('module');
	$_SESSION['xortify'][XORTIFY_INSTANCE_KEY]['module'] = $module_handler->getByDirname('xortify');

	$GLOBALS['xoops']->header('module:xortify|xortify_banning_notice.html');
	
	$GLOBALS['xoops']->tpl()->assign('xoops_pagetitle', _XOR_PAGETITLE);
	$GLOBALS['xoops']->tpl()->assign('description', _XOR_DESCRIPTION);
	$GLOBALS['xoops']->tpl()->assign('version', $_SESSION['xortify'][XORTIFY_INSTANCE_KEY]['module']->getVar('version')/100);
	$GLOBALS['xoops']->tpl()->assign('platform', XOOPS_VERSION);
	
	if (!empty($lid)) {
		$log_handler = $GLOBALS['xoops']->getModuleHandler('log', 'xortify');
		$log = $log_handler->get($lid);
		if (is_object($log)) {
			setcookie('xortify_lid', $lid, time()+3600*24*7*4*3);
			$GLOBALS['xoops']->tpl()->assign('status', $log->getVar('extra'));
			$GLOBALS['xoops']->tpl()->assign('provider', $log->getVar('provider'));
			$GLOBALS['xoops']->tpl()->assign('agent', $log->getVar('agent'));
		}
	}
    $GLOBALS['xoops']->tpl()->assign('xoops_lblocks', false);
    $GLOBALS['xoops']->tpl()->assign('xoops_rblocks', false);
    $GLOBALS['xoops']->tpl()->assign('xoops_ccblocks', false);
    $GLOBALS['xoops']->tpl()->assign('xoops_clblocks', false);
    $GLOBALS['xoops']->tpl()->assign('xoops_crblocks', false);
    $GLOBALS['xoops']->tpl()->assign('xoops_showlblock', false);
    $GLOBALS['xoops']->tpl()->assign('xoops_showrblock', false);
    $GLOBALS['xoops']->tpl()->assign('xoops_showcblock', false);
		
	$GLOBALS['xoops']->footer();
	
?>
