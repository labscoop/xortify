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

$modversion = array();
$modversion['name']		    	= 'X-JSON Server';
$modversion['version']			= 1.47;
$modversion['releasedate'] 		= "Wednesday: 20 April 2011";
$modversion['status'] 			= "Stable";
$modversion['author'] 			= "Chronolabs Australia";
$modversion['credits'] 			= "Simon Roberts";
$modversion['teammembers'] 		= "Wishcraft";
$modversion['license'] 			= "GPL";
$modversion['official'] 		= 1;
$modversion['description']		= 'JSON API Server to exchange JSON Packages with external server.';
$modversion['help']		    	= "";
$modversion['image']			= "images/xjson_slogo.png";
$modversion['dirname']			= 'xjson';

// All tables should not have any prefix!
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

$modversion['author_realname'] = "Simon Roberts";
$modversion['author_website_url'] = "http://www.chronolabs.org.au";
$modversion['author_website_name'] = "Chronolabs International";
$modversion['author_email'] = "simon@labs.coop";
$modversion['demo_site_url'] = "";
$modversion['demo_site_name'] = "";
$modversion['support_site_url'] = "http://www.chronolabs.org.au/forums/x-json/0,10,0,0,100,0,DESC,0";
$modversion['support_site_name'] = "x-json";
$modversion['submit_bug'] = "http://www.chronolabs.org.au/forums/x-json/0,10,0,0,100,0,DESC,0";
$modversion['submit_feature'] = "http://www.chronolabs.org.au/forums/x-json/0,10,0,0,100,0,DESC,0";
$modversion['usenet_group'] = "sci.chronolabs";
$modversion['maillist_announcements'] = "";
$modversion['maillist_bugs'] = "";
$modversion['maillist_features'] = "";

// Tables created by sql file (without prefix!)
$modversion['tables'][0]	= 'json_tables';
$modversion['tables'][1]	= 'json_fields';
$modversion['tables'][2]	= 'json_plugins';

// Admin things
$modversion['hasAdmin']		= 1;
$modversion['adminindex']	= "admin/index.php";
$modversion['adminmenu']	= "admin/menu.php";

// Menu
$modversion['hasMain'] = 1;

// Smarty
$modversion['use_smarty'] = 0;

$i++;
$modversion['config'][$i]['name'] = 'site_user_auth';
$modversion['config'][$i]['title'] = '_XC_USERAUTH';
$modversion['config'][$i]['description'] = '_XC_USERAUTHDESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 1;

$i++;
$modversion['config'][$i]['name'] = 'lock_seconds';
$modversion['config'][$i]['title'] = '_XC_SECONDS';
$modversion['config'][$i]['description'] = '_XC_SECONDS_DESC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 180;
$modversion['config'][$i]['options'] = array(_XC_SECONDS_3600 => 3600, _XC_SECONDS_1800 => 1800, _XC_SECONDS_1200 => 1200, _XC_SECONDS_600 => 600,
											_XC_SECONDS_300 => 300, _XC_SECONDS_180 => 180, _XC_SECONDS_60 => 60, _XC_SECONDS_30 => 30);

$i++;
											
$modversion['config'][$i]['name'] = 'function_cache';
$modversion['config'][$i]['title'] = '_XC_FUNCTIONCACHE';
$modversion['config'][$i]['description'] = '_XC_FUNCTIONCACHE_DESC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 180;
$modversion['config'][$i]['options'] = array(_XC_SECONDS_3600 => 3600, _XC_SECONDS_1800 => 1800, _XC_SECONDS_1200 => 1200, _XC_SECONDS_600 => 600,
											_XC_SECONDS_300 => 300, _XC_SECONDS_180 => 180, _XC_SECONDS_60 => 60, _XC_SECONDS_30 => 30);
											
											
srand((((float)('0' . substr(microtime(), strpos(microtime(), ' ') + 1, strlen(microtime()) - strpos(microtime(), ' ') + 1))) * mt_rand(30, 99999)));
srand((((float)('0' . substr(microtime(), strpos(microtime(), ' ') + 1, strlen(microtime()) - strpos(microtime(), ' ') + 1))) * mt_rand(30, 99999)));											
$i++;
$modversion['config'][$i]['name'] = 'lock_random_seed';
$modversion['config'][$i]['title'] = '_XC_USERANDOMLOCK';
$modversion['config'][$i]['description'] = '_XC_USERANDOMLOCK_DESC';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = mt_rand(30, 170);
												
$i++;
$modversion['config'][$i]['name'] = 'cache_seconds';
$modversion['config'][$i]['title'] = '_XC_SECONDSCACHE';
$modversion['config'][$i]['description'] = '_XC_SECONDSCACHE_DESC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 3600;
$modversion['config'][$i]['options'] = array(_XC_SECONDS_3600 => 3600, _XC_SECONDS_1800 => 1800, _XC_SECONDS_1200 => 1200, _XC_SECONDS_600 => 600,
											_XC_SECONDS_300 => 300, _XC_SECONDS_180 => 180, _XC_SECONDS_60 => 60, _XC_SECONDS_30 => 30);											
?>
