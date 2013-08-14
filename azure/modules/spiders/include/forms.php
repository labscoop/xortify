<?php

// $Author: wishcraft $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: Simon Roberts (AKA wishcraft)                                     //
// URL: http://www.chronolabs.org.au                                         //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //

	include ('../class/auth/authfactory.php');
	include_once ('functions.php');
	
	function XortifySignupForm()
	{
		
		include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
		
		$form = new XoopsThemeForm(_AM_SPIDERS_FRM_TITLE, "xortify_member", "", "post");
		
		$form->addElement(new XoopsFormText(_AM_SPIDERS_FRM_UNAME, "uname", 35, 128, $_REQUEST['uname']));					
		$form->addElement(new XoopsFormPassword(_AM_SPIDERS_FRM_PASS, "pass", 35, 128, $_REQUEST['pass']), false);					
		$form->addElement(new XoopsFormPassword(_AM_SPIDERS_FRM_VPASS, "vpass", 35, 128, $_REQUEST['vpass']), false);					
		$form->addElement(new XoopsFormText(_AM_SPIDERS_FRM_EMAIL, "email", 35, 128, $_REQUEST['email']));											
		$form->addElement(new XoopsFormText(_AM_SPIDERS_FRM_URL, "url", 35, 128, $_REQUEST['url']));											
		$form->addElement(new XoopsFormRadioYN(_AM_SPIDERS_FRM_VIEWEMAIL, "viewemail", $_REQUEST['viewemail']));
		$form->addElement(new XoopsFormSelectTimezone(_AM_SPIDERS_FRM_TIMEZONE, "timezone", $_REQUEST['timezone']));
		$xortifyAuth =& XortifyAuthFactory::getAuthConnection(false, apimethod());
		$myts =& MyTextSanitizer::getInstance();
		$disclaimer = $xortifyAuth->network_disclaimer();
		if (strlen(trim($disclaimer))==0)
			{
				$disclaimer = _AM_SPIDERS_FRM_NOSOAP_DISCLAIMER;
			}
		$form->addElement(new XoopsFormLabel(_AM_SPIDERS_FRM_DISCLAIMER, $myts->nl2br($disclaimer)));	
		$form->addElement(new XoopsFormRadioYN(_AM_SPIDERS_FRM_DISCLAIMER_AGREE, "agree", false));				
		$form->addElement(new XoopsFormCaptcha(_AM_SPIDERS_FRM_PUZZEL, 'xoopscaptcha', false), true);
		$form->addElement(new XoopsFormHidden('op', 'signup'));	
		$form->addElement(new XoopsFormHidden('fct', 'save'));
		if ($disclaimer != _AM_SPIDERS_FRM_NOSOAP_DISCLAIMER)
			$form->addElement(new XoopsFormButton('', 'submit', _AM_SPIDERS_FRM_REGISTER, 'submit'));	
		return $form->render();
	}
	
	function compair_spiders_form()
	{

		include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
		$form = new XoopsThemeForm(_AM_SPIDERS_COMPAIRAPI, "import", "", "post");

		$apiprotocol = new XoopsFormSelect(_AM_SPIDERS_APIPROT, 'api', apimethod());
		$apiprotocol->setDescription(_AM_SPIDERS_APIPROTDESC);
		
		$options['soap'] = _AM_SPIDERS_API_SOAP;
		$options['curl'] = _AM_SPIDERS_API_CURL;		
		$options['json'] = _AM_SPIDERS_API_JSON;
		
		$apiprotocol->addOptionArray($options);	
		$form->addElement($apiprotocol);
		$form->addElement(new XoopsFormHidden("op", 'compair-api'));
		$form->addElement(new XoopsFormButton('', 'contents_submit', _SUBMIT, "submit"));
		$form->display();
		
	}
	
	function import_spiders_form()
	{
		include(XOOPS_ROOT_PATH.'/class/xoopslists.php');
		$xl = new XoopsLists();
		$files = $xl->getFileListAsArray(XOOPS_ROOT_PATH.'/modules/'._MI_SPIDERS_DIRNAME.'/admin/resources/');

		include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
		$form = new XoopsThemeForm(_AM_SPIDERS_IMPORTFILE, "import", "", "post");

		$fileelement = new XoopsFormSelect(_AM_SPIDERS_FILE, 'file', $file);
		$fileelement->setDescription(_AM_SPIDERS_FILEDESC);
		
		foreach($files as $key => $file) {
			if (strpos($file,'.txt')>0)
				$options[$key] = $file;
		}
		
		$fileelement->addOptionArray($options);	
		$form->addElement($fileelement);
		$form->addElement(new XoopsFormHidden("op", 'import-file'));
		$form->addElement(new XoopsFormButton('', 'contents_submit', _SUBMIT, "submit"));
		$form->display();
		
	}
	
	function import_spiders_list()
	{
		include(XOOPS_ROOT_PATH.'/class/xoopslists.php');
		$xl = new XoopsLists();
		$files = $xl->getFileListAsArray(XOOPS_ROOT_PATH.'/modules/'._MI_SPIDERS_DIRNAME.'/admin/resources/');

		include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
		$form = new XoopsThemeForm(_AM_SPIDERS_LIST, "import", "", "post");

		$spiders_handler =& xoops_getmodulehandler('spiders', 'spiders');
		
		$spiders = $spiders_handler->getObjects(NULL, true);
		
		$formobjects=array();
		
		foreach ($spiders as $id => $spider) {
			$formobjects[$id] = new XoopsFormElementTray($spider->getVar('robot-name'));
			$formobjects[$id]->setDescription('<a href="'.$_SERVER['PHP_SELF'].'?op=edit&id='.$id.'">'._EDIT.'</a>&nbsp;|&nbsp;<a href="'.$_SERVER['PHP_SELF'].'?op=delete&id='.$id.'">'._DELETE.'</a>&nbsp;|&nbsp;<a href="'.$_SERVER['PHP_SELF'].'?op=send&id='.$id.'">'._SEND.'</a>');
			$formobjects[$id]->addElement(new XoopsFormText(_AM_SPIDERS_TXTBOX_EXCLUSION, 'robot-exclusion-useragent['.$id.']', 50, 255, $spider->getVar('robot-exclusion-useragent')));
			$formobjects[$id]->addElement(new XoopsFormText(_AM_SPIDERS_TXTBOX_USERAGENT, 'robot-useragent['.$id.']', 50, 255, $spider->getVar('robot-useragent')));		
			$formobjects[$id]->addElement(new XoopsFormHidden('id['.$id.']', $id));
		}
		
		foreach($formobjects as $id =>$formobject)
			$form->addElement($formobjects[$id]);
	
		$form->addElement($fileelement);
		$form->addElement(new XoopsFormHidden("op", 'savelist'));
		$form->addElement(new XoopsFormButton('', 'save_list', _SUBMIT, "submit"));
		$form->display();
		
	}

	function import_spidersmods_list()
	{

		include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
		$form = new XoopsThemeForm(_AM_SPIDERS_LISTMODS, "mods", "", "post");

		$spidersmods_handler =& xoops_getmodulehandler('modifications', 'spiders');
		
		$spidersmods = $spidersmods_handler->getObjects(NULL, true);
		
		$formobjects=array();
		
		foreach ($spidersmods as $id => $spider) {
			$formobjects[$id] = new XoopsFormElementTray($spider->getVar('robot-name'));
			$formobjects[$id]->setDescription('<a href="'.$_SERVER['PHP_SELF'].'?op=mergemod&id='.$id.'">'._MERGE.'</a>&nbsp;|&nbsp;<a href="'.$_SERVER['PHP_SELF'].'?op=deletemod&modid='.$id.'">'._DELETE.'</a>');
			$formobjects[$id]->addElement(new XoopsFormText(_AM_SPIDERS_TXTBOX_EXCLUSION, 'robot-exclusion-useragent['.$id.']', 50, 255, $spider->getVar('robot-exclusion-useragent')));
			$formobjects[$id]->addElement(new XoopsFormText(_AM_SPIDERS_TXTBOX_USERAGENT, 'robot-useragent['.$id.']', 50, 255, $spider->getVar('robot-useragent')));		
			$formobjects[$id]->addElement(new XoopsFormHidden('modid['.$id.']', $id));
		}
		
		foreach($formobjects as $id =>$formobject)
			$form->addElement($formobjects[$id]);
	
		$form->addElement($fileelement);
		$form->display();
		
	}

	function import_spiders_edit($id)
	{
		include(XOOPS_ROOT_PATH.'/class/xoopslists.php');
		$xl = new XoopsLists();
		$files = $xl->getFileListAsArray(XOOPS_ROOT_PATH.'/modules/'._MI_SPIDERS_DIRNAME.'/admin/resources/');
		$spiders_handler =& xoops_getmodulehandler('spiders', 'spiders');
		include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";



		if ($id<>0)
			$spider = $spiders_handler->get($id, true);
		else
			$spider = $spiders_handler->create();


		if ($id<>0)
			$form = new XoopsThemeForm(sprintf(_AM_SPIDERS_EDITSPIDER, $spider->getVar('robot-name')), "import", "", "post");
		else
			$form = new XoopsThemeForm(_AM_SPIDERS_NEWSSPIDER, "import", "", "post");
		
		
		$formobjects['robot-id'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_ROBOTID, 'robot-id['.$id.']', 50, 128, $spider->getVar('robot-id'));
		$formobjects['robot-id']->setDescription(_AM_SPIDERS_TXTBOX_ROBOTID_DESC);
		if ($id<>0)
			$formobjects['robot-id']->setExtra('disabled="disabled"');
			
		$formobjects['robot-name'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_ROBOTNAME, 'robot-name['.$id.']', 50, 255, $spider->getVar('robot-name'));
		$formobjects['robot-name']->setDescription(_AM_SPIDERS_TXTBOX_ROBOTNAME_DESC);			
		$formobjects['robot-cover-url'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_COVERURL, 'robot-cover-url['.$id.']', 50, 255, $spider->getVar('robot-cover-url'));
		$formobjects['robot-cover-url']->setDescription(_AM_SPIDERS_TXTBOX_COVERURL_DESC);			
		$formobjects['robot-details-url'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_DETAILURL, 'robot-details-url['.$id.']', 50, 255, $spider->getVar('robot-details-url'));
		$formobjects['robot-details-url']->setDescription(_AM_SPIDERS_TXTBOX_DETAILURL_DESC);			
		$formobjects['robot-owner-name'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_OWNERNME, 'robot-owner-name['.$id.']', 50, 128, $spider->getVar('robot-owner-name'));
		$formobjects['robot-owner-name']->setDescription(_AM_SPIDERS_TXTBOX_OWNERNAME_DESC);			
		$formobjects['robot-owner-url'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_OWNERURL, 'robot-owner-url['.$id.']', 50, 255, $spider->getVar('robot-owner-url'));
		$formobjects['robot-owner-url']->setDescription(_AM_SPIDERS_TXTBOX_OWNERURL_DESC);			
		$formobjects['robot-owner-email'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_OWNEREMAIL, 'robot-owner-email['.$id.']', 50, 255, $spider->getVar('robot-owner-email'));
		$formobjects['robot-owner-email']->setDescription(_AM_SPIDERS_TXTBOX_OWNEREMAIL_DESC);
		$formobjects['robot-status'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_STATUS, 'robot-status['.$id.']', 50, 255, $spider->getVar('robot-status'));
		$formobjects['robot-status']->setDescription(_AM_SPIDERS_TXTBOX_STATUS_DESC);			
		$formobjects['robot-purpose'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_PURPOSE, 'robot-purpose['.$id.']', 50, 128, $spider->getVar('robot-purpose'));
		$formobjects['robot-purpose']->setDescription(_AM_SPIDERS_TXTBOX_PURPOSE_DESC);			
		
		$formobjects['robot-type'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_TYPE, 'robot-type['.$id.']', 40, 64, $spider->getVar('robot-type'));
		$formobjects['robot-type']->setDescription(_AM_SPIDERS_TXTBOX_TYPE_DESC);			
		$formobjects['robot-availability'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_AVAILABILITY, 'robot-availability['.$id.']', 50, 128, $spider->getVar('robot-availability'));
		$formobjects['robot-availability']->setDescription(_AM_SPIDERS_TXTBOX_AVAILABILITY_DESC);			
		$formobjects['robot-exclusion'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_EXCLUSION, 'robot-exclusion['.$id.']', 40, 64, $spider->getVar('robot-exclusion'));
		$formobjects['robot-exclusion']->setDescription(_AM_SPIDERS_TXTBOX_EXCLUSION_DESC);	
		$formobjects['robot-exclusion-useragent'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_EXCLUSIONUSERAGENT, 'robot-exclusion-useragent['.$id.']', 50, 255, $spider->getVar('robot-exclusion-useragent'));
		$formobjects['robot-exclusion-useragent']->setDescription(_AM_SPIDERS_TXTBOX_EXCLUSIONUSERAGENT_DESC);
		$formobjects['robot-noindex'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_NOINDEX, 'robot-noindex['.$id.']', 20, 32, $spider->getVar('robot-noindex'));
		$formobjects['robot-noindex']->setDescription(_AM_SPIDERS_TXTBOX_NOINDEX_DESC);			
		
		$formobjects['robot-host'] = new XoopsFormTextArea(_AM_SPIDERS_TXTBOX_HOST, 'robot-host['.$id.']', 40, 10, $spider->getVar('robot-host'));
		$formobjects['robot-host']->setDescription(_AM_SPIDERS_TXTBOX_HOST_DESC);			
		$formobjects['robot-from'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_FROM, 'robot-from['.$id.']', 20, 32, $spider->getVar('robot-from'));
		$formobjects['robot-from']->setDescription(_AM_SPIDERS_TXTBOX_FROM_DESC);			
		
		$formobjects['robot-useragent'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_USERAGENT, 'robot-useragent['.$id.']', 50, 255, $spider->getVar('robot-useragent'));
		$formobjects['robot-useragent']->setDescription(_AM_SPIDERS_TXTBOX_USERAGENT_DESC);			
		$formobjects['robot-language'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_LANGUAGE, 'robot-language['.$id.']', 50, 255, $spider->getVar('robot-language'));
		$formobjects['robot-language']->setDescription(_AM_SPIDERS_TXTBOX_LANGUAGE_DESC);			
		
		
		$formobjects['robot-description'] = new XoopsFormTextArea(_AM_SPIDERS_TXTBOX_DESCRIPTION, 'robot-description['.$id.']', 40, 10, $spider->getVar('robot-description'));
		$formobjects['robot-description']->setDescription(_AM_SPIDERS_TXTBOX_DESCRIPTION_DESC);

		$formobjects['robot-history'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_HISTORY, 'robot-history['.$id.']', 50, 255, $spider->getVar('robot-history'));
		$formobjects['robot-history']->setDescription(_AM_SPIDERS_TXTBOX_HISTORY_DESC);			
		$formobjects['robot-environment'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_ENVIRONMENT, 'robot-environment['.$id.']', 50, 128, $spider->getVar('robot-environment'));
		$formobjects['robot-environment']->setDescription(_AM_SPIDERS_TXTBOX_ENVIRONMENT_DESC);			
		$formobjects['modified-date'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_MODIFIEDDATE, 'modified-date['.$id.']', 40, 64, $spider->getVar('modified-date'));
		$formobjects['modified-date']->setDescription(_AM_SPIDERS_TXTBOX_MODIFIEDDATE_DESC);			
		$formobjects['modified-by'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_MODIFIEDBY, 'modified-by['.$id.']', 40, 64, $spider->getVar('modified-by'));
		$formobjects['modified-by']->setDescription(_AM_SPIDERS_TXTBOX_MODIFIEDBY_DESC);			
		$formobjects['robot-safeuseragent'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_SAFEUSERAGENT, 'robot-safeuseragent['.$id.']', 50, 255, $spider->getVar('robot-safeuseragent'));
		$formobjects['robot-safeuseragent']->setDescription(_AM_SPIDERS_TXTBOX_SAFEUSERAGENT_DESC);			


		$formobjects['id'] = new XoopsFormHidden('id', $id);
											
		$required = array('robot-safeuseragent', 'robot-useragent', 'robot-exclusion-useragent', 'robot-name', 'robot-id', 'robot-owner-email');
		
		foreach($formobjects as $id =>$formobject)
			if (!in_array($id, $required))
				$form->addElement($formobjects[$id], false);
			else
				$form->addElement($formobjects[$id], true);
	
		$form->addElement($fileelement);
		$form->addElement(new XoopsFormHidden("op", 'save'));
		$form->addElement(new XoopsFormButton('', 'save_list', _SUBMIT, "submit"));
		$form->display();
		
	}

	function import_spidersmods_edit($modid)
	{
		$spidersmods_handler =& xoops_getmodulehandler('modifications', 'spiders');

		include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";

		if ($modid<>0)
			$spider = $spidersmods_handler->get($modid, true);
		else
			$spider = $spidersmods_handler->create();


		if ($modid<>0)
			$form = new XoopsThemeForm(sprintf(_AM_SPIDERS_EDITSPIDER, $spider->getVar('robot-name')), "import", "", "post");
		else
			$form = new XoopsThemeForm(_AM_SPIDERS_NEWSSPIDER, "import", "", "post");

		$id = $spider->getVar('id');
		
		$formobjects['robot-id'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_ROBOTID, 'robot-id['.$id.']', 50, 128, $spider->getVar('robot-id'));
		$formobjects['robot-id']->setDescription(_AM_SPIDERS_TXTBOX_ROBOTID_DESC);
		if ($id<>0)
			$formobjects['robot-id']->setExtra('disabled="disabled"');
			
		$formobjects['robot-name'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_ROBOTNAME, 'robot-name['.$id.']', 50, 255, $spider->getVar('robot-name'));
		$formobjects['robot-name']->setDescription(_AM_SPIDERS_TXTBOX_ROBOTNAME_DESC);			
		$formobjects['robot-cover-url'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_COVERURL, 'robot-cover-url['.$id.']', 50, 255, $spider->getVar('robot-cover-url'));
		$formobjects['robot-cover-url']->setDescription(_AM_SPIDERS_TXTBOX_COVERURL_DESC);			
		$formobjects['robot-details-url'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_DETAILURL, 'robot-details-url['.$id.']', 50, 255, $spider->getVar('robot-details-url'));
		$formobjects['robot-details-url']->setDescription(_AM_SPIDERS_TXTBOX_DETAILURL_DESC);			
		$formobjects['robot-owner-name'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_OWNERNME, 'robot-owner-name['.$id.']', 50, 128, $spider->getVar('robot-owner-name'));
		$formobjects['robot-owner-name']->setDescription(_AM_SPIDERS_TXTBOX_OWNERNAME_DESC);			
		$formobjects['robot-owner-url'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_OWNERURL, 'robot-owner-url['.$id.']', 50, 255, $spider->getVar('robot-owner-url'));
		$formobjects['robot-owner-url']->setDescription(_AM_SPIDERS_TXTBOX_OWNERURL_DESC);			
		$formobjects['robot-owner-email'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_OWNEREMAIL, 'robot-owner-email['.$id.']', 50, 255, $spider->getVar('robot-owner-email'));
		$formobjects['robot-owner-email']->setDescription(_AM_SPIDERS_TXTBOX_OWNEREMAIL_DESC);
		$formobjects['robot-status'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_STATUS, 'robot-status['.$id.']', 50, 255, $spider->getVar('robot-status'));
		$formobjects['robot-status']->setDescription(_AM_SPIDERS_TXTBOX_STATUS_DESC);			
		$formobjects['robot-purpose'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_PURPOSE, 'robot-purpose['.$id.']', 50, 128, $spider->getVar('robot-purpose'));
		$formobjects['robot-purpose']->setDescription(_AM_SPIDERS_TXTBOX_PURPOSE_DESC);			
		
		$formobjects['robot-type'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_TYPE, 'robot-type['.$id.']', 40, 64, $spider->getVar('robot-type'));
		$formobjects['robot-type']->setDescription(_AM_SPIDERS_TXTBOX_TYPE_DESC);			
		$formobjects['robot-availability'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_AVAILABILITY, 'robot-availability['.$id.']', 50, 128, $spider->getVar('robot-availability'));
		$formobjects['robot-availability']->setDescription(_AM_SPIDERS_TXTBOX_AVAILABILITY_DESC);			
		$formobjects['robot-exclusion'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_EXCLUSION, 'robot-exclusion['.$id.']', 40, 64, $spider->getVar('robot-exclusion'));
		$formobjects['robot-exclusion']->setDescription(_AM_SPIDERS_TXTBOX_EXCLUSION_DESC);	
		$formobjects['robot-exclusion-useragent'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_EXCLUSIONUSERAGENT, 'robot-exclusion-useragent['.$id.']', 50, 255, $spider->getVar('robot-exclusion-useragent'));
		$formobjects['robot-exclusion-useragent']->setDescription(_AM_SPIDERS_TXTBOX_EXCLUSIONUSERAGENT_DESC);
		$formobjects['robot-noindex'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_NOINDEX, 'robot-noindex['.$id.']', 20, 32, $spider->getVar('robot-noindex'));
		$formobjects['robot-noindex']->setDescription(_AM_SPIDERS_TXTBOX_NOINDEX_DESC);			
		
		$formobjects['robot-host'] = new XoopsFormTextArea(_AM_SPIDERS_TXTBOX_HOST, 'robot-host['.$id.']', 40, 10, $spider->getVar('robot-host'));
		$formobjects['robot-host']->setDescription(_AM_SPIDERS_TXTBOX_HOST_DESC);			
		$formobjects['robot-from'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_FROM, 'robot-from['.$id.']', 20, 32, $spider->getVar('robot-from'));
		$formobjects['robot-from']->setDescription(_AM_SPIDERS_TXTBOX_FROM_DESC);			
		
		$formobjects['robot-useragent'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_USERAGENT, 'robot-useragent['.$id.']', 50, 255, $spider->getVar('robot-useragent'));
		$formobjects['robot-useragent']->setDescription(_AM_SPIDERS_TXTBOX_USERAGENT_DESC);			
		$formobjects['robot-language'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_LANGUAGE, 'robot-language['.$id.']', 50, 255, $spider->getVar('robot-language'));
		$formobjects['robot-language']->setDescription(_AM_SPIDERS_TXTBOX_LANGUAGE_DESC);			
		
		
		$formobjects['robot-description'] = new XoopsFormTextArea(_AM_SPIDERS_TXTBOX_DESCRIPTION, 'robot-description['.$id.']', 40, 10, $spider->getVar('robot-description'));
		$formobjects['robot-description']->setDescription(_AM_SPIDERS_TXTBOX_DESCRIPTION_DESC);

		$formobjects['robot-history'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_HISTORY, 'robot-history['.$id.']', 50, 255, $spider->getVar('robot-history'));
		$formobjects['robot-history']->setDescription(_AM_SPIDERS_TXTBOX_HISTORY_DESC);			
		$formobjects['robot-environment'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_ENVIRONMENT, 'robot-environment['.$id.']', 50, 128, $spider->getVar('robot-environment'));
		$formobjects['robot-environment']->setDescription(_AM_SPIDERS_TXTBOX_ENVIRONMENT_DESC);			
		$formobjects['modified-date'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_MODIFIEDDATE, 'modified-date['.$id.']', 40, 64, $spider->getVar('modified-date'));
		$formobjects['modified-date']->setDescription(_AM_SPIDERS_TXTBOX_MODIFIEDDATE_DESC);			
		$formobjects['modified-by'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_MODIFIEDBY, 'modified-by['.$id.']', 40, 64, $spider->getVar('modified-by'));
		$formobjects['modified-by']->setDescription(_AM_SPIDERS_TXTBOX_MODIFIEDBY_DESC);			
		$formobjects['robot-safeuseragent'] = new XoopsFormText(_AM_SPIDERS_TXTBOX_SAFEUSERAGENT, 'robot-safeuseragent['.$id.']', 50, 255, $spider->getVar('robot-safeuseragent'));
		$formobjects['robot-safeuseragent']->setDescription(_AM_SPIDERS_TXTBOX_SAFEUSERAGENT_DESC);			


		$formobjects['id'] = new XoopsFormHidden('id', $id);
											
		$required = array('robot-safeuseragent', 'robot-useragent', 'robot-exclusion-useragent', 'robot-name', 'robot-id', 'robot-owner-email');
		
		foreach($formobjects as $id =>$formobject)
			if (!in_array($id, $required))
				$form->addElement($formobjects[$id], false);
			else
				$form->addElement($formobjects[$id], true);
	
		$form->addElement($fileelement);
		$form->addElement(new XoopsFormHidden("op", 'merge'));
		$form->addElement(new XoopsFormHidden("modid", $modid));		
		$form->addElement(new XoopsFormButton('', 'save_list', _SUBMIT, "submit"));
		$form->display();
		
	}
	
?>