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
 *  spiderstat_xsd()
 *
 *  @subpackage plugin
 *
 *  @return array
 */
function spiderstat_xsd(){
	$xsd = array();
	$i=0;
	$data = array();
			$data[] = array("name" => "username", "type" => "string");
			$data[] = array("name" => "password", "type" => "string");	
		$datab=array();
		$datab[] = array("name" => "uri", "type" => "string");
		$datab[] = array("name" => "useragent", "type" => "string");
		$datab[] = array("name" => "netaddy", "type" => "string");
		$datab[] = array("name" => "ip", "type" => "string");
		$datab[] = array("name" => "server-ip", "type" => "string");
		$datab[] = array("name" => "when", "type" => "string");
		$datab[] = array("name" => "sitename", "type" => "string");
		$datab[] = array("name" => "robot-id", "type" => "string");
		$datab[] = array("name" => "robot-name", "type" => "string");
			$data[] = array("items" => array("statistic" => $datab, "objname" => "statistic"));
	$xsd['request'][$i]['items']['data'] = $data;
	$xsd['request'][$i]['items']['objname'] = 'data';	
	
	$xsd['response'][] = array("name" => "ban_made", "type" => "boolean");
	$xsd['response'][] = array("name" => "made", "type" => "integer");
	return $xsd;
}


/*	Provide WSDL for function
 *  spiderstat_wsdl()
 *
 *  @subpackage plugin
 *
 *  @return array
 */
function spiderstat_wsdl(){

}

/*	Provide WSDL Service for function
 *  spiderstat_wsdl_service()
 *
 *  @subpackage plugin
 *
 *  @return array
 */
function spiderstat_wsdl_service(){

}


?>