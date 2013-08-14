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
if (!function_exists('seolinks')) {

	/*	Provides a list of cloud SEO Links
	 *  seolinks($username, $password, $records)
	 *
	 *  @subpackage plugin
	 *
	 *  @param string $username 	Xortify username for function
	 *  @param string $password 	Xortify password or md5 hash of password for function
	 *  @param integer $records 	Provides Number of Promotional Links from the Spider Statistics
	 *  @return array
	 */	
	function seolinks($username, $password, $records) {
		global $xoopsModuleConfig, $xoopsDB;
	
		if ($xoopsModuleConfig['site_user_auth']==1){
			if ($ret = check_for_lock(basename(__FILE__),$username,$password)) { return $ret; }
			if (!checkright(basename(__FILE__),$username,$password)) {
				mark_for_lock(basename(__FILE__),$username,$password);
				return array('ErrNum'=> 9, "ErrDesc" => 'No Permission for plug-in');
			}
		}
	
	
		$records = ($records!=0)?intval($records):12;
	
		$sql = "SELECT DISTINCT `uri`, `sitename` FROM ".$xoopsDB->prefix('spiders_statistics'). ' WHERE `sitename` <> \'\' order by `when` DESC limit '.intval($records);
		$result = $xoopsDB->query($sql);
		$ret = array();
		while($robot = $xoopsDB->fetchArray($result) ){
			$id++;
			foreach(array( 'uri','sitename' ) as $field)
				$ret[$id][$field] = urldecode($robot[$field]);
			
			$url = parse_url(urldecode($robot['uri']));
			$ret[$id]['host'] = $url['host'];
		}
		return array("links" => count($ret), "made" => time(), "seolinks" => $ret);
	
	}
}
?>