<?php
// $Author: wishcraft $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: Simon Roberts (AKA wishcraft)                                     //
// URL: http://www.chronolabs.org.au                                         //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //

	define('_MI_SPIDERS_DIRNAME','spiders');
	
	define('_MI_SPIDERS_NAME','Robot Manager');
	define('_MI_SPIDERS_DESCRIPTION','This module is for robot management cloud services!');	
	define('_MI_SPIDERS_LOGON', 'Logon Session');
	define('_MI_SPIDERS_LOGONDESC', 'When a robots agent is detected log the bot in under its username.');	
	
	define('_MI_SPIDERS_MM1', 'Robots');
	define('_MI_SPIDERS_MM2', 'Last Here Report');	
	define('_MI_SPIDERS_MM3', 'Save Robots Textfile');
	
	define('_MI_SPIDERS_ADMINMENU1', 'Manage Robots');
	define('_MI_SPIDERS_ADMINMENU2', 'Add Robot');
	define('_MI_SPIDERS_ADMINMENU3', 'List Robot Modifications');	
	define('_MI_SPIDERS_ADMINMENU4', 'Import Robot Definitions');
	define('_MI_SPIDERS_ADMINMENU5', 'Signup to Xortify.com');		
	
	define('_MI_SPIDERS_GROUP_TYPE', 'Spider');
	define('_MI_SPIDERS_GROUP_NAME', 'Robots, Crawlers & Spiders');
	define('_MI_SPIDERS_GROUP_DESCRIPTION', 'Robots, Crawlers & Spiders that scan your website and have authority.');	
	
	define('_MI_SPIDER_RESERVEDPHRASES', 'Protected Keywords from Useragents');
	define('_MI_SPIDER_RESERVEDPHRASES_DESC', 'These phrases cannot be a component used in authentication with the site (Seperate with a <strong>|</strong>)');

	define('_MI_SPIDER_MATCHPERCENTILE', 'User Agent Match Percentile');
	define('_MI_SPIDER_MATCHPERCENTILE_DESC', 'For increased security you and specify the percentile needed for match of a User agent of a spider.');
	
	define('_MI_SPIDER_WEEKSSTATS', 'Number of Weeks Spider Stats are Stored');
	define('_MI_SPIDER_WEEKSSTATS_DESC', 'This is the total number of weeks statistics on any spider, crawler or robot you have are stored!');
	
	define('_MI_SPIDER_HTACCESS', 'Enabled HTACCESS SEO');
	define('_MI_SPIDER_HTACCESS_DESC', 'This enables SEO');

	define('_MI_SPIDER_BASEURL', 'Base URL for SEO');
	define('_MI_SPIDER_BASEURL_DESC', 'Base URL for SEO');

	define('_MI_SPIDER_ENDOFURL', 'End of URL');
	define('_MI_SPIDER_ENDOFURL_DESC', 'File Extension to HTML Files');

	define('_MI_SPIDER_ENDOFURLRSS', 'End of URL');
	define('_MI_SPIDER_ENDOFURLRSS_DESC', 'File Extension to RSS Pages');

	define('_MI_SPIDER_ENDOFURLPDF', 'End of URL');
	define('_MI_SPIDER_ENDOFURLPDF_DESC', 'File Extension to Adobe Acrobat (PDF) Files');
	
	define('_MI_SPIDER_USERNAME', 'Xortify.com Username');
	define('_MI_SPIDER_USERNAME_DESC', 'You can get one of these by going to the menu <a href="'.XOOPS_URL.'/modules/spiders/admin/index.php?op=signup">Sign-up</a>');
	
	define('_MI_SPIDER_PASSWORD', 'Xortify.com Password');
	define('_MI_SPIDER_PASSWORD_DESC', 'You assign one of these by going to the menu <a href="'.XOOPS_URL.'/modules/spiders/admin/index.php?op=signup">Sign-up</a>');

	define('_MI_SPIDER_SEOSHAREME', 'Utilise Xortify.com SEO Sharing');
	define('_MI_SPIDER_SEOSHAREME_DESC', 'You can share your URL Across the Xortify Networking using this option!');
	
	define('_MI_SPIDER_BOTGROUP', 'Group which bots belong to');
	define('_MI_SPIDER_BOTGROUP_DESC', 'Group which bots belong to on the system for SEO Advantage!');

	define('_MI_SPIDER_COMPAIRPERCENTILE', 'User Agent API Compair Percentile');
	define('_MI_SPIDER_COMPAIRPERCENTILE_DESC', 'For increased security of your data this is the percentile of changes you need to lodge a changed robot text as a modification from the api.');

?>
