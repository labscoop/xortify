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
 *  comments_xsd()
 *
 *  @subpackage plugin
 *
 *  @return array
 */
function comments_xsd(){
	$xsd = array();
	$i=0;
	$data = array();
			$data[] = array("name" => "username", "type" => "string");
			$data[] = array("name" => "password", "type" => "string");	
			$data[] = array("name" => "itemid", "type" => "integer");	
			$data[] = array("name" => "module", "type" => "string");
			
	$xsd['request'][$i]['items']['data'] = $data;
	$xsd['request'][$i]['items']['objname'] = 'var';	
$i=0;
	$xsd['response'][$i] = array("name" => "comments", "type" => "integer");
	$i++;
	$xsd['response'][$i] = array("name" => "made", "type" => "integer");
	$datab=array();
	$datab[] = array("name" => "com_id", "type" => "integer");
	$datab[] = array("name" => "com_pid", "type" => "integer");
	$datab[] = array("name" => "com_modid", "type" => "integer");
	$datab[] = array("name" => "com_icon", "type" => "string");
	$datab[] = array("name" => "com_title", "type" => "string");
	$datab[] = array("name" => "com_text", "type" => "string");
	$datab[] = array("name" => "com_created", "type" => "string");
	$datab[] = array("name" => "com_modified", "type" => "string");
	$datab[] = array("name" => "com_uid", "type" => "integer");
	$datab[] = array("name" => "com_uname", "type" => "string");
	$datab[] = array("name" => "com_ip", "type" => "string");
	$datab[] = array("name" => "com_sig", "type" => "integer");
	$datab[] = array("name" => "com_itemid", "type" => "integer");
	$datab[] = array("name" => "com_rootid", "type" => "integer");
	$datab[] = array("name" => "com_status", "type" => "integer");
	$datab[] = array("name" => "com_exparams", "type" => "string");
	$datab[] = array("name" => "dohtml", "type" => "integer");
	$datab[] = array("name" => "dosmiley", "type" => "integer");
	$datab[] = array("name" => "doxcode", "type" => "integer");
	$datab[] = array("name" => "doimage", "type" => "integer");
	$datab[] = array("name" => "dobr", "type" => "integer");
		$data[] = array("items" => array("data" => $datab, "objname" => "data"));
	$i++;
	$xsd['response'][$i]['items']['data'] = $data;
	$xsd['response'][$i]['items']['objname'] = 'data';
	return $xsd;
}


/*	Provide WSDL for function
 *  comments_wsdl()
 *
 *  @subpackage plugin
 *
 *  @return array
 */
function comments_wsdl(){

}

/*	Provide WSDL Service for function
 *  comments_wsdl_service()
 *
 *  @subpackage plugin
 *
 *  @return array
 */
 function comments_wsdl_service(){

}

?>