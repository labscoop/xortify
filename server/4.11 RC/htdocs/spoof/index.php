<?php
/**
 * Xortify Bans & Unbans Function
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
 * @package         spam
 * @subpackage		spoof
 * @author         	Simon Roberts (wishcraft) - meshy@labs.coop
 */

	include_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'mainfile.php';
	include_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'xortify' . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'functions.php';
	include_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'cloud' . DIRECTORY_SEPARATOR . 'ban.php';
	
	$op = (isset($_REQUEST['op'])?(string)$_REQUEST['op']:'default');
	$fct = (isset($_REQUEST['op'])?(string)$_REQUEST['fct']:'default');
	
	$ip = xortify_getIPData(trim(isset($_REQUEST['ip'])?(string)$_REQUEST['ip']:xortify_getIP()));
	
	switch ($op) {
		case "recieve":
			switch ($fct) {
				case "registration":
					$ip['uname'] = (isset($_REQUEST['uname'])?(string)$_REQUEST['uname']:'');
					$ip['email'] = (isset($_REQUEST['email'])?(string)$_REQUEST['email']:'');
					$xoopsModuleConfig['site_user_auth'] = false;
					ban('', '', array($ip), array('comment' => 'Spoof Registration Ban'));
					break;
				case "comment":
					$ip['uname'] = (isset($_REQUEST['uname'])?(string)$_REQUEST['uname']:'');
					$ip['email'] = (isset($_REQUEST['email'])?(string)$_REQUEST['email']:'');
					$xoopsModuleConfig['site_user_auth'] = false;
					ban('', '', array($ip), array('comment' => 'Spoof Comment Ban - '.(isset($_REQUEST['uri'])?$_REQUEST['uri']:$_SERVER["REMOTE_ADDR"])));
					if (isset($_REQUEST['text'])&&!empty($_REQUEST['text'])) {
						$filename = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'spoof' . DIRECTORY_SEPARATOR . 'spam' . DIRECTORY_SEPARATOR . md5($_REQUEST['text']) . '.txt';
						$file = fopen($filename, 'w');
						fwrite($file, $_REQUEST['text'], strlen($_REQUEST['text']));
						fclose($file);    
					}
					break;
				case "thread":
					$ip['uname'] = (isset($_REQUEST['uname'])?(string)$_REQUEST['uname']:'');
					$ip['email'] = (isset($_REQUEST['email'])?(string)$_REQUEST['email']:'');
					$xoopsModuleConfig['site_user_auth'] = false;
					ban('', '', array($ip), array('comment' => 'Spoof Thread Ban - '.(isset($_REQUEST['uri'])?$_REQUEST['uri']:$_SERVER["REMOTE_ADDR"])));
					if (isset($_REQUEST['text'])&&!empty($_REQUEST['text'])) {
						$filename = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'spoof' . DIRECTORY_SEPARATOR . 'spam' . DIRECTORY_SEPARATOR . md5($_REQUEST['text']) . '.txt';
						$file = fopen($filename, 'w');
						fwrite($file, $_REQUEST['text'], strlen($_REQUEST['text']));
						fclose($file);
					}
					break;
				default:
					
			}
						
	}	
?>