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

	
	if (class_exists('Protector')) {
		
		XoopsLoad::load('xoopscache');
		if (!class_exists('XoopsCache')) {
			// XOOPS 2.4 Compliance
			XoopsLoad::load('cache');
			if (!class_exists('XoopsCache')) {
				include_once $GLOBALS['xoops']->root_path.'/class/cache/xoopscache.php';
			}
		}
			
		
		$bad_ips = Protector::get_bad_ips(false);
		$cache_bad_ips = XoopsCache::read('xortify_bans_protector');
		if (empty($cache_bad_ips))
			$cache_bad_ips = array();
	
		$module_handler = $GLOBALS['xoops']->getHandler('module');
		$config_handler = $GLOBALS['xoops']->getHandler('config');
		if (!isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module'])) $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module'] = $module_handler->getByDirname('xortify');
		if (!isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'])) $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig'] = $config_handler->getConfigList($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module']->getVar('mid'));
	
		require_once( $GLOBALS['xoops']->path('/modules/xortify/class/'.$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['protocol'].'.php') ); 	
		$func = strtoupper($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['moduleConfig']['protocol']).'XortifyExchange';
		$soapExchg = new $func;
		
		if (is_array($cache_bad_ips)) {
			foreach($bad_ips as $id => $ip) {
				if (!in_array($ip, $cache_bad_ips)) { 
					if ($ip!=$GLOBALS['xoops']->config['my_ip']) {    
						$sql = 'SELECT `lid`, `timestamp`, `type`, `agent`, `description` FROM '.$GLOBALS['xoops']->db->prefix('protector_log').' WHERE ip = "'.$ip.'" ORDER BY `timestamp`';
						$delete = 'DELECT FROM '.$GLOBALS['xoops']->db->prefix('protector_log')." WHERE `lid` = '%s'";
						$result = $GLOBALS['xoops']->db->queryF($sql);
						$comment = '';
						while($row = $GLOBALS['xoops']->db->fetchArray($result)) {
							$comment .= (strlen($comment)>0?"\n":'').$row['timestamp']. ' - ' . $row['type'] . ' - ' . $row['agent'] . ' - ' . $row['description'];
							$agent[] = $row['agent'];
							$GLOBALS['xoops']->db->queryF(sprintf($delete, $row['lid']));
						} 
						$results[] = $soapExchg->sendBan($comment, 1, $ip);
						
						$log_handler = $GLOBALS['xoops']->getModuleHandler('log', 'xortify');
						$log = $log_handler->create();
						$log->setVars(xortify_getIPData($ip));
						$log->setVar('provider', basename(dirname(__FILE__)));
						$log->setVar('action', 'banned');
						$log->setVar('extra', $comment);
						$log->setVar('agent', implode("\n", array_unique(array_unique($agent))));
						$log->setVar('email', '');
						$log->setVar('uname', '');
						$log_handler->insert($log, true);
						
					}
				}
			}
		}		
		XoopsCache::delete('xortify_bans_protector');
		XoopsCache::write('xortify_bans_protector', $bad_ips);			
	}
?>