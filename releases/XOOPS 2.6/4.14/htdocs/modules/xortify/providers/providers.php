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
 * Version:		4.11 Final (Stable)
 * Published:	Chronolabs
 * Download:	http://sourceforge.net/projects/xortify
 * This File:	providers.php		
 * Description:	Hooking Stratum Class for Security Provider Classes
 * Date:		09/09/2012 19:34 AEST
 * License:		GNU3
 * 
 */

if (!defined('XOOPS_ROOT_PATH')) die ('Restricted Access');

$GLOBALS['xoops'] = Xoops::getInstance();
$GLOBALS['xoops']->loadLocale();

/*
 * Provider Class for Security Module APIs this class wraps the method
 * of attack in the system for a security provider like protector.
 */
class Providers 
{
	/*
	 * List of the providers as per sub folder of this class
	 */
	var $providers = array();

	/*
	 * Intialisation of Provider Class
	 */
	function init($check) {
		$GLOBALS['xoops'] = Xoops::getInstance();
	}
		
	/*
	 * constructor
	 */
	function Providers($check = 'precheck')
	{
		$this->init($check);
		if (isset($GLOBALS['xoops']->getModuleConfig('xortify_providers', 'xortify')))
			$this->providers = $GLOBALS['xoops']->getModuleConfig('xortify_providers', 'xortify');
		else
			$this->providers = array();
		$this->$check();
	}
	
	/*
	 * Routine that fires all the security devices prechecks.
	 */
	private function precheck()
	{
		if (!isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['module']))
			return false;
		if ($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['module']->getVar('version')<302)
			return false;
		ob_start();
		foreach($this->providers as $id => $key)
		switch ($key) {
		default:
			
			if (file_exists(XOOPS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/precheck.inc.php')) 
				include_once(XOOPS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/precheck.inc.php');
			if (file_exists(XOOPS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/pre.loader.php')) 
				include_once(XOOPS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/pre.loader.php');
			
		}
		ob_end_clean();
	}
	
	/*
	 * Routine that fires all security devices postchecks
	 */
	private function postcheck()
	{
		if (!isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['module']))
			return false;
		if ($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['module']->getVar('version')<302)
			return false;
		ob_start();
		foreach($this->providers as $id => $key)
		switch ($key) {
		default:
			
			if (file_exists(XOOPS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/postcheck.inc.php'))
				include_once(XOOPS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/postcheck.inc.php');
			if (file_exists(XOOPS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/post.loader.php'))
				include_once(XOOPS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/post.loader.php');
		}
		ob_end_clean();
	}

	/*
	 * Security Header Post check are fired by this provider routine
	 */
	private function headerpostcheck()
	{
		if (!isset($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['module']))
			return false;
		if ($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['module']->getVar('version')<302)
			return false;
		ob_start();
		foreach($this->providers as $id => $key)
		switch ($key) {
		default:
			
			if (file_exists(XOOPS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/headerpostcheck.inc.php'))
				include_once(XOOPS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/headerpostcheck.inc.php');
			if (file_exists(XOOPS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/header.post.loader.php'))
				include_once(XOOPS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/header.post.loader.php');
			
		}
		ob_end_clean();
	}
	
	/*
	 * Loading of the footer loads this post check provider of security devices
	 */
	private function footerpostcheck()
	{
		if ($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['module']->getVar('version')<302)
			return false;
		ob_start();
		foreach($this->providers as $id => $key)
		switch ($key) {
		default:
			
			if (file_exists(XOOPS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/footerpostcheck.inc.php'))
				include_once(XOOPS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/footerpostcheck.inc.php');
			if (file_exists(XOOPS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/footer.post.loader.php'))
				include_once(XOOPS_ROOT_PATH.'/modules/xortify/providers/'.$key.'/footer.post.loader.php');
			
		}
		ob_end_clean();
	}
}

?>