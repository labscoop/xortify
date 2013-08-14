<?php
/**
 * @package     xortify
 * @subpackage  module
 * @description	Sector Network Security Drone
 * @author	    Simon Roberts WISHCRAFT <simon@chronolabs.coop>
 * @copyright	copyright (c) 2010-2013 XOOPS.org
 * @licence		GPL 2.0 - see docs/LICENCE.txt
 */


	// Version 1.17
	define('_XS_IPTYPE_IP4','IPv4');
	define('_XS_IPTYPE_IP6','IPv6');	
	define('_XS_IPTYPE_EMPTY','No IP');
	define('_XS_AM_IPTYPE','IP Type');
	define('_XS_AM_IPADDRESS','IP Address');	
	define('_XS_AM_NETADDRESS','Network Address');
	define('_XS_AM_PROXYIP','Proxy IP');	
	define('_XS_AM_MACADDRESS','Mac Address');
	define('_XS_AM_LONG','Network Pointer');	
	
	define('_XS_AM_BANS','Current Network Bans');	
	
	define('_XS_AM_NOCACHEMSG','<center><font size="+2">EMPTY BAN CACHE!! NOTHING TO DISPLAY!!</font></center>');

	// Version 2.40
	define('_XOR_AM_LOG_H1','Xortify Usage Log');
	define('_XOR_AM_LOG_P','This is the actions that have occured on your system in the last period up unto the log drop date!');
	define('_XOR_AM_TH_ACTION','Action');
	define('_XOR_AM_TH_PROVIDER','Provider');
	define('_XOR_AM_TH_DATE','Date');
	define('_XOR_AM_TH_UNAME','Username');
	define('_XOR_AM_TH_EMAIL','Email');
	define('_XOR_AM_TH_IP4','Ip4');
	define('_XOR_AM_TH_IP6','IP6');
	define('_XOR_AM_TH_PROXY_IP4','Proxy IP4');
	define('_XOR_AM_TH_PROXY_IP6','Proxy IP6');
	define('_XOR_AM_TH_NETWORK_ADDY','Netbios Address');
	define('_XOR_AM_TH_AGENT','User Agent');
	
	// Version 2.56
	define('_XOR_ADMIN_COUNTS','Log Action and Counts');
	define('_XOR_ADMIN_THEREARE_BANNED','Banned Individuals:&nbsp;%s');
	define('_XOR_ADMIN_THEREARE_BLOCKED','Blocked Individuals:&nbsp;%s');
	define('_XOR_ADMIN_THEREARE_MONITORED','Monitored Individuals:&nbsp;%s');
	define('_XOR_ADMIN_THEREARE_PROJECTHONEYPOTORG','Individuals or bots picked up by ProjectHoneypot.org:&nbsp;%s');
	define('_XOR_ADMIN_THEREARE_PROTECTOR','Individuals or bots picked up by Protector Module:&nbsp;%s');
	define('_XOR_ADMIN_THEREARE_SPIDERS','Individuals or bots picked up by Spiders Module:&nbsp;%s');
	define('_XOR_ADMIN_THEREARE_STOPFORUMSPAMCOM','Individuals or bots picked up by StopForumSpam.com:&nbsp;%s');
	define('_XOR_ADMIN_THEREARE_XORTIFY','Individuals or bots picked up by Xortify Cloud:&nbsp;%s');
	define('_XOR_ADMIN_THEREARE_CLOUDEDBANS','Clouded Bans Loaded from Xortify Cloud:&nbsp;%s');
	
	//Version 3.02
	define('_XOR_ADMIN_MAKEDONATE','Make Donation to Further Xortify!');
	define('_XOR_ADMIN_NONETWORKCOMM_DISCLAIMER','The current URI is unaccessable on the selected protocol. This could mean you are either missing a required PHP Extension or have a firewall blocking port 80 to the Xortify Cloud.<br/><br/><strong>You can try changing the protocol selected in preferences to see if this may elivate the communication fawl of your hosting services.</strong>');
	define('_XOR_ADMIN_ERROR_OCCURED','Error Occured Communicating with cloud');
	define('_XOR_ADMIN_ERROR_URL','URL/URI Being Contacted');
	define('_XOR_ADMIN_ERROR_PROTOCOL','Protocol Being Used');
	define('_XOR_ADMIN_SIGNUP_XORTIFY_H2','Signup to the XOOPS fortification cloud');
	define('_XOR_ADMIN_SIGNUP_XORTIFY_P','Here you can signup to the XOOPS fortification cloud called Xortify, the main node for this cloud is featured at http://xortify.com but there can be other nodes, this will prevent spam signup, website harvesting, and general hacking of your site.<br/><br/>It will also share the IP\'s of Bad IP Highlighted by Protector with other people running Xortify.');
	define('_XOR_ADMIN_SIGNUP_XORTIFY_FORM_TD2','Sign-up this Website\'s User Account on the Xortify Cloud.');
	
	// Version 3.05
	define('_XOR_ADMIN_THEREARE_WHENCLEANED','Xortify File Cache Cleaned Last: %s');
	define('_XOR_ADMIN_THEREARE_CLEANINGTOOK','Xortify File Cache Cleaning Took: %s seconds');
	define('_XOR_ADMIN_THEREARE_FILESDELETED','Xortify Cache Files Deleted: %s');
	define('_XOR_ADMIN_THEREARE_BYTESSAVED','Xortify Cache Cleaning Saved: %s bytes');
	define('_XS_AM_CATEGORY','Ban Category');
	define('_XS_AM_BANID','Ban Id:');
		
	// About
	define('_XOR_ADMIN_ABOUT_CHANGELOG','Change log');
	define('_XOR_ADMIN_ABOUT_DESCRIPTION','Description');
	define('_XOR_ADMIN_ABOUT_MODULEINFO','Module Info');
	define('_XOR_ADMIN_ABOUT_MODULESTATUS','Status:');
	define('_XOR_ADMIN_ABOUT_UPDATEDATE','Update date:');
	define('_XOR_ADMIN_ABOUT_WEBSITE',"Website:");
	
	// Version 4.02
	define('_XOR_ADMIN_KEYS', 'System Keys');
	define('_XOR_ADMIN_INSTANCE_KEY', 'Xortify Local Instance Key: %s');
?>