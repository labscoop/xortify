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

$modversion['name']		    	= 'x-Soap Server';
$modversion['version']			= 4.31;
$modversion['releasedate'] 		= "Sat: 30 May 2010";
$modversion['status'] 			= "Mature";
$modversion['author'] 			= "Chronolabs Australia";
$modversion['credits'] 			= "Simon Roberts";
$modversion['teammembers'] 		= "Wishcraft";
$modversion['license'] 			= "GPL";
$modversion['official'] 		= 1;
$modversion['description']		= 'SOAP Server to exchange XML SQL Queries with other services.';
$modversion['help']		    	= "";
$modversion['image']			= "images/xsoap_slogo.png";
$modversion['dirname']			= 'xsoap';

// All tables should not have any prefix!
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

$modversion['author_realname'] = "Simon Roberts";
$modversion['author_website_url'] = "http://www.chronolabs.org.au";
$modversion['author_website_name'] = "Chronolabs International";
$modversion['author_email'] = "simon@labs.coop";
$modversion['demo_site_url'] = "";
$modversion['demo_site_name'] = "";
$modversion['support_site_url'] = "http://www.chronolabs.org.au/forums/x-Soap/0,10,0,0,100,0,DESC,0";
$modversion['support_site_name'] = "x-Soap";
$modversion['submit_bug'] = "http://www.chronolabs.org.au/forums/x-Soap/0,10,0,0,100,0,DESC,0";
$modversion['submit_feature'] = "http://www.chronolabs.org.au/forums/x-Soap/0,10,0,0,100,0,DESC,0";
$modversion['usenet_group'] = "sci.chronolabs";
$modversion['maillist_announcements'] = "";
$modversion['maillist_bugs'] = "";
$modversion['maillist_features'] = "";

// Tables created by sql file (without prefix!)
$modversion['tables'][0]	= 'soap_tables';
$modversion['tables'][1]	= 'soap_fields';
$modversion['tables'][2]	= 'soap_plugins';

// Admin things
$modversion['hasAdmin']		= 1;
$modversion['adminindex']	= "admin/index.php";
$modversion['adminmenu']	= "admin/menu.php";

// Search
$modversion['hasSearch'] = 0;
$modversion['search']['file'] = "include/search.inc.php";
$modversion['search']['func'] = "content_search";

// Menu
$modversion['hasMain'] = 1;

// Smarty
$modversion['use_smarty'] = 0;

$i++;
$modversion['config'][$i]['name'] = 'wsdl';
$modversion['config'][$i]['title'] = '_XS_WDSL';
$modversion['config'][$i]['description'] = '_XS_WDSLDESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 0;

$i++;
$modversion['config'][$i]['name'] = 'site_user_auth';
$modversion['config'][$i]['title'] = '_XS_USERAUTH';
$modversion['config'][$i]['description'] = '_XS_USERAUTHDESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 1;

$i++;
$modversion['config'][$i]['name'] = 'lock_seconds';
$modversion['config'][$i]['title'] = '_XS_SECONDS';
$modversion['config'][$i]['description'] = '_XS_SECONDS_DESC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 180;
$modversion['config'][$i]['options'] = array(_XS_SECONDS_3600 => 3600, _XS_SECONDS_1800 => 1800, _XS_SECONDS_1200 => 1200, _XS_SECONDS_600 => 600,
											_XS_SECONDS_300 => 300, _XS_SECONDS_180 => 180, _XS_SECONDS_60 => 60, _XS_SECONDS_30 => 30);

srand((((float)('0' . substr(microtime(), strpos(microtime(), ' ') + 1, strlen(microtime()) - strpos(microtime(), ' ') + 1))) * mt_rand(30, 99999)));
srand((((float)('0' . substr(microtime(), strpos(microtime(), ' ') + 1, strlen(microtime()) - strpos(microtime(), ' ') + 1))) * mt_rand(30, 99999)));											
$i++;
$modversion['config'][$i]['name'] = 'lock_random_seed';
$modversion['config'][$i]['title'] = '_XS_USERANDOMLOCK';
$modversion['config'][$i]['description'] = '_XS_USERANDOMLOCK_DESC';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = mt_rand(30, 170);
												
$i++;
$modversion['config'][$i]['name'] = 'cache_seconds';
$modversion['config'][$i]['title'] = '_XS_SECONDSCACHE';
$modversion['config'][$i]['description'] = '_XS_SECONDSCACHE_DESC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 3600;
$modversion['config'][$i]['options'] = array(_XS_SECONDS_3600 => 3600, _XS_SECONDS_1800 => 1800, _XS_SECONDS_1200 => 1200, _XS_SECONDS_600 => 600,
											_XS_SECONDS_300 => 300, _XS_SECONDS_180 => 180, _XS_SECONDS_60 => 60, _XS_SECONDS_30 => 30);											
?>
