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
 * @subpackage		JSON
 * @author         	Simon Roberts (wishcraft) - meshy@labs.coop
 */

include (dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR.'mainfile.php');
if (empty($_POST)&&empty($_GET)) {
	header('Location: '.XOOPS_URL);
	exit;
} else {
		include (dirname(__FILE__).DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'server.php');;
	exit;
}
?>