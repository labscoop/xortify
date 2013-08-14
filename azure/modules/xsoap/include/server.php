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
 * @subpackage		SOAP
 * @author         	Simon Roberts (wishcraft) - meshy@labs.coop
 */

global $xoopsModuleConfig,$xoopsModule;

// Gets Execution Mode
$mode = (isset($_REQUEST['outputmode'])?(string)$_REQUEST['outputmode']:'json');
$parser = (isset($_REQUEST['parser'])?(string)$_REQUEST['parser']:'http');
$plugin = $_REQUEST['xrestplugin'];
$GLOBALS['xrestPlugin'] = $plugin;

// Gets URI Values and POST and GET Values
$path = parse_url(XOOPS_URL.$_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (substr($path,0,1)!='\\')
	$path .= '\\' .$path;
if (substr($path,strlen($path)-1,1)!='\\')
	$path .= $path . '\\';
$request = parse_url(XOOPS_URL.$_SERVER['REQUEST_URI'], PHP_URL_QUERY);
$values = array();
parse_str($request, $values);
if (isset($_POST)) {
	foreach($_POST as $field => $value) {
		$values[$field] = $value;
	}
}
if (isset($_GET)) {
	foreach($_GET as $field => $value) {
		$values[$field] = $value;
	}
}

$xoopsPreload =& XoopsPreload::getInstance();
$xoopsPreload->triggerEvent('api.server.start', array($mode, $plugin, $path, $request, $values));


if (!class_exists('Services_JSON'))
	include_once('JSON.php');

if (!file_exists(XOOPS_ROOT_PATH.'/class/soap/xoopssoap.php')){
	foreach (get_loaded_extensions() as $ext){
		if ($ext=="soap")
			$native=true;
	}
	if ($native!=true) {
		define('XOOPS_SOAP_LIB','NUSOAP');
		require_once('nusoap/nusoap.php');
	} else {
		define('XOOPS_SOAP_LIB','INHERIT');
	}
} else {
	require_once (XOOPS_ROOT_PATH.'/class/soap/xoopssoap.php');
}

require_once(XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->dirname().'/class/class.functions.php');
require_once('common.php');

	switch(XOOPS_SOAP_LIB){
	case "NUSOAP":
		if ($xoopsModuleConfig['wsdl']==1){
			if (!isset($_GET['wsdl'])) {
				$server = new soap_server("xsoap.wsdl");
			} else {
				$server = new soap_server($_GET['wsdl'].'.service.wsdl');
			}
		} else {
			$server = new soap_server();
		}
		break;
	case "INHERIT":
		if ($xoopsModuleConfig['wsdl']==1){
			if (!isset($_GET['wsdl'])) {
				$server = new SoapServer(XOOPS_URL.'/modules/xsoap/xsoap.wsdl', array('uri' => XOOPS_URL.'/modules/xsoap/'));
			} else {
				$server = new SoapServer(XOOPS_URL.'/modules/xsoap/'.$_GET['wsdl'].'.service.wsdl', array('uri' => XOOPS_URL.'/modules/xsoap/'));
			}
		} else {
			$server = new SoapServer(NULL, array('uri' => XOOPS_URL.'/modules/xsoap/'));
		}
		break;
	}
	

$funct = new FunctionsHandler($xoopsModuleConfig['wsdl']);

$FunctionDefine = array();
foreach($funct->GetServerExtensions() as $extension){
	global $xoopsDB;
	$sql = "SELECT count(*) rc FROM ".$xoopsDB->prefix('soap_plugins'). " where active = 1 and plugin_file = '".$extension."'";
	$ret = $xoopsDB->query($sql);
	$row = $xoopsDB->fetchArray($ret);
	if ($row['rc']==1){
		require_once(XOOPS_ROOT_PATH.'/modules/xsoap/plugins/'. $extension);
		$FunctionDefine[] = substr( $extension,0,strlen( $extension)-4);
	}	
}

$FunctionDefine = array_unique($FunctionDefine);

foreach($FunctionDefine as $function){
	if (function_exists($function)){
		switch(XOOPS_SOAP_LIB){
		case "NUSOAP":
			$server->register($function,array(),array(),XOOP_URL."/modules/xsoap/$function");
			break;
		case "INHERIT":
			$server->addFunction($function);
			break;
		}
	}
}
if (XOOPS_SOAP_LIB!="NUSOAP")
	$server->handle();
	
$xoopsPreload =& XoopsPreload::getInstance();
$xoopsPreload->triggerEvent('api.server.end', array($mode, $plugin, $path, $request, $values, $result));

	
?>