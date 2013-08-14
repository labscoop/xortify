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

	define('_AM_SPIDERS_IMPORTFILE', 'Select Robot Data file to Import');
	define('_AM_SPIDERS_FILE', 'Files available in resource');
	define('_AM_SPIDERS_FILEDESC', 'If you wish to get another copy of supported files please goto <a href="http://www.xortify.com/robotstxt,0,18.html">www.xortify.com</a>');
	define('_AM_SPIDERS_IMPORTAPI', 'Select API Data file to Import');
	define('_AM_SPIDERS_IMPORTAPIDESC', 'If you wish to link with the api signup at <a href="http://www.xortify.com/robotstxt,0,18.html">www.xortify.com</a>');

	define('_MERGE', 'Merge with Live');

	define('_AM_SPIDERS_TXTBOX_EXCLUSION', 'User Agent Exclusion:');
	define('_AM_SPIDERS_TXTBOX_USERAGENT', 'User Agent:');
	define('_AM_SPIDERS_IMPORTCOMPLETE', 'Import is complete');
	define('_AM_SPIDERS_DATADELETEDSUCCESSFULLY', 'Data Deleted Succesfully');
	define('_AM_SPIDERS_DATADELETEDUNSUCCESSFULLY', 'Data was not Deleted Succesfully - Record could be damaged now!');
	define('_AM_SPIDERS_DATASAVEDSUCCESSFULLY', 'Data saved successfully');
	define('_AM_SPIDERS_DATASAVEDUNSUCCESSFULLY', 'Data did not saved successfully');
		
	define('_AM_SPIDERS_CONFIRM_DELETE', 'Are you sure you wish to delete the spider %s!');
	
	define('_AM_SPIDERS_EDITSPIDER', 'Edit Spider %s');
	define('_AM_SPIDERS_NEWSSPIDER', 'New Spider');
	define('_AM_SPIDERS_TXTBOX_ROBOTID', 'Robot Identity String:');
	define('_AM_SPIDERS_TXTBOX_ROBOTID_DESC', '');
	define('_AM_SPIDERS_TXTBOX_ROBOTNAME', 'Robot Name');
	define('_AM_SPIDERS_TXTBOX_ROBOTNAME_DESC', '');
	define('_AM_SPIDERS_TXTBOX_COVERURL', 'Cover URL:');
	define('_AM_SPIDERS_TXTBOX_COVERURL_DESC', 'The URL which provides cover of the robot');
	define('_AM_SPIDERS_TXTBOX_DETAILURL', 'Details URL:');
	define('_AM_SPIDERS_TXTBOX_DETAILURL_DESC', 'Details URL:');
	define('_AM_SPIDERS_TXTBOX_OWNERNME', 'Owner Name');
	define('_AM_SPIDERS_TXTBOX_OWNERNAME_DESC', 'Owners Name of robot');
	define('_AM_SPIDERS_TXTBOX_OWNERURL', 'Owners URL');
	define('_AM_SPIDERS_TXTBOX_OWNERURL_DESC', 'Owners URL for the robot');
	define('_AM_SPIDERS_TXTBOX_OWNEREMAIL', 'Owners eMail:');
	define('_AM_SPIDERS_TXTBOX_OWNEREMAIL_DESC', 'Owner of the robots email address!');
	define('_AM_SPIDERS_TXTBOX_STATUS', 'Status:');
	define('_AM_SPIDERS_TXTBOX_STATUS_DESC', 'Status of robot!');
	define('_AM_SPIDERS_TXTBOX_PURPOSE', 'Purpose:');
	define('_AM_SPIDERS_TXTBOX_PURPOSE_DESC', 'Descirption of purpose');								
	define('_AM_SPIDERS_TXTBOX_TYPE', 'Type:');
	define('_AM_SPIDERS_TXTBOX_TYPE_DESC', 'Type of robot for the web');
	define('_AM_SPIDERS_TXTBOX_AVAILABILITY', 'Availability');
	define('_AM_SPIDERS_TXTBOX_AVAILABILITY_DESC', 'Available on the web now!');
	define('_AM_SPIDERS_TXTBOX_EXCLUSION_DESC', 'Exclusion Matchable');
	define('_AM_SPIDERS_TXTBOX_EXCLUSIONUSERAGENT', 'Exclusion Useragent:');
	define('_AM_SPIDERS_TXTBOX_EXCLUSIONUSERAGENT_DESC', 'Byte text matchable sequence for Useraget (Case Insensitive)');
	define('_AM_SPIDERS_TXTBOX_NOINDEX', 'No Index:');
	define('_AM_SPIDERS_TXTBOX_NOINDEX_DESC', 'No Index');
	define('_AM_SPIDERS_TXTBOX_HOST', 'Host:');
	define('_AM_SPIDERS_TXTBOX_HOST_DESC', 'Host details of the robot/spider/crawler');
	define('_AM_SPIDERS_TXTBOX_FROM', 'From:');
	define('_AM_SPIDERS_TXTBOX_FROM_DESC', 'From to anywhere');
	define('_AM_SPIDERS_TXTBOX_USERAGENT_DESC', 'User agent matchable to User Agent of the robot (Case Insensitive)');
	define('_AM_SPIDERS_TXTBOX_LANGUAGE', 'Language:');
	define('_AM_SPIDERS_TXTBOX_LANGUAGE_DESC', 'Lanuage it is programmed in');
	define('_AM_SPIDERS_TXTBOX_DESCRIPTION', 'Description:');
	define('_AM_SPIDERS_TXTBOX_DESCRIPTION_DESC', 'Description of the robot');
	define('_AM_SPIDERS_TXTBOX_HISTORY', 'History:');
	define('_AM_SPIDERS_TXTBOX_HISTORY_DESC', 'Breif history of the robot');
	define('_AM_SPIDERS_TXTBOX_ENVIRONMENT', 'Environment:');
	define('_AM_SPIDERS_TXTBOX_ENVIRONMENT_DESC', 'Enrvironment the Robot runs in');
	define('_AM_SPIDERS_TXTBOX_MODIFIEDDATE', 'Modified Date:');
	define('_AM_SPIDERS_TXTBOX_MODIFIEDDATE_DESC', 'The Date it was last modified');
	define('_AM_SPIDERS_TXTBOX_MODIFIEDBY', 'Modified By:');
	define('_AM_SPIDERS_TXTBOX_MODIFIEDBY_DESC', 'Your Name');
	define('_AM_SPIDERS_TXTBOX_SAFEUSERAGENT', 'Safe User Agent:');
	define('_AM_SPIDERS_TXTBOX_SAFEUSERAGENT_DESC', 'This is the safe text matchable string for matching the user agent of the bot to a record! (Case Insensitive)');
	
												
	if(!defined('_MI_SPIDERS_DIRNAME'))
		define('_MI_SPIDERS_DIRNAME','spiders');
	if(!defined('_MI_SPIDERS_GROUP_TYPE'))
		define('_MI_SPIDERS_GROUP_TYPE', 'Spider');
	if(!defined('_MI_SPIDERS_GROUP_NAME'))
		define('_MI_SPIDERS_GROUP_NAME', 'Robots, Crawlers & Spiders');
	if(!defined('_MI_SPIDERS_GROUP_DESCRIPTION'))
		define('_MI_SPIDERS_GROUP_DESCRIPTION', 'Robots, Crawlers & Spiders that scan your website and have authority.');	
		
	define('_AM_SPIDERS_INLINE', '<div style="clear:both; height 10px;">&nbsp;</div><div style="clear:both; height 10px;"><center><img src="http://www.chronolabs.coop/images/banners/loader/supportimage.php?flash=false" /></center></div><div style="clear:both;">Chronolabs offer limited free support should you want some development work done please contact us <a href="http://www.chronolabs.org.au/liaise/">on the question for a quote form.</a> We offer a wide range of XOOPS Professional Solution and have options for Basic SEO and marketing of your site as well as Search Engine Optimization for <a href="http://www.xoops.org/">XOOPS</a>. If you are looking for work done with this module/application or are looking for development on your site please contact us.</div>');
	
	define('_AM_SPIDERS_FRM_TITLE', 'Sign-up to Xortify.com - Fortify your XOOPS');	
	define('_AM_SPIDERS_FRM_UNAME', 'Your Xortify Username');
	define('_AM_SPIDERS_FRM_EMAIL', 'Your Email Address');
	define('_AM_SPIDERS_FRM_PASS', 'Your Password');
	define('_AM_SPIDERS_FRM_VPASS', 'Validate Your Password');
	define('_AM_SPIDERS_FRM_URL', 'Your Primary URL');
	define('_AM_SPIDERS_FRM_VIEWEMAIL', 'View Your Email');
	define('_AM_SPIDERS_FRM_TIMEZONE', 'Your Timezone');
	define('_AM_SPIDERS_FRM_DISCLAIMER', 'Xortify Disclaimer');
	define('_AM_SPIDERS_FRM_DISCLAIMER_AGREE', 'Agree to Xortify Disclaimer');
	define('_AM_SPIDERS_FRM_PUZZEL', 'Solve Puzzel');
	define('_AM_SPIDERS_FRM_REGISTER', 'Register with Xortify.com');

	define('_AM_SPIDERS_USERCREATED_PLEASEACTIVATE', 'Check Your Email for Activation Email, Click on the activation link to activate module!');
	define('_AM_SPIDERS_FRM_NOSOAP_DISCLAIMER', '<strong>There is no API Communication with Xortify.com check you have PHP SOAP installed as an extension and you have no firewalls preventing normal SOAP Protocol Communication. You will not be able to continue until this message has been replaced with the Xortify Disclaimer.<br/><br/>The Submit button below will appear when this is working, xortify is currently <strong>Offline</strong>');
	
	define('_AM_SPIDERS_COMPAIRAPI', 'Compair Database VIA API');
	define('_AM_SPIDERS_APIPROT', 'API Protocol to Use');
	define('_AM_SPIDERS_APIPROTDESC', 'API Protocol to Use on connecting with the Xortify Cloud! <strong>(best selected already)</strong>');
	define('_AM_SPIDERS_API_SOAP', 'SOAP Protocol');
	define('_AM_SPIDERS_API_CURL', 'cURL Method');
	define('_AM_SPIDERS_API_JSON', 'JSON Get Method');
	
	define('_AM_SPIDERS_LIST', 'List of spiders installed now!');
	define('_AM_SPIDERS_LISTMODS', 'Appliable Modifications to your list of spiders installed now!');
	
	define('_AM_SPIDERS_SENDDONE', 'Finished Sending %s as a possibly submitted modification in the cloud!');
	
	
?>