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
class SpidersModifications extends XoopsObject
{

    function SpidersModifications($fid = null)
    {
        $this->initVar('modid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('robot-id', XOBJ_DTYPE_TXTBOX, null, false, 128);
        $this->initVar('robot-name', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('robot-cover-url', XOBJ_DTYPE_OTHER, null, false, 255);
        $this->initVar('robot-details-url', XOBJ_DTYPE_OTHER, null, false, 255);
        $this->initVar('robot-owner-name', XOBJ_DTYPE_TXTBOX, null, false, 128);
        $this->initVar('robot-owner-url', XOBJ_DTYPE_OTHER, null, false, 255);
        $this->initVar('robot-owner-email', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('robot-status', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('robot-purpose', XOBJ_DTYPE_TXTBOX, null, false, 128);
		$this->initVar('robot-type', XOBJ_DTYPE_TXTBOX, null, false, 64);
		$this->initVar('robot-platform', XOBJ_DTYPE_TXTBOX, null, false, 64);
		$this->initVar('robot-availability', XOBJ_DTYPE_TXTBOX, null, false, 128);
		$this->initVar('robot-exclusion', XOBJ_DTYPE_TXTBOX, null, false, 32);
		$this->initVar('robot-exclusion-useragent', XOBJ_DTYPE_TXTBOX, null, false, 255);
		$this->initVar('robot-noindex', XOBJ_DTYPE_TXTBOX, null, false, 32);
		$this->initVar('robot-host', XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar('robot-from', XOBJ_DTYPE_TXTBOX, null, false, 32);
		$this->initVar('robot-useragent', XOBJ_DTYPE_TXTBOX, null, false, 255);
		$this->initVar('robot-language', XOBJ_DTYPE_TXTBOX, null, false, 64);
		$this->initVar('robot-description', XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar('robot-history', XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar('robot-environment', XOBJ_DTYPE_TXTBOX, null, false, 128);
		$this->initVar('modified-date', XOBJ_DTYPE_TXTBOX, null, false, 64);										
		$this->initVar('modified-by', XOBJ_DTYPE_TXTBOX, null, false, 64);
		$this->initVar('robot-safeuseragent', XOBJ_DTYPE_TXTBOX, null, false, 255);	
		$this->initVar('robot-handlesession', XOBJ_DTYPE_TXTBOX, null, false, 3);		
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
class SpidersModificationsHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db) 
    {
        parent::__construct($db, "spiders_modifications", 'SpidersModifications', "modid", "robot-id");
    }
	
	function safeAgent($useragent) {

		$part = explode('/', $useragent);
		foreach($part as $key => $value) {
			if (strpos(strtolower($value), '.x'))
				unset($part[$key]);
		}
		$useragent = implode('/', $part);
		
		$ver_char = array('x.x', '*.*', 'X.X', 'x.xxx', 'x.y', 'xxx', 'xxxx', 'xxxxx', 'xxxxxx', 'vX.X.X', 'X.X.X', 'X.xx');
		foreach($ver_char as $vc)
			if (strpos($useragent, $vc))
				$useragent = str_replace($vc, '', $useragent);

		$modulehandler =& xoops_gethandler('module');
		$confighandler =& xoops_gethandler('config');
		$xoModule = $modulehandler->getByDirname('spiders');
		$xoConfig = $confighandler->getConfigList($xoModule->getVar('mid'),false);
		$reservedphrases = explode('|', $xoConfig['reserved_prases']);
		foreach($reservedphrases as $id => $phrase)
			$useragent = str_replace($phrase, '', $useragent);

		return $useragent;
	}
	
}
?>