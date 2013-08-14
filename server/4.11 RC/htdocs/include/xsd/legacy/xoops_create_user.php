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
 *  xoops_create_user_xsd()
 *
 *  @subpackage plugin
 *
 *  @return array
 */	
function xoops_create_user_xsd() {
	$xsd = array();
	$i=0;
	$data = array();
	$data[] = array("name" => "username", "type" => "string");
	$data[] = array("name" => "password", "type" => "string");	
	$datab = array();
		$datab[] = array("name" => "user_viewemail", "type" => "integer");
		$datab[] = array("name" => "uname", "type" => "string");		
		$datab[] = array("name" => "email", "type" => "string");
		$datab[] = array("name" => "url", "type" => "string");		
		$datab[] = array("name" => "actkey", "type" => "string");
		$datab[] = array("name" => "pass", "type" => "string");
		$datab[] = array("name" => "timezone_offset", "type" => "string");
		$datab[] = array("name" => "user_mailok", "type" => "integer");
		$datab[] = array("name" => "passhash", "type" => "string");
		$datab[] = array("name" => "rand", "type" => "integer");
	$data[] = array("items" => array("data" => $datab, "objname" => "user"));
		$data_c = array();
		$data_c[] = array("name" => "sitename", "type" => "string");
		$data_c[] = array("name" => "adminmail", "type" => "string");
		$data_c[] = array("name" => "xoops_url", "type" => "string");
	$data[] = array("items" => array("data" => $datab, "objname" => "siteinfo"));
	
	$i++;
	$xsd['request'][$i]['items']['data'] = $data;
	$xsd['request'][$i]['items']['objname'] = 'var';
	$i=0;
	$xsd['response'][$i] = array("name" => "ERRNUM", "type" => "integer");
	$data = array();
		$data[] = array("name" => "id", "type" => "integer");
		$data[] = array("name" => "user", "type" => "string");		
		$data[] = array("name" => "text", "type" => "string");
	$i++;
	$xsd['response'][$i]['items']['data'] = $data;
	$xsd['response'][$i]['items']['objname'] = 'RESULT';
	
	return $xsd;
}
	
	
/*	Provide WSDL for function
 *  xoops_create_user_wsdl()
 *
 *  @subpackage plugin
 *
 *  @return array
 */
function xoops_create_user_wsdl() {

}

/*	Provide WSDL Service for function
 *  xoops_create_user_wsdl_service()
 *
 *  @subpackage plugin
 *
 *  @return array
 */
function xoops_create_user_wsdl_service() {
	
}
	



?>