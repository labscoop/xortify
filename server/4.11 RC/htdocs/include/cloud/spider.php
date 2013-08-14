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
if (!function_exists('spider')) {

	/*	Xortify Saves a Spider on the Manifest of Crawlers/Bots
	 *  spider($username, $password, $apispider)
 	 *
	 *  @subpackage plugin
	 *
	 *  @param string $username 	Xortify username for function
	 *  @param string $password 	Xortify password or md5 hash of password for function
	 *  @param array $apispider 	Spider/Robot Array [robot-name, robot-cover-url, robot-details-url, robot-owner-name, robot-owner-url, robot-owner-email, robot-status, robot-purpose, robot-type, robot-platform, robot-availability, robot-exclusion, robot-exclusion-useragent, robot-noindex, robot-host, robot-from, robot-useragent, robot-language, robot-description, robot-history, robot-environment, modified-date, modified-by, robot-safeuseragent, robot-handlesession]
	 *  @return array
	 */
	function spider($username, $password, $apispider) {
		global $xoopsModuleConfig, $xoopsDB;
		$id=0;
		
		if ($xoopsModuleConfig['site_user_auth']==1){
			if ($ret = check_for_lock(basename(__FILE__),$username,$password)) { return $ret; }
			if (!checkright(basename(__FILE__),$username,$password)) {
				mark_for_lock(basename(__FILE__),$username,$password);
				return array('ErrNum'=> 9, "ErrDesc" => 'No Permission for plug-in');
			}
		}
		
		$spider_handler = &xoops_getmodulehandler( 'spiders', 'spiders' );	
		$spidermods_handler = &xoops_getmodulehandler( 'modifications', 'spiders' );			
		$suser_handler = &xoops_getmodulehandler( 'spiders_user', 'spiders' );	
		$member_handler = &xoops_gethandler( 'member' );
		
		$modulehandler =& xoops_gethandler('module');
		$confighandler =& xoops_gethandler('config');
		$xoModule = $modulehandler->getByDirname('spiders');
		$xoConfig = $confighandler->getConfigList($xoModule->getVar('mid'),false);
		
		$spiders = $spider_handler->getObjects(NULL);
		
		foreach($spiders as $spider) {
			if (strtolower($spider->getVar('robot-id'))==strtolower($apispider['robot-id'])) {
				$id = $spider->getVar('id');
				$thespider = $spider;
			}
		}
		
		if ($id==0) {
			$part = $spider_handler->safeAgent($apispider['robot-useragent']);
			foreach(array(';','/',',','/','(',')',' ') as $split) {
				$ret= array();
				foreach(explode($split, $part) as $value) {
					$ret[] = $value;
				}
				$part = implode(' ',$ret);
			}
			$criteria = new CriteriaCompo();
			
			foreach($ret as $value) 
				if (!is_numeric((substr($value,0,1)))&&(substr($value,0,1))!='x')
					if (!empty($value)) {
						$criteria->add(new Criteria('`robot-safeuseragent`', '%'.$value.'%', 'LIKE'), 'OR');
						$uagereg[] = strtolower($value);
						$uageregb[] = $value;
					}
		
			$id = 0;
			$spiders = $spider_handler->getObjects($criteria, true);
		
			foreach($spiders as $spider) {
				
				$suser = $suser_handler->get($spider->getVar('id'));
				$robot = $member_handler->getUser( $suser->getVar('uid') );
			
				$part = $spider_handler->safeAgent($spider->getVar('robot-useragent'));
				foreach(array(';','/',',','\\','(',')',' ') as $split) {
					$usersafeagent = array();
					foreach(explode($split, $part) as $value) {
						$usersafeagent[] = $value;
					}
					$part = implode(' ',$usersafeagent);
				}
				$usersafeagent = explode(' ', $part);
				$match=0;
				$dos_crsafe = array();
					
				foreach($uagereg as $uaid => $ireg) {		
					if((in_array($ireg, $usersafeagent)||strpos(strtolower(' '.$part), strtolower($ireg)))&&!is_object($GLOBALS['xoopsUser'])) {
						$match++;			
						$dos_crsafe[] = $uageregb[$uaid];
					}
				}		
		
				if (intval($match/count($uagereg)*100)>intval($xoConfig['match_percentile'])) {
					$id = $spider->getVar('id');
					$thespider = $spider;
				}
			}
		}
		
		$newmod = $spidermods_handler->create();
				
		foreach($apispider as $key => $value){
			if ($id<>0) {
				if (md5($value)!=md5($thespider->getVar($key))&&strlen($value)<>strlen($thespider->getVar($key))) {
					$change++;
					$newmod->setVar($key, $value);							
				} else {
					$newmod->setVar($key, $thespider->getVar($key));							
				}
			} else {
				$change++;
				$newmod->setVar($key, $value);							
			}
		}
		
		$newmod->setVar('id', $id);
	
		/*if (strpos(strtolower($_SERVER['HTTP_HOST']), 'xortify.com')) {
			define('XORTIFY_API_URI', 'http://xortify.chronolabs.coop/soap/');
		} else {
			define('XORTIFY_API_URI', 'http://xortify.com/soap/');
		}
		
		define('_MI_SERVER_USER_AGENT', 'Mozilla/5.0 (X11; U; Linux i686; pl-PL; rv:1.9.0.2) XOOPS/20100101 XoopsAuth/1.xx (php)');
		
		if (!$ch = curl_init(str_replace('soap', 'ban', XORTIFY_API_URI))) {
			trigger_error('Could not intialise CURLSERIAL file: '.XORTIFY_API_URI);
			return array("mod_made" => $spidermods_handler->insert($newmod, true), "made" => time());
		}
		$cookies = XOOPS_VAR_PATH.'/cache/xoops_cache/authcurl_'.md5(XORTIFY_API_URI).'.cookie'; 
	
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_USERAGENT, _MI_SERVER_USER_AGENT); 
		
		$data = curl_exec($ch);
		curl_close($ch);
		
		if (strpos(strtolower($data), 'solve puzzel')>0) {
			$sc = new soapclient(NULL, array('location' => XORTIFY_API_URI, 'uri' => XORTIFY_API_URI));
			$result = $sc->__soapCall('rep_spider',
		 				array(      "username"	=> 	$username, 
									"password"	=> 	$password, 
									"spider"	=> 	$apispider 
							));
		}*/
							
		return array("mod_made" => $spidermods_handler->insert($newmod, true), "made" => time());
	
	}
}
?>