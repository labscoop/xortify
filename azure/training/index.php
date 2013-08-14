<?php
/**
 * Xortify Bans & Unbans Function
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
 * @package         spam
 * @subpackage		training
 * @author         	Simon Roberts (wishcraft) - meshy@labs.coop
 */

	include_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'mainfile.php';
	include_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'xortify' . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'functions.php';
	include_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'cloud' . DIRECTORY_SEPARATOR . 'ban.php';
	
	$op = (isset($_REQUEST['op'])?(string)$_REQUEST['op']:'default');
	$ip = xortify_getIPData(trim(isset($_REQUEST['ip'])?(string)$_REQUEST['ip']:xortify_getIP()));

	switch ($op) {
		case "spam":
			if (isset($_REQUEST['text'])&&!empty($_REQUEST['text'])) {
				$filename = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'spoof' . DIRECTORY_SEPARATOR . 'spam' . DIRECTORY_SEPARATOR . md5($_REQUEST['text']) . '.txt';
				$file = fopen($filename, 'w');
				fwrite($file, $_REQUEST['text'], strlen($_REQUEST['text']));
				fclose($file);    
			}
			break;
		case "ham":
			if (isset($_REQUEST['text'])&&!empty($_REQUEST['text'])) {
				$filename = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'spoof' . DIRECTORY_SEPARATOR . 'ham' . DIRECTORY_SEPARATOR . md5($_REQUEST['text']) . '.txt';
				$file = fopen($filename, 'w');
				fwrite($file, $_REQUEST['text'], strlen($_REQUEST['text']));
				fclose($file);
			}
			break;					
	}	
?>