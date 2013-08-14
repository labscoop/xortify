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
 * @subpackage		JSON
 * @author         	Simon Roberts (wishcraft) - meshy@labs.coop
 */

define('_XC_WDSL','Allow WDSL json Server');
define('_XC_WDSLDESC','Turn on and off WDSL Services');
define('_XC_USERAUTH','Requires Username');
define('_XC_USERAUTHDESC','Only Allows Site Users to use services');

define('_XC_ADMINMENU_1','Configure Tables');
define('_XC_ADMINMENU_2','Configure Fields');
define('_XC_ADMINMENU_3','Configure Views');	
define('_XC_ADMINMENU_4','Configure Plugins');
define('_XC_ADMINMENU_5','Permissions');		

define('_XC_PERMISSIONSVIEWMAN','Function Call Permissions');
define('_XC_VIEW_FUNCTION','Functions');
define('_XC_NOPERMSSET','No Permissions Set');

define('_XC_VIEWSFOR','Views for');
define('_XC_FIELDOPTIONSFOR','Field Options for');
define('_XC_SELECTTABLE','Select Table');
define('_XC_PLUGINAVAILABLE','Plugin\'s Available');
define('_XC_TABLESAVAILABLE','Tables\'s Available from ');

define('_XC_WSDLSUCCESSFUL','Database Updated - Complete Compile of WSDL');					
define('_XC_WSDLUNSUCCESSFUL','Database Updated - Error Compiling WSDL');
define('_XC_DATABASEUPDATED','Database Updated');						

define('_XC_SECONDS', 'Function lock out time');
define('_XC_SECONDS_DESC', 'Period of time for locked function to be invocated on incorrect username and password details!!');

define('_XC_SECONDSCACHE', 'Lockout cache stored for');
define('_XC_SECONDSCACHE_DESC', 'Period of time for cache function to be invocated!');

define('_XC_FUNCTIONSCACHE', 'Function calls are cached for');
define('_XC_FUNCTIONSCACHE_DESC', 'Period of time for cache function to be invocated!');

define('_XC_SECONDS_3600', '1 Hour');
define('_XC_SECONDS_1800', '30 minutes');
define('_XC_SECONDS_1200', '20 minutes');
define('_XC_SECONDS_600', '10 minutes');
define('_XC_SECONDS_300', '5 Minutes');
define('_XC_SECONDS_180', '3 Minutes');
define('_XC_SECONDS_60', '1 Minute');
define('_XC_SECONDS_30', '30 Seconds');	

define('_XC_USERANDOMLOCK', 'Random seconds seed (maximum value)');
define('_XC_USERANDOMLOCK_DESC', 'You should not set this above the maximum function lockout time!');
?>
