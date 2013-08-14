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
 * Version:		3.10 Final (Stable)
 * Published:	Chronolabs
 * Download:	http://code.google.com/p/chronolabs
 * This File:	header.post.loader.php		
 * Description:	Xortify Header Post Loader provider for client
 * Date:		09/09/2012 19:34 AEST
 * License:		GNU3
 * 
 */
	
	if (isset($GLOBALS['xoDoSoap']))
	{
		
							
		require_once( _RUN_XORTIFY_ROOT_PATH . '/class/'._RUN_XORTIFY_PROTOCOL.'.php' );
		$func = strtoupper(_RUN_XORTIFY_PROTOCOL).'XortifyExchange';
		$apiExchange = new $func;
		$result = $apiExchange->retrieveBans();
				
		if (is_array($result)) {
		
			include_once _RUN_XORTIFY_ROOT_PATH.'/class/cache/cache.php';
			unlinkOldCachefiles('xortify_',_RUN_XORTIFY_IPCACHE);
			Cache::delete('xortify_bans_cache');
			Cache::delete('xortify_bans_cache_backup');			
			Cache::write('xortify_bans_cache', $result, _RUN_XORTIFY_IPCACHE);
			Cache::write('xortify_bans_cache_backup', $result, (_RUN_XORTIFY_IPCACHE * 1.45));				
		}		
	}
	
?>