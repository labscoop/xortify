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
 *  checksfsbans_xsd()
 *
 *  @subpackage plugin
 *
 *  @return array
 */
function checksfsbans_xsd(){
	$xsd = array();
	$i=0;
	$data = array();
			$data[] = array("name" => "username", "type" => "string");
			$data[] = array("name" => "password", "type" => "string");	
		$datab=array();
		$datab[] = array("name" => "category_id", "type" => "integer");
		$datab[] = array("name" => "email", "type" => "string");
		$datab[] = array("name" => "uid", "type" => "integer");
		$datab[] = array("name" => "uname", "type" => "string");
		$datab[] = array("name" => "ip4", "type" => "string");
		$datab[] = array("name" => "ip6", "type" => "string");
		$datab[] = array("name" => "long", "type" => "string");
		$datab[] = array("name" => "proxy-ip4", "type" => "string");
		$datab[] = array("name" => "proxy-ip6", "type" => "string");
		$datab[] = array("name" => "network-addy", "type" => "string");
		$datab[] = array("name" => "mac-addy", "type" => "string");
		$datab[] = array("name" => "made", "type" => "integer");
			$data[] = array("items" => array("data" => $datab, "objname" => "ipdata"));
	$xsd['request'][$i]['items']['data'] = $data;
	$xsd['request'][$i]['items']['objname'] = 'var';	
	
	$xsd['response'][] = array("name" => "success", "type" => "integer");
	$datab=array();	
		$datab[] = array("name" => "lastseen", "type" => "string");
		$datab[] = array("name" => "frequency", "type" => "integer");
		$datab[] = array("name" => "appear", "type" => "integer");
	$xsd['response'][] = array("items" => array("data" => $datab, "objname" => "username"));							 
	$xsd['response'][] = array("items" => array("data" => $datab, "objname" => "email"));
	$xsd['response'][] = array("items" => array("data" => $datab, "objname" => "ip"));
	
	return $xsd;
}


/*	Provide WSDL for function
 *  checksfsbans_wsdl()
 *
 *  @subpackage plugin
 *
 *  @return array
 */
function checksfsbans_wsdl(){

}

/*	Provide WSDL Service for function
 *  checksfsbans_wsdl_service()
 *
 *  @subpackage plugin
 *
 *  @return array
 */
 function checksfsbans_wsdl_service(){

}

?>