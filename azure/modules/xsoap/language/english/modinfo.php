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
 * @subpackage		SOAP
 * @author         	Simon Roberts (wishcraft) - meshy@labs.coop 
 */

define('_XS_WDSL','Allow WDSL Soap Server');
define('_XS_WDSLDESC','Turn on and off WDSL Services');
define('_XS_USERAUTH','Requires Username');
define('_XS_USERAUTHDESC','Only Allows Site Users to use services');

define('_XS_ADMINMENU_1','Configure Tables');
define('_XS_ADMINMENU_2','Configure Fields');
define('_XS_ADMINMENU_3','Configure Views');	
define('_XS_ADMINMENU_4','Configure Plugins');
define('_XS_ADMINMENU_5','Permissions');		

define('_XS_PERMISSIONSVIEWMAN','Function Call Permissions');
define('_XS_VIEW_FUNCTION','Functions');
define('_XS_NOPERMSSET','No Permissions Set');

define('_XS_VIEWSFOR','Views for');
define('_XS_FIELDOPTIONSFOR','Field Options for');
define('_XS_SELECTTABLE','Select Table');
define('_XS_PLUGINAVAILABLE','Plugin\'s Available');
define('_XS_TABLESAVAILABLE','Tables\'s Available from ');

define('_XS_WSDLSUCCESSFUL','Database Updated - Complete Compile of WSDL');					
define('_XS_WSDLUNSUCCESSFUL','Database Updated - Error Compiling WSDL');
define('_XS_DATABASEUPDATED','Database Updated');						

define('_XS_SECONDS', 'Function lock out time');
define('_XS_SECONDS_DESC', 'Period of time for locked function to be invocated on incorrect username and password details!!');

define('_XS_SECONDSCACHE', 'Lockout cache stored for');
define('_XS_SECONDSCACHE_DESC', 'Period of time for cache function to be invocated!');
	
define('_XS_SECONDS_3600', '1 Hour');
define('_XS_SECONDS_1800', '30 minutes');
define('_XS_SECONDS_1200', '20 minutes');
define('_XS_SECONDS_600', '10 minutes');
define('_XS_SECONDS_300', '5 Minutes');
define('_XS_SECONDS_180', '3 Minutes');
define('_XS_SECONDS_60', '1 Minute');
define('_XS_SECONDS_30', '30 Seconds');	

define('_XS_USERANDOMLOCK', 'Random seconds seed (maximum value)');
define('_XS_USERANDOMLOCK_DESC', 'You should not set this above the maximum function lockout time!');
?>
