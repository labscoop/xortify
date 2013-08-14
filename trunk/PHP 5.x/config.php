<?php
/*
 * Prevents Spam, Harvesting, Human Rights Abuse, Captcha Abuse etc.
* basic statistic of them in XOOPS Copyright (C) 2012 Simon Roberts
* Contact: wishcraft - simon@chronolabs.com.au
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program. If not, see <http://www.gnu.org/licenses/>.
* See /docs/license.pdf for full license.
*
* Shouts:- 	Mamba (www.xoops.org), flipse (www.nlxoops.nl)
* 				Many thanks for your additional work with version 1.01
*
* Version:		3.10 Final (Stable)
* Published:	Chronolabs
* Download:	http://code.google.com/p/chronolabs
* This File:	post.header.endcache.php
* Description:	Post loader for end of cache before exit(); in /header.php
* Date:		09/09/2012 19:34 AEST
* License:		GNU3
*
*/

/**
 * URL to the base directory of the Xority Adaptor
 * @var string
 */
define('_RUN_XORTIFY_URL', 'http://yoursite.com');

/**
 * Username you have signed up under on Xortify.com
 * @var string
 */
define('_RUN_XORTIFY_USERNAME', 'testuser');

/**
 * Password in open text 
 * @var string
 */
define('_RUN_XORTIFY_PASSWORD', 'password');

/**
 * ProjectHoneypot.org OctetC Value to Score
 * @var integr
 */
define('_RUN_XORTIFY_MYIPv4_ADDRESS', '127.0.0.1');

/**
 * ProjectHoneypot.org OctetC Value to Score
 * @var integr
 */
define('_RUN_XORTIFY_MYIPv6_ADDRESS', '1270::assd:dde:ee:e.0:1');

/**
 * Folder's of providers being used in runtime see /providers
 * @var string
 */
define('_RUN_XORTIFY_PROVIDERS', 'projecthoneypot.org|stopforumspam.com|xortify');

/**
 * cURL Timeout for making a connection to the cloud
 * @var integer
 */
define('_RUN_XORTIFY_CURL_CONNECTIONTIMEOUT', 45);

/**
 * cURL Timeout for Reception
 * @var integer
 */
define('_RUN_XORTIFY_CURL_TIMEOUT', 45);

/**
 * URL for cURL JSON Adaptor Class
 * @var string
 */
define('_RUN_XORTIFY_API_CURL', 'https://xortify.com/curl/');

/**
 * URL for cURL JSON Adaptor Class
 * @var string
 */
define('_RUN_XORTIFY_API_JSON', 'https://xortify.com/json/');

/**
 * URL for cURL XML Adaptor Class
 * @var string
 */
define('_RUN_XORTIFY_API_XML', 'https://xortify.com/xml/');

/**
 * URL for SOAP Adaptor Class
 * @var string
 */
define('_RUN_XORTIFY_API_SOAP', 'https://xortify.com/soap/');

/**
 * URL for Serilisation Adaptor Class
 * @var string
 */
define('_RUN_XORTIFY_API_SERIAL', 'https://xortify.com/serial/');

/**
 * URL for REST Adaptor Class
 * @var string
 */
define('_RUN_XORTIFY_API_REST', 'https://xortify.com/rest/');

/**
 * Communication method & Protocol Type for Xortify.com Cloud
 * 
 * Options:			curl
 * 					curlserialised
 * 					curlxml
 * 					json
 * 					rest_curl
 * 					rest_curlserialised
 * 					rest_curlxml
 * 					rest_json
 * 					rest_wgetserialised
 * 					rest_wgetxml
 * 					soap
 * 					wgetserialised
 * 					wgetxml
 * 
 * @var string
 */
define('_RUN_XORTIFY_PROTOCOL', 'rest_curlserialised');

/**
 * Root path for Xortify PHP plugin
 * @var string
 */
define('_RUN_XORTIFY_ROOT_PATH', dirname(__FILE__));

/**
 * Root path for Variable Xortify Cloud
 * @var string
 */
define('_RUN_XORTIFY_VAR_PATH', dirname(__FILE__));

/**
 * Version of Xortify
 * @var string
 */
define('_RUN_XORTIFY_VERSION', "4.11");

/**
 * Minium Words Allowed For None Spam Entry
 * @var integer
*/
define('_RUN_XORTIFY_MINIUWORDS', 15);

/**
 * Minium Words Allowed For None Spam Entry
 * @var integer
*/
define('_RUN_XORTIFY_IPCACHE', 3600*18);

/**
 * Number of seconds to delay on a fault
 * @var integer
*/
define('_RUN_XORTIFY_FAULTDELAY', 750);

/**
 * ProjectHoneypot.org OctetA Value to Score
 * @var integer
 */
define('_RUN_XORTIFY_PROJECTHONEYPOT_OCTETA', 92);

/**
 * ProjectHoneypot.org OctetB Value to Score
 * @var integer
 */
define('_RUN_XORTIFY_PROJECTHONEYPOT_OCTETB', 15);

/**
 * ProjectHoneypot.org OctetC Value to Score
 * @var integer
 */
define('_RUN_XORTIFY_PROJECTHONEYPOT_OCTETC', 2);

/**
 * StopForumSpam.com IP Occurence Tollerance
 * @var integer
 */
define('_RUN_XORTIFY_SFS_IPFREQUENCY', 2);

/**
 * StopForumSpam.com Username Occurence Tollerance
 * @var integer
*/
define('_RUN_XORTIFY_SFS_UNAMEFREQUENCY', 2);

/**
 * StopForumSpam.com EMail Occurence Tollerance
 * @var integer
*/
define('_RUN_XORTIFY_SFS_EMAILFREQUENCY', 2);

/**
 * StopForumSpam.com IP Last Seen Tollerance
 * @var integer
*/
define('_RUN_XORTIFY_SFS_IP_LASTSEEN', (60*60*24*7*4*3));

/**
 * StopForumSpam.com Username Last Seen Tollerance
 * @var integer
*/
define('_RUN_XORTIFY_SFS_UNAME_LASTSEEN', (60*60*24*7*4*3));

/**
 * StopForumSpam.com Email Last Seen Tollerance
 * @var integer
*/
define('_RUN_XORTIFY_SFS_EMAIL_LASTSEEN', (60*60*24*7*4*3));

/**
 * Number of Spam Allowed
 * @var integer
 */
define('_RUN_XORTIFY_SPAMS_ALLOWED', 10);

/**
 * Allow adult keywords and phrases
 * @var integer
 */
define('_RUN_XORTIFY_SPAMS_ALLOWADULT', false);

