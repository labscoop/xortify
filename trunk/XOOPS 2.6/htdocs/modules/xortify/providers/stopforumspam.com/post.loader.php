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
	
	$GLOBALS['xoops']->load('xoopscache');
	if (!class_exists('XoopsCache')) {
		// XOOPS 2.4 Compliance
		$GLOBALS['xoops']->load('cache');
		if (!class_exists('XoopsCache')) {
			include_once $GLOBALS['xoops']->root_path.'/class/cache/xoopscache.php';
		}
	}
	
	
	
	$ipdata = xortify_getIPData(false);
	if ($ipdata['ip4']!=$GLOBALS['xoops']->config['my_ip']&&$ipdata['ip6']!=$GLOBALS['xoops']->config['my_ip']) {
		$checked = XoopsCache::read('xortify_sfs_'.sha1($ipdata['uname'].$ipdata['email'].(isset($ipdata['ip4'])?$ipdata['ip4']:"").(isset($ipdata['ip6'])?$ipdata['ip6']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip4']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip6']:"").$ipdata['network-addy']));
		
		$module_handler = $GLOBALS['xoops']->getHandler('module');
		$config_handler = $GLOBALS['xoops']->getHandler('config');
		if (!isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module'])) $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module'] = $module_handler->getByDirname('xortify');
		if (!isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'])) $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'] = $config_handler->getConfigList($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module']->getVar('mid'));
			
		if (!is_array($checked))
		{
			
			require_once( $GLOBALS['xoops']->path('/modules/xortify/class/'.$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['protocol'].'.php') );
			$func = strtoupper($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['protocol']).'XortifyExchange';
			ob_start();
			$soapExchg = new $func;
			
			$result = $soapExchg->checkSFSBans($ipdata);
			
			ob_end_flush();
			
			if (is_array($result)) {
				if (isset($result['success']))
					if ($result['success']==true)
						if (($result['email']['frequency']>=$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['email_freq']||$result['username']['frequency']>=$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['uname_freq']||$result['ip']['frequency']>=$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['ip_freq']) &&
							(strtotime($result['email']['lastseen'])>time()-$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['email_lastseen']||strtotime($result['username']['lastseen'])>time()-$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['uname_lastseen']||strtotime($result['ip']['lastseen'])>time()-$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['ip_lastseen'])) {
								
								XoopsCache::write('xortify_sfs_'.sha1($ipdata['uname'].$ipdata['email'].(isset($ipdata['ip4'])?$ipdata['ip4']:"").(isset($ipdata['ip6'])?$ipdata['ip6']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip4']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip6']:"").$ipdata['network-addy']), array_merge($result, array('ipdata' => $ipdata)), $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['xortify_ip_cache']);					
								
								$GLOBALS['xoops']->loadLanguage('ban', 'xortify');
								
								$result = $soapExchg->sendBan(_XOR_BAN_SFS_TYPE."\n".
															  _XOR_BAN_SFS_EMAIL_FREQ.': '.$result['email']['frequency'].' >= '.$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['email_freq']. "\n".
															  _XOR_BAN_SFS_EMAIL_LASTSEEN.': '.date(_DATESTRING, strtotime($result['email']['lastseen'])) . ' > ' . date(_DATESTRING, time()-$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['email_lastseen']) . "\n" .
															  _XOR_BAN_SFS_USERNAME_FREQ.': '.$result['username']['frequency'].' >= '.$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['uname_freq']. "\n".
															  _XOR_BAN_SFS_USERNAME_LASTSEEN.': '.date(_DATESTRING, strtotime($result['username']['lastseen'])) . ' > ' . date(_DATESTRING, time()-$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['uname_lastseen']) . "\n" .
															  _XOR_BAN_SFS_IP_FREQ.': '.$result['ip']['frequency'].' >= '.$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['ip_freq']. "\n".
															  _XOR_BAN_SFS_IP_LASTSEEN.': '.date(_DATESTRING, strtotime($result['ip']['lastseen'])) . ' > ' . date(_DATESTRING, time()-$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['ip_lastseen']), 4, $ipdata);
	 						    
								$log_handler = $GLOBALS['xoops']->getModuleHandler('log', 'xortify');
								$log = $log_handler->create();
								$log->setVars($ipdata);
								$log->setVar('provider', basename(dirname(__FILE__)));
								$log->setVar('action', 'banned');
								$log->setVar('extra', _XOR_BAN_SFS_TYPE."\n".
													  _XOR_BAN_SFS_EMAIL_FREQ.': '.$result['email']['frequency'].' >= '.$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['email_freq']. "\n".
													  _XOR_BAN_SFS_EMAIL_LASTSEEN.': '.date(_DATESTRING, strtotime($result['email']['lastseen'])) . ' > ' . date(_DATESTRING, time()-$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['email_lastseen']) . "\n" .
													  _XOR_BAN_SFS_USERNAME_FREQ.': '.$result['username']['frequency'].' >= '.$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['uname_freq']. "\n".
													  _XOR_BAN_SFS_USERNAME_LASTSEEN.': '.date(_DATESTRING, strtotime($result['username']['lastseen'])) . ' > ' . date(_DATESTRING, time()-$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['uname_lastseen']) . "\n" .
													  _XOR_BAN_SFS_IP_FREQ.': '.$result['ip']['frequency'].' >= '.$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['ip_freq']. "\n".
													  _XOR_BAN_SFS_IP_LASTSEEN.': '.date(_DATESTRING, strtotime($result['ip']['lastseen'])) . ' > ' . date(_DATESTRING, time()-$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['ip_lastseen']));
								$lid = $log_handler->insert($log, true);
								XoopsCache::write('xortify_core_include_common_end', array('time'=>microtime(true)), $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['fault_delay']);
								$GLOBALS['xortify']['lid'] = $lid;
								setcookie('xortify', array('lid' => $lid), time()+3600*24*7*4*3);
								header('Location: '.XOOPS_URL.'/banned.php');
								exit(0);
								
							}
				XoopsCache::write('xortify_sfs_'.sha1($ipdata['uname'].$ipdata['email'].(isset($ipdata['ip4'])?$ipdata['ip4']:"").(isset($ipdata['ip6'])?$ipdata['ip6']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip4']:"").(isset($ipdata['proxy-ip4'])?$ipdata['proxy-ip6']:"").$ipdata['network-addy']), array_merge($result, array('ipdata' => $ipdata)), $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['xortify_ip_cache']);
				$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['_pass'] = true;
			}		
		} elseif (isset($checked['success'])&&$checked['success']==true) {
			if (($checked['email']['frequency']>=2||$checked['username']['frequency']>=2||$checked['ip']['frequency']>=2) &&
				(strtotime($checked['email']['lastseen'])>time()-(60*60*24*7*4*3)||strtotime($checked['username']['lastseen'])>time()-(60*60*24*7*4*3)||strtotime($checked['ip']['lastseen'])>time()-(60*60*24*7*4*3))) {
									
					$GLOBALS['xoops']->loadLanguage('ban', 'xortify');
					
					$log_handler = $GLOBALS['xoops']->getModuleHandler('log', 'xortify');
					$log = $log_handler->create();
					$log->setVars($checked['ipdata']);
					$log->setVar('provider', basename(dirname(__FILE__)));
					$log->setVar('action', 'blocked');
					$log->setVar('extra', _XOR_BAN_SFS_TYPE."\n".
										  _XOR_BAN_SFS_EMAIL_FREQ.': '.$checked['email']['frequency'].' >= '.$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['email_freq']. "\n".
										  _XOR_BAN_SFS_EMAIL_LASTSEEN.': '.date(_DATESTRING, strtotime($checked['email']['lastseen'])) . ' > ' . date(_DATESTRING, time()-$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['email_lastseen']) . "\n" .
										  _XOR_BAN_SFS_USERNAME_FREQ.': '.$checked['username']['frequency'].' >= '.$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['uname_freq']. "\n".
										  _XOR_BAN_SFS_USERNAME_LASTSEEN.': '.date(_DATESTRING, strtotime($checked['username']['lastseen'])) . ' > ' . date(_DATESTRING, time()-$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['uname_lastseen']) . "\n" .
										  _XOR_BAN_SFS_IP_FREQ.': '.$checked['ip']['frequency'].' >= '.$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['ip_freq']. "\n".
										  _XOR_BAN_SFS_IP_LASTSEEN.': '.date(_DATESTRING, strtotime($checked['ip']['lastseen'])) . ' > ' . date(_DATESTRING, time()-$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['ip_lastseen']));
					$lid = $log_handler->insert($log, true);
					XoopsCache::write('xortify_core_include_common_end', array('time'=>microtime(true)), $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['fault_delay']);
					$GLOBALS['xortify']['lid'] = $lid;
					setcookie('xortify', array('lid' => $lid), time()+3600*24*7*4*3);
					header('Location: '.XOOPS_URL.'/banned.php');
					exit(0);
					
			}
		}
	}
?>