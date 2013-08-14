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
 *  seolinks_xsd()
 *
 *  @subpackage plugin
 *
 *  @return array
 */
function seolinks_xsd(){
	$xsd = array();
	$i=0;
	$data = array();
			$data[] = array("name" => "username", "type" => "string");
			$data[] = array("name" => "password", "type" => "string");	
			$data[] = array("name" => "records", "type" => "integer");	
											 
	$xsd['request'][$i]['items']['data'] = $data;
	$xsd['request'][$i]['items']['objname'] = 'var';	
	$i=0;
	$xsd['response'][$i] = array("name" => "links", "type" => "integer");
	$i++;
	$xsd['response'][$i] = array("name" => "made", "type" => "integer");
	$datab=array();
	$datab[] = array("name" => "uri", "type" => "string");
	$datab[] = array("name" => "host", "type" => "string");
	$datab[] = array("name" => "sitename", "type" => "string");
		$data[] = array("items" => array("data" => $datab, "objname" => "data"));
	$i++;
	$xsd['response'][$i]['items']['data'] = $data;
	$xsd['response'][$i]['items']['objname'] = 'data';
	return $xsd;
}


/*	Provide WSDL for function
 *  seolinks_wsdl()
 *
 *  @subpackage plugin
 *
 *  @return array
 */
function seolinks_wsdl(){

}

/*	Provide WSDL Service for function
 *  seolinks_wsdl_service()
 *
 *  @subpackage plugin
 *
 *  @return array
 */
 function seolinks_wsdl_service(){

}


?>