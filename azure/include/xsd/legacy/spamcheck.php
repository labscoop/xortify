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
 *  spamcheck_xsd()
 *
 *  @subpackage plugin
 *
 *  @return array
 */
function spamcheck_xsd(){
	$xsd = array();
	$i=0;
	$data = array();
	$data[] = array("name" => "username", "type" => "string");
	$data[] = array("name" => "password", "type" => "string");	
	$data[] = array("name" => "poll", "type" => "string");
	$data[] = array("name" => "content", "type" => "string");
	$data[] = array("name" => "adult", "type" => "boolean");
	$data[] = array("name" => "uname", "type" => "string");
	$data[] = array("name" => "name", "type" => "string");
	$data[] = array("name" => "email", "type" => "string");
	$data[] = array("name" => "ip", "type" => "string");
	$data[] = array("name" => "ip", "type" => "sessid");
	$xsd['request'][$i]['items']['data'] = $data;
	$xsd['request'][$i]['items']['objname'] = 'request';

	$i=0;
	$data = array();
	$data[] = array("name" => "spam", "type" => "boolean");
	$data[] = array("name" => "lower", "type" => "integer");
	$data[] = array("name" => "higher", "type" => "integer");
	$data[] = array("name" => "score", "type" => "float");
	$xsd['response'][$i]['items']['data'] = $data;
	$xsd['response'][$i]['items']['objname'] = 'response';
	return $xsd;
}


/*	Provide WSDL for function
 *  spamcheck_wsdl()
 *
 *  @subpackage plugin
 *
 *  @return array
 */
function spamcheck_wsdl(){

}

/*	Provide WSDL Service for function
 *  spamcheck_wsdl_service()
 *
 *  @subpackage plugin
 *
 *  @return array
 */
function spamcheck_wsdl_service(){

}


?>