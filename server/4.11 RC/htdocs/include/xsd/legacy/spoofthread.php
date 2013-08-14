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
 *  spoofthread_xsd()
*
*  @subpackage plugin
*
*  @return array
*/
function spoofthread_xsd(){
	$xsd = array();
	$i=0;
	$data = array();
			$data[] = array("name" => "username", "type" => "string");
			$data[] = array("name" => "password", "type" => "string");
			$data[] = array("name" => "subject", "type" => "string");
			$data[] = array("name" => "uri", "type" => "string");
			$data[] = array("name" => "ip", "type" => "string");
			$data[] = array("name" => "language", "type" => "string");
											 
	$xsd['request'][$i]['items']['data'] = $data;
	$xsd['request'][$i]['items']['objname'] = 'var';	
	$i=0;
	$xsd['response'][$i] = array("name" => "form", "type" => "string");
	$datab=array();
	$datab[] = array("name" => "element", "type" => "string");
	$data = array();
		$data[] = array("items" => array("data" => $datab, "objname" => "elements"));
	$i++;
	$xsd['response'][$i]['items']['data'] = $data;
	$xsd['response'][$i]['items']['objname'] = 'data';
	$i++;
	$xsd['response'][$i] = array("name" => "action", "type" => "string");
	$i++;
	$xsd['response'][$i] = array("name" => "method", "type" => "string");
	$datab[] = array("name" => "css", "type" => "string");
	$data = array();
	$data[] = array("items" => array("data" => $datab, "objname" => "css"));
	$i++;
	$xsd['response'][$i]['items']['data'] = $data;
	$xsd['response'][$i]['items']['objname'] = 'css';
	
	return $xsd;
}


/*	Provide WSDL for function
 *  spoofthread_wsdl()
 *
 *  @subpackage plugin
 *
 *  @return array
 */
function spoofthread_wsdl(){

}

/*	Provide WSDL Service for function
 *  spoofthread_wsdl_service()
 *
 *  @subpackage plugin
 *
 *  @return array
 */
function spoofthread_wsdl_service(){

}


?>