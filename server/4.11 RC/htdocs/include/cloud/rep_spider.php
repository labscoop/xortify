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
if (!function_exists('rep_spider')) {

	/*	Reparses Xortify Saves a Spider on the Manifest of Crawlers/Bots
	 *  rep_spider($username, $password, $apirep_spider)
	 *
	 *  @subpackage plugin
	 *
	 *  @param string $username 	Xortify username for function
	 *  @param string $password 	Xortify password or md5 hash of password for function
	 *  @param array $apispider 	Spider/Robot Array [robot-name, robot-cover-url, robot-details-url, robot-owner-name, robot-owner-url, robot-owner-email, robot-status, robot-purpose, robot-type, robot-platform, robot-availability, robot-exclusion, robot-exclusion-useragent, robot-noindex, robot-host, robot-from, robot-useragent, robot-language, robot-description, robot-history, robot-environment, modified-date, modified-by, robot-safeuseragent, robot-handlesession]
	 *  @return array
	 */
	function rep_spider($username, $password, $apirep_spider) {
		global $xoopsModuleConfig, $xoopsDB;
		$id=0;
		
		if ($xoopsModuleConfig['site_user_auth']==1){
			if ($ret = check_for_lock(basename(__FILE__),$username,$password)) { return $ret; }
			if (!checkright(basename(__FILE__),$username,$password)) {
				mark_for_lock(basename(__FILE__),$username,$password);
				return array('ErrNum'=> 9, "ErrDesc" => 'No Permission for plug-in');
			}
		}
		
		$rep_spider_handler = &xoops_getmodulehandler( 'rep_spiders', 'rep_spiders' );	
		$rep_spidermods_handler = &xoops_getmodulehandler( 'modifications', 'rep_spiders' );			
		$suser_handler = &xoops_getmodulehandler( 'rep_spiders_user', 'rep_spiders' );	
		$member_handler = &xoops_gethandler( 'member' );
		
		$modulehandler =& xoops_gethandler('module');
		$confighandler =& xoops_gethandler('config');
		$xoModule = $modulehandler->getByDirname('rep_spiders');
		$xoConfig = $confighandler->getConfigList($xoModule->getVar('mid'),false);
		
		$rep_spiders = $rep_spider_handler->getObjects(NULL);
		
		foreach($rep_spiders as $rep_spider) {
			if (strtolower($rep_spider->getVar('robot-id'))==strtolower($apirep_spider['robot-id'])) {
				$id = $rep_spider->getVar('id');
				$therep_spider = $rep_spider;
			}
		}
		
		if ($id==0) {
			$part = $rep_spider_handler->safeAgent($apirep_spider['robot-useragent']);
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
			$rep_spiders = $rep_spider_handler->getObjects($criteria, true);
		
			foreach($rep_spiders as $rep_spider) {
				
				$suser = $suser_handler->get($rep_spider->getVar('id'));
				$robot = $member_handler->getUser( $suser->getVar('uid') );
			
				$part = $rep_spider_handler->safeAgent($rep_spider->getVar('robot-useragent'));
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
					$id = $rep_spider->getVar('id');
					$therep_spider = $rep_spider;
				}
			}
		}
		
		$newmod = $rep_spidermods_handler->create();
				
		foreach($apirep_spider as $key => $value){
			if ($id<>0) {
				if (md5($value)!=md5($therep_spider->getVar($key))&&strlen($value)<>strlen($therep_spider->getVar($key))) {
					$change++;
					$newmod->setVar($key, $value);							
				} else {
					$newmod->setVar($key, $therep_spider->getVar($key));							
				}
			} else {
				$change++;
				$newmod->setVar($key, $value);							
			}
		}
		
		$newmod->setVar('id', $id);
					
		return array("mod_made" => $rep_spidermods_handler->insert($newmod, true), "made" => time());
	
	}
}
?>