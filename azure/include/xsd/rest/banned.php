<?php
function banned_xsd(){
	$xsd = array();
	$i=0;
	$xsd['request'][$i++] = array("name" => "username", "type" => "string");
	$xsd['request'][$i++] = array("name" => "password", "type" => "string");
	$xsd['request'][$i++] = array("name" => "bans", "type" => "array");
	$xsd['request'][$i++] = array("name" => "comments", "type" => "array");
	
	$i=0;
	$xsd['response'][$i++] = array("name" => "bans", "type" => "integer");
	$xsd['response'][$i++] = array("name" => "errors", "type" => "array");
	$xsd['response'][$i++] = array("name" => "made", "type" => "integer");
	
	return $xsd;

}

function banned_wsdl(){

}

function banned_wsdl_service(){

}


?>