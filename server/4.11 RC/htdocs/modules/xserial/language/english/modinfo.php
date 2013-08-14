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
 * @subpackage		Serialised
 * @author         	Simon Roberts (wishcraft) - meshy@labs.coop
 */

define('_XSERIAL_WDSL','Allow WDSL serial Server');
define('_XSERIAL_WDSLDESC','Turn on and off WDSL Services');
define('_XSERIAL_USERAUTH','Requires Username');
define('_XSERIAL_USERAUTHDESC','Only Allows Site Users to use services');

define('_XSERIAL_ADMINMENU_1','Configure Tables');
define('_XSERIAL_ADMINMENU_2','Configure Fields');
define('_XSERIAL_ADMINMENU_3','Configure Views');	
define('_XSERIAL_ADMINMENU_4','Configure Plugins');
define('_XSERIAL_ADMINMENU_5','Permissions');		

define('_XSERIAL_PERMISSIONSVIEWMAN','Function Call Permissions');
define('_XSERIAL_VIEW_FUNCTION','Functions');
define('_XSERIAL_NOPERMSSET','No Permissions Set');

define('_XSERIAL_VIEWSFOR','Views for');
define('_XSERIAL_FIELDOPTIONSFOR','Field Options for');
define('_XSERIAL_SELECTTABLE','Select Table');
define('_XSERIAL_PLUGINAVAILABLE','Plugin\'s Available');
define('_XSERIAL_TABLESAVAILABLE','Tables\'s Available from ');

define('_XSERIAL_WSDLSUCCESSFUL','Database Updated - Complete Compile of WSDL');					
define('_XSERIAL_WSDLUNSUCCESSFUL','Database Updated - Error Compiling WSDL');
define('_XSERIAL_DATABASEUPDATED','Database Updated');						

define('_XSERIAL_SECONDS', 'Function lock out time');
define('_XSERIAL_SECONDS_DESC', 'Period of time for locked function to be invocated on incorrect username and password details!!');

define('_XSERIAL_SECONDSCACHE', 'Lockout cache stored for');
define('_XSERIAL_SECONDSCACHE_DESC', 'Period of time for cache function to be invocated!');
	
define('_XSERIAL_FUNCTIONSCACHE', 'Function calls are cached for');
define('_XSERIAL_FUNCTIONSCACHE_DESC', 'Period of time for cache function to be invocated!');

define('_XSERIAL_SECONDS_3600', '1 Hour');
define('_XSERIAL_SECONDS_1800', '30 minutes');
define('_XSERIAL_SECONDS_1200', '20 minutes');
define('_XSERIAL_SECONDS_600', '10 minutes');
define('_XSERIAL_SECONDS_300', '5 Minutes');
define('_XSERIAL_SECONDS_180', '3 Minutes');
define('_XSERIAL_SECONDS_60', '1 Minute');
define('_XSERIAL_SECONDS_30', '30 Seconds');	

define('_XSERIAL_USERANDOMLOCK', 'Random seconds seed (maximum value)');
define('_XSERIAL_USERANDOMLOCK_DESC', 'You should not set this above the maximum function lockout time!');
?>
