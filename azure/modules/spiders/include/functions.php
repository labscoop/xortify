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

if (!function_exists("adminMenu")) {
  function adminMenu ($currentoption = 0)  {
  		global $xoopsConfig,$xoopsModule;
		$module_handler =& xoops_gethandler('module');
		$xoopsModule = $module_handler->getByDirname(_MI_SPIDERS_DIRNAME);
	  /* Nice buttons styles */
	    echo "
    	<style type='text/css'>
		#form {float:left; width:100%; background: #e7e7e7 url('" . XOOPS_URL . "/modules/"._MI_SPIDERS_DIRNAME."/images/bg.gif') repeat-x left bottom; font-size:93%; line-height:normal; border-bottom: 1px solid black; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;}
		    	#buttontop { float:left; width:100%; background: #e7e7e7; font-size:93%; line-height:normal; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; margin: 0; }
    	#buttonbar { float:left; width:100%; background: #e7e7e7 url('" . XOOPS_URL . "/modules/"._MI_SPIDERS_DIRNAME."/images/bg.gif') repeat-x left bottom; font-size:93%; line-height:normal; border-left: 1px solid black; border-right: 1px solid black; margin-bottom: 0px; border-bottom: 1px solid black; }
    	#buttonbar ul { margin:0; margin-top: 15px; padding:10px 10px 0; list-style:none; }
		  #buttonbar li { display:inline; margin:0; padding:0; }
		  #buttonbar a { float:left; background:url('" . XOOPS_URL . "/modules/"._MI_SPIDERS_DIRNAME."/images/left_both.gif') no-repeat left top; margin:0; padding:0 0 0 9px;  text-decoration:none; }
		  #buttonbar a span { float:left; display:block; background:url('" . XOOPS_URL . "/modules/"._MI_SPIDERS_DIRNAME."/images/right_both.gif') no-repeat right top; padding:5px 15px 4px 6px; font-weight:bold; color:#765; }
		  /* Commented Backslash Hack hides rule from IE5-Mac \*/
		  #buttonbar a span {float:none;}
		  /* End IE5-Mac hack */
		  #buttonbar a:hover span { color:#333; }
		  #buttonbar #current a { background-position:0 -150px; border-width:0; }
		  #buttonbar #current a span { background-position:100% -150px; padding-bottom:5px; color:#333; }
		  #buttonbar a:hover { background-position:0% -150px; }
		  #buttonbar a:hover span { background-position:100% -150px; }
		  </style>";
	
	   // global $xoopsDB, $xoopsModule, $xoopsConfig, $xoopsModuleConfig;
	
	   $myts = &MyTextSanitizer::getInstance();
	
	   $tblColors = Array();
		// $adminmenu=array();
	   if (file_exists(XOOPS_ROOT_PATH . '/modules/' . _MI_SPIDERS_DIRNAME . '/language/' . $xoopsConfig['language'] . '/modinfo.php')) {
		   include_once XOOPS_ROOT_PATH . '/modules/' . _MI_SPIDERS_DIRNAME . '/language/' . $xoopsConfig['language'] . '/modinfo.php';
	   } else {
		   include_once XOOPS_ROOT_PATH . '/modules/' . _MI_SPIDERS_DIRNAME . '/english/modinfo.php';
	   }
       
	   echo "<table width=\"100%\" border='0'><tr><td>";
	   echo "<div id='buttontop'>";
	   echo "<table style=\"width: 100%; padding: 0; \" cellspacing=\"0\"><tr>";
	   echo "<td style=\"width: 45%; font-size: 10px; text-align: left; color: #2F5376; padding: 0 6px; line-height: 18px;\"><!--<a class=\"nobutton\" href=\"".XOOPS_URL."/modules/system/admin.php?fct=preferences&amp;op=showmod&amp;mod=" . $xoopsModule->getVar('mid') . "\">" . _PREFERENCES . "</a>--></td>";
	   echo "<td style='font-size: 10px; text-align: right; color: #2F5376; padding: 0 6px; line-height: 18px;'><b>" . $myts->displayTarea($xoopsModule->name()) ."</td>";
	   echo "</tr></table>";
	   echo "</div>";
	   echo "<div id='buttonbar'>";
	   echo "<ul>";
		 foreach ($xoopsModule->getAdminMenu() as $key => $value) {
		   $tblColors[$key] = '';
		   $tblColors[$currentoption] = 'current';
	     echo "<li id='" . $tblColors[$key] . "'><a href=\"" . XOOPS_URL . "/modules/".$xoopsModule->getVar('dirname')."/".$value['link']."\"><span>" . $value['title'] . "</span></a></li>";
		 }
		 
	   echo "</ul></div>";
	   echo "</td></tr>";
	   echo "<tr'><td><div id='form'>";
    
  }
  
  function footer_adminMenu()
  {
		echo "</div></td></tr>";
  		echo "</table>";
  }
}

function import_robotstxt_org($file)
{
	ini_set("max_execution_time", "1000");  
	$spiders_handler =& xoops_getmodulehandler('spiders', _MI_SPIDERS_DIRNAME);
	$lines = file(XOOPS_ROOT_PATH.'/modules/'._MI_SPIDERS_DIRNAME.'/admin/resources/'.$file);
	while($notfinished != true)
	{
		if (strlen($lines[$ii])>0) {
			if (strpos(' '.$lines[$ii], 'robot-id')>0) {
				if (is_a($spider, 'SpidersSpiders'))
					$spiders_handler->import_insert($spider);
				$spider =& $spiders_handler->create();
			}
			if (!is_object($spider))
				$spider =& $spiders_handler->create();
			$exploded = explode(':', $lines[$ii]);
			switch($exploded[0]) {
			case "robot-cover-url":
			case "robot-details-url":
			case "robot-owner-url":
				$ml = false;								
				$spider->setVar($exploded[0], trim($exploded[1].':'.$exploded[2]));
				break;
			case "robot-id":
			case "robot-name":
			case "robot-owner-name":
			case "robot-owner-email":
			case "robot-status":
			case "robot-purpose":
			case "robot-type":
			case "robot-platform":
			case "robot-availability":
			case "robot-exclusion":
			case "robot-exclusion-useragent":
			case "robot-noindex":
			case "robot-host":
			case "robot-from":
			case "robot-language":
			case "robot-environment":
			case "modified-date":								
			case "modified-by":		
				$ml = false;								
				$spider->setVar($exploded[0], trim($exploded[1]));
				break;
			case "robot-useragent":
				$ml = false;								
				$spider->setVar("robot-safeuseragent", $spiders_handler->safeAgent(trim($exploded[1])));				
				$spider->setVar($exploded[0], trim($exploded[1]));
				break;
			case "robot-history":
			case "robot-description":		
				$ml = true;		
				$key = $exploded[0];
				$spider->setVar($exploded[0], trim($exploded[1]));
				break;
			default:
				if ($ml = true) {
					$spider->setVar($key, $spider->getVar($key).' '.trim($exploded[1]));
				}
			}
		} else {
			if (is_a($spider, 'SpidersSpiders'))
				$spiders_handler->import_insert($spider);
		}
		$ii++;
		if ($ii>sizeof($lines))
			$notfinished = true;
	}

	if (is_a($spider, 'SpidersSpiders'))
		$spiders_handler->import_insert($spider);
	
}

function chronolabs_inline($flash = false)
{
	return _AM_SPIDERS_INLINE;
}

function apimethod() {
	foreach (get_loaded_extensions() as $ext){
		if ($ext=="soap")
			return $ext;
	}
	foreach (get_loaded_extensions() as $ext){
		if ($ext=="curl")
			return $ext;
	}
	return 'json';
}
?>