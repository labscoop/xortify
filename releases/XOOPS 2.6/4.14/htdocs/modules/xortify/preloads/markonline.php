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
 * @file:			markonline.php		
 * @description:	Mode: Server - Preloader for handling bans.
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
 * Preloader of Marking Sessions.
 */
class XortifyMarkonlinePreload extends XoopsPreloadItem
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
		

		// Replace Constants
		define("PROT_SOAP", 'soap/');
		define("PROT_JSON", 'json/');
		define("PROT_CURL", 'curl/');
		define("PROT_CURLSERIALISED", 'serial/');
		define("PROT_CURLXML", 'xml/');
		define("PROT_WGETSERIAL", 'serial/');
		define("PROT_WGETXML", 'xml/');
		
		// Config Constants
		define("CONF_SOAP", 'xortify_urisoap');
		define("CONF_JSON", 'xortify_urijson');
		define("CONF_CURL", 'xortify_uricurl');
		define("CONF_CURLSERIALISED", 'xortify_uriserial');
		define("CONF_CURLXML", 'xortify_urixml');
		define("CONF_WGETSERIAL", 'xortify_uriserial');
		define("CONF_WGETXML", 'xortify_urixml');
			
		xoops_load('cache');
		
		xoops_loadLanguage('modinfo', 'xortify');
		$member_handler = $GLOBALS['xoops']->getHandler('member');
		$module_handler = $GLOBALS['xoops']->getHandler('module');
		$config_handler = $GLOBALS['xoops']->getHandler('config');		
		$GLOBALS['xortifyModule'] = $module_handler->getByDirname('xortify');
		if (is_object($GLOBALS['xortifyModule'])) {
			$GLOBALS['xortifyModuleConfig'] = $config_handler->getConfigList($GLOBALS['xortifyModule']->getVar('mid'));
		}
		
		require_once( XOOPS_ROOT_PATH.'/modules/xortify/class/'.$GLOBALS['xortifyModuleConfig']['protocol'].'.php' );
		$func = strtoupper($GLOBALS['xortifyModuleConfig']['protocol']).'XortifyExchange';
			
		$result = XoopsCache::read('markonline_bounce_last');
		if ((isset($result['when'])?(float)$result['when']:-microtime(true))+$GLOBALS['xortifyModuleConfig']['bounce']<=microtime(true)) {
			$result = array();
			$result['when'] = microtime(true);
			$servers_handler = xoops_getmodulehandler('servers', 'xortify');
			$criteria = new Criteria('`online`', true);
			$criteria->setSort('RAND()');
			$criteria->setOrder('ASC');
			if ($servers = $servers_handler->getObjects($criteria, true)&&$servers_handler->getCount($criteria)>0) {
				foreach($servers as $sid => $server) {
					if (!$data = XoopsCache::read('server_bounce_'.$server->getVar('sid'))) {
						foreach($servers_handler->getObjects($criteria, true) as $id => $srv) {
							$GLOBALS['xortifyModuleConfig'][constant('CONF_'.strtoupper($GLOBALS['xortifyModuleConfig']['protocol']))] = str_replace($srv->getVar('replace'), constant('PROT_'.strtoupper($GLOBALS['xortifyModuleConfig']['protocol'])), $srv->getVar('server'));
							$soapExchg = new $func;							
							$soapExchg->markOnline();
						}
					}
				}
			}
			$result['took'] = microtime(true)-$result['when'];
			XoopsCache::write('markonline_bounce_last', $result, $GLOBALS['xortifyModuleConfig']['bounce']*2);
		}
	}
}

?>