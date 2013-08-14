<?php
	set_time_limit(3600);
	include('../mainfile.php');
	xoops_load('XoopsLists');
	
	$cache_files = Xoopslists::getFileListAsArray(XOOPS_VAR_PATH.'/cache/xoops_cache');
	
	foreach($cache_files as $file) {
		if (fileatime(XOOPS_VAR_PATH.'/cache/xoops_cache/'.$file)<time()-3600)
			unlink(XOOPS_VAR_PATH.'/cache/xoops_cache/'.$file);
	}
	
?>