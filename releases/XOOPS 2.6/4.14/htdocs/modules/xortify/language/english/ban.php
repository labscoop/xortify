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
 * @Version:		4.11 Final (Stable)
 * @copyright:		Chronolabs Cooperative 2013 © Simon Roberts (www.simonaroberts.com)
 * @download:		http://sourceforge.net/projects/xortify
 * @file:			ban.php		
 * @description:	Language defines for banning and banning notices.
 * @date:			26/07/2013 19:40 AEST
 * @license:		GNU3 (http://web.labs.coop/public/legal/general-public-licence/13,3.html)
 * @package			xortify
 * @subpackage		language
 * @author			Simon A. Roberts - wishcraft (simon@labs.coop)

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
	
	// Version 4.06
	define('_XOR_WORDS', 'Minimum Word Criteria');
	define('_XOR_SPAM', 'Spam Heuristics');
	define('_XOR_WORDS_PAGETITLE', 'Minimum Word Criteria not met!');
	define('_XOR_SPAM_PAGETITLE', 'Spam Heuristics Flagged!');
	define('_XOR_WORDS_DESCRIPTION', '<p align="center"><img align="middle" src="'.XOOPS_URL.'/modules/xortify/images/accessdenied.png"></p><p align="center" style="font-size:18px">You have been blocked from our site by Xortify, this is because you didn\'t meet the minimum word requirements for a submissions; you cannot be banned for this you will have to press the back button and try again!</p>');
	define('_XOR_SPAM_DESCRIPTION', '<p align="center"><img align="middle" src="'.XOOPS_URL.'/modules/xortify/images/accessdenied.png"></p><p align="center" style="font-size:18px">You have been blocked for submitted spam by our spam heuristic checking system Xortify, this is possibly done by one of our third parties like Stop Forum Spam or Project Honeypot, you will have to check the provider of the ban!</p><div style="clear:both; height: 25px;">&nbsp;</div><p align="center" style="font-size:14px">You can occur an IP Bans in a certain number of these; that is a ban that is based on the provider "Xortify" are with your IP Can be removed by going to <a href="http://xortify.com/modules/unban/">XORTIFY DOT COM</a><br/><br/>This ban is not permenant and will drop in 3 months!</p>');
?>