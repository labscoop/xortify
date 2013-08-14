<?php
// $Author: wishcraft $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: Simon Roberts (AKA wishcraft)                                     //
// URL: http://www.chronolabs.org.au                                         //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //
	
	if(!defined('_MI_SPIDERS_DIRNAME'))
		define('_MI_SPIDERS_DIRNAME','spiders');

	$spider_handler = &xoops_getmodulehandler( 'spiders', _MI_SPIDERS_DIRNAME );	
	$suser_handler = &xoops_getmodulehandler( 'spiders_user', _MI_SPIDERS_DIRNAME );	
	$member_handler = &xoops_gethandler( 'member' );
	
	$modulehandler =& xoops_gethandler('module');
	$confighandler =& xoops_gethandler('config');
	$xoModule = $modulehandler->getByDirname('spiders');
	$xoConfig = $confighandler->getConfigList($xoModule->getVar('mid'),false);
	
	$criteria = new CriteriaCompo();
	$part = $spider_handler->safeAgent($_SERVER['HTTP_USER_AGENT']);
	foreach(array(';','/',',','\\','(',')',' ') as $split) {
		$ret= array();
		foreach(explode($split, $part) as $value) {
			$ret[] = $value;
		}
		$part = implode(' ',$ret);
	}

	foreach($ret as $value) 
		if (!empty($value)&&!strpos($value, 'www.')) {
			$criteria->add(new Criteria('`robot-safeuseragent`', '%'.$value.'%', 'LIKE'), 'OR');
			$uagereg[] = $value;
		}

	//echo '<pre>'.print_r($uagereg,true).'</pre>';
	
	$spiders = $spider_handler->getObjects($criteria, true);
	if (!is_object($GLOBALS['xoopsUser']))
	foreach($spiders as $spider) {
		$suser = $suser_handler->get($spider->getVar('id'));
		$robot = $member_handler->getUser( $suser->getVar('uid') );
	
		$part = $spider_handler->safeAgent($spider->getVar('robot-useragent'));
		foreach(array(';','/',',','\\','(',')',' ') as $split) {
			$usersafeagent = array();
			foreach(explode($split, $part) as $value) {
				if (strlen(trim($value))!=0)
					$usersafeagent[] = $value;
			}
			$part = implode(' ',$usersafeagent);
		}
		$usersafeagent = explode(' ', $part);
		$match=0;
		$dos_crsafe = array();

		foreach($uagereg as $uaid => $ireg) {		
			if((in_array($ireg, $usersafeagent)&&!is_object($GLOBALS['xoopsUser']))) {
				$match++;			
				$dos_crsafe[] = $uagereg[$uaid];
			}
		}	

		//echo '<pre>'.print_r($usersafeagent,true).'</pre>';
		//echo '<pre>'.$spider->getVar('robot-name').' = '.((intval($match/count($uagereg)*100)+intval($match/count($usersafeagent)*100))/2).'% > '.intval($xoConfig['match_percentile']).'%</pre>';
		
		if (((intval($match/count($uagereg)*100)+intval($match/count($usersafeagent)*100))/2)>intval($xoConfig['match_percentile'])) {
			$GLOBALS['xoopsUser'] = NULL;
			$xoopsUserIsAdmin = false;
			$member_handler = &xoops_gethandler( 'member' );
			$spider = $spider_handler->get( $suser->getVar('spider_id') );
			$statistics_handler =& xoops_getmodulehandler('statistics', 'spiders');
			$statistic = $statistics_handler->create();
			$statistic->setVar('id', $suser->getVar('spider_id') );
			$statistic->setVar('when', time() );
			$statistic->setVar('uri', XOOPS_PROT.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'?'.$_SERVER['QUERY_STRING']);
			$statistic->setVar('useragent', $_SERVER['HTTP_USER_AGENT']);
			if ($_SERVER["HTTP_X_FORWARDED_FOR"] != ""){ 
				$ip = (string)$_SERVER["HTTP_X_FORWARDED_FOR"]; 
				$netaddy = (string)@gethostbyaddr($ip); 
			}else{ 
				$ip = (string)$_SERVER["REMOTE_ADDR"]; 
				$netaddy = (string)@gethostbyaddr($ip); 
			} 
			$statistic->setVar('ip', $ip);
			$statistic->setVar('netaddy', $netaddy);			
			$statistics_handler->insert($statistic, true);
			
			$sql = 'DELETE FROM '.$GLOBALS['xoopsDB']->prefix('spiders_statistics').' WHERE `when` < '.time()-($xoConfig['weeks_stats']*(7*24*60*60));
			$GLOBALS['xoopsDB']->queryF($sql);
			
			$sess_handler = &xoops_gethandler( 'session' );
			
			if (strtolower($spider->getVar('robot-handlesession'))=='yes') {
				@ini_set( 'session.gc_maxlifetime', $xoopsConfig['session_expire'] * 60 );
				session_set_save_handler( array( &$sess_handler, 'open' ), array( &$sess_handler, 'close' ), array( &$sess_handler, 'read' ), array( &$sess_handler, 'write' ), array( &$sess_handler, 'destroy' ), array( &$sess_handler, 'gc' ) );
				session_start();
				$_SESSION['xoopsUserId'] = $suser->getVar('uid');
				$GLOBALS['xoopsUser'] = &$member_handler->getUser( $suser->getVar('uid') );
				$_SESSION['xoopsUserGroups'] = $GLOBALS['xoopsUser']->getGroups();
				$GLOBALS['sess_handler']->update_cookie();
			} else {
				$GLOBALS['xoopsUser'] = &$member_handler->getUser( $suser->getVar('uid') );
			}				

			$online_handler =& xoops_gethandler('online');
			$online_handler->write($suser->getVar('uid'), $GLOBALS['xoopsUser']->getVar('uname'), time(), $xoModule->getVar('mid'), (string)$_SERVER["REMOTE_ADDR"]);
			$GLOBALS['xoopsUser']->setVar('last_login', time());
			$member_handler->insertUser($GLOBALS['xoopsUser'], true);

			$xoopsUserIsAdmin = $GLOBALS['xoopsUser']->isAdmin();
		
			if ( in_array( XOOPS_GROUP_BANNED, $GLOBALS['xoopsUser']->getGroups() ) ) {
				include_once $GLOBALS['xoops']->path( 'include/site-banned.php' );
				exit();
			}
		} else {
			$dos_crsafe = array();
		}
	}
	
	if (count($dos_crsafe)>0) {
		$module =& $module_handler->getByDirname('protector');
		if (is_object($module)&&!empty($module)) {
			$config_handler =& xoops_gethandler('config');
			$criteria = CriteriaCompo(new Criteria('conf_name', 'dos_crsafe'), "AND");
			$criteria->add(new Criteria('conf_modid', $module->getVar('mid')));
			$ret = array();
			if ($config_handler->getConfigCount($criteria)>0) {
				$configs = $config_handler->getConfigs($criteria);
				if (is_object($configs[0])) {
					foreach($dos_crsafe as $uaid => $uasafe) {
						if (strpos($configs[0]->getVar('conf_value'), $uasafe)>0)
							unset($dos_crsafe[$uaid]);
					}
					if (count($dos_crsafe)>0) {
						$buf = $dos_crsafe;
						$bufb = explode('|', $configs[0]->getVar('conf_value'));
						if (count($bufb)>1) {
							foreach($bufb as $id => $pagent) {
								$ret[] = $pagent;
								if ($id==2) 
									foreach($buf as $id => $pagentb) 
										$ret[] = $pagentb;
							}
							$configs[0]->setVar('conf_value', implode('|', $ret));
							$config_handler->insertConfig($configs[0]);
						}
					}
				}
			}
		}
	}
	
?>