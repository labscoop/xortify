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
 *  spiders_xsd()
 *
 *  @subpackage plugin
 *
 *  @return array
 */
function spiders_xsd(){
	$xsd = array();
	$i=0;
	$data = array();
			$data[] = array("name" => "username", "type" => "string");
			$data[] = array("name" => "password", "type" => "string");	
			$data[] = array("name" => "records", "type" => "integer");	
											 
	$xsd['request'][$i]['items']['data'] = $data;
	$xsd['request'][$i]['items']['objname'] = 'var';	
	$i=0;
	$xsd['response'][$i] = array("name" => "spiders", "type" => "integer");
	$i++;
	$xsd['response'][$i] = array("name" => "made", "type" => "integer");
	$datab=array();
	$datab[] = array("name" => "robot-id", "type" => "string");
	$datab[] = array("name" => "robot-name", "type" => "string");
	$datab[] = array("name" => "robot-cover-url", "type" => "string");
	$datab[] = array("name" => "robot-details-url", "type" => "string");
	$datab[] = array("name" => "robot-owner-name", "type" => "string");
	$datab[] = array("name" => "robot-owner-url", "type" => "string");
	$datab[] = array("name" => "robot-owner-email", "type" => "string");
	$datab[] = array("name" => "robot-status", "type" => "string");
	$datab[] = array("name" => "robot-purpose", "type" => "string");
	$datab[] = array("name" => "robot-type", "type" => "string");
	$datab[] = array("name" => "robot-platform", "type" => "string");
	$datab[] = array("name" => "robot-availability", "type" => "string");
	$datab[] = array("name" => "robot-exclusion", "type" => "string");
	$datab[] = array("name" => "robot-exclusion-useragent", "type" => "string");
	$datab[] = array("name" => "robot-noindex", "type" => "string");
	$datab[] = array("name" => "robot-host", "type" => "string");
	$datab[] = array("name" => "robot-from", "type" => "string");
	$datab[] = array("name" => "robot-useragent", "type" => "string");
	$datab[] = array("name" => "robot-language", "type" => "string");
	$datab[] = array("name" => "robot-description", "type" => "string");
	$datab[] = array("name" => "robot-history", "type" => "string");	

	$datab[] = array("name" => "robot-environment", "type" => "string");
	$datab[] = array("name" => "modified-date", "type" => "string");
	$datab[] = array("name" => "modified-by", "type" => "string");
	$datab[] = array("name" => "robot-safeuseragent", "type" => "string");
	$datab[] = array("name" => "robot-handlesession", "type" => "string");
		$data[] = array("items" => array("data" => $datab, "objname" => "data"));
	$i++;
	$xsd['response'][$i]['items']['data'] = $data;
	$xsd['response'][$i]['items']['objname'] = 'data';
	return $xsd;
}


/*	Provide WSDL for function
 *  spiders_wsdl()
 *
 *  @subpackage plugin
 *
 *  @return array
 */
function spiders_wsdl(){

}

/*	Provide WSDL Service for function
 *  spiders_wsdl_service()
 *
 *  @subpackage plugin
 *
 *  @return array
 */
 function spiders_wsdl_service(){

}


?>