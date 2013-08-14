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

if (!function_exists('spamcheck')) {
	
	/*	Xortify Checks for content being spam by current training
	 *  spamcheck($username, $password, $puri = '', $content = '', $adult = false, $uname = 'anomynous', $name = 'Guest Anomynous', $email = 'spam@xortify.com', $ip = '127.0.0.1', $sessid = '', $scores = false)
	 *
	 *  @subpackage plugin
	 *
	 *  @param string $username 	Xortify username for function
	 *  @param string $password 	Xortify password or md5 hash of password for function
	 *  @param string $puri 		URI/URI of Place content was collected
	 *  @param string $content	 	Content for SPAM Checking
	 *  @param boolean $adult 		Support Adult Keywords
	 *  @param string $uname 		Username of clients user host posting content
	 *  @param string $name 		Name of clients user host posting content
	 *  @param string $email 		EMail of clients user host posting content
	 *  @param string $ip 			IP Address of clients user host posting content
	 *  @param string $sessid 		Session ID of clients user host posting content
	 *  @param boolean $scores 		if function returns scores or just boolean yes/no for spam or ham
	 *  @return array
	 */
	function spamcheck($username, $password, $puri = '', $content = '', $adult = false, $uname = 'anomynous', $name = 'Guest Anomynous', $email = 'spam@xortify.com', $ip = '127.0.0.1', $sessid = '', $scores = false) {
		
		if ($xoopsModuleConfig['site_user_auth']==1){
			if ($ret = check_for_lock(basename(__FILE__),$username,$password)) { return $ret; }
			if (!checkright(basename(__FILE__),$username,$password)) {
				mark_for_lock(basename(__FILE__),$username,$password);
				return array('ErrNum'=> 9, "ErrDesc" => 'No Permission for plug-in');
			}
		}
		
		
		$header = "Content-type: text/html; charset=utf-8";		
		$header .= "\nMIME-version: 1.0";
		$header .= "\nContent-transfer-encoding: quoted-printable";
		$header .= "\nSubject: ".$puri;
		$header .= "\nFrom: $uname"."@".$_SERVER['REMOTE_ADDR'];
		$header .= "\nTo: $uname"."@".$_SERVER['HTTP_HOST'];
		$header .= "\nDate: ".date('D, d M Y H:i:s +1000');
		$header .= "\n\n";		

		$exe = 'd:\== SERVER ==\@automation@\SpamAssassin\spamc -c --retry-sleep=2 ';
		if ($scores!=false) {
			$exe .= '-r ';
		}
		exec($exe . '< ' . $header . $content, $report, $code);
		$out = array('spam' => (intval($code)==0?false:true));
		if ($scores!=false) {
			$out['report'] = $report;
		}
		return $out;
	}
}
?>