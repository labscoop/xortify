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


/*	Checks for a Xortify Cloud Ban
 *  banned($username, $password, $ipdata)
 *
 *  @subpackage plugin
 *
 *  @param string $username 	Xortify username for function
 *  @param string $password 	Xortify password or md5 hash of password for function
 *  @param array $ipdata		Associative Array of Bans [0=>{ip4, ip6, proxy-ip4, proxy-ip6, long, network-addy, uname, email}]
 *  @return array
 */
function banned($username, $password, $ipdata) {

	global $xoopsModuleConfig, $xoopsDB;

	if ($xoopsModuleConfig['site_user_auth']==1){
		if ($ret = check_for_lock(basename(__FILE__),$username,$password)) { return $ret; }
		if (!checkright(basename(__FILE__),$username,$password)) {
			mark_for_lock(basename(__FILE__),$username,$password);
			return array('ErrNum'=> 9, "ErrDesc" => 'No Permission for plug-in');
		}
	}
		
	$bannedmemberHandler =& xoops_getmodulehandler('members', 'ban');
		
	$criteriaa = new CriteriaCompo(new Criteria('`ip4`', $ipdata['ip4']));
    $criteriaa->add(new Criteria('`proxy-ip4`', $ipdata['proxy-ip4']), 'AND');
    $criteriab = new CriteriaCompo(new Criteria('`ip6`', $ipdata['ip6']));
    $criteriab->add(new Criteria('`proxy-ip6`', $ipdata['proxy-ip6']), 'AND');
    $criteriac = new CriteriaCompo(new Criteria('`long`', $ipdata['long']));
    $criteriac->add(new Criteria('`network-addy`', $ipdata['network-addy']), 'OR');
    $criteriad = new CriteriaCompo(new Criteria('`email`', $ipdata['email']));
    $criteriad->add(new Criteria('`username`', $ipdata['username']), 'OR');
    	
    $criteria = new CriteriaCompo($criteriaa, 'OR');
    $criteria->add($criteriab, 'OR');
    $criteria->add($criteriac, 'OR');
    $criteria->add($criteriad, 'OR');
	
	if ($count = $bannedmemberHandler->getCount($criteria)) {
		foreach($bannedmemberHandler->getObjects($criteria, true) as $member_id => $ban)
			$ret[$member_id] = $ban->toArray();
		return array("banned" => true, 'count' => $count, "made" => time(), 'bans' => $ret);
	}
		

	return array("banned" => false, 'count' => $count, "made" => time());
	
}

?>