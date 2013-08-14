<?php
/**
 * Extended User Profile
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code 
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Chronolabs Coopertive (Australia)  http://web.labs.coop
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         profile
 * @since           2.3.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id: update.php 3704 2009-10-05 08:52:22Z wishcraft $
 */

function xoops_module_update_profile(&$module, $oldversion = null) 
{
    
    if ( $oldversion < 100 ) {
      
        // Drop old category table  
        $sql = "DROP TABLE " . $GLOBALS['xoopsDB']->prefix("profile_category");
        $GLOBALS['xoopsDB']->queryF($sql);
        
        // Drop old field-category link table
        $sql = "DROP TABLE " . $GLOBALS['xoopsDB']->prefix("profile_fieldcategory");
        $GLOBALS['xoopsDB']->queryF($sql);
        
        // Create new tables for new profile module
        $GLOBALS['xoopsDB']->queryFromFile(XOOPS_ROOT_PATH . "/modules/" . $module->getVar('dirname', 'n') . "/sql/mysql.sql");
        
        include_once dirname(__FILE__) . "/install.php";
        xoops_module_install_profile($module);
        $goupperm_handler =& xoops_getHandler("groupperm");
        
        $field_handler =& xoops_getModuleHandler('field', $module->getVar('dirname', 'n') );
        $skip_fields = $field_handler->getUserVars();
        $skip_fields[] = 'newemail';
        $skip_fields[] = 'pm_link';
        $sql = "SELECT * FROM `" . $GLOBALS['xoopsDB']->prefix("user_profile_field") . "` WHERE `field_name` NOT IN ('" . implode("', '", $skip_fields) . "')";
        $result = $GLOBALS['xoopsDB']->query($sql);
        $fields = array();
        while ($myrow = $GLOBALS['xoopsDB']->fetchArray($result)  ) {
            $fields[] = $myrow['field_name'];
            $object =& $field_handler->create();
            $object->setVars($myrow, true);
            $object->setVar('cat_id', 1);
            if ( !empty($myrow['field_register'])  ) {
                $object->setVar('step_id', 2);
            }
            if ( !empty($myrow['field_options'])  ) {
                $object->setVar('field_options', unserialize($myrow['field_options']) );
            }
            $field_handler->insert($object, true);
            
            $gperm_itemid = $object->getVar('field_id');
            $sql = "UPDATE " . $GLOBALS['xoopsDB']->prefix("group_permission") . " SET gperm_itemid = " . $gperm_itemid .
                    "   WHERE gperm_itemid = " . $myrow['fieldid'] .
                    "       AND gperm_modid = " . $module->getVar('mid') .
                    "       AND gperm_name IN ('profile_edit', 'profile_search')";
            $GLOBALS['xoopsDB']->queryF($sql);

            $groups_visible = $goupperm_handler->getGroupIds("profile_visible", $myrow['fieldid'], $module->getVar('mid') );
            $groups_show = $goupperm_handler->getGroupIds("profile_show", $myrow['fieldid'], $module->getVar('mid') );
            foreach ($groups_visible as $ugid ) {
                foreach ($groups_show as $pgid ) {
                    $sql = "INSERT INTO " . $GLOBALS['xoopsDB']->prefix("profile_visibility") . 
                        " (field_id, user_group, profile_group) " .
                        " VALUES " . 
                        " ({$gperm_itemid}, {$ugid}, {$pgid})";
                    $GLOBALS['xoopsDB']->queryF($sql);
                }
            }
            
            //profile_install_setPermissions($object->getVar('field_id'), $module->getVar('mid'), $canedit, $visible);
            unset($object);
        }
        
        // Copy data from profile table
        foreach ($fields as $field ) {
            $GLOBALS['xoopsDB']->queryF("UPDATE `" . $GLOBALS['xoopsDB']->prefix("profile_profile") . "` u, `" . $GLOBALS['xoopsDB']->prefix("user_profile") . "` p SET u.{$field} = p.{$field} WHERE u.profile_id=p.profileid");
        }
        
        // Drop old profile table
        $sql = "DROP TABLE " . $GLOBALS['xoopsDB']->prefix("user_profile");
        $GLOBALS['xoopsDB']->queryF($sql);
        
        // Drop old field module
        $sql = "DROP TABLE " . $GLOBALS['xoopsDB']->prefix("user_profile_field");
        $GLOBALS['xoopsDB']->queryF($sql);
        
        // Remove not used items
        $sql =  "DELETE FROM " . $GLOBALS['xoopsDB']->prefix("group_permission") . 
                "   WHERE `gperm_modid` = " . $module->getVar('mid') . " AND `gperm_name` IN ('profile_show', 'profile_visible')";
        $GLOBALS['xoopsDB']->queryF($sql);
    }

	$sql =  "CREATE TABLE `".$GLOBALS['xoopsDB']->prefix('profile_validation')."` (
		`rule_id` int(5) unsigned NOT NULL AUTO_INCREMENT,
		`weight` tinyint(5) unsigned DEFAULT '1',
		`type` enum('regex','sql','match') DEFAULT NULL,
		`action` tinytext,
		PRIMARY KEY (`rule_id`)
	) ENGINE=MyISAM;";
	$GLOBALS['xoopsDB']->queryF($sql);

	$GLOBALS['xoopsDB']->queryF("CREATE TABLE `" . $GLOBALS['xoopsDB']->prefix("profile_oauth_applet") ."` (`appid` int(12) unsigned NOT NULL auto_increment, `profile_id` int(12) unsigned NOT NULL default '0', `name` TEXT, `description` TEXT, `image` TEXT, `uri` TEXT, `client_id` TEXT, `org_name` TEXT,	`org_uri` TEXT,	PRIMARY KEY  (`appid`)) ENGINE=MyISAM");
	$GLOBALS['xoopsDB']->queryF("CREATE TABLE `" . $GLOBALS['xoopsDB']->prefix("profile_oauth_clients") . "` (client_id TEXT, client_secret TEXT, redirect_uri TEXT)");
	$GLOBALS['xoopsDB']->queryF("CREATE TABLE `" . $GLOBALS['xoopsDB']->prefix("profile_oauth_access_tokens") . "` (access_token TEXT, client_id TEXT, user_id TEXT, expires TIMESTAMP, scope TEXT)");
	$GLOBALS['xoopsDB']->queryF("CREATE TABLE `" . $GLOBALS['xoopsDB']->prefix("profile_oauth_authorization_codes") . "` (authorization_code TEXT, client_id TEXT, user_id TEXT, redirect_uri TEXT, expires TIMESTAMP, scope TEXT)");
	$GLOBALS['xoopsDB']->queryF("CREATE TABLE `" . $GLOBALS['xoopsDB']->prefix("profile_oauth_refresh_tokens") . "` (refresh_token TEXT, client_id TEXT, user_id TEXT, expires TIMESTAMP, scope TEXT)");
	$GLOBALS['xoopsDB']->queryF("CREATE TABLE `" . $GLOBALS['xoopsDB']->prefix("profile_oauth_jwt") . "` (client_id TEXT, subject TEXT, public_key TEXT)");
	
	$sql =  "DROP TABLE `".$GLOBALS['xoopsDB']->prefix('profile_oauth')."`";
	$GLOBALS['xoopsDB']->queryF($sql);
	$sql =  "DROP TABLE `".$GLOBALS['xoopsDB']->prefix('profile_oauth_log')."`";
	$GLOBALS['xoopsDB']->queryF($sql);
	$sql =  "DROP TABLE `".$GLOBALS['xoopsDB']->prefix('profile_oauth_consumer_registry')."`";
	$GLOBALS['xoopsDB']->queryF($sql);
	$sql =  "DROP TABLE `".$GLOBALS['xoopsDB']->prefix('profile_oauth_consumer_token')."`";
	$GLOBALS['xoopsDB']->queryF($sql);
	$sql =  "DROP TABLE `".$GLOBALS['xoopsDB']->prefix('profile_oauth_server_registry')."`";
	$GLOBALS['xoopsDB']->queryF($sql);
	$sql =  "DROP TABLE `".$GLOBALS['xoopsDB']->prefix('profile_oauth_server_nonce')."`";
	$GLOBALS['xoopsDB']->queryF($sql);
	$sql =  "DROP TABLE `".$GLOBALS['xoopsDB']->prefix('profile_oauth_server_token')."`";
	$GLOBALS['xoopsDB']->queryF($sql);
	
    $profile_handler =& xoops_getModuleHandler("profile", $module->getVar('dirname', 'n') );
    $profile_handler->cleanOrphan($GLOBALS['xoopsDB']->prefix("users"), "uid", "profile_id");
    $field_handler =& xoops_getModuleHandler('field', $module->getVar('dirname', 'n') );
    $user_fields = $field_handler->getUserVars();
    $criteria = new Criteria("field_name", "('" . implode("', '", $user_fields) . "')", "IN");
    $field_handler->updateAll("field_config", 0, $criteria);
    
    return true;
}
?>