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
 * @file:			stopforumspam.php		
 * @description:	Plugin for expanding and doing routing on banning.
 * @date:			26/07/2013 19:40 AEST
 * @license:		GNU3 (http://web.labs.coop/public/legal/general-public-licence/13,3.html)
 * @package			xortify
 * @subpackage		plugin
 * @author			Simon A. Roberts - wishcraft (simon@labs.coop)

 * 
 */

/*
 * Plugin Class for extending functions from logging class
 */
class XortifyPluginStopforumspam_com {
	
	/*
	 * Banned Item Pre Hook
	 * 
	 * @param boolean $default		Default Value
	 * @param XortifyLog $log		Current logging item calling
	 * @return boolean
	 */
	function BannedPreHook($default, XortifyLog $log) {
		return $default;
	}
	
	/*
	 * Blocked item Pre Hook
	 * 
	 * @param boolean $default		Default Value
	 * @param XortifyLog $log		Current logging item calling
	 * @return boolean
	 */
	function BlockedPreHook($default, XortifyLog $log) {
		return $default;
	}

	/*
	 * Monitored Item pre Hook
	 * 
	 * @param boolean $default		Default Value
	 * @param XortifyLog $log		Current logging item calling
	 * @return boolean
	 */
	function MonitoredPreHook($default, XortifyLog $log) {
		return $default;
	}
	
	/*
	 * Banned Item Post Hook
	 * 
	 * @param XortifyLog $log		Current logging item calling
	 * @param integer $lid			Logging Item ID
	 * @return integer
	 */
	function BannedPostHook(XortifyLog $log, $lid) {
		return $lid;
	}
	
	/*
	 * Blocked Item Post Hook
	 * 
	 * @param XortifyLog $log		Current logging item calling
	 * @param integer $lid			Logging Item ID
	 * @return integer
	 */
	function BlockedPostHook(XortifyLog $log, $lid) {
		return $lid;
	}

	/*
	 * Monitored PostHook
	 * 
	 * @param XortifyLog $log		Current logging item calling
	 * @param integer $lid			Logging Item ID
	 * @return integer
	 */
	function MonitoredPostHook(XortifyLog $log, $lid) {
		return $lid;
	}

}
?>