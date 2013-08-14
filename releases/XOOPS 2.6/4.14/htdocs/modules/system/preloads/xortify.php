<?php
/*
 * Prevents Spam, Harvesting, Human Rights Abuse, Captcha Abuse etc.
 * basic statistic of them in XOOPS Copyright (C) 2012 Simon Roberts 
 * Contact: wishcraft - simon@chronolabs.com.au
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
 * @version:		4.11 Final (Stable)
 * @copyright:		Chronolabs Cooperative 2013 © Simon Roberts (www.simonaroberts.com)
 * @download:		http://sourceforge.net/projects/xortify
 * @file:			unbanner.php		
 * @description:	Mode: Any - Preloader for handling checking for service and re-enabling services.
 * @date:			26/07/2013 19:40 AEST
 * @license:		GNU3 (http://web.labs.coop/public/legal/general-public-licence/13,3.html)
 * @package			xortify
 * @subpackage		preloaders
 * @author			Simon A. Roberts - wishcraft (simon@labs.coop)
 * 
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/*
 * Xortify System Preloader
 */
class SystemXortifyPreload extends XoopsPreloadItem
{
	/*
	 * Check if services are available and enable Xortify Client
	 */
	function eventCoreFooterEnd($args)
	{
		// Runs Security Preloader
		include_once XOOPS_ROOT_PATH.'/class/cache/xoopscache.php';
		$result = XoopsCache::read('xortify_system_footer_end');
		if ((isset($result['time'])?(float)$result['time']:0)<=microtime(true)) {
			XoopsCache::write('xortify_system_footer_end', array('time'=>microtime(true)+3600*24*1.75), 3600*24*1.75);
			include_once XOOPS_ROOT_PATH . ( '/modules/xortify/cron/serverup.php' );
		}
	}
}

?>