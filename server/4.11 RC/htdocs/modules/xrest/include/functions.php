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
 * @subpackage		REST
 * @author         	Simon Roberts (wishcraft) - meshy@labs.coop
 */


if (!function_exists('json_encode')){

	/*	Encodes an Array as JSON Flat Data
	 *  json_encode($data)
	 *
	 *  @param array $data			Array to be converted to JSON
	 *  @return string
	 */
	function json_encode($data) {
		static $json = NULL;
		if (!class_exists('Services_JSON')) include_once $GLOBALS['xoops']->path('/modules/xrest/include/JSON.php');
			$json = new Services_JSON();
		return $json->encode($data);
	}
}

if (!function_exists("xrest_toXml")) { 

	/*	Converts an Array to Streamed XML
	 *  xrest_toXml($array, $name, $standalone=false, $beginning=true, $nested)
	 *
	 *  @param array $array				Array to convert to XML
	 *  @param string $name				Namespace of XML Document
	 *  @param boolean $standalone		Output headers as standalone function
	 *  @param boolean $beginning		At the beginning of internal functional loop
	 *  @param integer $nested			Tab Levels of nesting
	 *  @return string
	 */
	function xrest_toXml($array, $name, $standalone=false, $beginning=true, $nested) {
		
		if ($beginning) {
			if ($standalone)
				header("content-type:text/xml;charset="._CHARSET);
			$output .= '<'.'?'.'xml version="1.0" encoding="'._CHARSET.'"'.'?'.'>' . "\n";    
			$output .= '<' . $name . '>' . "\n";
			$nested = 0;
		}    
		
		if (is_array($array)) {
			foreach ($array as $key=>$value) {
				$nested++;	
				if (is_array($value)) {
					$output .= str_repeat("\t", (1 * $nested)) . '<' . (is_string($key) ? $key : $name.'_' . $key) . '>' . "\n";
					$nested++;				
					$output .= xrest_toXml($value, $name, false, false, $nested);
					$nested--;
					$output .= str_repeat("\t", (1 * $nested)) . '</' . (is_string($key) ? $key : $name.'_' . $key) . '>' . "\n";
				} else {
					if (strlen($value)>0) {
					$nested++;				
						$output .= str_repeat("\t", (1 * $nested)) . '  <' . (is_string($key) ? $key : $name.'_' . $key) . '>' . trim($value) . '</' . (is_string($key) ? $key : $name.'_' . $key) . '>' . "\n";
						$nested--;
					}
				}
				$nested--;
			}
		} elseif (strlen($array)>0) {
			$nested++; 
			$output .= str_repeat("\t", (1 * $nested)) . trim($array) ."\n";
			$nested--;
		}
			
		if ($beginning) {
			$output .= '</' . $name . '>';
			return $output;
		} else {
			return $output;
		}
	} 
}

if (!function_exists("xrest_object2array")) {

	/*	Converts and Object to an Array (Recursively)
	 *  xrest_object2array($object)
	 *
	 *  @param object $object		RAW Object
	 *  @return array
	 */
	function xrest_object2array($object) {
	  if (is_object($object)) {
	    foreach ($object as $key => $value) {
	    	if (is_object($value)) {
	    		$array[$key] = object2array($value);
	    	} else {
	    		$array[$key] = $value;
	    	}
	    }
	  }
	  else {
	    $array = $object;
	  }
	
	  return $array;
	}
}

if (!function_exists("xrest_strip_prefix")) {

	/*	Strips Database Prefix
	 *  xrest_strip_prefix($raw_tablename) {
	 *
	 *  @param string $raw_tablename	RAW Table name from Show Function
	 *  @return string
	 */
	function xrest_strip_prefix($raw_tablename){
		return str_replace(XOOPS_DB_PREFIX."_",'',$raw_tablename);
	}
}
?>