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

	include('../../mainfile.php');
	
	$op = isset($_REQUEST['op'])?$_REQUEST['op']:'robots';
	$limit = isset($_REQUEST['num'])?$_REQUEST['num']:18;	
	$start = isset($_REQUEST['start'])?$_REQUEST['start']:0;	
	
	if ($GLOBALS['xoopsModuleConfig']['htaccess']) {
		$url = XOOPS_URL.'/'.$GLOBALS['xoopsModuleConfig']['baseurl'].'/'.$op.','.$start.','.$limit.$GLOBALS['xoopsModuleConfig']['endofurl'];
		if (strpos($url, $_SERVER['REQUEST_URI'])==0) {
			header( "HTTP/1.1 301 Moved Permanently" ); 
			header('Location: '.$url);
			exit(0);
		}
	}
	
	switch($op) {
	case "robots";
	
		$robots_handler =& xoops_getmodulehandler('spiders', 'spiders');
		$user_handler =& xoops_getmodulehandler('spiders_user', 'spiders');
		$statistics_handler =& xoops_getmodulehandler('statistics', 'spiders');				
		
		$ret = array();
		$total = $robots_handler->getCount(NULL);
		xoops_load('pagenav');
		$pagenav = new XoopsPageNav($total, $limit, $start, 'start', 'num='.$limit.'&op='.$_REQUEST['op']);
		if (is_object($pagenav))
			$ret['pagenav'] = $pagenav->renderNav();

		$criteria = new Criteria(1,1);
		$criteria->setStart($start);
		$criteria->setLimit($limit);

		$xoopsOption['template_main'] = 'spiders_robots.html';
		
		include($GLOBALS['xoops']->path('/header.php'));
			
		$robots = $robots_handler->getObjects($criteria);
		foreach($robots as $id => $obj) {
			$suser = $user_handler->get($obj->getVar('id'));
			$read = XoopsCache::read('spider_id%%'.$obj->getVar('id'));

			if (!is_array($read)) {
				$value = '0A';
			} else {
				$value = $read['value'];
			}

			$name = sprintf(_MA_SPIDERS_SPF_NAME, XOOPS_URL, $suser->getVar('uid'), $obj->getVar('robot-name'));

			$url = parse_url(XOOPS_URL);
			$herelast = sprintf(_MA_SPIDERS_LASTINSEO, XOOPS_URL, $url['host']);			

			$criteria = new CriteriaCompo(new Criteria('server-ip', ''));
			$criteria->add(new Criteria('id', $obj->getVar('id')));
			$criteria->setOrder('`when`');
			
			if ($statistics_handler->getCount($criteria)>0) {
				$criteria->setStart(0);
				$criteria->setLimit(1);
				$statistics = $statistics_handler->getObjects($criteria, false);
				$lastin = date('Y m D g H:m:s', $statistics[0]->getVar('when'));
				$url = parse_url($statistics[0]->getVar('uri'));
				$herelast = sprintf(_MA_SPIDERS_LASTINSEO, $statistics[0]->getVar('uri'), $url['host']);
			} else {
				$lastin = _MA_SPIDERS_NOVISIT;
			}
			
			$criteria = new CriteriaCompo(new Criteria('server-ip', '', 'NOT LIKE'));
			$criteria->add(new Criteria('id', $obj->getVar('id')));
			$criteria->setOrder('`when`');
			
			if ($statistics_handler->getCount($criteria)>0) {
				$criteria->setStart(0);
				$criteria->setLimit(1);
				$statistics = $statistics_handler->getObjects($criteria, false);
				$lastnetin = date('Y m D g H:m:s', $statistics[0]->getVar('when'));
				$url = parse_url($statistics[0]->getVar('uri'));
				$herelast = sprintf(_MA_SPIDERS_LASTINSEO, $statistics[0]->getVar('uri'), $url['host']);
			} else {
				$lastnetin = _MA_SPIDERS_NONETVISIT;
			}
			
			$ret['fields'][] = array('name' => $name, 'value' => $value, 'lastin' => $lastin, 'lastnetin' => $lastnetin, 'herelast' => $herelast);			
			
		}
		
		$GLOBALS['xoopsTpl']->assign('robots', $ret);
		include($GLOBALS['xoops']->path('/footer.php'));
		exit(0);
		break;
		
	case "lastin":
	
		$robots_handler =& xoops_getmodulehandler('spiders', 'spiders');
		$user_handler =& xoops_getmodulehandler('spiders_user', 'spiders');
		$statistics_handler =& xoops_getmodulehandler('statistics', 'spiders');				
		
		$ret = array();
		$total = $statistics_handler->getCount(NULL);
		xoops_load('pagenav');
		$pagenav = new XoopsPageNav($total, $limit, $start, 'start', 'num='.$limit.'&op='.$_REQUEST['op']);
		if (is_object($pagenav))
			$ret['pagenav'] = $pagenav->renderNav();

		$criteria = new Criteria(1,1);
		$criteria->setStart($start);
		$criteria->setLimit($limit);
		$criteria->setSort('`when`');
		$criteria->setOrder('DESC');

		$xoopsOption['template_main'] = 'spiders_robots_last.html';
		
		include($GLOBALS['xoops']->path('/header.php'));
			
		$statistics = $statistics_handler->getObjects($criteria);
		if (sizeof($statistics)==0) {
			if ($GLOBALS['xoopsModuleConfig']['htaccess']) {
				$url = XOOPS_URL.'/'.$GLOBALS['xoopsModuleConfig']['baseurl'].'/'.$op.',0,'.$limit.$GLOBALS['xoopsModuleConfig']['endofurl'];
				if (strpos($url, $_SERVER['REQUEST_URI'])==0) {
					header( "HTTP/1.1 301 Moved Permanently" ); 
					header('Location: '.$url);
					exit(0);
				}
			} else {
				$url = $_SERVER['PHP_SELF'].'/?op='.$op.'&start=0&limit='.$limit;
				if (strpos($url, $_SERVER['REQUEST_URI'])==0) {
					header( "HTTP/1.1 301 Moved Permanently" ); 
					header('Location: '.$url);
					exit(0);
				}
			}
		}
		foreach($statistics as $id => $statistic) {
			$obj = $robots_handler->get($statistic->getVar('id'));
			$suser = $user_handler->get($obj->getVar('id'));
			$name = sprintf(_MA_SPIDERS_SPF_NAME, XOOPS_URL, $suser->getVar('uid'), $obj->getVar('robot-name'));
			if (strlen($statistic->getVar('server-ip'))>0) {
				$lastnetin = date('Y m D g H:m:s', $statistic->getVar('when'));
			} else {
				$lastin = date('Y m D g H:m:s', $statistic->getVar('when'));
			}
			$url = parse_url($statistic->getVar('uri'));
			$herelast = sprintf(_MA_SPIDERS_LASTINSEO, $statistic->getVar('uri'), $url['host']);			
			$ret['fields'][] = array('name' => $name, 'lastin' => $lastin, 'lastnetin' => $lastnetin, 'herelast' => $herelast);			
		}
		$GLOBALS['xoopsTpl']->assign('robots', $ret);
		include($GLOBALS['xoops']->path('/footer.php'));
		exit(0);
		break;
		
	case "robotstxt":
	
		xoops_loadLanguage('user');
		if ( !isset($GLOBALS['xoopsTpl']) || !is_object($GLOBALS['xoopsTpl'])  ) {
			include_once $GLOBALS['xoops']->path( "/class/template.php" );
			$GLOBALS['xoopsTpl'] = new xoopsTpl();
		}
		$robots_handler =& xoops_getmodulehandler('spiders', 'spiders');
		$robots = $robots_handler->getObjects(NULL, true, false);
		foreach($robots as $id => $robot) {
			$ret = array();
			foreach($robot as $key => $value) {
				$ret[str_replace('-','_',$key)] = $value;	
			}
			$GLOBALS['xoopsTpl']->append('robots', $ret);
		}

		header('Content-Disposition: attachment; filename="robots.txt"');
		header("Content-Type: text");

		$GLOBALS['xoopsTpl']->display('db:spiders_robottxt.html');
		exit();
		
	}
	

	header('Location: '.XOOPS_URL);	
?>