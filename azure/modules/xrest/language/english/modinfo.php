<?php
/**
 * Xortify API Function
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
 * @package         api
 * @subpackage		REST
 * @author         	Simon Roberts (wishcraft) - meshy@labs.coop
 */


define('_XREST_MI_USERAUTH','Requires Username');
define('_XREST_MI_USERAUTHDESC','Only Allows Site Users to use services');

define('_XREST_MI_ADMINMENU_0','Dashboard');
define('_XREST_MI_ADMINMENU_1','Configure Tables');
define('_XREST_MI_ADMINMENU_2','Configure Fields');
define('_XREST_MI_ADMINMENU_3','Configure Views');	
define('_XREST_MI_ADMINMENU_4','Configure Plugins');
define('_XREST_MI_ADMINMENU_5','Permissions');
define('_XREST_MI_ADMINMENU_6','About');		

define('_XREST_MI_SECONDS', 'Function lock out time');
define('_XREST_MI_SECONDS_DESC', 'Period of time for locked function to be invocated on incorrect username and password details!!');

define('_XREST_MI_SECONDSCACHE', 'Lockout cache stored for');
define('_XREST_MI_SECONDSCACHE_DESC', 'Period of time for cache function to be invocated!');

define('_XREST_MI_SECONDS_3600', '1 Hour');
define('_XREST_MI_SECONDS_1800', '30 minutes');
define('_XREST_MI_SECONDS_1200', '20 minutes');
define('_XREST_MI_SECONDS_600', '10 minutes');
define('_XREST_MI_SECONDS_300', '5 Minutes');
define('_XREST_MI_SECONDS_180', '3 Minutes');
define('_XREST_MI_SECONDS_60', '1 Minute');
define('_XREST_MI_SECONDS_30', '30 Seconds');	

define('_XREST_MI_USERANDOMLOCK', 'Random seconds seed (maximum value)');
define('_XREST_MI_USERANDOMLOCK_DESC', 'You should not set this above the maximum function lockout time!');

// Version 1.52
define('_XREST_MI_SECONDS_LIST_CACHE', 'Plugin Function cache stored for');
define('_XREST_MI_SECONDS_LIST_CACHE_DESC', 'Period of time for Plugin function cache is to be stored for!');
define('_XREST_MI_SECONDS_TO_CLEANUP', 'Number of seconds between cache cleanup?');
define('_XREST_MI_SECONDS_TO_CLEANUP_DESC', 'Period of time for cleanup of cache function to wait before dropping relevant cache files!');

define('_XREST_MI_SECONDS_38707200', '1 Year');
define('_XREST_MI_SECONDS_19353600', '6 Months');
define('_XREST_MI_SECONDS_9676800', '1 Quarter');
define('_XREST_MI_SECONDS_2419200', '1 Month');
define('_XREST_MI_SECONDS_1209600', '1 Fortnight');
define('_XREST_MI_SECONDS_604800', '1 Week');
define('_XREST_MI_SECONDS_86400', '24 Hours');
define('_XREST_MI_SECONDS_43200', '12 hours');

define('_XREST_MI_NOPERMFORPLUGIN', 'No Permission for plug-in');
	
?>
