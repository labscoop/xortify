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
xoops_loadLanguage('server', 'global');


/*	Creates a ban on Stop Forum Spam
 *  ban_posttosfs($data)
 *
 *  @subpackage functions
 *
 *  @param string $data 		Data Request HTTP Query Data
 */
function ban_posttosfs($data) {
	$fp = fsockopen("www.stopforumspam.com",80);
	fputs($fp, "POST /add.php HTTP/1.1\n" );
	fputs($fp, "Host: www.stopforumspam.com\n" );
	fputs($fp, "Content-type: application/x-www-form-urlencoded\n" );
	fputs($fp, "Content-length: ".strlen($data)."\n" );
	fputs($fp, "Connection: close\n\n" );
	fputs($fp, $data);
	fclose($fp);
}


/*	Creates a ban
 *  ban($username, $password, $bans, $comments)
 *
 *  @subpackage plugin
 *
 *  @param string $username 	Xortify username for function
 *  @param string $password 	Xortify password or md5 hash of password for function
 *  @param array $bans 			Associative Array of Bans [0=>{ip4, ip6, proxy-ip4, proxy-ip6, long, network-addy, uname, email}]
 *  @param array $comments	 	Associative Array of Comments to go on all passing ban in array group [0=>{comment}, 1=>{comment}]
 *  @return array
 */
function ban($username, $password, $bans, $comments) {
	global $xoopsModuleConfig, $xoopsDB;
	
	
	if ($xoopsModuleConfig['site_user_auth']==1){
		if ($ret = check_for_lock(basename(__FILE__),$username,$password)) { return $ret; }
		if (!checkright(basename(__FILE__),$username,$password)) {
			mark_for_lock(basename(__FILE__),$username,$password);
			return array('ErrNum'=> 9, "ErrDesc" => 'No Permission for plug-in');
		}
	}
	
	$suid = user_uid($username, $password);
	
	$banmemberHandler =& xoops_getmodulehandler('members', 'ban');
	$comment_handler = & xoops_gethandler('comment');
	$module_handler = & xoops_gethandler('module');	
	
	$xoModule = $module_handler->getByDirname('ban');
	
	$error=array();
	
	foreach ($bans as $key => $ban){	
		if ($ban['network-addy']=='localhost'  ||
			$ban['ip4']=='127.0.0.1' ||
			strpos($ban['ip6'], '127.0.0.1'))
				$error[] = 'localhost cannot be specified in ban - '.$key;

		if ( !(intval($ban['made'])>time()-(48*60*60)  &&
			 intval($ban['made'])<time()+(48*60*60)) ) 
			 	$error[] = 'ban must be made within '.(48*60*60).' seconds ahead or behind of made server timestamp in ban - '.$key;

		$criteria = new CriteriaCompo();
						
		foreach($ban as $field => $value) {
			if ($field!='email'&&preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$/i", $value)) 
			 	$error[] = 'no email specified allowed for field - '.$field.' - in ban - '.$key;
				
			if (strpos(' '.$value, '*'))
				$error[] = 'wildcard * cannot be specified for field - '.$field.' - in ban - '.$key;

		}
		
		$criteriaa = new CriteriaCompo(new Criteria('`ip4`', $ban['ip4']));
    	$criteriaa->add(new Criteria('`proxy-ip4`', $ban['proxy-ip4']), 'AND');
    	$criteriab = new CriteriaCompo(new Criteria('`ip6`', $ban['ip6']));
    	$criteriab->add(new Criteria('`proxy-ip6`', $ban['proxy-ip6']), 'AND');
    	$criteriac = new CriteriaCompo(new Criteria('`long`', $ban['long']));
    	$criteriac->add(new Criteria('`network-addy`', $ban['network-addy']), 'OR');
    	$criteria = new CriteriaCompo($criteriaa, 'OR');
    	$criteria->add($criteriab, 'OR');
    	$criteria->add($criteriac, 'OR');
	
		if ($banmemberHandler->getCount($criteria)>0) 
			$error[] = 'Ban already exists for record - '.$criteria->renderWhere();
	}

	if (count($error)>0) 
		return array("errors" => $error, "made" => time());		
	
	foreach ($bans as $key => $ban){
				
		$banning = $banmemberHandler->create();

		$banning->setVars($ban, true);
		
		$banning->setVar('suid', $suid);
		$user_handler = xoops_gethandler('user');
		$criteria = new CriteriaCompo(new Criteria('uname', $banning->getVar('uname')));
		$criteria->add(new Criteria('email', $banning->getVar('email')));
		if ($user_handler->getCount($criteria)>0||$banning->getVar('category_id')==1) {
			$banning->setVar('uid', 0);
			$banning->setVar('email', '');
			$banning->setVar('uname', '');
		}
									
		if ($itemid = $banmemberHandler->insert($banning, true)) {
			$ii++;
			if (isset($comments['comment'])) {
				$sql = "INSERT INTO ".$xoopsDB->prefix('xoopscomments'). ' (com_created, com_pid, com_itemid, com_rootid, com_ip, com_title, com_text,  dohtml, dosmiley, doxcode, doimage, dobr, com_icon, com_modid, com_status, com_uid) VALUES("'.time().'", "0", "'.$itemid.'","0","'.$_SERVER['REMOTE_ADDR'].'","Banning Comment :: '.$comments['uname'].' :: '.date('d-M-Y H:i:s').'","'.str_replace('\n', '<br/>', htmlspecialchars($comments['comment'])).'",1,1,1,1,1,1,"'.$xoModule->getVar('mid').'", 2, "'.($suid>0?$suid:0).'")';
				$xoopsDB->queryF($sql);
				$sql = "UPDATE ".$xoopsDB->prefix('users'). 'SET `posts` = `posts` + 1 WHERE uid = '.$suid;
				$xoopsDB->queryF($sql);
					
			} elseif (is_array($comments)) {
				foreach($comments as $cmid => $commentor) {
	
					$sql = "INSERT INTO ".$xoopsDB->prefix('xoopscomments'). ' (com_created, com_pid, com_itemid, com_rootid, com_ip, com_title, com_text,  dohtml, dosmiley, doxcode, doimage, dobr, com_icon, com_modid, com_status, com_uid) VALUES("'.time().'", "0", "'.$itemid.'","0","'.$_SERVER['REMOTE_ADDR'].'","Banning Comment :: '.$commentor['uname'].' :: '.date('d-M-Y H:i:s').'","'.str_replace('\n', '<br/>', htmlspecialchars($commentor['comment'])).'",1,1,1,1,1,1,"'.$xoModule->getVar('mid').'", 2, "'.($suid>0?$suid:0).'")';
					$xoopsDB->queryF($sql);
					$sql = "UPDATE ".$xoopsDB->prefix('users'). 'SET `posts` = `posts` + 1 WHERE uid = '.$suid;
					$xoopsDB->queryF($sql);
				}
			}
			if (!isset($_REQUEST['bounce'])&&intval($_REQUEST['level'])<1) {
				ban_posttosfs("username=".$banning->getVar('uname')."&ip_addr=".$banning->ipaddy()."&email=".$banning->getVar('email')."&api_key="._MI_SERVER_STOPFORUMSPAM_KEY);
			}
			if (!isset($_REQUEST['bounce'])&&intval($GLOBALS['ban_level'])<1) {
				xoops_load('cache');
				XoopsCache::write('ban_bounce_'.$itemid, array('ip'=>$ban['ip4'].$ban['ip6'], 'category_id'=>$ban['category_id'], 'comment'=>$comments[sizeof($comments)-1], 'level'=>isset($GLOBALS['ban_level'])?intval($GLOBALS['ban_level']):0), 3600*2*7*4*6);
			}
					
		}
	}
	return array("bans" => intval($ii), "made" => time());
}

?>