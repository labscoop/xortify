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
 * @copyright:		Chronolabs Cooperative 2013 � Simon Roberts (www.simonaroberts.com)
 * @download:		http://sourceforge.net/projects/xortify
 * @file:			footer.post.loader.php		
 * @description:	Post Loader Routines for Xortify Provider
 * @date:			09/09/2012 19:34 AEST
 * @license:		GNU3 (http://web.labs.coop/public/legal/general-public-licence/13,3.html)
 * @package			xortify
 * @subpackage		security-providers
 * @author			Simon A. Roberts - wishcraft (simon@labs.coop)

 * 
 */

	$GLOBALS['xoops'] = Xoops::getInstance();
	
	if (isset($GLOBALS['xoDoSoap']))
	{
		
		require_once( $xoops->path('/modules/xortify/class/'.$GLOBALS['xoops']->getModuleConfig('protocol', 'xortify').'.php') );
		$func = strtoupper($GLOBALS['xoops']->getModuleConfig('protocol', 'xortify')).'XortifyExchange';
		$soapExchg = new $func;
		$result = $soapExchg->retrieveBans();
				
		if (is_array($result)) {
		
			XoopsLoad::load('xoopscache');
			if (!class_exists('XoopsCache')) {
				// XOOPS 2.4 Compliance
				XoopsLoad::load('cache');
				if (!class_exists('XoopsCache')) {
					include_once $xoops->root_path.'/class/cache/xoopscache.php';
				}
			}
					
			XoopsCache::delete('xortify_bans_cache');
			XoopsCache::delete('xortify_bans_cache_backup');			
			XoopsCache::write('xortify_bans_cache', $result, $GLOBALS['xoops']->getModuleConfig('xortify_seconds', 'xortify'));
			XoopsCache::write('xortify_bans_cache_backup', $result, ($GLOBALS['xoops']->getModuleConfig('xortify_seconds', 'xortify') * 1.45));			
		}		
	}
	
?>