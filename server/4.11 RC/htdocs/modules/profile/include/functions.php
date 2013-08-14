<?php

	xoops_load('xoopscache');
	
	if (!function_exists('profile_get_methods')) {
		function profile_get_methods($returndefault=false) {
			xoops_loadLanguage('modinfo', 'profile');
			$ret = array();
			if (extension_loaded('curl'))
				if ($returndefault==true)
				return _PROFILE_MI_CURL;
			else
				$ret[_PROFILE_MI_CURL_DESC] = _PROFILE_MI_CURL;
			if ($returndefault==true)
				return _PROFILE_MI_WGET;
			else
				$ret[_PROFILE_MI_WGET_DESC] = _PROFILE_MI_WGET;
			if (extension_loaded('gd'))
				if ($returndefault==true)
				return _PROFILE_MI_WIDEIMAGE;
			else
				$ret[_PROFILE_MI_WIDEIMAGE_DESC] = _PROFILE_MI_WIDEIMAGE;
			return $ret;
		}
	}
	
	/**
	 * Get either a Gravatar URL or complete image tag for a specified email address.
	 *
	 * @param string $email The email address
	 * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
	 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
	 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
	 * @return String containing either just a URL or a complete image tag
	 * @source http://gravatar.com/site/implement/images/php/
	 */
	if (!function_exists('profile_gravatar_uri')) {
		function profile_gravatar_uri( $email, $s = 80, $d = 'px', $r = 'g') {
			$url = 'http://www.gravatar.com/avatar/';
			$url .= md5( strtolower( trim( $email ) ) );
			$url .= "?s=$s&d=$d&r=$r";
			return $url;
		}
	}
	
	/**
	 * Checks the gravatar and updates it when nessecary
	 *
	 * @param integer $uid User Identification Number
	 * @return boolean whether function executed or not
	 */
	if (!function_exists('profile_do_avatar')) {
		function profile_do_avatar( $uid ) {
			set_time_limit(120);
			$module_handler = xoops_gethandler('module');
			$config_handler = xoops_gethandler('config');
			$user_handler = xoops_gethandler('user');
			$profileModule = $module_handler->getByDirname('profile');
			$configs = $config_handler->getConfigList($profileModule->getVar('mid'));
			if ($configs['profile_gravatar']) {
				if ($user = $user_handler->get($uid)) {
					if (file_exists(dirname(dirname(__FILE__)) . DS . 'class' . DS . 'gravatar' . DS . $configs['profile_method'] . '.php')) {
						include_once (dirname(dirname(__FILE__)) . DS . 'class' . DS . 'gravatar' . DS . $configs['profile_method'] . '.php');
						$class = 'ProfileGravatar'.ucfirst($configs['profile_method']);
						$methodClass = new $class($user, profile_gravatar_uri($user->getVar('email'), $GLOBALS['xoopsConfigUser']['avatar_width'] + $GLOBALS['xoopsConfigUser']['avatar_height'] / 2, 'px', $configs['profile_rating']), $configs);
						unset($methodClass);
						return true;
					}
				}
			}
			return false;
		}
	}
	
	if (!function_exists('profile_social_supported')) {
		function profile_social_supported() {
			$module_handler = xoops_gethandler('module');
			$config_handler = xoops_gethandler('config');
			$GLOBALS['profileModule'] = $module_handler->getByDirname('profile');
			$GLOBALS['profileModuleConfig'] = $config_handler->getConfigList($GLOBALS['profileModule']->getVar('mid'));
			
			$ret = array();
			if (!empty($GLOBALS['profileModuleConfig']['linkedin_app_key'])&&!empty($GLOBALS['profileModuleConfig']['linkedin_app_secret']))
				$ret['linkedin'] = true;
			else 
				$ret['linkedin'] = false;
			if (!empty($GLOBALS['profileModuleConfig']['twitter_app_key'])&&!empty($GLOBALS['profileModuleConfig']['twitter_app_secret']))
				$ret['twitter'] = true;
			else 
				$ret['twitter'] = false;
			if (!empty($GLOBALS['profileModuleConfig']['facebook_app_id'])&&!empty($GLOBALS['profileModuleConfig']['facebook_app_secret']))
				$ret['facebook'] = true;
			else 
				$ret['facebook'] = false;
			return $ret;
		}
	}
	
	if (!function_exists('profile_getfields')) {
		function profile_getfields()
		{
			if (!$fields = XoopsCache::read($GLOBALS['profileModule']->getVar('dirname').'_fields')) {
				if (!$fields = XoopsCache::read($GLOBALS['profileModule']->getVar('dirname').'_fields_cache')) {
					$fields = array();
					foreach($GLOBALS['profileModuleConfig']['display'] as $id => $field)	{
						$fields[$id]['weight'] = 1;
						$fields[$id]['field'] = $field;	
					}	
					XoopsCache::delete($GLOBALS['profileModule']->getVar('dirname').'_fields', $fields, 8000);
					XoopsCache::delete($GLOBALS['profileModule']->getVar('dirname').'_fields_cache', $fields, 8000*1000);
					XoopsCache::write($GLOBALS['profileModule']->getVar('dirname').'_fields', $fields, 8000);
					XoopsCache::write($GLOBALS['profileModule']->getVar('dirname').'_fields_cache', $fields, 8000*1000);
				} else {
					XoopsCache::delete($GLOBALS['profileModule']->getVar('dirname').'_fields', $fields, 8000);
					XoopsCache::delete($GLOBALS['profileModule']->getVar('dirname').'_fields_cache', $fields, 8000*1000);
					XoopsCache::write($GLOBALS['profileModule']->getVar('dirname').'_fields', $fields, 8000);
					XoopsCache::write($GLOBALS['profileModule']->getVar('dirname').'_fields_cache', $fields, 8000*1000);
				}
			}
			return $fields;	
		}
	}
	
	if (!function_exists('profile_get_curl')) {	
		function profile_get_curl($url) {
			$ch 		= curl_init();
			curl_setopt($ch, CURLOPT_HTTPHEADER,	 	$header);
			curl_setopt($ch, CURLOPT_USERAGENT,		 	$GLOBALS['profileModuleConfig']['user_agent']);
			curl_setopt($ch, CURLOPT_URL, 			 	$url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 	true);
			curl_setopt($ch, CURLOPT_HEADER, 		 	false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 	$GLOBALS['profileModuleConfig']['curl_ssl_verify']);
			curl_setopt($ch, CURLOPT_VERBOSE, 			$GLOBALS['profileModuleConfig']['curl_verbose']);
			curl_setopt($ch, CURLOPT_TIMEOUT, 		 	$GLOBALS['profileModuleConfig']['curl_timeout']);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 	$GLOBALS['profileModuleConfig']['curl_connection_timeout']);
			$txt = curl_exec($ch);
			if ($txt === false) {
				$error = curl_error($ch);
				curl_close($ch);
				trigger_error('CURL error: ' . $error);
			} 
			curl_close($ch);
			return $txt;
		}
	}
	
	if (!function_exists('profile_orderfields')) {
		function profile_orderfields($fields, $fieldnamelist = false) {
			$weights = array();
			foreach($fields as $id => $field){
				$weights[] = $field['weight'];
			}
			$weights = array_unique($weights);
			sort($weights);
			$ret = array();
			foreach($weights as $id => $weight) {
				foreach($fields as $id => $field){
					if ($field['weight'] == $weight) {
						if ($fieldnamelist==false)
							$ret[] = $field;	
						else
							$ret[] = $field['field'];							
					}
				}
			}
			return $ret;	
		}
	}

	if (!function_exists('profile_getIP')) {
		function profile_getIP() {
		    $ip = $_SERVER['REMOTE_ADDR'];
		    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		        $ip = $_SERVER['HTTP_CLIENT_IP'];
		    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		    }
		    return $ip;
		}
	}
	
	if (!function_exists("profile_object2array")) {
		function profile_object2array($objects) {
			$ret = array();
			foreach((array)$objects as $key => $value) {
				if (is_object($value)) {
					$ret[$key] = profile_object2array($value);
				} elseif (is_array($value)) {
					$ret[$key] = profile_object2array($value);
				} else {
					$ret[$key] = $value;
				}
			}
			return $ret;
		}
	}

	if (!function_exists("profile_getandwrite")) {
		function profile_getandwrite($url, $writepathfile) {
			$module_handler = xoops_gethandler('module');
			$config_handler = xoops_gethandler('config');
			if (!isset($GLOBALS['profileModule']))
				$GLOBALS['profileModule'] = $module_handler->getByDirname('profile');
			if (!isset($GLOBALS['profileModuleConfig']))
				$GLOBALS['profileModuleConfig'] = $config_handler->getConfigList($GLOBALS['profileModule']->getVar('mid'));
		
			$data = profile_get_curl($url); 
			
			// Clears Existing Data
			if (file_exists($writepathfile)) {
				unlink($writepathfile);
			}
			// Writes file
			$file = fopen($writepathfile, 'w+');
			fwrite($file, $data, strlen($data));
			fclose($file);
			
			return true;
		}
	}
?>