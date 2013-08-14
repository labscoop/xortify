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
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'translator.php';

if (!function_exists('spoofcomment')) {

	/*	Xortify Spoof Comment Form generation
	 *  spoofcomment($username, $password, $uri, $ip, $language = 'english')
	 *
	 *  @subpackage plugin
	 *
	 *  @param string $username 	Xortify username for function
	 *  @param string $password 	Xortify password or md5 hash of password for function
	 *  @param string $uri 			URL of where Spoof Thread form was presented
	 *  @param string $ip 			IP address of clients Host
	 *  @param string $language 	Language the form is in
	 *  @return array
	 */
	function spoofcomment($username, $password, $uri, $ip, $language = 'english') {
		global $xoopsModuleConfig, $xoopsDB;
	
		if ($xoopsModuleConfig['site_user_auth']==1){
			if ($ret = check_for_lock(basename(__FILE__),$username,$password)) { return $ret; }
			if (!checkright(basename(__FILE__),$username,$password)) {
				mark_for_lock(basename(__FILE__),$username,$password);
				return array('ErrNum'=> 9, "ErrDesc" => 'No Permission for plug-in');
			}
		}
	
		xoops_loadLanguage('spoof');
		xoops_load('XoopsCache');
		
		if (!$lang = XoopsCache('xortify_spoofcomment_languages')) {
			$lang = array();
			$lang['english']['commentform'] = _SPOOF_CM_POSTCOMMENT;
			$lang['english']['rules'] = _SPOOF_CM_COMRULES;
			$lang['english']['rule_text'] = _SPOOF_CM_COMBANALL;
			$lang['english']['title'] = _SPOOF_CM_TITLE;
			$lang['english']['email'] = _SPOOF_CM_EMAIL;
			$lang['english']['uname'] = _SPOOF_CM_UNAME;
			$lang['english']['msgicon'] = _MESSAGEICON;
			$lang['english']['message'] = _SPOOF_CM_MESSAGE;
			$lang['english']['post'] = _SPOOF_CM_POSTCOMMENT;
		}
		
		if (!isset($lang[$language])||!is_array($lang[$language])) {
			$translator = new GooglosityTranslator('');
			$GLOBAL['translation'] = false;
			foreach(GooglosityTranslator::_languages as $iso => $folder) {
				if (strtolower($folder) == strtolower($language) || strtolower($iso) == strtolower($language)) {
					foreach($lang['english'] as $key => $value) {
						$lang[$language][$key] = $translator->translate($value, 'en', $iso);
					}
					continue;
				}
						
			}
				
			if ($GLOBAL['translation'] == false) {
				$translator = new BingTranslator();
				foreach(BingTranslator::_languages as $iso => $folder) {
					if (strtolower($folder) == strtolower($language) || strtolower($iso) == strtolower($language)) {
						foreach($lang['english'] as $key => $value) {
							$lang[$language][$key] = $translator->translate($value, 'en', $iso);
						}
					}
					continue;
				}
			}
		}
		
		XoopsCache::write('xortify_spoofcomment_languages', $lang, 60*60*24*7*4*12);
		
		xoops_load('XoopsLists');
		xoops_load('XoopsFormLoader');
		
		$i = 0;
		$form = array();
		$form[++$i] = new XoopsFormLabel($lang[$language]['rules'], $lang[$language]['rule_text']);
		$form[++$i] = new XoopsFormText($lang[$language]['title'], 'title', 50, 255, $com_title);
		$form[++$i] = new XoopsFormText($lang[$language]['email'], 'email', 50, 255, $com_title);
		$form[++$i] = new XoopsFormText($lang[$language]['uname'], 'uname', 15, 35, $com_uname);
		$form[++$i] = new XoopsFormRadio($lang[$language]['msgicon'], 'icon', $com_icon);
		$subject_icons = XoopsLists::getSubjectsList();
		foreach ($subject_icons as $iconfile) {
		    $form[$i]->addOption($iconfile, '<img src="' . XOOPS_URL . '/images/subject/' . $iconfile . '" alt="" />');
		}
		// editor
		$editor = xoops_getModuleOption('comments_editor', 'system' );
		if ( class_exists( 'XoopsFormEditor' ) ) {
		    $configs=array(
		        'name'   => 'com_text',
		        'value'  => $com_text,
		        'rows'   => 25,
		        'cols'   => 90,
		        'width'  => '100%',
		        'height' => '400px',
		        'editor' => $editor
		    );
		    $form[++$i] = new XoopsFormEditor($lang[$language]['message'], 'text', $configs, false, $onfailure = 'textarea' );
		} else {
		    $form[++$i] = new XoopsFormDhtmlTextArea($lang[$language]['message'], 'text', $com_text, 10, 50);
		}
		$form[++$i] = new XoopsFormHidden('ip', $ip);
		$form[++$i] = new XoopsFormHidden('op', 'recieve');
		$form[++$i] = new XoopsFormHidden('fct', 'comment');
		$form[++$i] = new XoopsFormHidden('uri', $uri);
		$form[++$i] =  new XoopsFormButton('', 'dopost', $lang[$language]['post'], 'submit');
		
		$cform = new XoopsThemeForm($lang[$language]['commentform'], "commentform", XOOPS_URL.'/spoof/?op=recieve&fct=comment', 'post', true);
		$elements = array();
		foreach($form as $id => $element) {
			$cform->addElement($element, true);
			$elements[$id] = $element->render();
		}
		return array('form'=>$cform->render(), 'elements' => $elements, 'action' => XOOPS_URL.'/spoof/?op=recieve&fct=comment', 'method' => 'post', 'css' => array(XOOPS_URL . '/xoops.css', XOOPS_URL . '/spoof/comments.css'));
	}
}
?>