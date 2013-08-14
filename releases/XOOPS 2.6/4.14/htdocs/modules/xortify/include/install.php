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
 * @file:			install.php		
 * @description:	Install routine for Xortify.
 * @date:			09/09/2012 19:34 AEST
 * @license:		GNU3 (http://web.labs.coop/public/legal/general-public-licence/13,3.html)
 * @package			xortify
 * @subpackage		functions
 * @author			Simon A. Roberts - wishcraft (simon@labs.coop)

 * 
 */

include_once dirname(__FILE__) . DS . 'instance.php';

/*
 * Function for handingling installation of Xortify
 * 
 * @param XoopsModule $module		Module Object
 * @return boolean
 */
function xoops_module_pre_install_xortify(&$module) {
	xoops_load("xoopscache");	
	XoopsCache::write('xortify_bans_cache', array());
	XoopsCache::write('xortify_bans_cache_backup', array());	
	
	if (defined('XORTIFY_INSTANCE_KEY'))
		if (strlen(constant('XORTIFY_INSTANCE_KEY'))==0 ||
			constant('XORTIFY_INSTANCE_KEY')=='00000-00000-00000-00000-00000') {
			include_once $GLOBALS['xoops']->path('/modules/xortify/include/functions.php');
			$key = md5(microtime(true) . XOOPS_ROOT_PATH . XOOPS_VAR_PATH . XOOPS_DB_HOST . XOOPS_DB_NAME . XOOPS_DB_PREFIX . XOOPS_DB_USER . XOOPS_DB_PASS);
			$start = mt_rand(0, 31-25);
			$instance = "";
			$pos=0;
			for($i=0;$i<25;$i++) {
				$instance .= mt_rand(0,3)<2?strtolower($key[$start+$i]):strtoupper($key[$start+$i]);
				$pos++;
				if ($pos==5&&$i<24) {
					$pos=0;
					$instance .= '-';
				}
			}
			if (writeInstanceKey($instance)==false) {
				xoops_error(_XOR_MI_XORTIFY_INSTANCE_KEY_WASNOTWRITTEN);
			}
		} 

	$module_handler = $GLOBALS['xoops']->getHandler('module');
	$config_handler = $GLOBALS['xoops']->getHandler('config');
	$configitem_handler = $GLOBALS['xoops']->getHandler('configitem');
	if (!isset($GLOBALS['protector']))
		$GLOBALS['protector'] = $module_handler->getByDirname('protector');
	
	if (isset($GLOBALS['protector'])&&is_object($GLOBALS['protector'])&&!empty($GLOBALS['protector'])) {
	
		$criteria = new CriteriaCompo(new Criteria('conf_modid', $GLOBALS['protector']->getVar('mid')));
		$criteria->add(new Criteria('conf_name', 'contami_action'));
		$GLOBALS['protectorConfig'] = $configitem_handler->getObjects($criteria);
		if (isset($GLOBALS['protectorConfig'][0])) {
			$GLOBALS['protectorConfig'][0]->setVar('conf_value', 15);
			$configitem_handler->insert($GLOBALS['protectorConfig'][0], true);
		}
	
	
		$criteria = new CriteriaCompo(new Criteria('conf_modid', $GLOBALS['protector']->getVar('mid')));
		$criteria->add(new Criteria('conf_name', 'isocom_action'));
		$GLOBALS['protectorConfig'] = $configitem_handler->getObjects($criteria);
		if (isset($GLOBALS['protectorConfig'][0])) {
			$GLOBALS['protectorConfig'][0]->setVar('conf_value', 15);
			$configitem_handler->insert($GLOBALS['protectorConfig'][0], true);
		}
	
	
		$criteria = new CriteriaCompo(new Criteria('conf_modid', $GLOBALS['protector']->getVar('mid')));
		$criteria->add(new Criteria('conf_name', 'union_action'));
		$GLOBALS['protectorConfig'] = $configitem_handler->getObjects($criteria);
		if (isset($GLOBALS['protectorConfig'][0])) {
			$GLOBALS['protectorConfig'][0]->setVar('conf_value', 15);
			$configitem_handler->insert($GLOBALS['protectorConfig'][0], true);
		}
	
	
		$criteria = new CriteriaCompo(new Criteria('conf_modid', $GLOBALS['protector']->getVar('mid')));
		$criteria->add(new Criteria('conf_name', 'dos_craction'));
		$GLOBALS['protectorConfig'] = $configitem_handler->getObjects($criteria);
		if (isset($GLOBALS['protectorConfig'][0])) {
			$GLOBALS['protectorConfig'][0]->setVar('conf_value', 'bip');
			$configitem_handler->insert($GLOBALS['protectorConfig'][0], true);
		}
	
		$criteria = new CriteriaCompo(new Criteria('conf_modid', $GLOBALS['protector']->getVar('mid')));
		$criteria->add(new Criteria('conf_name', 'stopforumspam_action'));
		$GLOBALS['protectorConfig'] = $configitem_handler->getObjects($criteria);
		if (isset($GLOBALS['protectorConfig'][0])) {
			$GLOBALS['protectorConfig'][0]->setVar('conf_value', 'bip');
			$configitem_handler->insert($GLOBALS['protectorConfig'][0], true);
		}
	}
	
	return true;				
}

?>