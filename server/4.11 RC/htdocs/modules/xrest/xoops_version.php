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


$modversion['name']		    	= 'X-REST API Server';
$modversion['version']			= 1.53;
$modversion['releasedate'] 		= "Saturday: 02 March 2013";
$modversion['status'] 			= "Mature";
$modversion['author'] 			= "Chronolabs Cooperative";
$modversion['credits'] 			= "Simon Roberts";
$modversion['teammembers'] 		= "Wishcraft";
$modversion['license'] 			= "GPL";
$modversion['official'] 		= 1;
$modversion['description']		= 'REST API Service to exchange JSON, Serialised or XML Packages with external server.';
$modversion['help']		    	= "";
$modversion['image']			= "images/xrest_slogo.png";
$modversion['dirname']			= 'xrest';
$modversion['website'] 			= "www.chronolabs.com.au";

$modversion['dirmoduleadmin'] = 'Frameworks/moduleclasses';
$modversion['icons16'] = 'Frameworks/moduleclasses/icons/16';
$modversion['icons32'] = 'Frameworks/moduleclasses/icons/32';

$modversion['moduleadmin'] = 'modules/xrest/admin';
$modversion['modicons16'] = 'modules/xrest/images/icons/16';
$modversion['modicons32'] = 'modules/xrest/images/icons/32';

$modversion['release_info'] = "Stable 2012/01/09";
$modversion['release_file'] = XOOPS_URL."/modules/xrest/docs/changelog.txt";
$modversion['release_date'] = "2012/01/09";

$modversion['author_realname'] = "Simon Roberts";
$modversion['author_website_url'] = "http://www.chronolabs.com.au";
$modversion['author_website_name'] = "Chronolabs Cooperative";
$modversion['author_email'] = "simon@labs.coop";
$modversion['demo_site_url'] = "";
$modversion['demo_site_name'] = "";
$modversion['support_site_url'] = "http://www.chronolabs.com.au/forums/";
$modversion['support_site_name'] = "x-rest";
$modversion['submit_bug'] = "http://www.chronolabs.com.au/forums/";
$modversion['submit_feature'] = "http://www.chronolabs.com.au/forums/";
$modversion['usenet_group'] = "sci.chronolabs";
$modversion['maillist_announcements'] = "";
$modversion['maillist_bugs'] = "";
$modversion['maillist_features'] = "";

// All tables should not have any prefix!
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

// Tables created by sql file (without prefix!)
$modversion['tables'][0]	= 'rest_tables';
$modversion['tables'][1]	= 'rest_fields';
$modversion['tables'][2]	= 'rest_plugins';

// Admin things
$modversion['hasAdmin']		= 1;
$modversion['adminindex']	= "admin/index.php";
$modversion['adminmenu']	= "admin/menu.php";

// Menu
$modversion['hasMain'] = 1;

// Smarty
$modversion['use_smarty'] = 0;

$i=0;
$i++;
$modversion['config'][$i]['name'] = 'site_user_auth';
$modversion['config'][$i]['title'] = '_XREST_MI_USERAUTH';
$modversion['config'][$i]['description'] = '_XREST_MI_USERAUTHDESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 1;

$i++;
$modversion['config'][$i]['name'] = 'run_cleanup';
$modversion['config'][$i]['title'] = '_XREST_MI_SECONDS_TO_CLEANUP';
$modversion['config'][$i]['description'] = '_XREST_MI_SECONDS_TO_CLEANUP_DESC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 86400;
$modversion['config'][$i]['options'] = array(_XREST_MI_SECONDS_2419200 => 2419200, _XREST_MI_SECONDS_604800 => 604800, _XREST_MI_SECONDS_86400 => 86400, _XREST_MI_SECONDS_43200 => 43200,
											_XREST_MI_SECONDS_3600 => 3600, _XREST_MI_SECONDS_1800 => 1800, _XREST_MI_SECONDS_1200 => 1200, _XREST_MI_SECONDS_600 => 600,
											_XREST_MI_SECONDS_300 => 300, _XREST_MI_SECONDS_180 => 180, _XREST_MI_SECONDS_60 => 60, _XREST_MI_SECONDS_30 => 30);

$i++;
$modversion['config'][$i]['name'] = 'plugin_list_cache';
$modversion['config'][$i]['title'] = '_XREST_MI_SECONDS_LIST_CACHE';
$modversion['config'][$i]['description'] = '_XREST_MI_SECONDS_LIST_CACHE_DESC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 3600;
$modversion['config'][$i]['options'] = array(_XREST_MI_SECONDS_2419200 => 2419200, _XREST_MI_SECONDS_604800 => 604800, _XREST_MI_SECONDS_86400 => 86400, _XREST_MI_SECONDS_43200 => 43200,
											_XREST_MI_SECONDS_3600 => 3600, _XREST_MI_SECONDS_1800 => 1800, _XREST_MI_SECONDS_1200 => 1200, _XREST_MI_SECONDS_600 => 600,
											_XREST_MI_SECONDS_300 => 300, _XREST_MI_SECONDS_180 => 180, _XREST_MI_SECONDS_60 => 60, _XREST_MI_SECONDS_30 => 30);
											
$i++;
$modversion['config'][$i]['name'] = 'lock_seconds';
$modversion['config'][$i]['title'] = '_XREST_MI_SECONDS';
$modversion['config'][$i]['description'] = '_XREST_MI_SECONDS_DESC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 180;
$modversion['config'][$i]['options'] = array(_XREST_MI_SECONDS_3600 => 3600, _XREST_MI_SECONDS_1800 => 1800, _XREST_MI_SECONDS_1200 => 1200, _XREST_MI_SECONDS_600 => 600,
											_XREST_MI_SECONDS_300 => 300, _XREST_MI_SECONDS_180 => 180, _XREST_MI_SECONDS_60 => 60, _XREST_MI_SECONDS_30 => 30);

srand((((float)('0' . substr(microtime(), strpos(microtime(), ' ') + 1, strlen(microtime()) - strpos(microtime(), ' ') + 1))) * mt_rand(30, 99999)));
srand((((float)('0' . substr(microtime(), strpos(microtime(), ' ') + 1, strlen(microtime()) - strpos(microtime(), ' ') + 1))) * mt_rand(30, 99999)));											
$i++;
$modversion['config'][$i]['name'] = 'lock_random_seed';
$modversion['config'][$i]['title'] = '_XREST_MI_USERANDOMLOCK';
$modversion['config'][$i]['description'] = '_XREST_MI_USERANDOMLOCK_DESC';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = mt_rand(30, 170);
												
$i++;
$modversion['config'][$i]['name'] = 'cache_seconds';
$modversion['config'][$i]['title'] = '_XREST_MI_SECONDSCACHE';
$modversion['config'][$i]['description'] = '_XREST_MI_SECONDSCACHE_DESC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 3600;
$modversion['config'][$i]['options'] = array(_XREST_MI_SECONDS_3600 => 3600, _XREST_MI_SECONDS_1800 => 1800, _XREST_MI_SECONDS_1200 => 1200, _XREST_MI_SECONDS_600 => 600,
											_XREST_MI_SECONDS_300 => 300, _XREST_MI_SECONDS_180 => 180, _XREST_MI_SECONDS_60 => 60, _XREST_MI_SECONDS_30 => 30);											
?>
