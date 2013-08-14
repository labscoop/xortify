<?php

if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}

if (isset($_SESSION['oAuthFunction'])) {
	switch($_SESSION['oAuthFunction']) {
	case 'linkedin':
		include_once(dirname(dirname(__FILE__)).'/include/linkedin.php');
		break;
	default:
	case 'oauth':
		include_once(dirname(dirname(__FILE__)).'/include/oauth/OAuthServer.php');
		include_once(dirname(dirname(__FILE__)).'/include/oauth/OAuthStore.php');
		break;
	case 'twitter':
		include_once(dirname(dirname(__FILE__)).'/include/oauth/OAuthStore.php');
		break;
	}
}

/**
 * Class for Blue Room Xcenter
 * @author Simon Roberts <simon@xoops.org>
 * @copyright copyright (c) 2009-2003 XOOPS.org
 * @package kernel
 */
class ProfileOauth extends XoopsObject
{

	
    function __construct($id = null)
    {
   	
        $this->initVar('oauth_id', XOBJ_DTYPE_INT, null, false);
		$this->initVar('status', XOBJ_DTYPE_ENUM, 'valid', false, false, false, false, array('valid','invalid'));
		$this->initVar('mode', XOBJ_DTYPE_ENUM, 'oauth', false, false, false, false, array('oauth','openid'));
		$this->initVar('type', XOBJ_DTYPE_ENUM, 'facebook', false, false, false, false, array('facebook','twitter','linkedin','openid','other'));
		$this->initVar('access_oauth_token', XOBJ_DTYPE_TXTBOX, null, false, 255);
		$this->initVar('access_oauth_token_secret', XOBJ_DTYPE_TXTBOX, null, false, 255);
		$this->initVar('access_oauth_expires_in', XOBJ_DTYPE_INT, null, false);
		$this->initVar('request_oauth_token', XOBJ_DTYPE_TXTBOX, null, false, 255);
		$this->initVar('request_oauth_token_secret', XOBJ_DTYPE_TXTBOX, null, false, 255);
		$this->initVar('request_oauth_expires_in', XOBJ_DTYPE_INT, null, false);
		$this->initVar('username', XOBJ_DTYPE_TXTBOX, null, false, 64);
        $this->initVar('ip', XOBJ_DTYPE_TXTBOX, null, false, 64);
        $this->initVar('netbios', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('uid', XOBJ_DTYPE_INT, null, false);
		$this->initVar('created', XOBJ_DTYPE_INT, null, false); // Removed Unicode in 2.10
		$this->initVar('updated', XOBJ_DTYPE_INT, null, false); // Removed Unicode in 2.10
		$this->initVar('signedup', XOBJ_DTYPE_INT, null, false); // Removed Unicode in 2.10
		
		if ($id>0) {
			$handler = new ProfileOauthHandler($GLOBALS['xoopsDB']);
			$object = $handler->get($id);
			if (is_object($object)) {
				if (is_a($object, 'ProfileOauth')) {
					$this->assignVars($object->getValues());
				}
			}
			unset($object);
		}
    }

    function setVar($field, $value) {
    	if (isset($this->vars[$field])) {
	    	switch ($this->vars[$field]['data_type']) {
	    		case XOBJ_DTYPE_ARRAY:
	    			if (md5(serialize($value))!=md5(serialize($this->getVar($field))))
	    				parent::setVar($field, $value);
	    			break;
	    		default:
	    			if (is_array($value)) {
		    			if (md5(serialize($value))!=md5($this->getVar($field))) {
		    				parent::setVar($field, $value);
		    			}
	    			} elseif (md5($value)!=md5($this->getVar($field))) {
	    				parent::setVar($field, $value);
	    			}
	    			break;
	    	}
    	}
    }
            
    function setVars($arr, $not_gpc=false) {
    	foreach($arr as $field => $value) {
    		if (isset($this->vars[$field])) {
	    		switch ($this->vars[$field]['data_type']) {
	    			case XOBJ_DTYPE_ARRAY:
	    				if (md5(serialize($value))!=md5(serialize($this->getVar($field))))
	    					parent::setVar($field, $value);
	    				break;
	    			default:
			    		if (is_array($value)) {
			    			if (md5(serialize($value))!=md5($this->getVar($field))) {
			    				parent::setVar($field, $value);
			    			}
		    			} elseif (md5($value)!=md5($this->getVar($field))) {
		    				parent::setVar($field, $value);
		    			}
	    				break;
	    		}
    		}
    	}	
    }   
   
    function getAccessToken() {
    	$ret = array();
    	$ret['oauth_token'] = $this->getVar('access_oauth_token');
    	$ret['oauth_token_secret'] = $this->getVar('access_oauth_token_secret');
    	$ret['oauth_expires_in'] = time()-$this->getVar('access_oauth_expires_in');
    	return $ret;
    }
    
	function getRequestToken() {
    	$ret = array();
    	$ret['oauth_token'] = $this->getVar('request_oauth_token');
    	$ret['oauth_token_secret'] = $this->getVar('request_oauth_token_secret');
    	$ret['oauth_expires_in'] = time()-$this->getVar('request_oauth_expires_in');
    	return $ret;
    }
    
    function getName() {
    	return $this->getVar('status').', '.$this->getVar('type').', '.$this->getVar('mode').', '.$this->getVar('ip').', '.$this->getVar('uid');
    }
    
    function getForm($as_array=false, $title='') {
    	$class = explode('.',basename(__FILE__));
		unset($class[sizeof($class)-1]);
		$class = implode('.',$class);
		// Gets Title
		xoops_loadLanguage('forms', 'profile');
		if (empty($title)) {
    		if ($this->isNew()) {
    			if (defined("FRM_LINKEDIN_TITLE_NEW_".strtoupper($class)))
    				$title = constant("FRM_LINKEDIN_TITLE_NEW_".strtoupper($class));
    		} else {
    			if (defined("FRM_LINKEDIN_TITLE_EDIT_".strtoupper($class)))
    				$title = sprintf(constant("FRM_LINKEDIN_TITLE_EDIT_".strtoupper($class)), $this->getName());
    		}
    	}
    	// Gets Form
		$func = 'profile_form_item_'.$class;
		if (function_exists($func)) {
			return $func($this, $title, $as_array);
		}
    }
    
    function toArray() {
    	$ret = array();
    	foreach(parent::toArray() as $field => $value) {
    		$ret[str_replace('-', '_', $field)] = $value;
    	}
    	
    	if (isset($ret['created'])&&$ret['created']>0) {
    		$ret['created'] = date(_DATESTRING, $ret['created']);
    	}
    	if (isset($ret['updated'])&&$ret['updated']>0) {
    		$ret['updated'] = date(_DATESTRING, $ret['updated']);
    	}
    	if (isset($ret['signedup'])&&$ret['signedup']>0) {
    		$ret['signedup'] = date(_DATESTRING, $ret['signedup']);
    	}
    	if (is_array($form = $this->getForm(true, ''))) {
    		foreach($form as $field => $element) {
    			$ret['form'][$field] = $form[$field]->render();
    		}
    	} 
    	return $ret;
    }

    
}


/**
* XOOPS policies handler class.
* This class is responsible for providing data access mechanisms to the data source
* of XOOPS user class objects.
*
* @author  Simon Roberts <simon@labs.coop>
* @package kernel
*/
class ProfileOauthHandler extends XoopsPersistableObjectHandler
{

	var $_linkedin_api_linkedin_config = 	array(	'appKey'       => '',
	  												'appSecret'    => '',
	  												'callbackUrl'  => NULL	); 
	var $_twitter_api_config			=	array(	'prefix'				=>	'profile_',
													'consumer_key'			=>	'',
													'consumer_secret'		=>	'',
													'callback_url'			=>	NULL,
													'server_uri'			=>	'https://api.twitter.com',
													'request_token_uri'		=>	'https://api.twitter.com/oauth/request_token',
													'authorize_uri'			=>	'https://api.twitter.com/oauth/authorize',
													'access_token_uri'		=>	'https://api.twitter.com/oauth/access_token'
											);
											
	var $_facebook_api_config			=	array();											
	var $_state 	=	'oauth';
	
	// Objects  
	var $_api_linkedin 	 = 	NULL;
	var $_obj	 = 	NULL;
	
	// OAuth Server Information Objects
	var $server = NULL;
	var $store = NULL;
	
	function __construct(&$db) 
    {
		$this->db = $db;
        parent::__construct($db, 'profile_oauth', 'ProfileOauth', "oauth_id", "type");
               
        $module_handler = xoops_gethandler('module');
    	$config_handler = xoops_gethandler('config');
    	if (!isset($GLOBALS['profileModule']))
    		$GLOBALS['profileModule'] = $module_handler->getByDirname('profile');
    	if (!isset($GLOBALS['profileModuleConfig']))
    		$GLOBALS['profileModuleConfig'] = $config_handler->getConfigList($GLOBALS['profileModule']->getVar('mid'));
		
    	if (isset($_SESSION['oAuthFunction'])) {
    		$this->_state = $_SESSION['oAuthFunction'];
    	}
    	
    	if (isset($_SESSION['oauth']['oauth_id']))
        	$this->_obj = $this->get($_SESSION['oauth']['oauth_id']);
    	
		switch($this->_state) {
			case 'linkedin':
		    	$this->_linkedin_api_linkedin_config['appKey'] = $GLOBALS['profileModuleConfig']['linkedin_app_key'];
		    	$this->_linkedin_api_linkedin_config['appSecret'] = $GLOBALS['profileModuleConfig']['linkedin_app_secret'];
		    	$this->_linkedin_api_linkedin_config['callbackUrl'] = $GLOBALS['profileModuleConfig']['linkedin_callback_url'] . '?' . LINKEDIN::_GET_TYPE . '=initiate&' . LINKEDIN::_GET_RESPONSE . '=1';
		    	$this->_api_linkedin = new LinkedIn($this->_linkedin_api_linkedin_config);

		    	$_SESSION['oauth']['linkedin']['authorized'] = (isset($_SESSION['oauth']['linkedin']['authorized'])) ? $_SESSION['oauth']['linkedin']['authorized'] : FALSE;
		        if($_SESSION['oauth']['linkedin']['authorized'] === TRUE) {
		            $this->_api_linkedin->setTokenAccess($_SESSION['oauth']['linkedin']['access']);
		          	$this->_api_linkedin->setResponseFormat(LINKEDIN::_RESPONSE_XML);
		        }
		       	break;
			case 'twitter':
			 	$this->_twitter_api_config['consumer_key'] = $GLOBALS['profileModuleConfig']['twitter_app_key'];
    			$this->_twitter_api_config['consumer_secret'] = $GLOBALS['profileModuleConfig']['twitter_app_secret'];
        		$this->_twitter_api_config['callback_url'] = $GLOBALS['profileModuleConfig']['twitter_callback_url'];
                $this->store = OAuthStore::instance("Session", $this->_twitter_api_config);
                break;
			case 'facebook':
	            break;
            default:
			case 'oauth':
				$this->intialiseServer();
				break;
		}
    }

    function intialiseServer() {
    	$this->store = OAuthStore::instance("MySQL", array('prefix'=>$GLOBALS['xoopsDB']->prefix('profile_'), 'conn'=>$GLOBALS['xoopsDB']->conn));
    	$this->server = new OAuthServer();
    }
    
    function getByCriteria($criteria = NULL) {
    	if ($this->getCount($criteria)==0)
    		return $this->create();
    	$criteria->setStart(0);
    	$criteria->setLimit(1);
    	$objects = $this->getObjects($criteria, false);
    	if (!is_object($objects[0]))
    		return $this->create();
    	return $objects[0];
    }
    
    function getOAuthUser_Linkedin($id, $firstname, $lastname, $avatar) {
    	$member_handler = xoops_gethandler('member');
  		$criteria = new CriteriaCompo(new Criteria('`username`', $id));
  		$criteria->add(new Criteria('`type`', 'linkedin'));
    	if ($this->getCount($criteria)==0) {
    		// Gets Avatar
    		$write = XOOPS_ROOT_PATH.'/uploads/'.substr(md5($avatar), mt_rand(0,22),10).'.jpg';
    		profile_getandwrite($avatar, $write);
			if (!is_object($GLOBALS['xoopsUser'])) {
				
				if ($GLOBALS['profileModuleConfig']['local']==true&&strlen($GLOBALS['profileModuleConfig']['ipdb_apikey'])>0) {
					if (!class_exists('ip2location_lite')) {
						include_once $GLOBALS['xoops']->path('modules/profile/include/ip2locationlite.class.php');
					}
					set_time_limit(120);
					$ipLite = new ip2location_lite;
					$ipLite->setKey($GLOBALS['profileModuleConfig']['ipdb_apikey']);
					//Get errors and locations
					$locations = $ipLite->getCity(profile_getIP());
					if (	!in_array(strtoupper($locations['countryCode']), $GLOBALS['profileModuleConfig']['countrycodes']) &&
							!in_array(ucfirst($locations['regionName']), explode('|',$GLOBALS['profileModuleConfig']['districts'])) && 
							!in_array(ucfirst($locations['cityName']), explode('|',$GLOBALS['profileModuleConfig']['citys']))) {
								
								$xoopsOption['template_main'] = 'profile_not_local.html';
								include $GLOBALS['xoops']->path('header.php');
								if (file_exists($GLOBALS['xoops']->path('modules/' . $GLOBALS['profileModule']->getVar('dirname', 'n') . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/style.css'))) {
									$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL . '/modules/' . $GLOBALS['profileModule']->getVar('dirname', 'n') . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/style.css', array('type'=>'text/css'));
								} else { 
									$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL . '/modules/' . $GLOBALS['profileModule']->getVar('dirname', 'n') . '/language/english/style.css', array('type'=>'text/css'));
								}
								$GLOBALS['xoopsTpl']->assign('ip', profile_getIP());
								$GLOBALS['xoopsTpl']->assign('location', $locations);
								$GLOBALS['xoopsTpl']->assign('countrycodes', $GLOBALS['profileModuleConfig']['countrycodes']);
								$GLOBALS['xoopsTpl']->assign('districts', explode('|',$GLOBALS['profileModuleConfig']['districts']));
								$GLOBALS['xoopsTpl']->assign('citys', explode('|',$GLOBALS['profileModuleConfig']['citys']));
								include $GLOBALS['xoops']->path('footer.php');
								exit(0);			
								
							}
					    	
				}
				
				if ($GLOBALS['profileModuleConfig']['speedtest']==true) {
					$speedtest_handler = xoops_getmodulehandler('speedtest', 'speedtest');
					$criteria = new Criteria('`ip_string`', profile_getIP());
					if ($speedtest_handler->getCount($criteria)==0) {
						$_SESSION['profileDoSpeedtest'] = true;
						header("location: ".XOOPS_URL . '/modules/speedtest/download.php');
				    	exit();	
					}
					$criteria->setSort('`downspeed`, `upspeed`');
					$criteria->setOrder('DESC');
					$criteria->setLimit(1);
					$speedtests = $speedtest_handler->getObjects($criteria, false);
					$downlink_passes = false;
					if (in_array('DOWNLINK', $GLOBALS['profileModuleConfig']['speedtest_test'])&&(($speedtests[0]->getVar('downspeed')/1024)>=$GLOBALS['profileModuleConfig']['speedtest_downlink'])) {
						$downlink_passes = true;
						$downlink_diff = (($speedtests[0]->getVar('downspeed')/1024)-$GLOBALS['profileModuleConfig']['speedtest_downlink']);
					} elseif (!in_array('DOWNLINK', $GLOBALS['profileModuleConfig']['speedtest_test'])) {
						$downlink_passes = true;
						$downlink_diff = 0;
					} elseif (in_array('DOWNLINK', $GLOBALS['profileModuleConfig']['speedtest_test'])&&(($speedtests[0]->getVar('downspeed')/1024)<$GLOBALS['profileModuleConfig']['speedtest_downlink'])) {
						$downlink_passes = false;
						$downlink_diff = ($GLOBALS['profileModuleConfig']['speedtest_downlink']-($speedtests[0]->getVar('downspeed')/1024));
					}
					$uplink_passes = false;
					if (in_array('UPLINK', $GLOBALS['profileModuleConfig']['speedtest_test'])&&(($speedtests[0]->getVar('upspeed')/1024)>=$GLOBALS['profileModuleConfig']['speedtest_uplink'])) {
						$uplink_passes = true;
						$uplink_diff = (($speedtests[0]->getVar('upspeed')/1024)-$GLOBALS['profileModuleConfig']['speedtest_uplink']);
					} elseif (!in_array('UPLINK', $GLOBALS['profileModuleConfig']['speedtest_test'])) {
						$uplink_passes = true;
						$uplink_diff = 0;
					} elseif (in_array('UPLINK', $GLOBALS['profileModuleConfig']['speedtest_test'])&&(($speedtests[0]->getVar('upspeed')/1024)<$GLOBALS['profileModuleConfig']['speedtest_uplink'])) {
						$uplink_passes = false;
						$uplink_diff = ($GLOBALS['profileModuleConfig']['speedtest_uplink']-($speedtests[0]->getVar('upspeed')/1024));
					}
					if ($uplink_passes!=true||$downlink_passes!=true) {
						$xoopsOption['template_main'] = 'profile_not_passed_speedtest.html';
						include $GLOBALS['xoops']->path('header.php');
						if (file_exists($GLOBALS['xoops']->path('modules/' . $GLOBALS['profileModule']->getVar('dirname', 'n') . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/style.css'))) {
							$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL . '/modules/' . $GLOBALS['profileModule']->getVar('dirname', 'n') . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/style.css', array('type'=>'text/css'));
						} else { 
							$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL . '/modules/' . $GLOBALS['profileModule']->getVar('dirname', 'n') . '/language/english/style.css', array('type'=>'text/css'));
						}
						$GLOBALS['xoopsTpl']->assign('downlink_passes', $downlink_passes);
						$GLOBALS['xoopsTpl']->assign('uplink_passes', $uplink_passes);
						$GLOBALS['xoopsTpl']->assign('downlink_diff', number_format($downlink_diff,2));
						$GLOBALS['xoopsTpl']->assign('uplink_diff', number_format($uplink_diff,2));
						$GLOBALS['xoopsTpl']->assign('downlink_tested', number_format($speedtests[0]->getVar('downspeed')/1024, 2));
						$GLOBALS['xoopsTpl']->assign('uplink_tested', number_format($speedtests[0]->getVar('upspeed')/1024, 2));
						$GLOBALS['xoopsTpl']->assign('downlink_required', number_format($GLOBALS['profileModuleConfig']['speedtest_downlink'], 2));
						$GLOBALS['xoopsTpl']->assign('uplink_required', number_format($GLOBALS['profileModuleConfig']['speedtest_uplink'], 2));
						include $GLOBALS['xoops']->path('footer.php');
						exit(0);			
					}
					
				}
	    		// Created New User
	    		$user = $member_handler->createUser();
				$user->setVar('name', $firstname. ' ' . $lastname);
				$user->setVar('uname', $firstname.$lastname.mt_rand(0,22));
				$user->setVar('pass', md5(xoops_makepass()));
				$user->setVar('user_avatar', basename($write));
				$user->setVar('user_regdate', time());
				$user->setVar('last_login', time());
				$user->setVar('level', 1);
				$user = $member_handler->getUser($uid = $member_handler->insertUser($user, true));
				$GLOBALS['xoopsDB']->queryF("INSERT INTO `".$GLOBALS['xoopsDB']->prefix('groups_users_link')."` (`groupid`, `uid`) VALUES (".XOOPS_GROUP_USERS.",".$uid.')');
				if ($GLOBALS['profileModuleConfig']['linkedin_group']!=XOOPS_GROUP_USERS&&$GLOBALS['profileModuleConfig']['linkedin_group']!=XOOPS_GROUP_ADMIN) {
					$GLOBALS['xoopsDB']->queryF("INSERT INTO `".$GLOBALS['xoopsDB']->prefix('groups_users_link')."` (`groupid`, `uid`) VALUES (".$GLOBALS['profileModuleConfig']['linkedin_group'].",".$uid.')');
				}
				
			} else {
				$user = $GLOBALS['xoopsUser'];
			}
			//Creates Oauth Record for harvesting
			$oauth = $this->create();
			$oauth->setVar('uid', $user->getVar('uid'));
			$oauth->setVar('ip', $ip = profile_getIP());
			$oauth->setVar('netddy', gethostbyaddr($ip));
			$oauth->setVar('username', $id);
			$oauth->setVar('status', 'valid');
			$oauth->setVar('mode', 'oauth');
			$oauth->setVar('type', 'linkedin');
			$oauth->setVar('created', time());
			$oauth->setVar('signedup', time());
			$oauth->setVar('access_oauth_token', $_SESSION['oauth']['linkedin']['access']['oauth_token']);
			$oauth->setVar('access_oauth_token_secret', $_SESSION['oauth']['linkedin']['access']['oauth_token_secret']);
			$oauth->setVar('access_oauth_expires_in', time() + $_SESSION['oauth']['linkedin']['access']['oauth_expires_in']);
			$oauth->setVar('request_oauth_token', $_SESSION['oauth']['linkedin']['request']['oauth_token']);
			$oauth->setVar('request_oauth_token_secret', $_SESSION['oauth']['linkedin']['request']['oauth_token_secret']);
			$oauth->setVar('request_oauth_expires_in', time() + $_SESSION['oauth']['linkedin']['request']['oauth_expires_in']);
			$oauth->setDirty(true);
			$oauth = $this->get($_SESSION['oauth']['oauth_id'] = parent::insert($oauth, true));
			$this->_obj = $oauth;
			return $user;
    	} else {
    		$oauths = $this->getObjects($criteria, false);
    		if (is_object($oauths[0])) {
    			if ($GLOBALS['profileModuleConfig']['local']==true&&strlen($GLOBALS['profileModuleConfig']['ipdb_apikey'])>0) {
					if (!class_exists('ip2location_lite')) {
						include_once $GLOBALS['xoops']->path('modules/profile/include/ip2locationlite.class.php');
					}
					set_time_limit(120);
					$ipLite = new ip2location_lite;
					$ipLite->setKey($GLOBALS['profileModuleConfig']['ipdb_apikey']);
					//Get errors and locations
					$locations = $ipLite->getCity(profile_getIP());
					if (	!in_array(strtoupper($locations['countryCode']), $GLOBALS['profileModuleConfig']['countrycodes']) &&
							!in_array(ucfirst($locations['regionName']), explode('|',$GLOBALS['profileModuleConfig']['districts'])) && 
							!in_array(ucfirst($locations['cityName']), explode('|',$GLOBALS['profileModuleConfig']['citys']))) {
								
								$xoopsOption['template_main'] = 'profile_not_local.html';
								include $GLOBALS['xoops']->path('header.php');
								if (file_exists($GLOBALS['xoops']->path('modules/' . $GLOBALS['profileModule']->getVar('dirname', 'n') . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/style.css'))) {
									$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL . '/modules/' . $GLOBALS['profileModule']->getVar('dirname', 'n') . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/style.css', array('type'=>'text/css'));
								} else { 
									$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL . '/modules/' . $GLOBALS['profileModule']->getVar('dirname', 'n') . '/language/english/style.css', array('type'=>'text/css'));
								}
								$GLOBALS['xoopsTpl']->assign('ip', profile_getIP());
								$GLOBALS['xoopsTpl']->assign('location', $locations);
								$GLOBALS['xoopsTpl']->assign('countrycodes', $GLOBALS['profileModuleConfig']['countrycodes']);
								$GLOBALS['xoopsTpl']->assign('districts', explode('|',$GLOBALS['profileModuleConfig']['districts']));
								$GLOBALS['xoopsTpl']->assign('citys', explode('|',$GLOBALS['profileModuleConfig']['citys']));
								include $GLOBALS['xoops']->path('footer.php');
								exit(0);			
								
							}
    			}
    							
    			$user = $member_handler->getUser($oauths[0]->getVar('uid'));
    			if ($user->getVar('level')>0) {
					$oauths[0]->setVar('ip', $ip = profile_getIP());
					$oauths[0]->setVar('netddy', gethostbyaddr($ip));
					$oauths[0]->setVar('status', 'valid');
					$oauths[0]->setVar('mode', 'oauth');
					$oauths[0]->setVar('type', 'linkedin');
					$oauths[0]->setVar('updated', time());
					$oauths[0]->setVar('signedup', time());
					$oauths[0]->setVar('access_oauth_token', $_SESSION['oauth']['linkedin']['access']['oauth_token']);
					$oauths[0]->setVar('access_oauth_token_secret', $_SESSION['oauth']['linkedin']['access']['oauth_token_secret']);
					$oauths[0]->setVar('access_oauth_expires_in', time() + $_SESSION['oauth']['linkedin']['access']['oauth_expires_in']);
					$oauths[0]->setVar('request_oauth_token', $_SESSION['oauth']['linkedin']['request']['oauth_token']);
					$oauths[0]->setVar('request_oauth_token_secret', $_SESSION['oauth']['linkedin']['request']['oauth_token_secret']);
					$oauths[0]->setVar('request_oauth_expires_in', time() + $_SESSION['oauth']['linkedin']['request']['oauth_expires_in']);
		    		$_SESSION['oauth']['oauth_id'] = parent::insert($oauths[0], true);
		    		$this->_obj = $oauths[0];
					$user->setVar('last_login', time());
					if (!empty($avatar)) {
    					$write = XOOPS_ROOT_PATH.'/uploads/'.(strlen($user->getVar('user_avatar'))==0||$user->getVar('user_avatar')=='blank.gif'?substr(md5($avatar), mt_rand(0,22),10).'.jpg':$user->getVar('user_avatar'));
    					profile_getandwrite($avatar, $write);
						$user->setVar('user_avatar', basename($write));
					}
				}
				$user = $member_handler->getUser($member_handler->insertUser($user, true));
				if ($user->getVar('level')>0) {
					return $user;
				}
    		}
    	}
    	return false;
    }
    
 	function getOAuthUser_Twitter($id, $name, $username, $avatar) {
    	$member_handler = xoops_gethandler('member');
  		$criteria = new CriteriaCompo(new Criteria('`username`', $id));
  		$criteria->add(new Criteria('`type`', 'twitter'));
    	if ($this->getCount($criteria)==0) {
    		// Gets Avatar
    		$write = XOOPS_ROOT_PATH.'/uploads/'.substr(md5($avatar), mt_rand(0,22),10).'.jpg';
    		profile_getandwrite($avatar, $write);
			if (!is_object($GLOBALS['xoopsUser'])) {
				if ($GLOBALS['profileModuleConfig']['local']==true&&strlen($GLOBALS['profileModuleConfig']['ipdb_apikey'])>0) {
					if (!class_exists('ip2location_lite')) {
						include_once $GLOBALS['xoops']->path('modules/profile/include/ip2locationlite.class.php');
					}
					set_time_limit(120);
					$ipLite = new ip2location_lite;
					$ipLite->setKey($GLOBALS['profileModuleConfig']['ipdb_apikey']);
					//Get errors and locations
					$locations = $ipLite->getCity(profile_getIP());
					if (	!in_array(strtoupper($locations['countryCode']), $GLOBALS['profileModuleConfig']['countrycodes']) &&
							!in_array(ucfirst($locations['regionName']), explode('|',$GLOBALS['profileModuleConfig']['districts'])) && 
							!in_array(ucfirst($locations['cityName']), explode('|',$GLOBALS['profileModuleConfig']['citys']))) {
								
								$xoopsOption['template_main'] = 'profile_not_local.html';
								include $GLOBALS['xoops']->path('header.php');
								if (file_exists($GLOBALS['xoops']->path('modules/' . $GLOBALS['profileModule']->getVar('dirname', 'n') . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/style.css'))) {
									$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL . '/modules/' . $GLOBALS['profileModule']->getVar('dirname', 'n') . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/style.css', array('type'=>'text/css'));
								} else { 
									$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL . '/modules/' . $GLOBALS['profileModule']->getVar('dirname', 'n') . '/language/english/style.css', array('type'=>'text/css'));
								}
								$GLOBALS['xoopsTpl']->assign('ip', profile_getIP());
								$GLOBALS['xoopsTpl']->assign('location', $locations);
								$GLOBALS['xoopsTpl']->assign('countrycodes', $GLOBALS['profileModuleConfig']['countrycodes']);
								$GLOBALS['xoopsTpl']->assign('districts', explode('|',$GLOBALS['profileModuleConfig']['districts']));
								$GLOBALS['xoopsTpl']->assign('citys', explode('|',$GLOBALS['profileModuleConfig']['citys']));
								include $GLOBALS['xoops']->path('footer.php');
								exit(0);			
								
							}
					    	
				}
				
				if ($GLOBALS['profileModuleConfig']['speedtest']==true) {
					$speedtest_handler = xoops_getmodulehandler('speedtest', 'speedtest');
					$criteria = new Criteria('`ip_string`', profile_getIP());
					if ($speedtest_handler->getCount($criteria)==0) {
						$_SESSION['profileDoSpeedtest'] = true;
						header("location: ".XOOPS_URL . '/modules/speedtest/download.php');
				    	exit();	
					}
					$criteria->setSort('`downspeed`, `upspeed`');
					$criteria->setOrder('DESC');
					$criteria->setLimit(1);
					$speedtests = $speedtest_handler->getObjects($criteria, false);
					$downlink_passes = false;
					if (in_array('DOWNLINK', $GLOBALS['profileModuleConfig']['speedtest_test'])&&(($speedtests[0]->getVar('downspeed')/1024)>=$GLOBALS['profileModuleConfig']['speedtest_downlink'])) {
						$downlink_passes = true;
						$downlink_diff = (($speedtests[0]->getVar('downspeed')/1024)-$GLOBALS['profileModuleConfig']['speedtest_downlink']);
					} elseif (!in_array('DOWNLINK', $GLOBALS['profileModuleConfig']['speedtest_test'])) {
						$downlink_passes = true;
						$downlink_diff = 0;
					} elseif (in_array('DOWNLINK', $GLOBALS['profileModuleConfig']['speedtest_test'])&&(($speedtests[0]->getVar('downspeed')/1024)<$GLOBALS['profileModuleConfig']['speedtest_downlink'])) {
						$downlink_passes = false;
						$downlink_diff = ($GLOBALS['profileModuleConfig']['speedtest_downlink']-($speedtests[0]->getVar('downspeed')/1024));
					}
					$uplink_passes = false;
					if (in_array('UPLINK', $GLOBALS['profileModuleConfig']['speedtest_test'])&&(($speedtests[0]->getVar('upspeed')/1024)>=$GLOBALS['profileModuleConfig']['speedtest_uplink'])) {
						$uplink_passes = true;
						$uplink_diff = (($speedtests[0]->getVar('upspeed')/1024)-$GLOBALS['profileModuleConfig']['speedtest_uplink']);
					} elseif (!in_array('UPLINK', $GLOBALS['profileModuleConfig']['speedtest_test'])) {
						$uplink_passes = true;
						$uplink_diff = 0;
					} elseif (in_array('UPLINK', $GLOBALS['profileModuleConfig']['speedtest_test'])&&(($speedtests[0]->getVar('upspeed')/1024)<$GLOBALS['profileModuleConfig']['speedtest_uplink'])) {
						$uplink_passes = false;
						$uplink_diff = ($GLOBALS['profileModuleConfig']['speedtest_uplink']-($speedtests[0]->getVar('upspeed')/1024));
					}
					if ($uplink_passes!=true||$downlink_passes!=true) {
						$xoopsOption['template_main'] = 'profile_not_passed_speedtest.html';
						include $GLOBALS['xoops']->path('header.php');
						if (file_exists($GLOBALS['xoops']->path('modules/' . $GLOBALS['profileModule']->getVar('dirname', 'n') . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/style.css'))) {
							$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL . '/modules/' . $GLOBALS['profileModule']->getVar('dirname', 'n') . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/style.css', array('type'=>'text/css'));
						} else { 
							$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL . '/modules/' . $GLOBALS['profileModule']->getVar('dirname', 'n') . '/language/english/style.css', array('type'=>'text/css'));
						}
						$GLOBALS['xoopsTpl']->assign('downlink_passes', $downlink_passes);
						$GLOBALS['xoopsTpl']->assign('uplink_passes', $uplink_passes);
						$GLOBALS['xoopsTpl']->assign('downlink_diff', number_format($downlink_diff,2));
						$GLOBALS['xoopsTpl']->assign('uplink_diff', number_format($uplink_diff,2));
						$GLOBALS['xoopsTpl']->assign('downlink_tested', number_format($speedtests[0]->getVar('downspeed')/1024, 2));
						$GLOBALS['xoopsTpl']->assign('uplink_tested', number_format($speedtests[0]->getVar('upspeed')/1024, 2));
						$GLOBALS['xoopsTpl']->assign('downlink_required', number_format($GLOBALS['profileModuleConfig']['speedtest_downlink'], 2));
						$GLOBALS['xoopsTpl']->assign('uplink_required', number_format($GLOBALS['profileModuleConfig']['speedtest_uplink'], 2));
						include $GLOBALS['xoops']->path('footer.php');
						exit(0);			
					}
					
				}
				// Created New User
	    		$user = $member_handler->createUser();
				$user->setVar('name', $name);
				$user->setVar('uname', $username);
				$user->setVar('url', 'http://twitter.com/'.$username);
				$user->setVar('pass', md5(xoops_makepass()));
				$user->setVar('user_avatar', basename($write));
				$user->setVar('user_regdate', time());
				$user->setVar('last_login', time());
				$user->setVar('level', 1);
				$user = $member_handler->getUser($uid = $member_handler->insertUser($user, true));
				$GLOBALS['xoopsDB']->queryF("INSERT INTO `".$GLOBALS['xoopsDB']->prefix('groups_users_link')."` (`groupid`, `uid`) VALUES (".XOOPS_GROUP_USERS.",".$uid.')');
				if ($GLOBALS['profileModuleConfig']['twitter_group']!=XOOPS_GROUP_USERS&&$GLOBALS['profileModuleConfig']['twitter_group']!=XOOPS_GROUP_ADMIN) {
					$GLOBALS['xoopsDB']->queryF("INSERT INTO `".$GLOBALS['xoopsDB']->prefix('groups_users_link')."` (`groupid`, `uid`) VALUES (".$GLOBALS['profileModuleConfig']['twitter_group'].",".$uid.')');
				}
			} else {
				$user = $GLOBALS['xoopsUser'];
			}
			//Creates Oauth Record for harvesting
			$oauth = $this->create();
			$oauth->setVar('uid', $user->getVar('uid'));
			$oauth->setVar('ip', $ip = profile_getIP());
			$oauth->setVar('netddy', gethostbyaddr($ip));
			$oauth->setVar('username', $id);
			$oauth->setVar('status', 'valid');
			$oauth->setVar('mode', 'oauth');
			$oauth->setVar('type', 'twitter');
			$oauth->setVar('created', time());
			$oauth->setVar('signedup', time());
			$oauth->setVar('access_oauth_token', $_SESSION['oauth']['twitter']['access']['oauth_token']);
			$oauth->setVar('access_oauth_token_secret', $_SESSION['oauth']['twitter']['access']['oauth_token_secret']);
			$oauth->setVar('access_oauth_expires_in', time() + $_SESSION['oauth']['twitter']['access']['oauth_expires_in']);
			$oauth->setVar('request_oauth_token', $_SESSION['oauth']['twitter']['request']['oauth_token']);
			$oauth->setVar('request_oauth_token_secret', $_SESSION['oauth']['twitter']['request']['oauth_token_secret']);
			$oauth->setVar('request_oauth_expires_in', time() + $_SESSION['oauth']['twitter']['request']['oauth_expires_in']);
			$oauth->setDirty(true);
			$_SESSION['oauth']['oauth_id'] = parent::insert($oauth, true);
			$this->_obj = $this->get($_SESSION['oauth']['oauth_id']);
			return $user;
    	} else {
    		$oauths = $this->getObjects($criteria, false);
    		if (is_object($oauths[0])) {
    			if ($GLOBALS['profileModuleConfig']['local']==true&&strlen($GLOBALS['profileModuleConfig']['ipdb_apikey'])>0) {
					if (!class_exists('ip2location_lite')) {
						include_once $GLOBALS['xoops']->path('modules/profile/include/ip2locationlite.class.php');
					}
					set_time_limit(120);
					$ipLite = new ip2location_lite;
					$ipLite->setKey($GLOBALS['profileModuleConfig']['ipdb_apikey']);
					//Get errors and locations
					$locations = $ipLite->getCity(profile_getIP());
					if (	!in_array(strtoupper($locations['countryCode']), $GLOBALS['profileModuleConfig']['countrycodes']) &&
							!in_array(ucfirst($locations['regionName']), explode('|',$GLOBALS['profileModuleConfig']['districts'])) && 
							!in_array(ucfirst($locations['cityName']), explode('|',$GLOBALS['profileModuleConfig']['citys']))) {
								
								$xoopsOption['template_main'] = 'profile_not_local.html';
								include $GLOBALS['xoops']->path('header.php');
								if (file_exists($GLOBALS['xoops']->path('modules/' . $GLOBALS['profileModule']->getVar('dirname', 'n') . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/style.css'))) {
									$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL . '/modules/' . $GLOBALS['profileModule']->getVar('dirname', 'n') . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/style.css', array('type'=>'text/css'));
								} else { 
									$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL . '/modules/' . $GLOBALS['profileModule']->getVar('dirname', 'n') . '/language/english/style.css', array('type'=>'text/css'));
								}
								$GLOBALS['xoopsTpl']->assign('ip', profile_getIP());
								$GLOBALS['xoopsTpl']->assign('location', $locations);
								$GLOBALS['xoopsTpl']->assign('countrycodes', $GLOBALS['profileModuleConfig']['countrycodes']);
								$GLOBALS['xoopsTpl']->assign('districts', explode('|',$GLOBALS['profileModuleConfig']['districts']));
								$GLOBALS['xoopsTpl']->assign('citys', explode('|',$GLOBALS['profileModuleConfig']['citys']));
								include $GLOBALS['xoops']->path('footer.php');
								exit(0);			
								
							}
    			}
    		
    			$user = $member_handler->getUser($oauths[0]->getVar('uid'));
    			if ($user->getVar('level')>0) {
					$oauths[0]->setVar('ip', $ip = profile_getIP());
					$oauths[0]->setVar('netddy', gethostbyaddr($ip));
					$oauths[0]->setVar('status', 'valid');
					$oauths[0]->setVar('mode', 'oauth');
					$oauths[0]->setVar('type', 'twitter');
					$oauths[0]->setVar('updated', time());
					$oauths[0]->setVar('signedup', time());
					$oauths[0]->setVar('access_oauth_token', $_SESSION['oauth']['twitter']['access']['oauth_token']);
					$oauths[0]->setVar('access_oauth_token_secret', $_SESSION['oauth']['twitter']['access']['oauth_token_secret']);
					$oauths[0]->setVar('access_oauth_expires_in', time() + $_SESSION['oauth']['twitter']['access']['oauth_expires_in']);
					$oauths[0]->setVar('request_oauth_token', $_SESSION['oauth']['twitter']['request']['oauth_token']);
					$oauths[0]->setVar('request_oauth_token_secret', $_SESSION['oauth']['twitter']['request']['oauth_token_secret']);
					$oauths[0]->setVar('request_oauth_expires_in', time() + $_SESSION['oauth']['twitter']['request']['oauth_expires_in']);
		    		$_SESSION['oauth']['oauth_id'] = parent::insert($oauths[0], true);
		    		$this->_obj = $oauths[0];
					$user->setVar('last_login', time());
					if (!empty($avatar)) {
    					$write = XOOPS_ROOT_PATH.'/uploads/'.(strlen($user->getVar('user_avatar'))==0||$user->getVar('user_avatar')=='blank.gif'?substr(md5($avatar), mt_rand(0,22),10).'.jpg':$user->getVar('user_avatar'));
    					profile_getandwrite($avatar, $write);
						$user->setVar('user_avatar', basename($write));
					}
				}
				$user = $member_handler->getUser($member_handler->insertUser($user, true));
				if ($user->getVar('level')>0) {
					return $user;
				}
    		}
    	}
    	return false;
    }

     function getOAuthUser_Facebook($id, $name, $username, $url, $avatar) {
    	$member_handler = xoops_gethandler('member');
  		$criteria = new CriteriaCompo(new Criteria('`username`', $id));
  		$criteria->add(new Criteria('`type`', 'facebook'));
    	if ($this->getCount($criteria)==0) {
    		// Gets Avatar
    		$write = XOOPS_ROOT_PATH.'/uploads/'.substr(md5($avatar), mt_rand(0,22),10).'.jpg';
    		profile_getandwrite($avatar, $write);
			if (!is_object($GLOBALS['xoopsUser'])) {
			if ($GLOBALS['profileModuleConfig']['local']==true&&strlen($GLOBALS['profileModuleConfig']['ipdb_apikey'])>0) {
					if (!class_exists('ip2location_lite')) {
						include_once $GLOBALS['xoops']->path('modules/profile/include/ip2locationlite.class.php');
					}
					set_time_limit(120);
					$ipLite = new ip2location_lite;
					$ipLite->setKey($GLOBALS['profileModuleConfig']['ipdb_apikey']);
					//Get errors and locations
					$locations = $ipLite->getCity(profile_getIP());
					if (	!in_array(strtoupper($locations['countryCode']), $GLOBALS['profileModuleConfig']['countrycodes']) &&
							!in_array(ucfirst($locations['regionName']), explode('|',$GLOBALS['profileModuleConfig']['districts'])) && 
							!in_array(ucfirst($locations['cityName']), explode('|',$GLOBALS['profileModuleConfig']['citys']))) {
								
								$xoopsOption['template_main'] = 'profile_not_local.html';
								include $GLOBALS['xoops']->path('header.php');
								if (file_exists($GLOBALS['xoops']->path('modules/' . $GLOBALS['profileModule']->getVar('dirname', 'n') . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/style.css'))) {
									$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL . '/modules/' . $GLOBALS['profileModule']->getVar('dirname', 'n') . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/style.css', array('type'=>'text/css'));
								} else { 
									$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL . '/modules/' . $GLOBALS['profileModule']->getVar('dirname', 'n') . '/language/english/style.css', array('type'=>'text/css'));
								}
								$GLOBALS['xoopsTpl']->assign('ip', profile_getIP());
								$GLOBALS['xoopsTpl']->assign('location', $locations);
								$GLOBALS['xoopsTpl']->assign('countrycodes', $GLOBALS['profileModuleConfig']['countrycodes']);
								$GLOBALS['xoopsTpl']->assign('districts', explode('|',$GLOBALS['profileModuleConfig']['districts']));
								$GLOBALS['xoopsTpl']->assign('citys', explode('|',$GLOBALS['profileModuleConfig']['citys']));
								include $GLOBALS['xoops']->path('footer.php');
								exit(0);			
								
							}
					    	
				}
				
				if ($GLOBALS['profileModuleConfig']['speedtest']==true) {
					$speedtest_handler = xoops_getmodulehandler('speedtest', 'speedtest');
					$criteria = new Criteria('`ip_string`', profile_getIP());
					if ($speedtest_handler->getCount($criteria)==0) {
						$_SESSION['profileDoSpeedtest'] = true;
						header("location: ".XOOPS_URL . '/modules/speedtest/download.php');
				    	exit();	
					}
					$criteria->setSort('`downspeed`, `upspeed`');
					$criteria->setOrder('DESC');
					$criteria->setLimit(1);
					$speedtests = $speedtest_handler->getObjects($criteria, false);
					$downlink_passes = false;
					if (in_array('DOWNLINK', $GLOBALS['profileModuleConfig']['speedtest_test'])&&(($speedtests[0]->getVar('downspeed')/1024)>=$GLOBALS['profileModuleConfig']['speedtest_downlink'])) {
						$downlink_passes = true;
						$downlink_diff = (($speedtests[0]->getVar('downspeed')/1024)-$GLOBALS['profileModuleConfig']['speedtest_downlink']);
					} elseif (!in_array('DOWNLINK', $GLOBALS['profileModuleConfig']['speedtest_test'])) {
						$downlink_passes = true;
						$downlink_diff = 0;
					} elseif (in_array('DOWNLINK', $GLOBALS['profileModuleConfig']['speedtest_test'])&&(($speedtests[0]->getVar('downspeed')/1024)<$GLOBALS['profileModuleConfig']['speedtest_downlink'])) {
						$downlink_passes = false;
						$downlink_diff = ($GLOBALS['profileModuleConfig']['speedtest_downlink']-($speedtests[0]->getVar('downspeed')/1024));
					}
					$uplink_passes = false;
					if (in_array('UPLINK', $GLOBALS['profileModuleConfig']['speedtest_test'])&&(($speedtests[0]->getVar('upspeed')/1024)>=$GLOBALS['profileModuleConfig']['speedtest_uplink'])) {
						$uplink_passes = true;
						$uplink_diff = (($speedtests[0]->getVar('upspeed')/1024)-$GLOBALS['profileModuleConfig']['speedtest_uplink']);
					} elseif (!in_array('UPLINK', $GLOBALS['profileModuleConfig']['speedtest_test'])) {
						$uplink_passes = true;
						$uplink_diff = 0;
					} elseif (in_array('UPLINK', $GLOBALS['profileModuleConfig']['speedtest_test'])&&(($speedtests[0]->getVar('upspeed')/1024)<$GLOBALS['profileModuleConfig']['speedtest_uplink'])) {
						$uplink_passes = false;
						$uplink_diff = ($GLOBALS['profileModuleConfig']['speedtest_uplink']-($speedtests[0]->getVar('upspeed')/1024));
					}
					if ($uplink_passes!=true||$downlink_passes!=true) {
						$xoopsOption['template_main'] = 'profile_not_passed_speedtest.html';
						include $GLOBALS['xoops']->path('header.php');
						if (file_exists($GLOBALS['xoops']->path('modules/' . $GLOBALS['profileModule']->getVar('dirname', 'n') . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/style.css'))) {
							$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL . '/modules/' . $GLOBALS['profileModule']->getVar('dirname', 'n') . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/style.css', array('type'=>'text/css'));
						} else { 
							$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL . '/modules/' . $GLOBALS['profileModule']->getVar('dirname', 'n') . '/language/english/style.css', array('type'=>'text/css'));
						}
						$GLOBALS['xoopsTpl']->assign('downlink_passes', $downlink_passes);
						$GLOBALS['xoopsTpl']->assign('uplink_passes', $uplink_passes);
						$GLOBALS['xoopsTpl']->assign('downlink_diff', number_format($downlink_diff,2));
						$GLOBALS['xoopsTpl']->assign('uplink_diff', number_format($uplink_diff,2));
						$GLOBALS['xoopsTpl']->assign('downlink_tested', number_format($speedtests[0]->getVar('downspeed')/1024, 2));
						$GLOBALS['xoopsTpl']->assign('uplink_tested', number_format($speedtests[0]->getVar('upspeed')/1024, 2));
						$GLOBALS['xoopsTpl']->assign('downlink_required', number_format($GLOBALS['profileModuleConfig']['speedtest_downlink'], 2));
						$GLOBALS['xoopsTpl']->assign('uplink_required', number_format($GLOBALS['profileModuleConfig']['speedtest_uplink'], 2));
						include $GLOBALS['xoops']->path('footer.php');
						exit(0);			
					}
					
				}
				// Created New User
	    		$user = $member_handler->createUser();
				$user->setVar('name', $name);
				$user->setVar('uname', $username);
				$user->setVar('url', $url);
				$user->setVar('pass', md5(xoops_makepass()));
				$user->setVar('user_avatar', basename($write));
				$user->setVar('user_regdate', time());
				$user->setVar('last_login', time());
				$user->setVar('level', 1);
				$user = $member_handler->getUser($uid = $member_handler->insertUser($user, true));
				$GLOBALS['xoopsDB']->queryF("INSERT INTO `".$GLOBALS['xoopsDB']->prefix('groups_users_link')."` (`groupid`, `uid`) VALUES (".XOOPS_GROUP_USERS.",".$uid.')');
				if ($GLOBALS['profileModuleConfig']['facebook_group']!=XOOPS_GROUP_USERS&&$GLOBALS['profileModuleConfig']['facebook_group']!=XOOPS_GROUP_ADMIN) {
					$GLOBALS['xoopsDB']->queryF("INSERT INTO `".$GLOBALS['xoopsDB']->prefix('groups_users_link')."` (`groupid`, `uid`) VALUES (".$GLOBALS['profileModuleConfig']['facebook_group'].",".$uid.')');
				}
			} else {
				$user = $GLOBALS['xoopsUser'];
			}
			//Creates Oauth Record for harvesting
			$oauth = $this->create();
			$oauth->setVar('uid', $user->getVar('uid'));
			$oauth->setVar('ip', $ip = profile_getIP());
			$oauth->setVar('netddy', gethostbyaddr($ip));
			$oauth->setVar('username', $id);
			$oauth->setVar('status', 'valid');
			$oauth->setVar('mode', 'oauth');
			$oauth->setVar('type', 'facebook');
			$oauth->setVar('created', time());
			$oauth->setVar('signedup', time());
			$oauth->setVar('access_oauth_token', $_SESSION['oauth']['facebook']['access']['oauth_token']);
			$oauth->setVar('access_oauth_token_secret', $_SESSION['oauth']['facebook']['access']['oauth_token_secret']);
			$oauth->setVar('access_oauth_expires_in', time() + $_SESSION['oauth']['facebook']['access']['oauth_expires_in']);
			$oauth->setVar('request_oauth_token', $_SESSION['oauth']['facebook']['request']['oauth_token']);
			$oauth->setVar('request_oauth_token_secret', $_SESSION['oauth']['facebook']['request']['oauth_token_secret']);
			$oauth->setVar('request_oauth_expires_in', time() + $_SESSION['oauth']['facebook']['request']['oauth_expires_in']);
			$oauth->setDirty(true);
			$_SESSION['oauth']['oauth_id'] = parent::insert($oauth, true);
			$this->_obj = $this->get($_SESSION['oauth']['oauth_id']);
			return $user;
    	} else {
    		$oauths = $this->getObjects($criteria, false);
    		if (is_object($oauths[0])) {
    			
    			if ($GLOBALS['profileModuleConfig']['local']==true&&strlen($GLOBALS['profileModuleConfig']['ipdb_apikey'])>0) {
					if (!class_exists('ip2location_lite')) {
						include_once $GLOBALS['xoops']->path('modules/profile/include/ip2locationlite.class.php');
					}
					set_time_limit(120);
					$ipLite = new ip2location_lite;
					$ipLite->setKey($GLOBALS['profileModuleConfig']['ipdb_apikey']);
					//Get errors and locations
					$locations = $ipLite->getCity(profile_getIP());
					if (	!in_array(strtoupper($locations['countryCode']), $GLOBALS['profileModuleConfig']['countrycodes']) &&
							!in_array(ucfirst($locations['regionName']), explode('|',$GLOBALS['profileModuleConfig']['districts'])) && 
							!in_array(ucfirst($locations['cityName']), explode('|',$GLOBALS['profileModuleConfig']['citys']))) {
								
								$xoopsOption['template_main'] = 'profile_not_local.html';
								include $GLOBALS['xoops']->path('header.php');
								if (file_exists($GLOBALS['xoops']->path('modules/' . $GLOBALS['profileModule']->getVar('dirname', 'n') . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/style.css'))) {
									$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL . '/modules/' . $GLOBALS['profileModule']->getVar('dirname', 'n') . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/style.css', array('type'=>'text/css'));
								} else { 
									$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL . '/modules/' . $GLOBALS['profileModule']->getVar('dirname', 'n') . '/language/english/style.css', array('type'=>'text/css'));
								}
								$GLOBALS['xoopsTpl']->assign('ip', profile_getIP());
								$GLOBALS['xoopsTpl']->assign('location', $locations);
								$GLOBALS['xoopsTpl']->assign('countrycodes', $GLOBALS['profileModuleConfig']['countrycodes']);
								$GLOBALS['xoopsTpl']->assign('districts', explode('|',$GLOBALS['profileModuleConfig']['districts']));
								$GLOBALS['xoopsTpl']->assign('citys', explode('|',$GLOBALS['profileModuleConfig']['citys']));
								include $GLOBALS['xoops']->path('footer.php');
								exit(0);			
								
							}
    			}
    			$user = $member_handler->getUser($oauths[0]->getVar('uid'));
    			if ($user->getVar('level')>0) {
					$oauths[0]->setVar('ip', $ip = profile_getIP());
					$oauths[0]->setVar('netddy', gethostbyaddr($ip));
					$oauths[0]->setVar('status', 'valid');
					$oauths[0]->setVar('mode', 'oauth');
					$oauths[0]->setVar('type', 'facebook');
					$oauths[0]->setVar('updated', time());
					$oauths[0]->setVar('signedup', time());
					$oauths[0]->setVar('access_oauth_token', $_SESSION['oauth']['facebook']['access']['oauth_token']);
					$oauths[0]->setVar('access_oauth_token_secret', $_SESSION['oauth']['facebook']['access']['oauth_token_secret']);
					$oauths[0]->setVar('access_oauth_expires_in', time() + $_SESSION['oauth']['facebook']['access']['oauth_expires_in']);
					$oauths[0]->setVar('request_oauth_token', $_SESSION['oauth']['facebook']['request']['oauth_token']);
					$oauths[0]->setVar('request_oauth_token_secret', $_SESSION['oauth']['facebook']['request']['oauth_token_secret']);
					$oauths[0]->setVar('request_oauth_expires_in', time() + $_SESSION['oauth']['facebook']['request']['oauth_expires_in']);
		    		$_SESSION['oauth']['oauth_id'] = parent::insert($oauths[0], true);
		    		$this->_obj = $oauths[0];
					$user->setVar('last_login', time());
					if (!empty($avatar)) {
    					$write = XOOPS_ROOT_PATH.'/uploads/'.(strlen($user->getVar('user_avatar'))==0||$user->getVar('user_avatar')=='blank.gif'?substr(md5($avatar), mt_rand(0,22),10).'.jpg':$user->getVar('user_avatar'));
    					profile_getandwrite($avatar, $write);
						$user->setVar('user_avatar', basename($write));
					}
				}
				$user = $member_handler->getUser($member_handler->insertUser($user, true));
				if ($user->getVar('level')>0) {
					return $user;
				}
    		}
    	}
    	return false;
    }
    
    function insert($object, $force = true) {
    	if($object->isNew()) {
    	    $criteria = new CriteriaCompo();
    		foreach($object->vars as $field => $values) {
    			if (!in_array($field, array($this->keyName, 'searched', 'polled', 'emailed', 'sms', 'synced', 'created', 'updated')))
    				if ($values['data_type']!=XOBJ_DTYPE_ARRAY)	
    					if (!empty($values['value'])||intval($values['value'])<>0)
    						$criteria->add(new Criteria('`'.$field.'`', $object->getVar($field)));
    		}
    		if ($this->getCount($criteria)>0) {
    			$obj = $this->getByCriteria($criteria);
    			if (is_object($obj)) {
    				return $obj->getVar($this->keyName);
    			}
    		}
    		
    		$object->setVar('created', time());
    	} else {
    		if (!$object->isDirty())
    			return $object->getVar($this->keyName);
    		$object->setVar('updated', time());
    	}
    	return parent::insert($object, $force);
    }
}

?>