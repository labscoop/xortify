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
 * @Version:		3.10 Final (Stable)
 * @copyright:		Chronolabs Cooperative 2013 � Simon Roberts (www.simonaroberts.com)
 * @download:		http://sourceforge.net/projects/xortify
 * @file:			emails_links.php
 * @description:	Emails Linking Registrar in Xortify Cloud
 * @date:			30/03/2012 19:34 AEST
 * @license:		GNU3 (http://web.labs.coop/public/legal/general-public-licence/13,3.html)
 * @package			xortify
 * @subpackage		classes
 * @author			Simon A. Roberts - wishcraft (simon@labs.coop)

 *
 * Table:
 * 
 * 		CREATE TABLE `xortify_emails_links` (
 *		 `elid` mediumint(64) unsigned NOT NULL AUTO_INCREMENT,
 *		 `eid` mediumint(32) unsigned NOT NULL DEFAULT '0',
 *		 `uid` int(13) NOT NULL DEFAULT '0',
 *		 `ip` varchar(128) NOT NULL DEFAULT '127.0.0.1',
 *		) ENGINE=INNODB DEFAULT CHARSET=utf8;
 *
 */


if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}
/**
 * Class for Blue Room Xortify Emails Linking
 * @author Simon Roberts <simon@labs.coop>
 * @package xortify
 */
class XortifyEmails_links extends XoopsObject
{

    /*
	 * Constructor
	 */
	function XortifyEmails_links($id = null)
    {
        $this->initVar('elid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('eid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('uid', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('ip', XOBJ_DTYPE_TXTBOX, '127.0.0.1', false, 128);
   }

   /*
    * Returns Object as Array
    */
    function toArray() {
    	$ret = parent::toArray();
    	foreach($ret as $key => $value)
    		$ret[str_replace('-', '_', $key)] = $value;
    	return $ret;
    }
    
}


/**
* XOOPS Xortify Emails Linking handler class.
* This class is responsible for providing data access mechanisms to the data source
* of XOOPS user class objects.
*
* @author  Simon Roberts <simon@chronolabs.coop>
* @package xortify
*/
class XortifyEmails_linksHandler extends XoopsPersistableObjectHandler
{
    /*
	 * Constructor
	 */
	function __construct(&$db) 
    {
		$this->db = $db;
        parent::__construct($db, 'xortify_emails_links', 'XortifyEmails_links', "elid", "eid");
    }
}

?>