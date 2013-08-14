<?php
function xoops_module_update_spiders(&$module) {
	ini_set("max_execution_time", "600");  
	$GLOBALS['xoopsDB']->queryF("ALTER TABLE ".$GLOBALS['xoopsDB']->prefix("spiders").' ADD COLUMN `robot-safeuseragent` VARCHAR(255) DEFAULT NULL');
	$GLOBALS['xoopsDB']->queryF("ALTER TABLE ".$GLOBALS['xoopsDB']->prefix("spiders").' ADD COLUMN `robot-handlesession` VARCHAR(3) DEFAULT \'Yes\'');
	$GLOBALS['xoopsDB']->queryF("CREATE TABLE ".$GLOBALS['xoopsDB']->prefix("spiders_statistics").' ( `id` int(12) unsigned DEFAULT NULL,
																									  `uri` tinytext,
																									  `useragent` varchar(255) DEFAULT NULL,
																									  `netaddy` varchar(255) DEFAULT NULL,
																									  `ip` tinytext,
																									  `server-ip` tinytext,
																									  `when` int(12) unsigned DEFAULT \'0\',
																									  `sitename` varchar(255) DEFAULT NULL,																									
																									  KEY `statistics` (`id`,`uri`(50),`netaddy`(30),`server-ip`(15))
																									) ENGINE=InnoDB DEFAULT CHARSET=utf8');	
	$GLOBALS['xoopsDB']->queryF("CREATE TABLE ".$GLOBALS['xoopsDB']->prefix("spiders_modifications").' (  `modid` INT(12) UNSIGNED NOT NULL AUTO_INCREMENT,          
																									`id` INT(12) UNSIGNED NOT NULL DEFAULT 0,          
																									`robot-id` VARCHAR(128) DEFAULT NULL,                   
																									`robot-name` VARCHAR(255) DEFAULT NULL,                 
																									`robot-cover-url` VARCHAR(255) DEFAULT NULL,            
																									`robot-details-url` VARCHAR(255) DEFAULT NULL,          
																									`robot-owner-name` VARCHAR(128) DEFAULT NULL,           
																									`robot-owner-url` VARCHAR(255) DEFAULT NULL,            
																									`robot-owner-email` VARCHAR(255) DEFAULT NULL,          
																									`robot-status` VARCHAR(255) DEFAULT NULL,               
																									`robot-purpose` VARCHAR(128) DEFAULT NULL,              
																									`robot-type` VARCHAR(64) DEFAULT NULL,                  
																									`robot-platform` VARCHAR(64) DEFAULT NULL,              
																									`robot-availability` VARCHAR(128) DEFAULT NULL,         
																									`robot-exclusion` VARCHAR(32) DEFAULT NULL,             
																									`robot-exclusion-useragent` VARCHAR(255) DEFAULT NULL,  
																									`robot-noindex` VARCHAR(32) DEFAULT NULL,               
																									`robot-host` MEDIUMTEXT,                                
																									`robot-from` VARCHAR(32) DEFAULT NULL,                  
																									`robot-useragent` VARCHAR(255) DEFAULT NULL,            
																									`robot-language` VARCHAR(64) DEFAULT NULL,              
																									`robot-description` MEDIUMTEXT,                         
																									`robot-history` MEDIUMTEXT,                             
																									`robot-environment` VARCHAR(128) DEFAULT NULL,          
																									`modified-date` VARCHAR(64) DEFAULT NULL,               
																									`modified-by` VARCHAR(64) DEFAULT NULL,    
																									`robot-safeuseragent` VARCHAR(255) DEFAULT NULL,
																									`robot-handlesession` VARCHAR(3) DEFAULT \'Yes\',
																									PRIMARY KEY (`modid`)                                      
																								) ENGINE=InnoDB DEFAULT CHARSET=utf8;');																										
	$spiders_handler =& xoops_getmodulehandler('spiders', _MI_SPIDERS_DIRNAME);
	$spiders = $spiders_handler->getObjects(NULL);
	foreach($spiders as $spider) {
		$GLOBALS['xoopsDB']->queryF("UPDATE  ".$GLOBALS['xoopsDB']->prefix("spiders")." SET `robot-safeuseragent` = '" . $spiders_handler->safeAgent($spider->getVar('robot-useragent')) . "' WHERE `id` = '".$spider->getVar('id')."'");
		echo "&nbsp;&nbsp;UPDATE  ".$GLOBALS['xoopsDB']->prefix("spiders")." SET `robot-safeuseragent` = '" . $spiders_handler->safeAgent($spider->getVar('robot-useragent')) . "' WHERE `id` = '".$spider->getVar('id')."'<br/>";
	}
	return true;
	
}

?>