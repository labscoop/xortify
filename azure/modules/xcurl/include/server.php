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
 * @subpackage		cURL (JSON)
 * @author         	Simon Roberts (wishcraft) - meshy@labs.coop
 */

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
		if ($field==$plugin)
			$values[$field] = obj2array(json_decode(str_replace('\\"', '"', $value)));
		else
			$values[$field] = $value;
	}
}
if (isset($_GET)) {
	foreach($_GET as $field => $value) {
		if ($field==$plugin)
			$values[$field] = obj2array(json_decode(str_replace('\\"', '"', $value)));
		else
			$values[$field] = $value;
		
	}
}

$xoopsPreload =& XoopsPreload::getInstance();
$xoopsPreload->triggerEvent('api.server.start', array($mode, $plugin, $path, $request, $values));

function obj2array($objects) {
	$ret = array();
	foreach($objects as $key => $value) {
		if (is_a($value, 'stdClass')) {
			$ret[$key] = (array)obj2array($value);
		} elseif (is_array($value)) {
			$ret[$key] = obj2array($value);
		} else {
			$ret[$key] = $value;
		}
	}
	return $ret;
}

global $xoopsModuleConfig,$xoopsModule;
$ttlresult = array();

xoops_load('xoopscache');

require_once(XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->dirname().'/class/class.functions.php');
require_once('common.php');
if (!function_exists('json_encode')) {
	require_once('JSON.php');
	$json = new Services_JSON();
}
	
$funct = new FunctionsHandler();

$FunctionDefine = array();
foreach($funct->GetServerExtensions() as $extension){
	global $xoopsDB;
	$sql = "SELECT count(*) rc FROM ".$xoopsDB->prefix('json_plugins'). " where active = 1 and plugin_file = '".$extension."'";
	$ret = $xoopsDB->query($sql);
	$row = $xoopsDB->fetchArray($ret);
	if ($row['rc']==1){
		require_once(XOOPS_ROOT_PATH.'/modules/xjson/plugins/'. $extension);
		$FunctionDefine[] = substr( $extension,0,strlen( $extension)-4);
	}	
}

$FunctionDefine = array_unique($FunctionDefine);

foreach($FunctionDefine as $id => $func)  {
	if (!empty($_REQUEST[$func])) {
		$opfunc = $func;
		$xsd = $func.'_xsd';	
		$opxsd = $xsd();
		if (!function_exists('json_decode')) {
			$opdata = obj2array(json_decode(str_replace('\\"', '"', $_REQUEST[$func])));
		} else {
			$opdata = obj2array(json_decode(str_replace('\\"', '"', $_REQUEST[$func])));
		}

		if (!$result = XoopsCache::read('xcurl_'.$opfunc.'_'.sha1(implode(':', $opdata)))) {
		
			$tmp=array();
			if (!empty($opfunc)) {
				$fields=0;
				foreach($opxsd['request'] as $ii => $request) {
					foreach($request['items']['data'] as $iu => $field)
					{
						if (!empty($field['items'])) {
							$tmp[$fields] = $opdata[$field['items']['objname']]		;
							$fields++;
						} elseif (!empty($field['name'])&&!empty($field['type'])) {
							switch($field['type']) {
							default:
							case "string":
								$tmp[$fields] = (string)$opdata[$field['name']];
								break;
							case "integer":
								$tmp[$fields] = (integer)$opdata[$field['name']];					
								break;
							case "array":
								$tmp[$fields] = $opdata[$field['name']];					
								break;
							}
							$fields++;				
						}
					}
				}
				
			switch($fields) {
				case 0:
					$result = $opfunc($ttlresult);
					break;
				case 1:
					$result = $opfunc($tmp[0], $ttlresult);
					break;
				case 2:
					$result = $opfunc($tmp[0], $tmp[1], $ttlresult);
					break;
				case 3:
					$result = $opfunc($tmp[0], $tmp[1], $tmp[2], $ttlresult);
					break;
				case 4:
					$result = $opfunc($tmp[0], $tmp[1], $tmp[2], $tmp[3], $ttlresult);
					break;
				case 5:
					$result = $opfunc($tmp[0], $tmp[1], $tmp[2], $tmp[3], $tmp[4], $ttlresult);
					break;
				case 6:
					$result = $opfunc($tmp[0], $tmp[1], $tmp[2], $tmp[3], $tmp[4], $tmp[5], $ttlresult);
					break;
				case 7:
					$result = $opfunc($tmp[0], $tmp[1], $tmp[2], $tmp[3], $tmp[4], $tmp[5], $tmp[6], $ttlresult);
					break;
				case 8:
					$result = $opfunc($tmp[0], $tmp[1], $tmp[2], $tmp[3], $tmp[4], $tmp[5], $tmp[6], $tmp[7], $ttlresult);
					break;
				case 9:
					$result = $opfunc($tmp[0], $tmp[1], $tmp[2], $tmp[3], $tmp[4], $tmp[5], $tmp[6], $tmp[7], $tmp[8], $ttlresult);
					break;
				case 10:
					$result = $opfunc($tmp[0], $tmp[1], $tmp[2], $tmp[3], $tmp[4], $tmp[5], $tmp[6], $tmp[7], $tmp[8], $tmp[9], $ttlresult);
					break;
				case 11:
					$result = $opfunc($tmp[0], $tmp[1], $tmp[2], $tmp[3], $tmp[4], $tmp[5], $tmp[6], $tmp[7], $tmp[8], $tmp[9], $tmp[10], $ttlresult);
					break;
				case 12:
					$result = $opfunc($tmp[0], $tmp[1], $tmp[2], $tmp[3], $tmp[4], $tmp[5], $tmp[6], $tmp[7], $tmp[8], $tmp[9], $tmp[10], $tmp[11], $ttlresult);
					break;		
				case 13:
					$result = $opfunc($tmp[0], $tmp[1], $tmp[2], $tmp[3], $tmp[4], $tmp[5], $tmp[6], $tmp[7], $tmp[8], $tmp[9], $tmp[10], $tmp[11], $tmp[12], $ttlresult);
					break;		
				case 14:
					$result = $opfunc($tmp[0], $tmp[1], $tmp[2], $tmp[3], $tmp[4], $tmp[5], $tmp[6], $tmp[7], $tmp[8], $tmp[9], $tmp[10], $tmp[11], $tmp[12], $tmp[13], $ttlresult);
					break;		
				case 15:
					$result = $opfunc($tmp[0], $tmp[1], $tmp[2], $tmp[3], $tmp[4], $tmp[5], $tmp[6], $tmp[7], $tmp[8], $tmp[9], $tmp[10], $tmp[11], $tmp[12], $tmp[13], $tmp[14], $ttlresult);
					break;		
				case 16:
					$result = $opfunc($tmp[0], $tmp[1], $tmp[2], $tmp[3], $tmp[4], $tmp[5], $tmp[6], $tmp[7], $tmp[8], $tmp[9], $tmp[10], $tmp[11], $tmp[12], $tmp[13], $tmp[14], $tmp[15], $ttlresult);
					break;		
				case 17:
					$result = $opfunc($tmp[0], $tmp[1], $tmp[2], $tmp[3], $tmp[4], $tmp[5], $tmp[6], $tmp[7], $tmp[8], $tmp[9], $tmp[10], $tmp[11], $tmp[12], $tmp[13], $tmp[14], $tmp[15], $tmp[16], $ttlresult);
					break;		
				case 18:
					$result = $opfunc($tmp[0], $tmp[1], $tmp[2], $tmp[3], $tmp[4], $tmp[5], $tmp[6], $tmp[7], $tmp[8], $tmp[9], $tmp[10], $tmp[11], $tmp[12], $tmp[13], $tmp[14], $tmp[15], $tmp[16], $tmp[17], $ttlresult);
					break;		
				case 19:
					$result = $opfunc($tmp[0], $tmp[1], $tmp[2], $tmp[3], $tmp[4], $tmp[5], $tmp[6], $tmp[7], $tmp[8], $tmp[9], $tmp[10], $tmp[11], $tmp[12], $tmp[13], $tmp[14], $tmp[15], $tmp[16], $tmp[17], $tmp[18], $ttlresult);
					break;		
				case 20:
					$result = $opfunc($tmp[0], $tmp[1], $tmp[2], $tmp[3], $tmp[4], $tmp[5], $tmp[6], $tmp[7], $tmp[8], $tmp[9], $tmp[10], $tmp[11], $tmp[12], $tmp[13], $tmp[14], $tmp[15], $tmp[16], $tmp[17], $tmp[18], $tmp[19], $ttlresult);
					break;		
				}
				XoopsCache::write('xcurl_'.$opfunc.'_'.sha1(implode(':', $opdata)), $result, $GLOBALS['xoopsModuleConfig']['function_cache']);
			}
			$ttlresult = array_merge($ttlresult, $result);
		} else {
			$ttlresult = array_merge($ttlresult, $result);
		}
	}	
}
if (!function_exists('json_encode')) {
	echo $json->encode($ttlresult);
} else {
	echo json_encode($ttlresult);
}
$xoopsPreload =& XoopsPreload::getInstance();
$xoopsPreload->triggerEvent('api.server.end', array($mode, $plugin, $path, $request, $values, $result));


exit(0);
?>