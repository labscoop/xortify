<?php
	include( '../mainfile.php' );

	echo ":: Cron Started :: ".date('d-M-Y h:i:s', time()). "\n";
		
	$banmemberhandler =& xoops_getmodulehandler('members','ban');

	$criteria = new CriteriaCompo(new Criteria('`made`', time() - (((7*(24*(60*60))))*3), '<'));
	$criteria->add(new Criteria('`made`', 0, '='), 'OR');
	
	$banned = $banmemberhandler->getObjects($criteria, true);

	$comment_handler = xoops_gethandler('comment');
	$unbanmemberhandler =& xoops_getmodulehandler('members','unban');
	$module_handler = & xoops_gethandler('module');	
	
	$xoBanModule = $module_handler->getByDirname('ban');
	$xoUnbanModule = $module_handler->getByDirname('unban');
	
	if (is_array($banned))
	foreach( $banned as $key => $ban ) {
				
		$unban = $unbanmemberhandler->create();

		$unban->setVars($ban->toArray());
		$unban->setVar('made', time());
	
		if (!$banmemberhandler->delete( $ban, true ))
			echo "\nBan was unremovable :: ".$key." :: ".$ban->getVar('network-addy');			
		else
			echo "\nBan was removable :: ".$key." :: ".$ban->getVar('network-addy');			
			
		if (!$unbanmemberhandler->insert($unban))
			echo "\nUnban was not saved :: ".$unban->getVar('network-addy');			
		else {
			echo "\nUnban was saved  :: ".$unban->getVar('network-addy');			
			$sql = "UPDATE ".$xoopsDB->prefix('xoopscomments'). " set `com_modid` = '".$xoUnbanModule->getVar('mid')."' WHERE `com_modid` = '".$xoBanModule->getVar('mid')."' AND `com_itemid` = '".$ban->getVar('member_id')."'" ;
			$xoopsDB->queryF($sql);
		}
	} else 
		echo ":: Nothing done :: ".date('d-M-Y h:i:s', time()). "\n";
		
	echo ":: Cron Ended :: ".date('d-M-Y h:i:s', time()). "\n";
	exit(0);


?>