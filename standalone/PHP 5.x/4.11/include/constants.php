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
	
	define('_MI_RUN_XORTIFY_NAME', 'Xortify');
	define('_MI_RUN_XORTIFY_VERSION', '4.11');
	define('_MI_RUN_XORTIFY_USER_AGENT', '%s/%s %s Sector Network Security (%s)');
	define('_MI_RUN_XORTIFY_RUNTIME', 'PHP ' . PHP_VERSION. ' ['.PHP_VERSION_ID . '], XORTIFY '._MI_RUN_XORTIFY_VERSION);

	define('_RUN_RUN_XORTIFYTIFY_BAN_PHP_TYPE','Project Honey Pot Ban - Details:');
	define('_RUN_RUN_XORTIFYTIFY_BAN_PHP_OCTETA','Number of Days');
	define('_RUN_RUN_XORTIFYTIFY_BAN_PHP_OCTETB','Severity');
	define('_RUN_RUN_XORTIFYTIFY_BAN_PHP_OCTETC','Scan Mode');
	define('_RUN_RUN_XORTIFYTIFY_BAN_SPIDER_TYPE','Spider Block - $_POST Variables are:');
	define('_RUN_RUN_XORTIFYTIFY_BAN_SFS_TYPE','Stop Forum Spam Ban - Details:');
	define('_RUN_RUN_XORTIFYTIFY_BAN_SFS_EMAIL_FREQ','Email Frequency');
	define('_RUN_RUN_XORTIFYTIFY_BAN_SFS_EMAIL_LASTSEEN','Email Last Seen');
	define('_RUN_RUN_XORTIFYTIFY_BAN_SFS_USERNAME_FREQ','Usename Frequency');
	define('_RUN_RUN_XORTIFYTIFY_BAN_SFS_USERNAME_LASTSEEN','Username Last Seen');
	define('_RUN_RUN_XORTIFYTIFY_BAN_SFS_IP_FREQ','IP Frequency');
	define('_RUN_RUN_XORTIFYTIFY_BAN_SFS_IP_LASTSEEN','IP Last Seen');
	
	define('_RUN_XORTIFY_SPAM', 'SPAM');
	define('_RUN_XORTIFY_SPAM_CONTENTS', 'Spam Contents');
	define('_RUN_XORTIFY_SPAM_PAGETITLE', 'You have been blocked for trying to submit Spam!');
	define('_RUN_XORTIFY_SPAM_DESCRIPTION', '<p align="center"><img align="middle" src="'._RUN_XORTIFY_URL.'/images/accessdenied.png"></p><p align="center" style="font-size:18px">You have been blocked from our site for submitting spam by Xortify, this is possibly done by one of our third parties! You will have %s attempts left then you will be banned!</p><div style="clear:both; height: 25px;">&nbsp;</div><p align="center" style="font-size:14px">IP Bans that is a ban that is based on the provider "Xortify" are with your IP Can be removed by going to <a href="http://xortify.com/modules/unban/">XORTIFY DOT COM</a><br/><br/>This ban is not permenant and will drop in 3 months!</p>');
?>