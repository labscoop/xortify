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
 *  xoops_check_activation_xsd()
 *
 *  @subpackage plugin
 *
 *  @return array
 */
function xoops_check_activation_xsd() {
	$xsd = array();
	$i=0;
	$xsd['request'][$i] = array("name" => "username", "type" => "string");
	$xsd['request'][$i++] = array("name" => "password", "type" => "string");	
	$data = array();
		$data[] = array("name" => "uname", "type" => "string");
		$data[] = array("name" => "actkey", "type" => "string");		
		$data_b = array();
			$data_b[] = array("name" => "sitename", "type" => "string");
			$data_b[] = array("name" => "adminmail", "type" => "string");
			$data_b[] = array("name" => "xoops_url", "type" => "string");
		$data[] = array("items" => array("data" => $data_b, "objname" => "siteinfo"));
	$i++;
	$xsd['request'][$i]['items']['data'] = $data;
	$xsd['request'][$i]['items']['objname'] = 'user';
	
	$i=0;
	$xsd['response'][$i] = array("name" => "ERRNUM", "type" => "integer");
	$data = array();
		$data[] = array("name" => "uname", "type" => "integer");
		$data[] = array("name" => "actkey", "type" => "string");		
	$i++;
	$xsd['response'][$i]['items']['data'] = $data;
	$xsd['response'][$i]['items']['objname'] = 'RESULT';
	
	return $xsd;
}
	
	
/*	Provide WSDL for function
 *  xoops_check_activation_wsdl()
 *
 *  @subpackage plugin
 *
 *  @return array
 */
function xoops_check_activation_wsdl() {

}
	
/*	Provide WSDL Service for function
 *  xoops_check_activation_wsdl_service()
 *
 *  @subpackage plugin
 *
 *  @return array
 */
function xoops_check_activation_wsdl_service() {
	
}
	
	

	
?>