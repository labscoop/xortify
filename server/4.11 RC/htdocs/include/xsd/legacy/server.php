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
 *  server_xsd()
 *
 *  @subpackage plugin
 *
 *  @return array
 */
function server_xsd(){
	$xsd = array();
	$i=0;
	$data = array();
			$data[] = array("name" => "username", "type" => "string");
			$data[] = array("name" => "password", "type" => "string");	
			$data[] = array("name" => "server", "type" => "string");
			$data[] = array("name" => "replace", "type" => "string");	
			$data[] = array("name" => "search", "type" => "string");
			$data[] = array("name" => "crc", "type" => "string");
											 
	$xsd['request'][$i]['items']['data'] = $data;
	$xsd['request'][$i]['items']['objname'] = 'server';	
	$i=0;
	return $xsd;
}


/*	Provide WSDL for function
 *  server_wsdl()
 *
 *  @subpackage plugin
 *
 *  @return array
 */
function server_wsdl(){

}

/*	Provide WSDL Service for function
 *  server_wsdl_service()
 *
 *  @subpackage plugin
 *
 *  @return array
 */
 function server_wsdl_service(){

}


?>