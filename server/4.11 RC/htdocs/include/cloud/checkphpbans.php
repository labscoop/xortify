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
xoops_loadLanguage('server', 'global');

if (!class_exists('Services_JSON'))
	include_once($GLOBALS['xoops']->path('/include/libs/JSON.php'));

if (!function_exists('obj2array')) {

	/*	Converts Object to Array.
	 *  obj2array($objects)
	 *
	 *  @subpackage functions
	 *
	 *  @param object $objects 		Object to be convert to an array
	 *  @return array
	 */	
	function obj2array($objects) {
		$ret = array();
		foreach($objects as $key => $value) {
			if (is_a($value, 'stdClass')) {
				$ret[$key] = (array)obj2array($value);
			} elseif (is_array($value)) {
				$ret[$key] = obj2array($value);
			} else {
				$ret[$key] = $value;
			}
		}
		return $ret;
	}
}

if (!function_exists('checkphpbans_dolookup')) {
	
	/*	Gets from Project Honeypot the current ban information for the data provided
	 *  checksfsbans_GetFromToHost($data)
	 *
	 *  @subpackage plugin
	 *
	 *  @param string $apikey 		API Key for Project Honeypot
	 *  @param string $ip 			IPv4 Address
	 *  @return string
	 */
	function checkphpbans_dolookup($apikey,$ip){ 
		$itman = $apikey . "." . $ip . "." . "dnsbl.httpbl.org";
		$host = gethostbyname($itman);
		return ($host); 
	}
}

// Define the method as a PHP function
if (!function_exists('checkphpbans')) {

	/*	Gets from Project Honeypot the current ban information for the IP Data and User Data provided
	 *  checksfsbans($username, $password, $ipdata)
	 *
	 *  @subpackage plugin
	 *
	 *  @param string $username 	Xortify username for function
	 *  @param string $password 	Xortify password or md5 hash of password for function
	 *  @param array $ipdata 		Associative Array [ip4]
	 *  @return array
	 */
	function checkphpbans($username, $password, $ipdata) {
		
		if ($xoopsModuleConfig['site_user_auth']==1){
			if ($ret = check_for_lock(basename(__FILE__),$username,$password)) { return $ret; }
			if (!checkright(basename(__FILE__),$username,$password)) {
				mark_for_lock(basename(__FILE__),$username,$password);
				return array('ErrNum'=> 9, "ErrDesc" => 'No Permission for plug-in');
			}
		}
		
		
		$what2lookup = implode('.', array_reverse(explode('.',$ipdata['ip4'])));
		$octet = implode('.', checkphpbans_dolookup(_MI_SERVER_PROJECTHONEYPOT_BL_KEY, $what2lookup));
		
		return array('success'	=>	($octet[0]=='127')?true:false,
					 'octeta'	=>	$octet[1],
					 'octetb'	=>	$octet[2],
					 'octetc'	=>	$octet[3]);
	}
}
?>