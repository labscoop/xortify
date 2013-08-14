<?php
/*
 * Prevents Spam, Harvesting, Human Rights Abuse, Captcha Abuse etc.
 * basic statistic of them in XOOPS Copyright (C) 2012 Simon Roberts 
 * Contact: wishcraft - simon@labs.coop
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 * See /docs/license.pdf for full license.
 * 
 * Shouts:- 	Mamba (www.xoops.org), flipse (www.nlxoops.nl)
 * 				Many thanks for your additional work with version 1.01
 * 
 * @Version:		3.10 Final (Stable)
 * @copyright:		Chronolabs Cooperative 2013 © Simon Roberts (www.simonaroberts.com)
 * @download:		http://sourceforge.net/projects/xortify
 * @file:			index.php		
 * @description:	Admin Index File.
 * @date:			26/07/2013 19:40 AEST
 * @license:		GNU3 (http://web.labs.coop/public/legal/general-public-licence/13,3.html)
 * @package			xortify
 * @subpackage		administration
 * @author			Simon A. Roberts - wishcraft (simon@labs.coop)
 */

	include_once dirname(dirname(__FILE__)) . '/include/instance.php';
	include_once dirname(dirname(__FILE__)) . '/language/english/modinfo.php';
	include_once dirname(dirname(dirname(dirname(__FILE__)))) . '/include/cp_header.php';
	
	$GLOBALS['xoops'] = Xoops::getInstance();
	XoopsLoad::load('system', 'system');
		
	if (!defined('_CHARSET'))
		define("_CHARSET","UTF-8");
	if (!defined('_CHARSET_ISO'))
		define("_CHARSET_ISO","ISO-8859-1");
	
	$GLOBALS['myts'] = MyTextSanitizer::getInstance();
	
	include_once $GLOBALS['xoops']->path('class'.DS.'cache'.DS.'xoopscache.php');
	include_once $GLOBALS['xoops']->path('class'.DS.'pagenav.php');
	include_once $GLOBALS['xoops']->path('class'.DS.'xoopslists.php');
	include_once $GLOBALS['xoops']->path('class'.DS.'xoopsmailer.php');
	include_once $GLOBALS['xoops']->path('class'.DS.'tree.php');
	include_once $GLOBALS['xoops']->path('class'.DS.'moduleadmin.php');
	include_once $GLOBALS['xoops']->path('modules'.DS.'xortify'.DS.'class'.DS.'auth'.DS.'authfactory.php');
	include_once $GLOBALS['xoops']->path('modules'.DS.'xortify'.DS.'include'.DS.'functions.php');
	include_once $GLOBALS['xoops']->path('modules'.DS.'xortify'.DS.'include'.DS.'forms.xortify.php');
	
	$module_handler = $GLOBALS['xoops']->getHandler('module');
	$config_handler = $GLOBALS['xoops']->getHandler('config');
	
	$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['module'] = $module_handler->getByDirname('xortify');		
	$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['ImageIcon'] = XOOPS_URL .'/'. $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['module']->getInfo('icons16');
	$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['ImageAdmin'] = XOOPS_URL .'/'. $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['module']->getInfo('icons32');
	
	$myts = MyTextSanitizer::getInstance();
	
	if ($GLOBALS['xoopsUser']) {
	    $moduleperm_handler = $GLOBALS['xoops']->getHandler('groupperm');
	    if (!$moduleperm_handler->checkRight('module_admin', $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['module']->getVar( 'mid' ), $GLOBALS['xoopsUser']->getGroups())) {
	        $GLOBALS['xoops']->redirect(XOOPS_URL, 1, _NOPERM);
	        exit();
	    }
	} else {
	    $GLOBALS['xoops']->redirect(XOOPS_URL . "/user.php", 1, _NOPERM);
	    exit();
	}
	
	$GLOBALS['xoops']->loadLanguage('admin', 'xortify');	
	$GLOBALS['xoops']->loadLanguage('modinfo', 'xortify');
	
	$GLOBALS['xoops']->header();
	$GLOBALS['xoops']->tpl()->assign('pathImageIcon', $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['ImageIcon']);
	$GLOBALS['xoops']->tpl()->assign('pathImageAdmin', $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['ImageAdmin']);
	
	$op = isset($_REQUEST['op'])?$_REQUEST['op']:"dashboard";
	$fct = isset($_REQUEST['fct'])?$_REQUEST['fct']:"";
	
	switch($op) {
	case "signup":	
		
		switch ($fct)
		{
		case "save":	
			
			$xortifyAuth = XortifyAuthFactory::getAuthConnection(false, $GLOBALS['xoops']->getModuleConfig('protocol', 'xortify'));
			$myts = MyTextSanitizer::getInstance();
			$uname = isset($_POST['uname']) ? $myts->stripSlashesGPC(trim($_POST['uname'])) : '';
			$email = isset($_POST['email']) ? $myts->stripSlashesGPC(trim($_POST['email'])) : '';
			$url = isset($_POST['url']) ? $myts->stripSlashesGPC(trim($_POST['url'])) : '';
			$pass = isset($_POST['pass']) ? $myts->stripSlashesGPC(trim($_POST['pass'])) : '';
			$vpass = isset($_POST['vpass']) ? $myts->stripSlashesGPC(trim($_POST['vpass'])) : '';
			$agree = (isset($_POST['agree']) && intval($_POST['agree'])) ? 1 : 0;
			
			if ($agree != 1) {
				$stop .= _US_UNEEDAGREE . '<br />';
			}
			
			$validate = $xortifyAuth->validate($uname, $email, $pass, $vpass);
			
			if ($validate!=false)
				$stop .= "User details didn't validate with Xortify.com<br/>$validate";
			
						
			if ($stop!='') {
				
				echo xortify_adminMenu(4, 'index.php?op=signup&fct=signup');
				echo "<p align='center' style='font-size: 15px; color: #FF0000;'>$stop</p>";
				$GLOBALS['xoops'] = Xoops::getInstance();
				$xortifyAuth = XortifyAuthFactory::getAuthConnection(false, $GLOBALS['xoops']->getModuleConfig('protocol', 'xortify'));
				$disclaimer = $xortifyAuth->network_disclaimer();
				if (strlen(trim($disclaimer))==0)
				{
					$disclaimer = _XOR_ADMIN_NONETWORKCOMM_DISCLAIMER;
				}
				if ($disclaimer != _XOR_ADMIN_NONETWORKCOMM_DISCLAIMER) {
					$uname = new XoopsFormText('', "uname", 35, 128, (isset($_POST['uname'])?$_POST['uname']:''));
					$pass = new XoopsFormPassword('', "pass", 35, 128, (isset($_POST['pass'])?$_POST['pass']:''));					
					$vpass = new XoopsFormPassword('', "vpass", 35, 128, (isset($_POST['vpass'])?$_POST['vpass']:''));					
					$email = new XoopsFormText('', "email", 35, 128, (isset($_POST['email'])?$_POST['email']:''));											
					$url = new XoopsFormText('', "url", 35, 128, (isset($_POST['url'])?$_POST['url']:''));											
					$viewemail = new XoopsFormRadioYN('', "viewemail", (isset($_POST['viewemail'])?$_POST['viewemail']:false));
					$timezone = new XoopsFormSelectTimezone('', "timezone", (isset($_POST['timezone'])?$_POST['timezone']:''));
					$myts = MyTextSanitizer::getInstance();
					$disclaim = new XoopsFormLabel('', $myts->nl2br($disclaimer));
					$agree = new XoopsFormRadioYN('', "agree", false);			
					$op = new XoopsFormHidden('op', 'signup');	
					$fct = new XoopsFormHidden('fct', 'save');
					$submit = new XoopsFormButton('', 'submit', _XOR_FRM_REGISTER, 'submit');
					$GLOBALS['xoops']->tpl()->assign('uname',$uname->render());
					$GLOBALS['xoops']->tpl()->assign('pass',$pass->render());
					$GLOBALS['xoops']->tpl()->assign('vpass',$vpass->render());
					$GLOBALS['xoops']->tpl()->assign('email',$email->render());
					$GLOBALS['xoops']->tpl()->assign('yoururl',$url->render());
					$GLOBALS['xoops']->tpl()->assign('viewemail',$viewemail->render());
					$GLOBALS['xoops']->tpl()->assign('timezone',$timezone->render());
					$GLOBALS['xoops']->tpl()->assign('disclaimer',$disclaim->render());
					$GLOBALS['xoops']->tpl()->assign('agree',$agree->render());
					$GLOBALS['xoops']->tpl()->assign('op',$op->render());
					$GLOBALS['xoops']->tpl()->assign('fct',$fct->render());
					$GLOBALS['xoops']->tpl()->assign('submit',$submit->render());
					$GLOBALS['xoops']->tpl()->display('admin:xortify|xortify_cpanel_signup_form.html');
					xortify_footer_adminMenu();
					$GLOBALS['xoops']->footer();
					exit;
				} else {
					$GLOBALS['xoops']->tpl()->assign('protocol', $GLOBALS['xoops']->getModuleConfig('protocol', 'xortify'));
					switch($GLOBALS['xoops']->getModuleConfig('protocol', 'xortify')) {
						case 'rest_curlserialised':
						case 'rest_wgetserialised':
						case 'rest_curl':
						case 'rest_json':
						case 'rest_xml':
						case 'rest_wetxml':
						case 'rest_curlxml':
						case 'minimumcloud':
							$GLOBALS['xoops']->tpl()->assign('port', $GLOBALS['xoops']->getModuleConfig('xortify_urirest', 'xortify'));
							break;
						case 'curlserialised':
						case 'wgetserialised':
							$GLOBALS['xoops']->tpl()->assign('port', $GLOBALS['xoops']->getModuleConfig('xortify_uriserial', 'xortify'));
							break;
						case 'curl':
						case 'json':
							$GLOBALS['xoops']->tpl()->assign('port', $GLOBALS['xoops']->getModuleConfig('xortify_urijson', 'xortify'));
							break;
						case 'curlxml':
						case 'wgetxml':
							$GLOBALS['xoops']->tpl()->assign('port', $GLOBALS['xoops']->getModuleConfig('xortify_urixml', 'xortify'));
							break;
						case 'soap':
							$GLOBALS['xoops']->tpl()->assign('port', $GLOBALS['xoops']->getModuleConfig('xortify_urisoap', 'xortify'));
							break;
					}
					$GLOBALS['xoops']->tpl()->assign('error', $disclaimer);
					$GLOBALS['xoops']->tpl()->display('admin:xortify|xortify_cpanel_signup_nocommunication.html');
				}
			} else {
				@$xortifyAuth->create_user(	$_POST['viewemail'], $uname, $email, $url, $actkey, 
											$pass, $_POST['timezone'], $_POST['mailok'], $xortifyAuth->check_siteinfo(array()));
				
				$module_handler = $GLOBALS['xoops']->getHandler('module');
				$config_handler = $GLOBALS['xoops']->getHandler('config');
				if (!isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['module'])) $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['module'] = $module_handler->getByDirname('xortify');
				$GLOBALS['xoops'] = Xoops::getInstance();
				foreach($configs as $id => $config)
					switch($config->getVar('conf_name')) {
					case 'xortify_username':
						$config->setVar('conf_value', $uname);
						@$configHandler->insertConfig($config);
						break;
					case 'xortify_password':
						$config->setVar('conf_value', $pass);
						@$configHandler->insertConfig($config);
						break;
					}
				
				$GLOBALS['xoops']->redirect("index.php", 4, _XOR_USERCREATED_PLEASEACTIVATE);
				exit(0);
			}
			break;
		default:	
		case "signup":
			
			echo xortify_adminMenu(4, 'index.php?op=signup&fct=signup');
			$GLOBALS['xoops'] = Xoops::getInstance();
			
			$disclaimer = XortifyAuthFactory::getAuthConnection(false, $GLOBALS['xoops']->getModuleConfig('protocol', 'xortify'))->network_disclaimer();
			
			if (strlen(trim($disclaimer))==0)
			{
				$disclaimer = _XOR_ADMIN_NONETWORKCOMM_DISCLAIMER;
			}
			if ($disclaimer != _XOR_ADMIN_NONETWORKCOMM_DISCLAIMER) {
				
				$uname = new XoopsFormText('', "uname", 35, 128, (isset($_POST['uname'])?$_POST['uname']:''));
				$pass = new XoopsFormPassword('', "pass", 35, 128, (isset($_POST['pass'])?$_POST['pass']:''));					
				$vpass = new XoopsFormPassword('', "vpass", 35, 128, (isset($_POST['vpass'])?$_POST['vpass']:''));					
				$email = new XoopsFormText('', "email", 35, 128, (isset($_POST['email'])?$_POST['email']:''));											
				$url = new XoopsFormText('', "yoururl", 35, 128, (isset($_POST['url'])?$_POST['url']:''));											
				$viewemail = new XoopsFormRadioYN('', "viewemail", (isset($_POST['viewemail'])?$_POST['viewemail']:false));
				$timezone = new XoopsFormSelectTimezone('', "timezone", (isset($_POST['timezone'])?$_POST['timezone']:''));
				$myts = MyTextSanitizer::getInstance();
				$disclaim = new XoopsFormLabel('', $myts->nl2br($disclaimer));
				$agree = new XoopsFormRadioYN('', "agree", false);
				$op = new XoopsFormHidden('op', 'signup');	
				$fct = new XoopsFormHidden('fct', 'save');
				$submit = new XoopsFormButton('', 'submit', _XOR_FRM_REGISTER, 'submit');
				$GLOBALS['xoops']->tpl()->assign('uname',$uname->render());
				$GLOBALS['xoops']->tpl()->assign('pass',$pass->render());
				$GLOBALS['xoops']->tpl()->assign('vpass',$vpass->render());
				$GLOBALS['xoops']->tpl()->assign('email',$email->render());
				$GLOBALS['xoops']->tpl()->assign('yoururl',$url->render());
				$GLOBALS['xoops']->tpl()->assign('viewemail',$viewemail->render());
				$GLOBALS['xoops']->tpl()->assign('timezone',$timezone->render());
				$GLOBALS['xoops']->tpl()->assign('disclaimer',$disclaim->render());
				$GLOBALS['xoops']->tpl()->assign('agree',$agree->render());
				$GLOBALS['xoops']->tpl()->assign('op',$op->render());
				$GLOBALS['xoops']->tpl()->assign('fct',$fct->render());
				$GLOBALS['xoops']->tpl()->assign('submit',$submit->render());
				$GLOBALS['xoops']->tpl()->display('admin:xortify|xortify_cpanel_signup_form.html');
				
				xortify_footer_adminMenu();
				$GLOBALS['xoops']->footer();
				exit;
			} else {
				
				$GLOBALS['xoops']->tpl()->assign('protocol', $GLOBALS['xoops']->getModuleConfig('protocol', 'xortify'));
				switch($GLOBALS['xoops']->getModuleConfig('protocol', 'xortify')) {
					case 'rest_wgetserialised':
					case 'rest_curl':
					case 'rest_json':
					case 'rest_xml':
					case 'rest_wetxml':
					case 'rest_curlxml':
					case 'minimumcloud':
						$GLOBALS['xoops']->tpl()->assign('port', $GLOBALS['xoops']->getModuleConfig('xortify_urirest', 'xortify'));
						break;
					case 'curlserialised':
					case 'wgetserialised':
						$GLOBALS['xoops']->tpl()->assign('port', $GLOBALS['xoops']->getModuleConfig('xortify_uriserial', 'xortify'));
						break;
					case 'curl':
					case 'json':
						$GLOBALS['xoops']->tpl()->assign('port', $GLOBALS['xoops']->getModuleConfig('xortify_urijson', 'xortify'));
						break;
					case 'curlxml':
					case 'wgetxml':
						$GLOBALS['xoops']->tpl()->assign('port', $GLOBALS['xoops']->getModuleConfig('xortify_urixml', 'xortify'));
						break;
					case 'soap':
						$GLOBALS['xoops']->tpl()->assign('port', $GLOBALS['xoops']->getModuleConfig('xortify_urisoap', 'xortify'));
						break;
				}
				$GLOBALS['xoops']->tpl()->assign('error', $disclaimer);
				$GLOBALS['xoops']->tpl()->display('admin:xortify|xortify_cpanel_signup_nocommunication.html');
				
			}
			break;
		}
		break;
	case "log":	
		echo xortify_adminMenu(3, 'index.php?op=log');
		
		include_once $GLOBALS['xoops']->path( "/class/pagenav.php" );
		
		$log_handler = $GLOBALS['xoops']->getModuleHandler('log', 'xortify');
		
		$ttl = $log_handler->getCount(NULL);
		$limit = !empty($_POST['limit'])?intval($_POST['limit']):30;
		$start = !empty($_POST['start'])?intval($_POST['start']):0;
		$order = !empty($_POST['order'])?$_POST['order']:'DESC';
		$sort = !empty($_POST['sort'])?''.$_POST['sort'].'':'date';
		
		$pagenav = new XoopsPageNav($ttl, $limit, $start, 'start', 'limit='.$limit.'&sort='.$sort.'&order='.$order.'&op='.$op);
		$GLOBALS['xoops']->tpl()->assign('pagenav', $pagenav->renderNav());
		
		foreach (array(	'action','provider','date','uname','email','ip4','ip6','proxy-ip4',
						'proxy-ip6','network-addy','agent') as $id => $key) {
			$GLOBALS['xoops']->tpl()->assign(strtolower(str_replace('-','_',$key).'_th'), '<a href="'.$_SERVER['PHP_SELF'].'?start='.$start.'&limit='.$limit.'&sort='.str_replace('_','-',$key).'&order='.((str_replace('_','-',$key)==$sort)?($order=='DESC'?'ASC':'DESC'):$order).'&op='.$op.'">'.(defined('_XOR_AM_TH_'.strtoupper(str_replace('-','_',$key)))?constant('_XOR_AM_TH_'.strtoupper(str_replace('-','_',$key))):'_XOR_AM_TH_'.strtoupper(str_replace('-','_',$key))).'</a>');
		}
		
		$criteria = new Criteria('1','1');
		$criteria->setStart($start);
		$criteria->setLimit($limit);
		$criteria->setSort('`'.$sort.'`');
		$criteria->setOrder($order);
		
		$logs = $log_handler->getObjects($criteria, true);
		foreach($logs as $id => $log) {
			$GLOBALS['xoops']->tpl()->append('log', $log->toArray());
		}
		$GLOBALS['xoops']->tpl()->display('admin:xortify|xortify_cpanel_log.html');		
		
		break;		
	case "list":

		echo xortify_adminMenu(2, 'index.php?op=list&fct=bans');
		
		XoopsLoad::load("xoopscache");
		if (!class_exists('XoopsCache'))
			XoopsLoad::load("cache");			
		XoopsLoad::load("pagenav");			
		
		if (!$bans = XoopsCache::read('xortify_bans_cache')) {
			
			require_once( XOOPS_ROOT_PATH.'/modules/xortify/class/'.$GLOBALS['xoops']->getModuleConfig('protocol', 'xortify').'.php' );
			 	
			$func = strtoupper($GLOBALS['xoops']->getModuleConfig('protocol', 'xortify')).'XortifyExchange';
			
			ob_start();
			$soapExchg = new $func;
			$bans = $soapExchg->retrieveBans();
			ob_end_flush();
			
			XoopsCache::delete('xortify_bans_cache');
			XoopsCache::delete('xortify_bans_cache_backup');			
			XoopsCache::write('xortify_bans_cache', $bans, $GLOBALS['xoops']->getModuleConfig('xortify_seconds', 'xortify'));
			XoopsCache::write('xortify_bans_cache_backup', $bans, ($GLOBALS['xoops']->getModuleConfig('xortify_seconds', 'xortify') * 1.45));					
		}
		
		if ($bans['bans']==0) {
			
			echo _XS_AM_NOCACHEMSG;	
		}	else {
			
			$limit = !empty($_REQUEST['limit'])?intval($_REQUEST['limit']):30;
			$start = !empty($_REQUEST['start'])?intval($_REQUEST['start']):0;
			
			$pagenav = new XoopsPageNav($bans['bans'], $limit, $start, 'start', 'limit='.$limit.'&op='.$op);
			$GLOBALS['xoops']->tpl()->assign('pagenav', $pagenav->renderNav());
			$i=0;
			$num=0;
			foreach($bans['data'] as $key => $data) {
				$i++;
				if ($i>=$start&&$num<=$limit) {
					$num++;
					if (strlen($data['ip4'])>0) {
						$ipaddy = $data['ip4'];
						$iptype = _XS_IPTYPE_IP4;
					} elseif (strlen($data['ip6'])>0) {
						$ipaddy = $data['ip6'];
						$iptype = _XS_IPTYPE_IP6;
					} else {
						$ipaddy = '';
						$iptype = _XS_IPTYPE_EMPTY;				
					}
					
					if (strlen($data['proxy-ip4'])>0) {
						$proxyip = $data['proxy-ip4'];
						$proxyiptype = _XS_IPTYPE_IP4;
					} elseif (strlen($data['proxy-ip6'])>0) {
						$proxyip = $data['proxy-ip6'];
						$proxyiptype = _XS_IPTYPE_IP6;
					} else {
						$proxyip = '';
						$proxyiptype = '';					
					}
					
					$GLOBALS['xoops']->tpl()->append('bans', array('netaddy'=>$data['network-addy']?$data['network-addy']:'&nbsp;',
															 'macaddy'=>$data['mac-addy']?$data['mac-addy']:'&nbsp;',
															 'iptype'=>$iptype, 'ipaddy'=>$ipaddy, 
															 'proxyiptype'=>$proxyiptype,'ip'=>$proxyip,
															 'long' => $data['long']?$data['long']:'&nbsp;', 
															 'category' =>$data['category'],'comments'=>(isset($data['comments'])?$data['comments']:false)));
					
				}		
			}
			$hostname = 'xortify.com';
			switch($GLOBALS['xoops']->getModuleConfig('protocol', 'xortify')) {
				case 'rest_wgetserialised':
				case 'rest_curl':
				case 'rest_json':
				case 'rest_xml':
				case 'rest_wetxml':
				case 'rest_curlxml':
				case 'minimumcloud':
					$GLOBALS['xoops']->tpl()->assign('port', $GLOBALS['xoops']->getModuleConfig('xortify_urirest', 'xortify'));
					break;
				case 'curlserialised':
				case 'wgetserialised':
					$hostname = parse_url($GLOBALS['xoops']->getModuleConfig('xortify_uriserial', 'xortify'), PHP_URL_HOST);
					break;
				case 'curl':
				case 'json':
					$hostname = parse_url($GLOBALS['xoops']->getModuleConfig('xortify_urijson', 'xortify'), PHP_URL_HOST);
					break;
				case 'curlxml':
				case 'wgetxml':
					$hostname = parse_url($GLOBALS['xoops']->getModuleConfig('xortify_urixml', 'xortify'), PHP_URL_HOST);
					break;
				case 'soap':
					$hostname = parse_url($GLOBALS['xoops']->getModuleConfig('xortify_urisoap', 'xortify'), PHP_URL_HOST);
					break;
			}
			$GLOBALS['xoops']->tpl()->assign('cloudurl', 'http://'.$hostname); 
			$GLOBALS['xoops']->tpl()->display('admin:xortify|xortify_cpanel_bans.html');	
		}
		
		 
		break;
	case "dashboard":
	default:
		
		echo xortify_adminMenu(1, 'index.php?op=dashboard');
		
	    $log_handler = $GLOBALS['xoops']->getModuleHandler('log', 'xortify');
		
	 	$indexAdmin = new XoopsModuleAdmin();	
		
	    $indexAdmin->addInfoBox(_XOR_ADMIN_COUNTS);
	    $indexAdmin->addInfoBoxLine(sprintf(_XOR_ADMIN_THEREARE_BANNED, $log_handler->getCountByField('action','banned')), 'default', 'Green');
	    $indexAdmin->addInfoBoxLine(sprintf(_XOR_ADMIN_THEREARE_BLOCKED, $log_handler->getCountByField('action','blocked')), 'default', 'Green');
	    $indexAdmin->addInfoBoxLine(sprintf(_XOR_ADMIN_THEREARE_MONITORED, $log_handler->getCountByField('action','monitored')), 'default', 'Green');
	    $indexAdmin->addInfoBoxLine(sprintf(_XOR_ADMIN_THEREARE_PROJECTHONEYPOTORG, $log_handler->getCountByField('provider','projecthoneypot.org')), 'default', 'Green');
	    $indexAdmin->addInfoBoxLine(sprintf(_XOR_ADMIN_THEREARE_PROTECTOR, $log_handler->getCountByField('provider','protector')), 'default', 'Green');
	    $indexAdmin->addInfoBoxLine(sprintf(_XOR_ADMIN_THEREARE_SPIDERS, $log_handler->getCountByField('provider','spiders')), 'default', 'Green');
	    $indexAdmin->addInfoBoxLine(sprintf(_XOR_ADMIN_THEREARE_STOPFORUMSPAMCOM, $log_handler->getCountByField('provider','stopforumspam.com')), 'default', 'Green');
	    $indexAdmin->addInfoBoxLine(sprintf(_XOR_ADMIN_THEREARE_XORTIFY, $log_handler->getCountByField('provider','xortify')), 'default', 'Green');
		
		XoopsLoad::load('xoopscache');
		if (!class_exists('XoopsCache')) {
			// XOOPS 2.4 Compliance
			XoopsLoad::load('cache');
			if (!class_exists('XoopsCache')) {
				include_once XOOPS_ROOT_PATH.DS.'class'.DS.'cache'.DS.'xoopscache.php';
			}
		}
		
		if ($bans = XoopsCache::read('xortify_bans_cache')) {
			$indexAdmin->addInfoBoxLine(sprintf(_XOR_ADMIN_THEREARE_CLOUDEDBANS, count($bans['data'])), 'default', 'Green');
		}
	    
		if ($result = XoopsCache::read('xortify_cleanup_last')) {
			$indexAdmin->addInfoBoxLine(sprintf(_XOR_ADMIN_THEREARE_WHENCLEANED, date('D, Y-m-d H:i:s', $result['when'])), 'default' , 'Purple');
			$indexAdmin->addInfoBoxLine(sprintf(_XOR_ADMIN_THEREARE_CLEANINGTOOK, $result['took']), 'default', 'Purple');
			$indexAdmin->addInfoBoxLine(sprintf(_XOR_ADMIN_THEREARE_FILESDELETED, $result['files']), 'default' , 'Purple');
			$indexAdmin->addInfoBoxLine(sprintf(_XOR_ADMIN_THEREARE_BYTESSAVED, $result['size']), 'default', 'Purple');
		}
		
		$indexAdmin->addInfoBox(_XOR_ADMIN_KEYS, 1);
		$indexAdmin->addInfoBoxLine(sprintf("<label>"._XOR_ADMIN_INSTANCE_KEY."</label>", XORTIFY_INSTANCE_KEY), 1, (constant('XORTIFY_INSTANCE_KEY')!='00000-00000-00000-00000-00000'?'Green':'Red'));
		echo $indexAdmin->renderIndex();	
		
		break;	
	case "about":
		
		echo xortify_adminMenu(5, 'index.php?op=about');
		
		$paypalitemno='XBZNTNX2BZUR4';
		$aboutAdmin = new XoopsModuleAdmin();
		
		echo $aboutAdmin->renderabout($paypalitemno, false);

	}
	
	xortify_footer_adminMenu();
	$GLOBALS['xoops']->footer();
?>