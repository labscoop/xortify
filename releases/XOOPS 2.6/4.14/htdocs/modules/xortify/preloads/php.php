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
 * @Version:		4.11 Final (Stable)
 * @copyright:		Chronolabs Cooperative 2013 © Simon Roberts (www.simonaroberts.com)
 * @download:		http://sourceforge.net/projects/xortify
 * @file:			php.php		
 * @description:	Mode: Server - Preloader for handling Project Honeypot.
 * @date:			26/07/2013 19:40 AEST
 * @license:		GNU3 (http://web.labs.coop/public/legal/general-public-licence/13,3.html)
 * @package			xortify
 * @subpackage		preloaders
 * @author			Simon A. Roberts - wishcraft (simon@labs.coop)

 * 
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');


/*
 * Class for Xortify in Server Mode!
 * Preloader of Project Honey Pot.
 */
class XortifyPhpPreload extends XoopsPreloadItem
{

	/*
	 * Event for loading when one of the APIs are finished being called
	* @param array $args		Arguements passed to the API
	*/
	function eventApiServerEnd($args)
	{
		xoops_loadLanguage('modinfo', 'xortify');
		include_once dirname(dirname(__FILE__)) . '/xoops_version.php';
		if (_MI_XOR_MODE!=_MI_XOR_MODE_SERVER)
			return true;
		
		
		if ($args[1]=='checkPhpbans') {
			
			include_once($GLOBALS['xoops']->path('/modules/xortify/include/functions.php'));
		
			xoops_load('xoopscache');
			if (!class_exists('XoopsCache')) {
				// XOOPS 2.4 Compliance
				xoops_load('cache');
				if (!class_exists('XoopsCache')) {
					include_once XOOPS_ROOT_PATH.'/class/cache/xoopscache.php';
				}
			}
			
			$checked = XoopsCache::read('xortify_php_provider_'.sha1($args[5]['uname'].$args[5]['email'].(isset($args[5]['ip4'])?$args[5]['ip4']:"").(isset($args[5]['ip6'])?$args[5]['ip6']:"").(isset($args[5]['proxy-ip4'])?$args[5]['proxy-ip4']:"").(isset($args[5]['proxy-ip4'])?$args[5]['proxy-ip6']:"").$args[5]['network-addy']));
			
			if (!is_array($checked))
			{	
				if (is_array($args[6])) {
					if ($args[6]['success']==true)
						if (($args[6]['octeta']<=$GLOBALS['xoops']->getModuleConfig('octeta', 'xortify')&&$args[6]['octetb']>$GLOBALS['xoops']->getModuleConfig('octetb', 'xortify')&&$args[6]['octetc']>=$GLOBALS['xoops']->getModuleConfig('octetc', 'xortify'))) {
						XoopsCache::write('xortify_php_'.sha1($args[5]['uname'].$args[5]['email'].(isset($args[5]['ip4'])?$args[5]['ip4']:"").(isset($args[5]['ip6'])?$args[5]['ip6']:"").(isset($args[5]['proxy-ip4'])?$args[5]['proxy-ip4']:"").(isset($args[5]['proxy-ip4'])?$args[5]['proxy-ip6']:"").$args[5]['network-addy']), array_merge($args[6], array('ipdata' => $args[5])), $GLOBALS['xoops']->getModuleConfig('xortify_ip_cache', 'xortify'));
			
						xoops_loadLanguage('ban', 'xortify');

						$bans = array(0=>$args[5]);
						$comments = array('comment' => _XOR_BAN_PHP_TYPE."\n".
								_XOR_BAN_PHP_OCTETA.' '.$args[6]['octeta'].' <= ' . $GLOBALS['xoops']->getModuleConfig('octeta', 'xortify')."\n".
								_XOR_BAN_PHP_OCTETB.' '.$args[6]['octetb'].' > ' . $GLOBALS['xoops']->getModuleConfig('octetb', 'xortify')."\n".
								_XOR_BAN_PHP_OCTETC.' '.$args[6]['octetc'].' >= ' . $GLOBALS['xoops']->getModuleConfig('octetc', 'xortify')."\n", 5, $args[5]);
						$error=array();
							
						foreach ($bans as $key => $ban){
							if ($ban['network-addy']=='localhost'  ||
									$ban['ip4']=='127.0.0.1' ||
									strpos($ban['ip6'], '127.0.0.1'))
								$error[] = 'localhost cannot be specified in ban - '.$key;
								
							if ( !(intval($ban['made'])>time()-(48*60*60)  &&
									intval($ban['made'])<time()+(48*60*60)) )
								$error[] = 'ban must be made within '.(48*60*60).' seconds ahead or behind of made server timestamp in ban - '.$key;
								
							$criteria = new CriteriaCompo();
								
							foreach($ban as $field => $value) {
								if ($field!='email'&&preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$/i", $value))
									$error[] = 'no email specified allowed for field - '.$field.' - in ban - '.$key;
									
								if (strpos(' '.$value, '*'))
									$error[] = 'wildcard * cannot be specified for field - '.$field.' - in ban - '.$key;
									
							}
								
							$criteriaa = new CriteriaCompo(new Criteria('`ip4`', $ban['ip4']));
							$criteriaa->add(new Criteria('`proxy-ip4`', $ban['proxy-ip4']), 'AND');
							$criteriab = new CriteriaCompo(new Criteria('`ip6`', $ban['ip6']));
							$criteriab->add(new Criteria('`proxy-ip6`', $ban['proxy-ip6']), 'AND');
							$criteriac = new CriteriaCompo(new Criteria('`long`', $ban['long']));
							$criteriac->add(new Criteria('`network-addy`', $ban['network-addy']), 'OR');
							$criteria = new CriteriaCompo($criteriaa, 'OR');
							$criteria->add($criteriab, 'OR');
							$criteria->add($criteriac, 'OR');
								
							if ($banmemberHandler->getCount($criteria)>0)
								$error[] = 'Ban already exists for record - '.$criteria->renderWhere();
						}
							
						if (count($error)>0)
							return array("errors" => $error, "made" => time());
							
						foreach ($bans as $key => $ban){
								
							$banning = $banmemberHandler->create();
								
							$banning->setVars($ban, true);
								
							$banning->setVar('suid', $suid);
							$user_handler = $GLOBALS['xoops']->getHandler('user');
							$criteria = new CriteriaCompo(new Criteria('uname', $banning->getVar('uname')));
							$criteria->add(new Criteria('email', $banning->getVar('email')));
							if ($user_handler->getCount($criteria)>0||$banning->getVar('category_id')==1) {
								$banning->setVar('uid', 0);
								$banning->setVar('email', '');
								$banning->setVar('uname', '');
							}
								
							if ($itemid = $banmemberHandler->insert($banning, true)) {
								$ii++;
								if (isset($comments['comment'])) {
									$sql = "INSERT INTO ".$xoopsDB->prefix('xoopscomments'). ' (com_created, com_pid, com_itemid, com_rootid, com_ip, com_title, com_text,  dohtml, dosmiley, doxcode, doimage, dobr, com_icon, com_modid, com_status, com_uid) VALUES("'.time().'", "0", "'.$itemid.'","0","'.$_SERVER['REMOTE_ADDR'].'","Banning Comment :: '.$comments['uname'].' :: '.date('d-M-Y H:i:s').'","'.str_replace('\n', '<br/>', htmlspecialchars($comments['comment'])).'",1,1,1,1,1,1,"'.$xoModule->getVar('mid').'", 2, "'.($suid>0?$suid:0).'")';
									$xoopsDB->queryF($sql);
									$sql = "UPDATE ".$xoopsDB->prefix('users'). 'SET `posts` = `posts` + 1 WHERE uid = '.$suid;
									$xoopsDB->queryF($sql);
										
								} elseif (is_array($comments)) {
									foreach($comments as $cmid => $commentor) {
											
										$sql = "INSERT INTO ".$xoopsDB->prefix('xoopscomments'). ' (com_created, com_pid, com_itemid, com_rootid, com_ip, com_title, com_text,  dohtml, dosmiley, doxcode, doimage, dobr, com_icon, com_modid, com_status, com_uid) VALUES("'.time().'", "0", "'.$itemid.'","0","'.$_SERVER['REMOTE_ADDR'].'","Banning Comment :: '.$commentor['uname'].' :: '.date('d-M-Y H:i:s').'","'.str_replace('\n', '<br/>', htmlspecialchars($commentor['comment'])).'",1,1,1,1,1,1,"'.$xoModule->getVar('mid').'", 2, "'.($suid>0?$suid:0).'")';
										$xoopsDB->queryF($sql);
										$sql = "UPDATE ".$xoopsDB->prefix('users'). 'SET `posts` = `posts` + 1 WHERE uid = '.$suid;
										$xoopsDB->queryF($sql);
									}
								}
						
							}
						}
							
						
						$log_handler = xoops_getmodulehandler('log', 'xortify');
						$log = $log_handler->create();
						$log->setVars($args[5]);
						$log->setVar('provider', basename(dirname(__FILE__)));
						$log->setVar('action', 'banned');
						$log->setVar('extra', _XOR_BAN_PHP_OCTETA.' '.$args[6]['octeta'].' <= ' . $GLOBALS['xoops']->getModuleConfig('octeta', 'xortify')."\n".
								_XOR_BAN_PHP_OCTETB.' '.$args[6]['octetb'].' > ' . $GLOBALS['xoops']->getModuleConfig('octetb', 'xortify')."\n".
								_XOR_BAN_PHP_OCTETC.' '.$args[6]['octetc'].' >= ' . $GLOBALS['xoops']->getModuleConfig('octetc', 'xortify'));
							
						$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY] = $log_handler->insert($log, true);
						XoopsCache::write('xortify_core_include_common_end', array('time'=>microtime(true)), $GLOBALS['xoops']->getModuleConfig('fault_delay', 'xortify'));
			
					}
						
				}
				unlinkOldCachefiles('xortify_',$GLOBALS['xoops']->getModuleConfig('xortify_ip_cache', 'xortify'));
				$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['_pass'] = true;
				XoopsCache::write('xortify_php_provider_'.sha1($args[5]['uname'].$args[5]['email'].(isset($args[5]['ip4'])?$args[5]['ip4']:"").(isset($args[5]['ip6'])?$args[5]['ip6']:"").(isset($args[5]['proxy-ip4'])?$args[5]['proxy-ip4']:"").(isset($args[5]['proxy-ip4'])?$args[5]['proxy-ip6']:"").$args[5]['network-addy']), array_merge($args[6], array('ipdata' => $args[5])), $GLOBALS['xoops']->getModuleConfig('xortify_seconds', 'xortify'));
			
			}
		}
	}
}

?>