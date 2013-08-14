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

if (!function_exists('ban_diff')) {

	/*	Gets the count difference between hostage and client cloud services
	 *  ban_diff($username, $password)
	 *
	 *  @subpackage plugin
	 *
	 *  @param string $username 	Xortify username for function
	 *  @param string $password 	Xortify password or md5 hash of password for function
	 *  @return array
	 */	
	function ban_diff($username, $password) {
		global $xoopsModuleConfig, $xoopsDB;
	
		if ($xoopsModuleConfig['site_user_auth']==1){
			if ($ret = check_for_lock(basename(__FILE__),$username,$password)) { return $ret; }
			if (!checkright(basename(__FILE__),$username,$password)) {
				mark_for_lock(basename(__FILE__),$username,$password);
				return array('ErrNum'=> 9, "ErrDesc" => 'No Permission for plug-in');
			}
		}
		
		$soap_client = new soapclient(NULL, array('location' => XORTIFY_API_LOCAL, 'uri' => XORTIFY_API_URI));
		
		$status_remote = $soap_client->__soapCall('next_id', array(      		"username"	=> 	USERNAME, 
																				"password"	=> 	PASSWORD,
																				"tablename" =>	'ban_member'));
		$remote_member_id = 0;
		if (is_numeric($status_remote['next_id'])>0)
			$remote_member_id = $status_remote['next_id'];
		
		$next_increment 	= 0;
		$qShowStatus 		= "SHOW TABLE STATUS LIKE '".$GLOBALS['xoopsDB']->prefix('ban_member')."'";
		$qShowStatusResult 	= $GLOBALS['xoopsDB']->queryF($qShowStatus);
		$row = $GLOBALS['xoopsDB']->fetchArray($qShowStatusResult);
		$local_member_id = $row['Auto_increment'];
		unset($row['Auto_increment']);
				
		return 	array(	'diff' 			=> 	intval($remote_member_id-$local_member_id), 
						'remote_status' => 	$status_remote['status'], 
						'local_status' 	=> 	$row,
						'remote_nextid'	=>	$remote_member_id,
						'local_nextid'	=>	$local_member_id
				 );
	}
}
?>