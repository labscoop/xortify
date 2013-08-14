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

$modversion['name']		    	= 'X-XML Server';
$modversion['version']			= 1.53;
$modversion['releasedate'] 		= "Wednesday: 20 April 2011";
$modversion['status'] 			= "Stable";
$modversion['author'] 			= "Chronolabs Australia";
$modversion['credits'] 			= "Simon Roberts";
$modversion['teammembers'] 		= "Wishcraft";
$modversion['license'] 			= "GPL";
$modversion['official'] 		= 1;
$modversion['description']		= 'XML API Server to exchange XML Packages with external server.';
$modversion['help']		    	= "";
$modversion['image']			= "images/xxml_slogo.png";
$modversion['dirname']			= 'xxml';

// All tables should not have any prefix!
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

$modversion['author_realname'] = "Simon Roberts";
$modversion['author_website_url'] = "http://www.chronolabs.coop";
$modversion['author_website_name'] = "Chronolabs International";
$modversion['author_email'] = "simon@labs.coop";
$modversion['usenet_group'] = "sci.chronolabs";
$modversion['maillist_announcements'] = "";
$modversion['maillist_bugs'] = "";
$modversion['maillist_features'] = "";

// Tables created by sql file (without prefix!)
$modversion['tables'][0]	= 'xml_tables';
$modversion['tables'][1]	= 'xml_fields';
$modversion['tables'][2]	= 'xml_plugins';

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
$modversion['config'][$i]['title'] = '_XXMLC_USERAUTH';
$modversion['config'][$i]['description'] = '_XXMLC_USERAUTHDESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 1;

$i++;
											
$modversion['config'][$i]['name'] = 'function_cache';
$modversion['config'][$i]['title'] = '_XXMLC_FUNCTIONCACHE';
$modversion['config'][$i]['description'] = '_XXMLC_FUNCTIONCACHE_DESC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 180;
$modversion['config'][$i]['options'] = array(_XXMLC_SECONDS_3600 => 3600, _XXMLC_SECONDS_1800 => 1800, _XXMLC_SECONDS_1200 => 1200, _XXMLC_SECONDS_600 => 600,
											_XXMLC_SECONDS_300 => 300, _XXMLC_SECONDS_180 => 180, _XXMLC_SECONDS_60 => 60, _XXMLC_SECONDS_30 => 30);
											
$i++;
$modversion['config'][$i]['name'] = 'lock_seconds';
$modversion['config'][$i]['title'] = '_XXMLC_SECONDS';
$modversion['config'][$i]['description'] = '_XXMLC_SECONDS_DESC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 180;
$modversion['config'][$i]['options'] = array(_XXMLC_SECONDS_3600 => 3600, _XXMLC_SECONDS_1800 => 1800, _XXMLC_SECONDS_1200 => 1200, _XXMLC_SECONDS_600 => 600,
											_XXMLC_SECONDS_300 => 300, _XXMLC_SECONDS_180 => 180, _XXMLC_SECONDS_60 => 60, _XXMLC_SECONDS_30 => 30);

srand((((float)('0' . substr(microtime(), strpos(microtime(), ' ') + 1, strlen(microtime()) - strpos(microtime(), ' ') + 1))) * mt_rand(30, 99999)));
srand((((float)('0' . substr(microtime(), strpos(microtime(), ' ') + 1, strlen(microtime()) - strpos(microtime(), ' ') + 1))) * mt_rand(30, 99999)));											
$i++;
$modversion['config'][$i]['name'] = 'lock_random_seed';
$modversion['config'][$i]['title'] = '_XXMLC_USERANDOMLOCK';
$modversion['config'][$i]['description'] = '_XXMLC_USERANDOMLOCK_DESC';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = mt_rand(30, 170);
												
$i++;
$modversion['config'][$i]['name'] = 'cache_seconds';
$modversion['config'][$i]['title'] = '_XXMLC_SECONDSCACHE';
$modversion['config'][$i]['description'] = '_XXMLC_SECONDSCACHE_DESC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 3600;
$modversion['config'][$i]['options'] = array(_XXMLC_SECONDS_3600 => 3600, _XXMLC_SECONDS_1800 => 1800, _XXMLC_SECONDS_1200 => 1200, _XXMLC_SECONDS_600 => 600,
											_XXMLC_SECONDS_300 => 300, _XXMLC_SECONDS_180 => 180, _XXMLC_SECONDS_60 => 60, _XXMLC_SECONDS_30 => 30);											
?>
