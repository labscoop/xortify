<?php
/**
 * @package     xortify
 * @subpackage  module
 * @description	Sector Network Security Drone
 * @author	    Simon Roberts WISHCRAFT <simon@chronolabs.coop>
 * @copyright	copyright (c) 2010-2013 XOOPS.org
 * @licence		GPL 2.0 - see docs/LICENCE.txt
 */

	$GLOBALS['xoops'] = Xoops::getInstance();	
	include_once($GLOBALS['xoops']->path("/include/common.php"));
	include_once($GLOBALS['xoops']->path('/modules/xortify/include/functions.php'));
		
	if (is_object($GLOBALS['xoops']->user)) {
		$uid = $GLOBALS['xoops']->user->getVar('uid');
		$uname = $GLOBALS['xoops']->user->getVar('uname');
		$email = $GLOBALS['xoops']->user->getVar('email');
	} else {
		$uid = 0;
		$uname = (isset($_REQUEST['uname'])?$_REQUEST['uname']:'');
		$email = (isset($_REQUEST['email'])?$_REQUEST['email']:'');
	}
	
	$module_handler = $GLOBALS['xoops']->getHandler('module');
	$config_handler = $GLOBALS['xoops']->getHandler('config');
	if (!isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module'])) $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module'] = $module_handler->getByDirname('xortify');
	$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'] = $config_handler->getConfigList($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module']->getVar('mid'));
	
	XoopsLoad::load('xoopscache');
	if (!class_exists('XoopsCache')) {
		// XOOPS 2.4 Compliance
		XoopsLoad::load('cache');
		if (!class_exists('XoopsCache')) {
			include_once $GLOBALS['xoops']->root_path.'/class/cache/xoopscache.php';
		}
	}
	
	if (!$ipdata = XoopsCache::read('xortify_php_'.sha1($_SERVER['REMOTE_ADDR'].(isset($_SERVER['HTTP_X_FORWARDED_FOR'])?$_SERVER['HTTP_X_FORWARDED_FOR']:'').$uid.$uname.$email))) {
		$ipdata = xortify_getIPData(false);
		XoopsCache::write('xortify_php_'.sha1($_SERVER['REMOTE_ADDR'].(isset($_SERVER['HTTP_X_FORWARDED_FOR'])?$_SERVER['HTTP_X_FORWARDED_FOR']:'').$uid.$uname.$email), $ipdata, $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['xortify_ip_cache']);
	}
	if ($ipdata['ip4']!=$GLOBALS['xoops']->config['my_ip']&&$ipdata['ip6']!=$GLOBALS['xoops']->config['my_ip']) {
		$checked = XoopsCache::read('xortify_php_'.sha1($ipdata['uname'].$ipdata['email'].(isset($ipdata['ip4'])?$ipdata['ip4']:"").(isset($ipdata['ip6'])?$ipdata['ip6']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip4']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip6']:"").$ipdata['network-addy']));
		
		  
		if (!is_array($checked))
		{
			require_once( $GLOBALS['xoops']->path('/modules/xortify/class/'.$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['protocol'].'.php') );
			$func = strtoupper($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['protocol']).'XortifyExchange';
			ob_start();
			$soapExchg = new $func;
			$result = $soapExchg->checkPHPBans($ipdata);
			ob_end_flush();
			
			if (is_array($result)) {
				if ($result['success']==true)
					if (($result['octeta']<=$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['octeta']&&$result['octetb']>$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['octetb']&&$result['octetc']>=$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['octetc'])) {
						$module_handler = $GLOBALS['xoops']->getHandler('module');
						$config_handler = $GLOBALS['xoops']->getHandler('config');
						if (!isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module'])) $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module'] = $module_handler->getByDirname('xortify');
						if (!isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'])) $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'] = $config_handler->getConfigList($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module']->mid());
						
						XoopsCache::write('xortify_php_'.sha1($ipdata['uname'].$ipdata['email'].(isset($ipdata['ip4'])?$ipdata['ip4']:"").(isset($ipdata['ip6'])?$ipdata['ip6']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip4']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip6']:"").$ipdata['network-addy']), array_merge($result, array('ipdata' => $ipdata)), $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['xortify_ip_cache']);					
					
						$GLOBALS['xoops']->loadLanguage('ban', 'xortify');
						
						$result = $soapExchg->sendBan(_XOR_BAN_PHP_TYPE."\n".
													  _XOR_BAN_PHP_OCTETA.' '.$result['octeta'].' <= ' . $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['octeta']."\n".
													  _XOR_BAN_PHP_OCTETB.' '.$result['octetb'].' > ' . $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['octetb']."\n".
													  _XOR_BAN_PHP_OCTETC.' '.$result['octetc'].' >= ' . $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['octetc']."\n", 5, $ipdata);
													  
						$log_handler = $GLOBALS['xoops']->getModuleHandler('log', 'xortify');
						$log = $log_handler->create();
						$log->setVars($ipdata);
						$log->setVar('provider', basename(dirname(__FILE__)));
						$log->setVar('action', 'banned');
						$log->setVar('extra', _XOR_BAN_PHP_OCTETA.' '.$result['octeta'].' <= ' . $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['octeta']."\n".
											  _XOR_BAN_PHP_OCTETB.' '.$result['octetb'].' > ' . $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['octetb']."\n".
											  _XOR_BAN_PHP_OCTETC.' '.$result['octetc'].' >= ' . $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['octetc']);
						
						$lid = $log_handler->insert($log, true);
						XoopsCache::write('xortify_core_include_common_end', array('time'=>microtime(true)), $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['fault_delay']);
						$GLOBALS['xortify']['lid'] = $lid;
						setcookie('xortify', array('lid' => $lid), time()+3600*24*7*4*3);
						header('Location: '.XOOPS_URL.'/banned.php');
						exit(0);
					
					}
			}
	
			XoopsCache::write('xortify_php_'.sha1($ipdata['uname'].$ipdata['email'].(isset($ipdata['ip4'])?$ipdata['ip4']:"").(isset($ipdata['ip6'])?$ipdata['ip6']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip4']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip6']:"").$ipdata['network-addy']), array_merge($result, array('ipdata' => $ipdata)), $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['xortify_seconds']);
			$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['_pass'] = true;
					
		} elseif (isset($checked['success'])) {
			if ($checked['success']==true) {
				if (($checked['octeta']<=$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['octeta']&&$checked['octetb']>$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['octetb']&&$checked['octetc']>=$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['octetc'])) {
					
					$GLOBALS['xoops']->loadLanguage('ban', 'xortify');
					
					$log_handler = $GLOBALS['xoops']->getModuleHandler('log', 'xortify');
					$log = $log_handler->create();
					$log->setVars($checked['ipdata']);
					$log->setVar('provider', basename(dirname(__FILE__)));
					$log->setVar('action', 'blocked');
					$log->setVar('extra', _XOR_BAN_PHP_OCTETA.' '.$checked['octeta'].' <= ' . $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['octeta']."\n".
										  _XOR_BAN_PHP_OCTETB.' '.$checked['octetb'].' > ' . $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['octetb']."\n".
										  _XOR_BAN_PHP_OCTETC.' '.$checked['octetc'].' >= ' . $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['octetc']);
					
					$lid = $log_handler->insert($log, true);
					XoopsCache::write('xortify_core_include_common_end', array('time'=>microtime(true)), $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['fault_delay']);
					$GLOBALS['xortify']['lid'] = $lid;
					setcookie('xortify', array('lid' => $lid), time()+3600*24*7*4*3);
					header('Location: '.XOOPS_URL.'/banned.php');
					exit(0);		
				}
			}
		}
	}
?>