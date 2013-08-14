<?php
/**
 * @package     xortify
 * @subpackage  module
 * @description	Sector Network Security Drone
 * @author	    Simon Roberts WISHCRAFT <simon@chronolabs.coop>
 * @copyright	copyright (c) 2010-2013 XOOPS.org
 * @licence		GPL 2.0 - see docs/LICENCE.txt
 */

include_once dirname(__FILE__) . DS . 'instance.php';

if (!function_exists('checkWordLength')){
	function checkWordLength($content) {
		
		if (is_group(user_groups(), $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['min_words_groups'])==true) {
			xoops_load('textsanitizer');
			$myts = new MyTextSanitizer();
			$data = strip_tags($myts->displayTarea($content, true, true, true, true, true));
			$parts = explode(' ', $data);
			foreach($parts as $id => $part)
				if (strlen($part)<4)
				unset($parts[$id]);
			if (count($parts)<$GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['min_words']) {

				xoops_loadLanguage('ban', 'xortify');

				$log_handler = xoops_getmodulehandler('log', 'xortify');
				$log = $log_handler->create();
				$log->setVars($ipdata);
				$log->setVar('provider', basename(dirname(__FILE__)));
				$log->setVar('action', 'blocked');
				$log->setVar('extra', _XOR_WORDS . ' :: [' . $_REQUEST[$field] . '] words('.count($parts).')');
				if (isset($GLOBALS['xoops']->user)) {
					$log->setVar('email', $GLOBALS['xoops']->user->getVar('email'));
					$log->setVar('uname', $GLOBALS['xoops']->user->getVar('uname'));
				}
				$lid = $log_handler->insert($log, true);

				$GLOBALS['xoops'] = Xoops::getInstance();
				$GLOBALS['xortifyModule'] = $GLOBALS['xoops']->moduleHandler()->getByDirname('xortify');
				$GLOBALS['xoops']->header('module:xortify|xortify_words_notice.html');
					
				addmeta_googleanalytics(_XOR_MI_XOOPS_GOOGLE_ANALYTICS_ACCOUNTID_FAILEDTOPASS, $_SERVER['HTTP_HOST']);
				if (defined('_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_FAILEDTOPASS')&&strlen(constant('_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_FAILEDTOPASS'))>=13) {
					addmeta_googleanalytics(_XOR_MI_CLIENT_GOOGLE_ANALYTICS_ACCOUNTID_FAILEDTOPASS, $_SERVER['HTTP_HOST']);
				}

				$GLOBALS['xoops']->tpl()->assign('xoops_pagetitle', _XOR_WORDS_PAGETITLE);
				$GLOBALS['xoops']->tpl()->assign('description', _XOR_WORDS_DESCRIPTION);
				$GLOBALS['xoops']->tpl()->assign('version', $GLOBALS['xortifyModule']->getVar('version')/100);
				$GLOBALS['xoops']->tpl()->assign('platform', XOOPS_VERSION);
				$GLOBALS['xoops']->tpl()->assign('provider', basename(dirname(__FILE__)));
				$GLOBALS['xoops']->tpl()->assign('spam', htmlspecialchars($data));
				$GLOBALS['xoops']->tpl()->assign('agent', $_SERVER['HTTP_USER_AGENT']);
				$GLOBALS['xoops']->tpl()->assign('words', count($parts));
				$GLOBALS['xoops']->tpl()->assign('required', $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['min_words']);

				$GLOBALS['xoops']->tpl()->assign('xoops_lblocks', false);
				$GLOBALS['xoops']->tpl()->assign('xoops_rblocks', false);
				$GLOBALS['xoops']->tpl()->assign('xoops_ccblocks', false);
				$GLOBALS['xoops']->tpl()->assign('xoops_clblocks', false);
				$GLOBALS['xoops']->tpl()->assign('xoops_crblocks', false);
				$GLOBALS['xoops']->tpl()->assign('xoops_showlblock', false);
				$GLOBALS['xoops']->tpl()->assign('xoops_showrblock', false);
				$GLOBALS['xoops']->tpl()->assign('xoops_showcblock', false);
				$GLOBALS['xoops']->footer();
				exit(0);
			}
		}
		return true;
	}
}


if (!function_exists('is_group')){
	function is_group($from, $in) {
		foreach($from as $groupid)
			if (in_array($groupid, $in))
				return true;
		return false;
	}
}

if (!function_exists('user_groups')){
	function user_groups() {
		$GLOBALS['xoops'] = Xoops::getInstance();
		if (is_object($GLOBALS['xoops']->user))
			return $GLOBALS['xoops']->user->getGroups();
		return array(XOOPS_GROUP_ANONYMOUS=>XOOPS_GROUP_ANONYMOUS);
	}
}

if (!function_exists('writeInstanceKey')){
	function writeInstanceKey($key) {
		$GLOBALS['xoops'] = Xoops::getInstance();
		$file = XOOPS_ROOT_PATH . '/modules/xortify/include/instance.php';
		ob_start();
		$GLOBALS['xoops']->tpl()->assign('instance_key', $key);
		$GLOBALS['xoops']->header('xortify|xortify_instance_key.php.txt');
		$GLOBALS['xoops']->tpl()->display('xortify|xortify_instance_key.php.txt');
		$data = ob_get_contents();
		ob_end_clean();
		chmod(XOOPS_ROOT_PATH . '/modules/xortify/include', 0777);
		chmod($file, 0777);
		if (strlen($data)>0) {
			unlink($file);
			$f = fopen($file, 'w');
			fwrite($f, $data, strlen($data));
			fclose($f);
			return true;
		}
		return false;
	}
}

if (!function_exists('unlinkOldCachefiles')){
	include_once XOOPS_ROOT_PATH.'/class/cache/xoopscache.php';
	include_once XOOPS_ROOT_PATH.'/class/xoopslists.php';

	function unlinkOldCachefiles($prefix, $howold=0) {
		$files = XoopsLists::getFileListAsArray(XOOPS_VAR_PATH.'/caches/xoops_cache', $prefix);
		foreach($files as $file) {
			if (file_exists(XOOPS_VAR_PATH.'/caches/xoops_cache/' . $file)) {
				$contents = file_get_contents(XOOPS_VAR_PATH.'/caches/xoops_cache/' . $file);
				$content = explode('/n', $contents);
				$lock = (int)$content[0];
				if ($lock<time()-$howold) {
					unlink(XOOPS_VAR_PATH.'/caches/xoops_cache/' . $file);
				}
			}
		}

	}
}

if (!function_exists('json_encode')){
	function json_encode($data) {
		static $json = NULL;
		if (!class_exists('Services_JSON')) include_once $GLOBALS['xoops']->path('/modules/xortify/include/JSON.php');
		$json = new Services_JSON();
		return $json->encode($data);
	}
}

if (!function_exists('json_decode')){
	function json_decode($data) {
		static $json = NULL;
		if (!class_exists('Services_JSON')) include_once $GLOBALS['xoops']->path('/modules/xortify/include/JSON.php');
		$json = new Services_JSON();
		return $json->decode($data);
	}
}


if (!function_exists("xortify_adminMenu")) {
  function xortify_adminMenu ($currentoption = 0, $page)  {
	   	echo "<table width=\"100%\" border='0'><tr><td>";
	   	echo "<tr><td>";
	   	$indexAdmin = new XoopsModuleAdmin();
	   	echo $indexAdmin->renderNavigation($page);
  	   	echo "</td></tr>";
	   	echo "<tr'><td><div id='form'>";
   }
  
  function xortify_footer_adminMenu()
  {
		echo "</div></td></tr>";
  		echo "</table>";
		echo "<div align=\"center\"><a href=\"http://www.xoops.org\" target=\"_blank\"><img src=" . XOOPS_URL . '/' . $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['ImageAdmin'].'/xoopsmicrobutton.gif'.' '." alt='XOOPS' title='XOOPS'></a></div>";
		echo "<div class='center smallsmall italic pad5'><strong>" . $GLOBALS['xortify'][XORTIFY_INSTANCE_KEY][_MI_XOR_VERSION]['module']->getVar("name") . "</strong> is maintained by the <a class='tooltip' rel='external' href='http://www.xoops.org/' title='Visit XOOPS Community'>XOOPS Community</a> and <a class='tooltip' rel='external' href='http://www.chronolabs.coop/' title='Visit Chronolabs Co-op'>Chronolabs Co-op</a></div>";
  }
}
if (!function_exists("is_ipv6")) {
	function is_ipv6($ip = "") 
	{ 
		if ($ip == "") 
			return false;
			
		if (substr_count($ip,":") > 0){ 
			return true; 
		} else { 
			return false; 
		} 
	} 
}


if (!function_exists("xortify_apimethod")) {
	function xortify_apimethod($asarray=false) {
		if ($asarray==false) {
			foreach (get_loaded_extensions() as $ext){
				if ($ext=="curl")
					return "rest_curlserialised";
			}
			if (function_exists('json_decode'))
				return 'rest_json';
			elseif (function_exists('xml_parser_create'))
			return "rest_wetxml";
			else
				foreach (get_loaded_extensions() as $ext){
				if ($ext=="soap")
					return $ext;
			}
			return "rest_wgetserialised";
		} else {
			$ret = array(_XOR_MI_PROTOCOL_MINIMUMCLOUD => 'minimumcloud');
			foreach (get_loaded_extensions() as $ext){
				if ($ext=="curl") {
					if (function_exists('json_decode')) {
						$ret[_XOR_MI_PROTOCOL_CURL] = 'rest_curl';
						$ret[_XOR_MI_PROTOCOL_LEGACY_CURL] = 'curl';
					}
					$ret[_XOR_MI_PROTOCOL_CURLSERIAL] = 'rest_curlserialised';
					$ret[_XOR_MI_PROTOCOL_LEGACY_CURLSERIAL] = 'curlserialised';
				}
				if ($ext=="soap")
					$ret[_XOR_MI_PROTOCOL_SOAP] = 'soap';

				if (function_exists('xml_parser_create')) {
					if (in_array('curl', get_loaded_extensions())) {
						$ret[_XOR_MI_PROTOCOL_CURLXML] = 'rest_curlxml';
						$ret[_XOR_MI_PROTOCOL_LEGACY_CURLXML] = 'curlxml';
					}
					$xmlparser=true;
				}
			}
			if ($xmlparser=true) {
				$ret[_XOR_MI_PROTOCOL_WGETXML] = 'rest_wgetxml';
				$ret[_XOR_MI_PROTOCOL_LEGACY_WGETXML] = 'wgetxml';
			}
			if (function_exists('json_decode')) {
				$ret[_XOR_MI_PROTOCOL_JSON] = 'rest_json';
				$ret[_XOR_MI_PROTOCOL_LEGACY_JSON] = 'json';
			}
			$ret[_XOR_MI_PROTOCOL_WGETSERIAL] = 'rest_wgetserialised';
			$ret[_XOR_MI_PROTOCOL_LEGACY_WGETSERIAL] = 'wgetserialised';
			return $ret;
		}
	}
}

if (!function_exists("xortify_obj2array")) {
	function xortify_obj2array($objects) {
		$ret = array();
		foreach((array)$objects as $key => $value) {
			if (is_a($value, 'stdClass')) {
				$ret[$key] = xortify_obj2array($value);
			} elseif (is_array($value)) {
				$ret[$key] = xortify_obj2array($value);
			} else {
				$ret[$key] = $value;
			}
		}
		return $ret;
	}
}

if (!function_exists("xortify_elekey2numeric")) {
	function xortify_elekey2numeric($array, $name) {
		$ret = array();
		foreach($array as $key => $value) {
			if (is_array($value)) {
				$key = str_replace($name.'_', '', $key);
				if (is_numeric($key))
					$key = (integer)$key;
				$ret[$key] = xortify_elekey2numeric($value, $name);
			} else {
				$key = str_replace($name.'_', '', $key);
				if (is_numeric($key))
					$key = (integer)$key;
				$ret[$key] = $value;
			}
		}
		return $ret;
	}
}

if (!function_exists("xortify_xml2array")) {
	function xortify_xml2array($contents, $get_attributes=1, $priority = 'tag') { 
	    if(!$contents) return array(); 
	
	    if(!function_exists('xml_parser_create')) { 
	        return array(); 
	    } 
	
	    //Get the XML parser of PHP - PHP must have this module for the parser to work
	     $parser = xml_parser_create(''); 
	    xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss
	     xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0); 
	    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1); 
	    xml_parse_into_struct($parser, trim($contents), $xml_values); 
	    xml_parser_free($parser); 
	
	    if(!$xml_values) return;//Hmm... 
	
	    //Initializations 
	    $xml_array = array(); 
	    $parents = array(); 
	    $opened_tags = array(); 
	    $arr = array(); 
	
	    $current = $xml_array; //Refference 
	
	    //Go through the tags. 
	    $repeated_tag_index = array();//Multiple tags with same name will be turned into an array
	     foreach($xml_values as $data) { 
	        unset($attributes,$value);//Remove existing values, or there will be trouble
	 
	        //This command will extract these variables into the foreach scope 
	        // tag(string), type(string), level(int), attributes(array). 
	        extract($data);//We could use the array by itself, but this cooler. 
	
	        $result = array(); 
	        $attributes_data = array(); 
	         
	        if(isset($value)) { 
	            if($priority == 'tag') $result = $value; 
	            else $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
	         } 
	
	        //Set the attributes too. 
	        if(isset($attributes) and $get_attributes) { 
	            foreach($attributes as $attr => $val) { 
	                if($priority == 'tag') $attributes_data[$attr] = $val; 
	                else $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
	             } 
	        } 
	
	        //See tag status and do the needed. 
	        if($type == "open") {//The starting of the tag '<tag>' 
	            $parent[$level-1] = $current; 
	            if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
	                 $current[$tag] = $result; 
	                if($attributes_data) $current[$tag. '_attr'] = $attributes_data;
	                 $repeated_tag_index[$tag.'_'.$level] = 1; 
	
	                $current = $current[$tag]; 
	
	            } else { //There was another element with the same tag name 
	
	                if(isset($current[$tag][0])) {//If there is a 0th element it is already an array
	                     $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
	                     $repeated_tag_index[$tag.'_'.$level]++; 
	                } else {//This section will make the value an array if multiple tags with the same name appear together
	                     $current[$tag] = array($current[$tag],$result);//This will combine the existing item and the new item together to make an array
	                     $repeated_tag_index[$tag.'_'.$level] = 2; 
	                     
	                    if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
	                         $current[$tag]['0_attr'] = $current[$tag.'_attr']; 
	                        unset($current[$tag.'_attr']); 
	                    } 
	
	                } 
	                $last_item_index = $repeated_tag_index[$tag.'_'.$level]-1; 
	                $current = $current[$tag][$last_item_index]; 
	            } 
	
	        } elseif($type == "complete") { //Tags that ends in 1 line '<tag />' 
	            //See if the key is already taken. 
	            if(!isset($current[$tag])) { //New Key 
	                $current[$tag] = $result; 
	                $repeated_tag_index[$tag.'_'.$level] = 1; 
	                if($priority == 'tag' and $attributes_data) $current[$tag. '_attr'] = $attributes_data;
	 
	            } else { //If taken, put all things inside a list(array) 
	                if(isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array...
	 
	                    // ...push the new element into that array. 
	                    $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
	                      
	                    if($priority == 'tag' and $get_attributes and $attributes_data) {
	                         $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
	                     } 
	                    $repeated_tag_index[$tag.'_'.$level]++; 
	
	                } else { //If it is not an array... 
	                    $current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value
	                     $repeated_tag_index[$tag.'_'.$level] = 1; 
	                    if($priority == 'tag' and $get_attributes) { 
	                        if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
	                              
	                            $current[$tag]['0_attr'] = $current[$tag.'_attr']; 
	                            unset($current[$tag.'_attr']); 
	                        } 
	                         
	                        if($attributes_data) { 
	                            $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
	                         } 
	                    } 
	                    $repeated_tag_index[$tag.'_'.$level]++; //0 and 1 index is already taken
	                 } 
	            } 
	
	        } elseif($type == 'close') { //End of tag '</tag>' 
	            $current = $parent[$level-1]; 
	        } 
	    } 
	     
	    return($xml_array); 
	}
}  

if (!function_exists("xortify_toXml")) { 
	function xortify_toXml($array, $name, $standalone=false, $beginning=true, $nested) {
		
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
					$output .= xortify_toXml($value, $name, false, false, $nested);
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

// Version 4.02
if (!function_exists("xortify_getIP")) {
	function xortify_getIP() {
		include_once XOOPS_ROOT_PATH ."/class/userutility.php";
	    return XoopsUserUtility::getIP(true);
	}
}

if (!function_exists("xortify_getIPData")) {
	function xortify_getIPData($ip=false){
		$xoops = Xoops::getInstance();	
		$ret = array();
		if (is_object($xoops->user)) {
			$ret['uid'] = $xoops->user->getVar('uid');
			$ret['uname'] = $xoops->user->getVar('uname');
			$ret['email'] = $xoops->user->getVar('email');
		} else {
			$ret['uid'] = 0;
			$ret['uname'] = (isset($_REQUEST['uname'])?$_REQUEST['uname']:'');
			$ret['email'] = (isset($_REQUEST['email'])?$_REQUEST['email']:'');
		}
		$ret['agent'] = $_SERVER['HTTP_USER_AGENT'];
		if (!$ip) {
			if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])||isset($_SERVER["HTTP_CLIENT_IP"])){ 
				$ip = (string)xortify_getIP(); 
				$ret['is_proxied'] = true;
				$proxy_ip = $_SERVER["REMOTE_ADDR"]; 
				$ret['network-addy'] = @gethostbyaddr($ip); 
				$ret['long'] = @ip2long($ip);
				if (is_ipv6($ip)) {
					$ret['ip6'] = $ip;
					$ret['proxy-ip6'] = $proxy_ip;
				} else {
					$ret['ip4'] = $ip;
					$ret['proxy-ip4'] = $proxy_ip;
				}
			}else{ 
				$ret['is_proxied'] = false;
				$ip = (string)xortify_getIP(); 
				$ret['network-addy'] = @gethostbyaddr($ip); 
				$ret['long'] = @ip2long($ip);
				if (is_ipv6($ip)) {
					$ret['ip6'] = $ip;
				} else {
					$ret['ip4'] = $ip;
				}
			} 
		} else {
			$ret['is_proxied'] = false;
			$ret['network-addy'] = @gethostbyaddr($ip); 
			$ret['long'] = @ip2long($ip);
			if (is_ipv6($ip)) {
				$ret['ip6'] = $ip;
			} else {
				$ret['ip4'] = $ip;
			}
		}
		$ret['made'] = time();				
		return $ret;
	}
}

if (!function_exists('addmeta_googleanalytics')) {
	function addmeta_googleanalytics($accountID, $hostname = 'none') {
		if (empty($hostname))
			$hostname = 'none';
		if (strlen($accountID)>=13) {
			$xoops = Xoops::getInstance();
			$xoops->theme->addScript('', array('type'=>'text/javascript'), "var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', '".$accountID."']);
	  _gaq.push(['_setDomainName', '".$hostname."']);
	  _gaq.push(['_setAllowLinker', true]);
	  _gaq.push(['_trackPageview']);
	
	  (static function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();");
		}
	}
}
?>