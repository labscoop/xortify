<?php
if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}
/**
 * Class for Spiders
 * @author Simon Roberts (simon@xoops.org)
 * @copyright copyright (c) 2010-2013 labs.coop
 * @package kernel
 */
class SpidersSpiders_user extends XoopsObject
{

    function SpidersSpiders_user($fid = null)
    {
        $this->initVar('spider_id', XOBJ_DTYPE_INT, null, false);
		$this->initVar('uid', XOBJ_DTYPE_INT, null, false);    
	}


}


/**
* XOOPS Spider handler class.
* This class is responsible for providing data access mechanisms to the data source
* of XOOPS user class objects.
*
* @author  Simon Roberts <simon@xoops.org>
* @package kernel
*/
class SpidersSpiders_userHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db) 
    {
        parent::__construct($db, "spiders_user", 'SpidersSpiders_user', "spider_id", "uid");
    }
	
}
?>