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
include_once dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'xortify' . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'functions.php';
include_once dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'cloud' . DIRECTORY_SEPARATOR . 'ban.php';

if (!function_exists('training')) {

	/*	Xortify Training for Spam/Ham Detection
	 *  training($username, $password, $op, $text)
	 *
	 *  @subpackage plugin
	 *
	 *  @param string $username 	Xortify username for function
	 *  @param string $password 	Xortify password or md5 hash of password for function
	 *  @param string $op 			Whether Spam or Ham is being produced for training Spam Assassin
	 *  @param string $text 		Text/Content Etc for Spam/Ham Training
	 *  @return array
	 */
	function training($username, $password, $op, $text) {
		global $xoopsModuleConfig, $xoopsDB;
	
		if ($xoopsModuleConfig['site_user_auth']==1){
			if ($ret = check_for_lock(basename(__FILE__),$username,$password)) { return $ret; }
			if (!checkright(basename(__FILE__),$username,$password)) {
				mark_for_lock(basename(__FILE__),$username,$password);
				return array('ErrNum'=> 9, "ErrDesc" => 'No Permission for plug-in');
			}
		}
		
		$op = (isset($_REQUEST['op'])?(string)$_REQUEST['op']:'default');
		$ip = xortify_getIPData(trim(isset($_REQUEST['ip'])?(string)$_REQUEST['ip']:xortify_getIP()));
	
		switch ($op) {
			case "spam":
				if (isset($text)&&!empty($text)) {
					$filename = dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'spoof' . DIRECTORY_SEPARATOR . 'spam' . DIRECTORY_SEPARATOR . md5($text) . '.txt';
					$file = fopen($filename, 'w');
					fwrite($file, $text, strlen($text));
					fclose($file);    
				}
				break;
			case "ham":
				if (isset($text)&&!empty($text)) {
					$filename = dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'spoof' . DIRECTORY_SEPARATOR . 'ham' . DIRECTORY_SEPARATOR . md5($text) . '.txt';
					$file = fopen($filename, 'w');
					fwrite($file, $text, strlen($text));
					fclose($file);
				}
				break;					
		}	
			
	}
}
?>