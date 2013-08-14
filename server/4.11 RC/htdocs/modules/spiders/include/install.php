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

function xoops_module_pre_install_spiders(&$module) {

	$group_handler = &xoops_gethandler( 'group' );
		
	$group =& $group_handler->create();
	$group->setVar('name', _MI_SPIDERS_GROUP_NAME);
	$group->setVar('description', _MI_SPIDERS_GROUP_DESCRIPTION);
	$group->setVar('group_type', _MI_SPIDERS_GROUP_TYPE);
	@$group_handler->insert($group);
	
	// Requested by Trabis
	$groupperm_handler = &xoops_gethandler( 'groupperm' );
	$criteria = new Criteria('gperm_groupid', XOOPS_GROUP_USERS);
	$gperms = $groupperm_handler->getObjects($criteria);
	
	foreach($gperms as $perm) {
		$ngperm = $groupperm_handler->create();
		foreach(array('gperm_itemid', 'gperm_modid', 'gperm_name') as $pras)
			$ngperm->setVar($pras, $perm->getVar($pras));
		$ngperm->setVar('gperm_groupid', $group->getVar('groupid'));
		$groupperm_handler->insert($ngperm);
	}
	
	return true;
		
}

?>