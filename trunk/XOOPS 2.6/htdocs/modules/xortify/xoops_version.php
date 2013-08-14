<?php
/**
 * @package     xortify
 * @subpackage  module
 * @description	Sector Network Security Drone
 * @author	    Simon Roberts WISHCRAFT <simon@chronolabs.coop>
 * @copyright	copyright (c) 2010-2013 XOOPS.org
 * @licence		GPL 2.0 - see docs/LICENCE.txt
 */


if (!defined('XOOPS_ROOT_PATH')) {
	die('XOOPS root path not defined');
}

/* Mode of Application can be set to either:
 *
*  _MI_XOR_MODE_CLIENT  = for any XOOPS Client
*  _MI_XOR_MODE_SERVER  = Xortify Cloud Server Client
*
*/
define('_MI_XOR_MODE', _MI_XOR_MODE_CLIENT);

$modversion['dirname'] 		= basename(dirname(__FILE__));
$modversion['name'] 		= _MI_XOR_NAME . ' (' . _MI_XOR_MODE . ')';
$modversion['version']     	= _MI_XOR_VERSION;
$modversion['releasedate'] 	= "Saturday: March 25, 2013";
$modversion['module_status']= "Stable";
$modversion['description'] 	= _MI_XOR_DESC . ' ( Mode: ' . _MI_XOR_MODE . ' )';
$modversion['credits']     	= _MI_XOR_CREDITS;
$modversion['author']      	= "Wishcraft (Simon Roberts)";
$modversion['help']        	= "";
$modversion['license']     	= "GPL 3.0";
$modversion['official']    	= 1;
$modversion['image']       	= "images/xortify_slogo.png";
$modversion['website']      = "xortify.com";

$modversion['release_info'] = "Stable 2013/03/25";
$modversion['release_file'] = XOOPS_URL."/modules/xortify/docs/changelog.txt";
$modversion['release_date'] = "2013/03/25";

$modversion['release_date']        = '2013/03/25';
$modversion['module_website_url']  = 'www.chronolabs.coop/articles/Xoops-Modules/Xortify-3.04/91.html';
$modversion['module_website_name'] = 'Chronolabs Co-op';
$modversion['module_status']       = 'Stable';
$modversion['min_php']             = '5.2';
$modversion['min_xoops']           = '2.6.0';
$modversion['min_db']              = array('mysql'=>'5.0.7', 'mysqli'=>'5.0.7');

$modversion['author_realname'] = "Simon Antony Roberts";
$modversion['author_website_url'] = "http://www.chronolabs.coops";
$modversion['author_website_name'] = "Chronolabs Australia";
$modversion['author_email'] = "simon@chronolabs.coop";
$modversion['demo_site_url'] = "Chronolabs Australia";
$modversion['demo_site_name'] = "";
$modversion['support_site_url'] = "http://www.chronolabs.coops/forums/viewforum.php?forum=30";
$modversion['support_site_name'] = "Chronolabs";
$modversion['submit_bug'] = "http://www.chronolabs.coops/forums/viewforum.php?forum=30";
$modversion['submit_feature'] = "http://www.chronolabs.coops/forums/viewforum.php?forum=30";
$modversion['usenet_group'] = "sci.chronolabs";
$modversion['maillist_announcements'] = "";
$modversion['maillist_bugs'] = "";
$modversion['maillist_features'] = "";

$modversion['warning'] = "For XOOPS 2.6.0";

$modversion['plugin'] = 1;
$modversion['plugin_module'][] = 'protector';


// Mysql file
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
$modversion['tables'][0] = 'xortify_log';
$modversion['tables'][1] = 'xortify_servers';
$modversion['tables'][2] = 'xortify_emails';
$modversion['tables'][3] = 'xortify_emails_links';

// Main
$modversion['hasMain'] = 0;

// Install Scripts
$modversion['onInstall'] = 'include/install.php';
$modversion['onUpdate'] = 'include/update.php';

// Admin
$modversion['system_menu'] = 1;
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php?op=dashboard";
$modversion['adminmenu'] = "admin/menu.php";

// Blocks
$i = 0;
$modversion['blocks'][$i]['file'] = 'spoof.php';
$modversion['blocks'][$i]['name'] = _XOR_MI_SPOOF_COMMENT;
$modversion['blocks'][$i]['description'] = _XOR_MI_SPOOF_COMMENT_DESC;
$modversion['blocks'][$i]['show_func'] = 'b_xortify_spoof_comment';
$modversion['blocks'][$i]['template'] = 'xortify_block_spoof.html';
$i++;
$modversion['blocks'][$i]['file'] = 'spoof.php';
$modversion['blocks'][$i]['name'] = _XOR_MI_SPOOF_REGISTRATION;
$modversion['blocks'][$i]['description'] = _XOR_MI_SPOOF_REGISTRATION_DESC;
$modversion['blocks'][$i]['show_func'] = 'b_xortify_spoof_registration';
$modversion['blocks'][$i]['template'] = 'xortify_block_spoof.html';
$i++;
$modversion['blocks'][$i]['file'] = 'spoof.php';
$modversion['blocks'][$i]['name'] = _XOR_MI_SPOOF_THREAD;
$modversion['blocks'][$i]['description'] = _XOR_MI_SPOOF_THREAD_DESC;
$modversion['blocks'][$i]['show_func'] = 'b_xortify_spoof_thread';
$modversion['blocks'][$i]['template'] = 'xortify_block_spoof.html';

$i = 0;
// Config items
$i++;
$modversion['config'][$i]['name'] = 'xortify_username';
$modversion['config'][$i]['title'] = '_XOR_MI_USERNAME';
$modversion['config'][$i]['description'] = '_XOR_MI_USERNAME_DESC';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '';

$i++;
$modversion['config'][$i]['name'] = 'xortify_password';
$modversion['config'][$i]['title'] = '_XOR_MI_PASSWORD';
$modversion['config'][$i]['description'] = '_XOR_MI_PASSWORD_DESC';
$modversion['config'][$i]['formtype'] = 'password';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '';

$i++;
$modversion['config'][$i]['name'] = 'xortify_seconds';
$modversion['config'][$i]['title'] = '_XOR_MI_SECONDS';
$modversion['config'][$i]['description'] = '_XOR_MI_SECONDS_DESC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 3600;
$modversion['config'][$i]['options'] = array(_XOR_MI_SECONDS_37600 => 37600, _XOR_MI_SECONDS_28800 => 28800, _XOR_MI_SECONDS_14400 => 14400, _XOR_MI_SECONDS_7200 => 7200,
											_XOR_MI_SECONDS_3600 => 3600, _XOR_MI_SECONDS_1800 => 1800, _XOR_MI_SECONDS_1200 => 1200, _XOR_MI_SECONDS_600 => 600,
											_XOR_MI_SECONDS_300 => 300, _XOR_MI_SECONDS_180 => 180, _XOR_MI_SECONDS_60 => 60, _XOR_MI_SECONDS_30 => 30);
											
$i++;
$modversion['config'][$i]['name'] = 'xortify_ip_cache';
$modversion['config'][$i]['title'] = '_XOR_MI_IPCACHE';
$modversion['config'][$i]['description'] = '_XOR_MI_IPCACHE_DESC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 86400;
$modversion['config'][$i]['options'] = array(_XOR_MI_SECONDS_29030400 => 29030400, _XOR_MI_SECONDS_14515200 => 14515200, _XOR_MI_SECONDS_7257600 => 7257600, _XOR_MI_SECONDS_2419200 => 2419200,
											_XOR_MI_SECONDS_1209600 => 1209600, _XOR_MI_SECONDS_604800 => 604800, _XOR_MI_SECONDS_86400 => 86400, _XOR_MI_SECONDS_43200 => 43200,
											_XOR_MI_SECONDS_37600 => 37600, _XOR_MI_SECONDS_28800 => 28800, _XOR_MI_SECONDS_14400 => 14400, _XOR_MI_SECONDS_7200 => 7200,
											_XOR_MI_SECONDS_3600 => 3600, _XOR_MI_SECONDS_1800 => 1800, _XOR_MI_SECONDS_1200 => 1200, _XOR_MI_SECONDS_600 => 600,
											_XOR_MI_SECONDS_300 => 300, _XOR_MI_SECONDS_180 => 180, _XOR_MI_SECONDS_60 => 60, _XOR_MI_SECONDS_30 => 30);

$i++;
$modversion['config'][$i]['name'] = 'xortify_records';
$modversion['config'][$i]['title'] = '_XOR_MI_RECORDS';
$modversion['config'][$i]['description'] = '_XOR_MI_RECORDS_DESC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 3600;
$modversion['config'][$i]['options'] = array(_XOR_MI_RECORDS_177600 => 177600, _XOR_MI_RECORDS_48800 => 48800, _XOR_MI_RECORDS_24400 => 24400, _XOR_MI_RECORDS_12200 => 12200,
											_XOR_MI_RECORDS_3600 => 3600, _XOR_MI_RECORDS_1800 => 1800, _XOR_MI_RECORDS_1200 => 1200, _XOR_MI_RECORDS_600 => 600,
											_XOR_MI_RECORDS_300 => 300, _XOR_MI_RECORDS_180 => 180, _XOR_MI_RECORDS_60 => 60, _XOR_MI_RECORDS_30 => 30);
											
$i++;
$modversion['config'][$i]['name'] = 'xortify_providers';
$modversion['config'][$i]['title'] = '_XOR_MI_PROVIDERS';
$modversion['config'][$i]['description'] = '_XOR_MI_PROVIDERS_DESC';
$modversion['config'][$i]['formtype'] = 'select_multi';
$modversion['config'][$i]['valuetype'] = 'array';
$modversion['config'][$i]['default'] = array('xortify', 'protector', 'stopforumspam.com', 'projecthoneypot.org');
$modversion['config'][$i]['options'] = array('(none)' => '', _XOR_MI_PROVIDER_XORTIFY => 'xortify', _XOR_MI_PROVIDER_PROTECTOR => 'protector', _XOR_MI_PROVIDER_STOPFORUMSPAM => 'stopforumspam.com', _XOR_MI_PROVIDER_SPIDERS => 'spiders', _XOR_MI_PROVIDER_PROJECTHONEYPOT => 'projecthoneypot.org');

include_once($GLOBALS['xoops']->path('/modules/xortify/include/functions.php'));

$i++;
$modversion['config'][$i]['name'] = 'protocol';
$modversion['config'][$i]['title'] = '_XOR_MI_PROTOCOL';
$modversion['config'][$i]['description'] = '_XOR_MI_PROTOCOL_DESC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = xortify_apimethod(false);
$modversion['config'][$i]['options'] = xortify_apimethod(true);

$i++;
$modversion['config'][$i]['name'] = 'xortify_mc_spamc';
$modversion['config'][$i]['title'] = '_XOR_MI_MC_SPAMC';
$modversion['config'][$i]['description'] = '_XOR_MI_MC_SPAMC_DESC';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = DIRECTORY_SEPARATOR . 'usr' . DIRECTORY_SEPARATOR . 'bin' . DIRECTORY_SEPARATOR . 'spamc';

$i++;
$modversion['config'][$i]['name'] = 'xortify_mc_sfs_api';
$modversion['config'][$i]['title'] = '_XOR_MI_MC_SFS_API';
$modversion['config'][$i]['description'] = '_XOR_MI_MC_SFS_API_DESC';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = 'http://www.stopforumspam.com/api';

$i++;
$modversion['config'][$i]['name'] = 'xortify_mc_sfs_key';
$modversion['config'][$i]['title'] = '_XOR_MI_MC_SFS_KEY';
$modversion['config'][$i]['description'] = '_XOR_MI_MC_SFS_KEY_DESC';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '';

$i++;
$modversion['config'][$i]['name'] = 'xortify_mc_php_api';
$modversion['config'][$i]['title'] = '_XOR_MI_MC_PHP_API';
$modversion['config'][$i]['description'] = '_XOR_MI_MC_PHP_API_DESC';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = 'dnsbl.httpbl.org';

$i++;
$modversion['config'][$i]['name'] = 'xortify_mc_php_key';
$modversion['config'][$i]['title'] = '_XOR_MI_MC_PHP_KEY';
$modversion['config'][$i]['description'] = '_XOR_MI_MC_PHP_KEY_DESC';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '';

$i++;
$modversion['config'][$i]['name'] = 'xortify_urirest';
$modversion['config'][$i]['title'] = '_XOR_MI_URIREST';
$modversion['config'][$i]['description'] = '_XOR_MI_URIREST_DESC';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = 'https://xortify.com/rest/';

$i++;
$modversion['config'][$i]['name'] = 'xortify_urisoap';
$modversion['config'][$i]['title'] = '_XOR_MI_URISOAP';
$modversion['config'][$i]['description'] = '_XOR_MI_URISOAP_DESC';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = 'https://xortify.com/soap/';

$i++;
$modversion['config'][$i]['name'] = 'xortify_uricurl';
$modversion['config'][$i]['title'] = '_XOR_MI_URICURL';
$modversion['config'][$i]['description'] = '_XOR_MI_URICURL_DESC';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = 'https://xortify.com/curl/';

$i++;
$modversion['config'][$i]['name'] = 'xortify_urijson';
$modversion['config'][$i]['title'] = '_XOR_MI_URIJSON';
$modversion['config'][$i]['description'] = '_XOR_MI_URIJSON_DESC';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = 'https://xortify.com/json/';

$i++;
$modversion['config'][$i]['name'] = 'xortify_uriserial';
$modversion['config'][$i]['title'] = '_XOR_MI_URISERIAL';
$modversion['config'][$i]['description'] = '_XOR_MI_URISERIAL_DESC';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = 'https://xortify.com/serial/';

$i++;
$modversion['config'][$i]['name'] = 'xortify_urixml';
$modversion['config'][$i]['title'] = '_XOR_MI_URIXML';
$modversion['config'][$i]['description'] = '_XOR_MI_URIXML_DESC';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = 'https://xortify.com/xml/';

$i++;
$modversion['config'][$i]['name'] = 'save_banned';
$modversion['config'][$i]['title'] = '_XOR_MI_LOG_BANNED';
$modversion['config'][$i]['description'] = '_XOR_MI_LOG_BANNED_DESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = true;

$i++;
$modversion['config'][$i]['name'] = 'save_blocked';
$modversion['config'][$i]['title'] = '_XOR_MI_LOG_BLOCKED';
$modversion['config'][$i]['description'] = '_XOR_MI_LOG_BLOCKED_DESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = true;

$i++;
$modversion['config'][$i]['name'] = 'save_monitored';
$modversion['config'][$i]['title'] = '_XOR_MI_LOG_MONITORED';
$modversion['config'][$i]['description'] = '_XOR_MI_LOG_MONITORED_DESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = true;

$options = array();
for($days=1;$days<=255;$days++)
	$options[sprintf(_XOR_MI_PHP_DAYS, $days)] = $days;
$i++;
$modversion['config'][$i]['name'] = 'octeta';
$modversion['config'][$i]['title'] = '_XOR_MI_PHP_NUMBEROFDAYS';
$modversion['config'][$i]['description'] = '_XOR_MI_PHP_NUMBEROFDAYS_DESC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 92;
$modversion['config'][$i]['options'] = $options;

$options = array();
for($severity=5;$severity<=255;$severity++)
	$options[sprintf(_XOR_MI_PHP_SEVERITY, number_format($severity/255*100, 2))] = $severity;
$i++;
$modversion['config'][$i]['name'] = 'octetb';
$modversion['config'][$i]['title'] = '_XOR_MI_PHP_SEVERITYLEVEL';
$modversion['config'][$i]['description'] = '_XOR_MI_PHP_SEVERITYLEVEL_DESC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 15;
$modversion['config'][$i]['options'] = $options;

$i++;
$modversion['config'][$i]['name'] = 'octetc';
$modversion['config'][$i]['title'] = '_XOR_MI_PHP_SCANMODE';
$modversion['config'][$i]['description'] = '_XOR_MI_PHP_SCANMODE_DESC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 2;
$modversion['config'][$i]['options'] = array(_XOR_MI_PHP_SCANMODE_SUSPICIOUS => 1, _XOR_MI_PHP_SCANMODE_HARVESTER => 2, _XOR_MI_PHP_SCANMODE_SUSICIOUSHARVESTER => 3, 
											 _XOR_MI_PHP_SCANMODE_SPAMMER => 4, _XOR_MI_PHP_SCANMODE_SUSPICIOUSSPAMMER => 5, _XOR_MI_PHP_SCANMODE_HARVESTERSPAMMER => 6, 
											 _XOR_MI_PHP_SCANMODE_SUSPICIOUSHARVESTERSPAMMER => 7);

$options = array();
for($num=1;$num<=150;$num++)
	$options[sprintf(_XOR_MI_SFS_EMAIILFREQ, $num)] = $num;
$i++;
$modversion['config'][$i]['name'] = 'email_freq';
$modversion['config'][$i]['title'] = '_XOR_MI_SFS_EMAILFREQUENCY';
$modversion['config'][$i]['description'] = '_XOR_MI_SFS_EMAILFREQUENCY_DESC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 2;
$modversion['config'][$i]['options'] = $options;

$i++;
$modversion['config'][$i]['name'] = 'email_lastseen';
$modversion['config'][$i]['title'] = '_XOR_MI_SFS_EMAILLASTSEEN';
$modversion['config'][$i]['description'] = '_XOR_MI_SFS_EMAILLASTSEEN_DESC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = (60*60*24*7*4*3);
$modversion['config'][$i]['options'] = array(_XOR_MI_SFS_LASTSEEN_24HOURS => (60*60*24), _XOR_MI_SFS_LASTSEEN_1WEEK => (60*60*24*7), _XOR_MI_SFS_LASTSEEN_FORTNIGHT => (60*60*24*7*2), 
											 _XOR_MI_SFS_LASTSEEN_1MONTH => (60*60*24*7*4*1), _XOR_MI_SFS_LASTSEEN_2MONTHS => (60*60*24*7*4*2), _XOR_MI_SFS_LASTSEEN_3MONTHS => (60*60*24*7*4*3), 
											 _XOR_MI_SFS_LASTSEEN_4MONTHS => (60*60*24*7*4*4), _XOR_MI_SFS_LASTSEEN_5MONTHS => (60*60*24*7*4*5), _XOR_MI_SFS_LASTSEEN_6MONTHS => (60*60*24*7*4*6),
											 _XOR_MI_SFS_LASTSEEN_12MONTHS => (60*60*24*7*4*12), _XOR_MI_SFS_LASTSEEN_24MONTHS => (60*60*24*7*4*24));
											 
$options = array();
for($num=1;$num<=150;$num++)
	$options[sprintf(_XOR_MI_SFS_UNAMEFREQ, $num)] = $num;
$i++;
$modversion['config'][$i]['name'] = 'uname_freq';
$modversion['config'][$i]['title'] = '_XOR_MI_SFS_UNAMEFREQUENCY';
$modversion['config'][$i]['description'] = '_XOR_MI_SFS_UNAMEFREQUENCY_DESC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 2;
$modversion['config'][$i]['options'] = $options;

$i++;
$modversion['config'][$i]['name'] = 'uname_lastseen';
$modversion['config'][$i]['title'] = '_XOR_MI_SFS_UNAMELASTSEEN';
$modversion['config'][$i]['description'] = '_XOR_MI_SFS_UNAMELASTSEEN_DESC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = (60*60*24*7*4*3);
$modversion['config'][$i]['options'] = array(_XOR_MI_SFS_LASTSEEN_24HOURS => (60*60*24), _XOR_MI_SFS_LASTSEEN_1WEEK => (60*60*24*7), _XOR_MI_SFS_LASTSEEN_FORTNIGHT => (60*60*24*7*2), 
											 _XOR_MI_SFS_LASTSEEN_1MONTH => (60*60*24*7*4*1), _XOR_MI_SFS_LASTSEEN_2MONTHS => (60*60*24*7*4*2), _XOR_MI_SFS_LASTSEEN_3MONTHS => (60*60*24*7*4*3), 
											 _XOR_MI_SFS_LASTSEEN_4MONTHS => (60*60*24*7*4*4), _XOR_MI_SFS_LASTSEEN_5MONTHS => (60*60*24*7*4*5), _XOR_MI_SFS_LASTSEEN_6MONTHS => (60*60*24*7*4*6),
											 _XOR_MI_SFS_LASTSEEN_12MONTHS => (60*60*24*7*4*12), _XOR_MI_SFS_LASTSEEN_24MONTHS => (60*60*24*7*4*24));

$options = array();
for($num=1;$num<=150;$num++)
	$options[sprintf(_XOR_MI_SFS_IPFREQ, $num)] = $num;
$i++;
$modversion['config'][$i]['name'] = 'ip_freq';
$modversion['config'][$i]['title'] = '_XOR_MI_SFS_IPFREQUENCY';
$modversion['config'][$i]['description'] = '_XOR_MI_SFS_IPFREQUENCY_DESC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 2;
$modversion['config'][$i]['options'] = $options;

$i++;
$modversion['config'][$i]['name'] = 'ip_lastseen';
$modversion['config'][$i]['title'] = '_XOR_MI_SFS_IPLASTSEEN';
$modversion['config'][$i]['description'] = '_XOR_MI_SFS_IPLASTSEEN_DESC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = (60*60*24*7*4*3);
$modversion['config'][$i]['options'] = array(_XOR_MI_SFS_LASTSEEN_24HOURS => (60*60*24), _XOR_MI_SFS_LASTSEEN_1WEEK => (60*60*24*7), _XOR_MI_SFS_LASTSEEN_FORTNIGHT => (60*60*24*7*2), 
											 _XOR_MI_SFS_LASTSEEN_1MONTH => (60*60*24*7*4*1), _XOR_MI_SFS_LASTSEEN_2MONTHS => (60*60*24*7*4*2), _XOR_MI_SFS_LASTSEEN_3MONTHS => (60*60*24*7*4*3), 
											 _XOR_MI_SFS_LASTSEEN_4MONTHS => (60*60*24*7*4*4), _XOR_MI_SFS_LASTSEEN_5MONTHS => (60*60*24*7*4*5), _XOR_MI_SFS_LASTSEEN_6MONTHS => (60*60*24*7*4*6),
											 _XOR_MI_SFS_LASTSEEN_12MONTHS => (60*60*24*7*4*12), _XOR_MI_SFS_LASTSEEN_24MONTHS => (60*60*24*7*4*24));

$i++;
$modversion['config'][$i]['name'] = 'logdrops';
$modversion['config'][$i]['title'] = '_XOR_MI_LOGDROPS';
$modversion['config'][$i]['description'] = '_XOR_MI_LOGDROPS_DESC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = (60*60*24*7*4*1);
$modversion['config'][$i]['options'] = array(_XOR_MI_SFS_LASTSEEN_24HOURS => (60*60*24), _XOR_MI_SFS_LASTSEEN_1WEEK => (60*60*24*7), _XOR_MI_SFS_LASTSEEN_FORTNIGHT => (60*60*24*7*2), 
											 _XOR_MI_SFS_LASTSEEN_1MONTH => (60*60*24*7*4*1), _XOR_MI_SFS_LASTSEEN_2MONTHS => (60*60*24*7*4*2), _XOR_MI_SFS_LASTSEEN_3MONTHS => (60*60*24*7*4*3), 
											 _XOR_MI_SFS_LASTSEEN_4MONTHS => (60*60*24*7*4*4), _XOR_MI_SFS_LASTSEEN_5MONTHS => (60*60*24*7*4*5), _XOR_MI_SFS_LASTSEEN_6MONTHS => (60*60*24*7*4*6),
											 _XOR_MI_SFS_LASTSEEN_12MONTHS => (60*60*24*7*4*12), _XOR_MI_SFS_LASTSEEN_24MONTHS => (60*60*24*7*4*24));

$i++;
$modversion['config'][$i]['name'] = 'crontype';
$modversion['config'][$i]['title'] = '_XOR_MI_CRONTYPE';
$modversion['config'][$i]['description'] = '_XOR_MI_CRONTYPE_DESC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = 'preloader';
$modversion['config'][$i]['options'] = 	array(	_XOR_MI_CRONTYPE_PRELOADER => 'preloader', 
												_XOR_MI_CRONTYPE_CRONTAB => 'crontab', 
												_XOR_MI_CRONTYPE_SCHEDULER => 'scheduler'
										);

$i++;
$modversion['config'][$i]['name'] = 'croninterval';
$modversion['config'][$i]['title'] = '_XOR_MI_CRONINTERVAL';
$modversion['config'][$i]['description'] = '_XOR_MI_CRONINTERVAL_DESC';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 60*60*2;

$i++;
$modversion['config'][$i]['name'] = 'fault_delay';
$modversion['config'][$i]['title'] = '_XOR_MI_FAULT_DELAY';
$modversion['config'][$i]['description'] = '_XOR_MI_FAULT_DELAY_DESC';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = (60*10);

$i++;
$modversion['config'][$i]['name'] = 'curl_timeout';
$modversion['config'][$i]['title'] = '_XOR_MI_CURL_TIMOUT';
$modversion['config'][$i]['description'] = '_XOR_MI_CURL_TIMOUT_DESC';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 40;

$i++;
$modversion['config'][$i]['name'] = 'curl_connecttimeout';
$modversion['config'][$i]['title'] = '_XOR_MI_CURL_CONNECTTIMOUT';
$modversion['config'][$i]['description'] = '_XOR_MI_CURL_CONNECTTIMOUT_DESC';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 20;

$i++;
$modversion['config'][$i]['name'] = 'google';
$modversion['config'][$i]['title'] = '_XOR_MI_GOOGLE_ANALYTICS';
$modversion['config'][$i]['description'] = '_XOR_MI_GOOGLE_ANALYTICS_DESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = false;

$i++;
$modversion['config'][$i]['name'] = 'allowed_spams';
$modversion['config'][$i]['title'] = '_XOR_MI_ALLOWEDSPAMS';
$modversion['config'][$i]['description'] = '_XOR_MI_ALLOWEDSPAMS_DESC';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 10;

$i++;
$modversion['config'][$i]['name'] = 'check_spams';
$modversion['config'][$i]['title'] = '_XOR_MI_CHECKSPAMS';
$modversion['config'][$i]['description'] = '_XOR_MI_CHECKSPAMS_DESC';
$modversion['config'][$i]['formtype'] = 'group_multi';
$modversion['config'][$i]['valuetype'] = 'array';
$modversion['config'][$i]['default'] = array(XOOPS_GROUP_USERS=>XOOPS_GROUP_USERS);

$i++;
$modversion['config'][$i]['name'] = 'min_words';
$modversion['config'][$i]['title'] = '_XOR_MI_MINIMUMWORDS';
$modversion['config'][$i]['description'] = '_XOR_MI_MINIMUMWORDS_DESC';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 40;

$i++;
$modversion['config'][$i]['name'] = 'min_words_groups';
$modversion['config'][$i]['title'] = '_XOR_MI_MINIMUMWORDSGROUPS';
$modversion['config'][$i]['description'] = '_XOR_MI_MINIMUMWORDSGROUPS_DESC';
$modversion['config'][$i]['formtype'] = 'group_multi';
$modversion['config'][$i]['valuetype'] = 'array';
$modversion['config'][$i]['default'] = array(XOOPS_GROUP_USERS=>XOOPS_GROUP_USERS);

$i++;
$modversion['config'][$i]['name'] = 'allow_adult';
$modversion['config'][$i]['title'] = '_XOR_MI_ALLOWEDADULT';
$modversion['config'][$i]['description'] = '_XOR_MI_ALLOWEDADULT_DESC';
$modversion['config'][$i]['formtype'] = 'group_multi';
$modversion['config'][$i]['valuetype'] = 'array';
$modversion['config'][$i]['default'] = array(XOOPS_GROUP_ADMIN=>XOOPS_GROUP_ADMIN);

$i++;
$modversion['config'][$i]['name'] = 'bounce';
$modversion['config'][$i]['title'] = '_XOR_MI_BOUNCE';
$modversion['config'][$i]['description'] = '_XOR_MI_BOUNCE_DESC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 28800;
$modversion['config'][$i]['options'] = array(_XOR_MI_SECONDS_29030400 => 29030400, _XOR_MI_SECONDS_14515200 => 14515200, _XOR_MI_SECONDS_7257600 => 7257600, _XOR_MI_SECONDS_2419200 => 2419200,
		_XOR_MI_SECONDS_1209600 => 1209600, _XOR_MI_SECONDS_604800 => 604800, _XOR_MI_SECONDS_86400 => 86400, _XOR_MI_SECONDS_43200 => 43200,
		_XOR_MI_SECONDS_37600 => 37600, _XOR_MI_SECONDS_28800 => 28800, _XOR_MI_SECONDS_14400 => 14400, _XOR_MI_SECONDS_7200 => 7200,
		_XOR_MI_SECONDS_3600 => 3600, _XOR_MI_SECONDS_1800 => 1800, _XOR_MI_SECONDS_1200 => 1200, _XOR_MI_SECONDS_600 => 600,
		_XOR_MI_SECONDS_300 => 300, _XOR_MI_SECONDS_180 => 180, _XOR_MI_SECONDS_60 => 60, _XOR_MI_SECONDS_30 => 30);


$i++;
$modversion['config'][$i]['name'] = 'server_cache';
$modversion['config'][$i]['title'] = '_XOR_MI_SERVERCACHE';
$modversion['config'][$i]['description'] = '_XOR_MI_SERVERCACHE_DESC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 86400;
$modversion['config'][$i]['options'] = array(_XOR_MI_SECONDS_29030400 => 29030400, _XOR_MI_SECONDS_14515200 => 14515200, _XOR_MI_SECONDS_7257600 => 7257600, _XOR_MI_SECONDS_2419200 => 2419200,
		_XOR_MI_SECONDS_1209600 => 1209600, _XOR_MI_SECONDS_604800 => 604800, _XOR_MI_SECONDS_86400 => 86400, _XOR_MI_SECONDS_43200 => 43200,
		_XOR_MI_SECONDS_37600 => 37600, _XOR_MI_SECONDS_28800 => 28800, _XOR_MI_SECONDS_14400 => 14400, _XOR_MI_SECONDS_7200 => 7200,
		_XOR_MI_SECONDS_3600 => 3600, _XOR_MI_SECONDS_1800 => 1800, _XOR_MI_SECONDS_1200 => 1200, _XOR_MI_SECONDS_600 => 600,
		_XOR_MI_SECONDS_300 => 300, _XOR_MI_SECONDS_180 => 180, _XOR_MI_SECONDS_60 => 60, _XOR_MI_SECONDS_30 => 30);


?>
