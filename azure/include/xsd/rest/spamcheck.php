<?php
function spamcheck_xsd(){
	$xsd = array();
		$xsd = array();
	$i=0;
	$xsd['request'][$i++] =  array("name" => "username", "type" => "string");
	$xsd['request'][$i++] =  array("name" => "password", "type" => "string");	
	$xsd['request'][$i++] =  array("name" => "poll", "type" => "string");
	$xsd['request'][$i++] =  array("name" => "content", "type" => "string");
	$xsd['request'][$i++] =  array("name" => "adult", "type" => "boolean");
	$xsd['request'][$i++] =  array("name" => "uname", "type" => "string");
	$xsd['request'][$i++] =  array("name" => "name", "type" => "string");
	$xsd['request'][$i++] =  array("name" => "email", "type" => "string");
	$xsd['request'][$i++] =  array("name" => "ip", "type" => "string");
	$xsd['request'][$i++] =  array("name" => "ip", "type" => "sessid");
			
	$i=0;
	$xsd['response'][$i++] = array("name" => "spam", "type" => "boolean");
	$xsd['response'][$i++] = array("name" => "lower", "type" => "integer");
	$xsd['response'][$i++] = array("name" => "higher", "type" => "integer");
	$xsd['response'][$i++] = array("name" => "score", "type" => "float");
	
	$i=0;
	return $xsd;
}

function spamcheck_wsdl(){

}

function spamcheck_wsdl_service(){

}


?>