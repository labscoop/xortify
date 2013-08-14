<?php
/*
 * Prevents Spam, Harvesting, Human Rights Abuse, Captcha Abuse etc.
 * basic statistic of them in XOOPS Copyright (C) 2012 Simon Roberts 
 * Contact: wishcraft - simon@labs.coop
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
 * This File:	ban.php		
 * Description:	Ban language defines
 * Date:		09/09/2012 19:34 AEST
 * License:		GNU3
 * 
 */

	define('_XOR_PAGETITLE', 'You are Banned!!');
	define('_XOR_DESCRIPTION', '<p align="center"><img align="middle" src="'.XOOPS_URL.'/modules/xortify/images/accessdenied.png"></p><p align="center" style="font-size:18px">You have been Banned from our site by Xortify, this is possibly done by one of our third parties like Stop Forum Spam or Project Honeypot, you will have to check the provider of the ban!</p><div style="clear:both; height: 25px;">&nbsp;</div><p align="center" style="font-size:14px">IP Bans that is a ban that is based on the provider "Xortify" are with your IP Can be removed by going to <a href="http://xortify.com/modules/unban/">XORTIFY DOT COM</a><br/><br/>This ban is not permenant and will drop in 3 months!</p>');
	
	// Version 2.40
	define('_XOR_BAN_PHP_TYPE','Project Honey Pot Ban - Details:');
	define('_XOR_BAN_PHP_OCTETA','Number of Days');
	define('_XOR_BAN_PHP_OCTETB','Severity');
	define('_XOR_BAN_PHP_OCTETC','Scan Mode');
	define('_XOR_BAN_SPIDER_TYPE','Spider Block - $_POST Variables are:');
	define('_XOR_BAN_SFS_TYPE','Stop Forum Spam Ban - Details:');
	define('_XOR_BAN_SFS_EMAIL_FREQ','Email Frequency');
	define('_XOR_BAN_SFS_EMAIL_LASTSEEN','Email Last Seen');
	define('_XOR_BAN_SFS_USERNAME_FREQ','Usename Frequency');
	define('_XOR_BAN_SFS_USERNAME_LASTSEEN','Username Last Seen');
	define('_XOR_BAN_SFS_IP_FREQ','IP Frequency');
	define('_XOR_BAN_SFS_IP_LASTSEEN','IP Last Seen');
	
	// Version 2.44
	define('_XOR_BAN_XORT_KEY', 'Field');
	define('_XOR_BAN_XORT_MATCH', 'Match');
	define('_XOR_BAN_XORT_LENGTH', 'Length');
	
	// Version 4.03
	define('_XOR_SPAM', 'SPAM');
	define('_XOR_SPAM_CONTENTS', 'Spam Contents');
	define('_XOR_SPAM_PAGETITLE', 'You have been blocked for trying to submit Spam!');
	define('_XOR_SPAM_DESCRIPTION', '<p align="center"><img align="middle" src="'.XOOPS_URL.'/modules/xortify/images/accessdenied.png"></p><p align="center" style="font-size:18px">You have been blocked from our site for submitting spam by Xortify, this is possibly done by one of our third parties! You will have '.($GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['moduleConfig']['spams_allowed']-$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY]['spams']) .' attempts left then you will be banned!</p><div style="clear:both; height: 25px;">&nbsp;</div><p align="center" style="font-size:14px">IP Bans that is a ban that is based on the provider "Xortify" are with your IP Can be removed by going to <a href="http://xortify.com/modules/unban/">XORTIFY DOT COM</a><br/><br/>This ban is not permenant and will drop in 3 months!</p>');
?>