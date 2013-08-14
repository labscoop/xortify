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
 * @subpackage		xml
 * @author         	Simon Roberts (wishcraft) - meshy@labs.coop
 */ 

define('_XXMLC_USERAUTH','Requires Username');
define('_XXMLC_USERAUTHDESC','Only Allows Site Users to use services');

define('_XXMLC_ADMINMENU_1','Configure Tables');
define('_XXMLC_ADMINMENU_2','Configure Fields');
define('_XXMLC_ADMINMENU_3','Configure Views');	
define('_XXMLC_ADMINMENU_4','Configure Plugins');
define('_XXMLC_ADMINMENU_5','Permissions');		

define('_XXMLC_PERMISSIONSVIEWMAN','Function Call Permissions');
define('_XXMLC_VIEW_FUNCTION','Functions');
define('_XXMLC_NOPERMSSET','No Permissions Set');

define('_XXMLC_VIEWSFOR','Views for');
define('_XXMLC_FIELDOPTIONSFOR','Field Options for');
define('_XXMLC_SELECTTABLE','Select Table');
define('_XXMLC_PLUGINAVAILABLE','Plugin\'s Available');
define('_XXMLC_TABLESAVAILABLE','Tables\'s Available from ');

define('_XXMLC_WSDLSUCCESSFUL','Database Updated - Complete Compile of WSDL');					
define('_XXMLC_WSDLUNSUCCESSFUL','Database Updated - Error Compiling WSDL');
define('_XXMLC_DATABASEUPDATED','Database Updated');						

define('_XXMLC_SECONDS', 'Function lock out time');
define('_XXMLC_SECONDS_DESC', 'Period of time for locked function to be invocated on incorrect username and password details!!');

define('_XXMLC_SECONDSCACHE', 'Lockout cache stored for');
define('_XXMLC_SECONDSCACHE_DESC', 'Period of time for cache function to be invocated!');

define('_XXMLC_FUNCTIONSCACHE', 'Function calls are cached for');
define('_XXMLC_FUNCTIONSCACHE_DESC', 'Period of time for cache function to be invocated!');

define('_XXMLC_SECONDS_3600', '1 Hour');
define('_XXMLC_SECONDS_1800', '30 minutes');
define('_XXMLC_SECONDS_1200', '20 minutes');
define('_XXMLC_SECONDS_600', '10 minutes');
define('_XXMLC_SECONDS_300', '5 Minutes');
define('_XXMLC_SECONDS_180', '3 Minutes');
define('_XXMLC_SECONDS_60', '1 Minute');
define('_XXMLC_SECONDS_30', '30 Seconds');	

define('_XXMLC_USERANDOMLOCK', 'Random seconds seed (maximum value)');
define('_XXMLC_USERANDOMLOCK_DESC', 'You should not set this above the maximum function lockout time!');
?>
