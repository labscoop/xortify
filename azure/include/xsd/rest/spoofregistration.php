<?php
function spoofregistration_xsd(){

	$i=0;
	$xsd['request'][$i++] = array("name" => "username", "type" => "string");
	$xsd['request'][$i++] = array("name" => "password", "type" => "string");
	$xsd['request'][$i++] = array("name" => "uri", "type" => "string");
	$xsd['request'][$i++] = array("name" => "ip", "type" => "string");
	$xsd['request'][$i++] = array("name" => "language", "type" => "string");
	
	$i=0;
	$xsd['response'][$i++] = array("name" => "form", "type" => "string");
	$xsd['response'][$i++] = array("name" => "elements", "type" => "array");
	$xsd['response'][$i++] = array("name" => "action", "type" => "string");
	$xsd['response'][$i++] = array("name" => "method", "type" => "string");
	$xsd['response'][$i++] = array("name" => "css", "type" => "array");
		
	return $xsd;
}

function spoofregistration_wsdl(){

}

function spoofregistration_wsdl_service(){

}


?>