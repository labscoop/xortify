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

/*	Provide XSD for function
 *  xoops_authentication_xsd()
 *
 *  @subpackage plugin
 *
 *  @return array
 */
function xoops_authentication_xsd() {
	$xsd = array();
	$i=0;
	$xsd['request'][$i] = array("name" => "username", "type" => "string");
	$xsd['request'][$i++] = array("name" => "password", "type" => "string");	
	$data = array();
		$data[] = array("name" => "username", "type" => "string");
		$data[] = array("name" => "password", "type" => "string");		
	$xsd['request'][$i++]['items']['data'] = $data;
	$xsd['request'][$i]['items']['objname'] = 'auth';
	
	$i=0;
	$xsd['response'][$i] = array("name" => "ERRNUM", "type" => "integer");
	$data = array();
		$data[] = array("name" => "uid", "type" => "integer");
		$data[] = array("name" => "uname", "type" => "string");		
		$data[] = array("name" => "email", "type" => "string");
		$data[] = array("name" => "user_from", "type" => "string");
		$data[] = array("name" => "name", "type" => "integer");
		$data[] = array("name" => "url", "type" => "string");		
		$data[] = array("name" => "user_icq", "type" => "string");
		$data[] = array("name" => "user_sig", "type" => "string");
		$data[] = array("name" => "user_viewemail", "type" => "integer");
		$data[] = array("name" => "user_aim", "type" => "string");		
		$data[] = array("name" => "user_yim", "type" => "string");
		$data[] = array("name" => "user_msnm", "type" => "string");
		$data[] = array("name" => "attachsig", "type" => "integer");
		$data[] = array("name" => "timezone_offset", "type" => "string");		
		$data[] = array("name" => "notify_method", "type" => "integer");
		$data[] = array("name" => "user_occ", "type" => "string");											
		$data[] = array("name" => "bio", "type" => "string");											
		$data[] = array("name" => "user_intrest", "type" => "string");	
		$data[] = array("name" => "user_mailok", "type" => "integer");																			
	$i++;
	$xsd['response'][$i]['items']['data'] = $data;
	$xsd['response'][$i]['items']['objname'] = 'RESULT';
	
	return $xsd;
}
	
	
/*	Provide WSDL for function
 *  xoops_authentication_wsdl()
 *
 *  @subpackage plugin
 *
 *  @return array
 */
function xoops_authentication_wsdl() {

}
	
/*	Provide WSDL Service for function
 *  xoops_authentication_wsdl_service()
 *
 *  @subpackage plugin
 *
 *  @return array
 */
function xoops_authentication_wsdl_service() {
	
}
	


?>