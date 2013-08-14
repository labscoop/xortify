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
 *  banned_xsd()
 *
 *  @subpackage plugin
 *
 *  @return array
 */
function banned_xsd(){
	$xsd = array();
	$i=0;
	$data = array();
			$data[] = array("name" => "username", "type" => "string");
			$data[] = array("name" => "password", "type" => "string");	
		$datab=array();
		$datab[] = array("name" => "category_id", "type" => "integer");
		$datab[] = array("name" => "uid", "type" => "integer");
		$datab[] = array("name" => "uname", "type" => "string");
		$datab[] = array("name" => "email", "type" => "string");
		$datab[] = array("name" => "ip4", "type" => "string");
		$datab[] = array("name" => "ip6", "type" => "string");
		$datab[] = array("name" => "long", "type" => "string");
		$datab[] = array("name" => "proxy-ip4", "type" => "string");
		$datab[] = array("name" => "proxy-ip6", "type" => "string");
		$datab[] = array("name" => "network-addy", "type" => "string");
		$datab[] = array("name" => "mac-addy", "type" => "string");
		$datab[] = array("name" => "made", "type" => "integer");
			$data[] = array("items" => array("data" => $datab, "objname" => "banneds"));
		$datac = array();	
		$datac[] = array("name" => "uid", "type" => "string");
		$datac[] = array("name" => "uname", "type" => "string");											 
		$datac[] = array("name" => "comment", "type" => "string");				
			$data[] = array("items" => array("data" => $datac, "objname" => "comments"));							 
	$xsd['request'][$i]['items']['data'] = $data;
	$xsd['request'][$i]['items']['objname'] = 'var';	
	
	$xsd['response'][] = array("name" => "banneds", "type" => "boolean");
	$datab=array();	
		$datab[] = array("name" => "errors", "type" => "string");
	$xsd['response'][] = array("items" => array("data" => $datab, "objname" => "errors"));							 
	$xsd['response'][] = array("name" => "made", "type" => "integer");
	return $xsd;
}


/*	Provide WSDL for function
 *  banned_wsdl()
 *
 *  @subpackage plugin
 *
 *  @return array
 */
function banned_wsdl(){

}

/*	Provide WSDL Service for function
 *  banned_wsdl_service()
 *
 *  @subpackage plugin
 *
 *  @return array
 */
 function banned_wsdl_service(){

}


?>