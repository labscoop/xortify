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
 * @since           2.5.0
 * @author          Simon Roberts <simon@labs.coop>
 * @version         $Id: dojsonvalidate.php 3988 2009-12-05 15:46:47Z wishcraft $
 */

class ProfileValidation extends XoopsObject {
	function ProfileValidation(){
		$this->XoopsObject();
		$this->initVar("rule_id", XOBJ_DTYPE_INT);
		$this->initVar("weight", XOBJ_DTYPE_INT);
		$this->initVar("type", XOBJ_DTYPE_OTHER);
		$this->initVar("action", XOBJ_DTYPE_TXTBOX, false, false, 65535);		
	}
}

class ProfileValidationHandler extends XoopsPersistableObjectHandler {

    function ProfileRegstepHandler(&$db)
    {
        $this->__construct($db);
    }

    function __construct($db)
    {
        parent::__construct($db, 'profile_validation', 'ProfileValidation', 'rule_id', 'action');
    }
	
}
?>