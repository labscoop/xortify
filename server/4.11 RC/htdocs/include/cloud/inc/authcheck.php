<?php
/**
 * Xortify API Function
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Chronolabs Coopertive (Australia)  http://web.labs.coop
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         api
 * @author         	Simon Roberts (wishcraft) - meshy@labs.coop
 */

if (!function_exists('check_auth_class')){
		function check_auth_class($authclass){
			$class_methods = get_class_methods($authclass);	
			foreach ($class_methods as $method_name) {
				switch ($method_name){
				case "validate":
				case "network_disclaimer":
				case "create_user":
				case "check_activation":
					$t++;
					break;
				default:
					break;
				}
			}
		
			if ($t>3)
				return true;
			else
				return false;
		
		}
	}
?>