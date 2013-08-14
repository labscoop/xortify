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
if (!function_exists('server')) {
	
	/*	Provide Push of Server Lists
	 *  server($username, $password, $server)
	 *
	 *  @subpackage plugin
	 *
	 *  @param string $username 	Xortify username for function
	 *  @param string $password 	Xortify password or md5 hash of password for function
	 *  @param array $server 		Registers a Xortify Service on the primary cloud [server, replace, search]
	 *  @return array
	 */
	function server($username, $password, $server) {
		global $xoopsModuleConfig, $xoopsDB;
	
		if ($xoopsModuleConfig['site_user_auth']==1){
			if ($ret = check_for_lock(basename(__FILE__),$username,$password)) { return $ret; }
			if (!checkright(basename(__FILE__),$username,$password)) {
				mark_for_lock(basename(__FILE__),$username,$password);
				return array('ErrNum'=> 9, "ErrDesc" => 'No Permission for plug-in');
			}
		}
	
		$module_handler =& xoops_gethandler('module');
		$config_handler =& xoops_gethandler('config');
		if (!isset($GLOBALS['xortifyModule'])) $GLOBALS['xortifyModule'] = $module_handler->getByDirname('xortify');
		if (!isset($GLOBALS['xortifyModuleConfig'])) $GLOBALS['xortifyModuleConfig'] = $config_handler->getConfigList($GLOBALS['xortifyModule']->mid());
		
		
		$servers_handler = xoops_getmodulehandler('servers', 'xortify');
		$user_handler = xoops_gethandler('user');
		
		$srv = parse_url($server['server']);
		
		$criteriaa = new CriteriaCompo(new Criteria('`server`', '%/'.$srv['host'].'/%', 'LIKE'), 'OR');
		$criteriaa->add(new Criteria('`replace`',$server['replace']), 'OR');
		$criteriaa->add(new Criteria('`search`',$server['search']), 'OR');
		$criteriab = new CriteriaCompo(new Criteria('`user`',$username), 'AND');
		$criteriab->add(new Criteria('`pass`',$password), 'AND');
		$criteria = new CriteriaCompo($criteriaa, 'AND');
		$criteria->add($criteriab, 'OR');
		$criteria->setSort('`polled`');
		
		if ($servers_handler->getCount($criteria)>0) {
			$servers = $servers_handler->getObjects($criteria, false);
			$servers[0]->setVar('polled', time());
			$servers[0]->setVar('online', true);
			$servers[0]->setVar('server', $server['server']);
			$servers[0]->setVar('replace', $server['replace']);
			$servers[0]->setVar('search', $server['search']);
			$servers_handler->insert($servers[0], true);
			return array('crc' => md5($servers[0]->getVar('sid').$username.$password));
		}
			
	}
}
?>