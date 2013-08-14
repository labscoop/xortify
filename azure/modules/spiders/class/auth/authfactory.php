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

class XortifyAuthFactory
{

	/**
	 * Get a reference to the only instance of authentication class
	 * 
	 * if the class has not been instantiated yet, this will also take 
	 * care of that
	 * 
	 * @static
	 * @return      object  Reference to the only instance of authentication class
	 */
	function &getAuthConnection($uname, $xortify_auth_method = 'soap')
	{
		static $auth_instance;		
		if (!isset($auth_instance)) {
			require_once XOOPS_ROOT_PATH.'/modules/xortify/class/auth/auth.php';
			// Verify if uname allow to bypass LDAP auth 
			$file = XOOPS_ROOT_PATH . '/modules/xortify/class/auth/auth_' . $xortify_auth_method . '.php';			
			require_once $file;
			$class = 'XortifyAuth' . ucfirst($xortify_auth_method);
			switch ($xortify_auth_method) {
				case 'soap';
					$dao = null;
					break;

			}
			$auth_instance = new $class($dao);
		}
		return $auth_instance;
	}

}

?>
