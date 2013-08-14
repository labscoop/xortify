<?php
/*
 * Prevents Spam, Harvesting, Human Rights Abuse, Captcha Abuse etc.
* basic statistic of them in XOOPS Copyright (C) 2012 Simon Roberts
* Contact: wishcraft - simon@labs.coop
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program. If not, see <http://www.gnu.org/licenses/>.
* See /docs/license.pdf for full license.
*
* Shouts:- 	Mamba (www.xoops.org), flipse (www.nlxoops.nl)
* 				Many thanks for your additional work with version 1.01
*
* Version:		3.10 Final (Stable)
* @copyright:		Chronolabs Cooperative 2013 © Simon Roberts (www.simonaroberts.com)
* Download:		http://sourceforge.net/projects/xortify
* This File:	log.php
* Description:	Log Object and Handler Class for Xortify
* @date:				09/09/2012 19:34 AEST
* @license:		GNU3 (http://web.labs.coop/public/legal/general-public-licence/13,3.html)
*
*/

if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}

/**
 * Class for Blue Room Xortify Log
 * @author Simon Roberts <simon@labs.coop>
 * @package kernel
 */
class XortifyLog extends XoopsObject
{

    /*
	 * Constructor
	 */
	function XortifyLog()
    {
        $this->initVar('lid', XOBJ_DTYPE_INT, null, false);
		$this->initVar('uid', XOBJ_DTYPE_INT, null, false);
		$this->initVar('uname', XOBJ_DTYPE_TXTBOX, false, false, 64);
		$this->initVar('email', XOBJ_DTYPE_TXTBOX, false, false, 255);
		$this->initVar('ip4', XOBJ_DTYPE_TXTBOX, false, false, 15);
		$this->initVar('ip6', XOBJ_DTYPE_TXTBOX, false, false, 128);
		$this->initVar('proxy-ip4', XOBJ_DTYPE_TXTBOX, false, false, 15);
		$this->initVar('proxy-ip6', XOBJ_DTYPE_TXTBOX, false, false, 128);
		$this->initVar('network-addy', XOBJ_DTYPE_TXTBOX, false, false, 255);
		$this->initVar('provider', XOBJ_DTYPE_TXTBOX, false, false, 128);
		$this->initVar('agent', XOBJ_DTYPE_TXTBOX, false, false, 255);
		$this->initVar('extra', XOBJ_DTYPE_OTHER, false, false);
		$this->initVar('date', XOBJ_DTYPE_INT, null, false);
		$this->initVar('action', XOBJ_DTYPE_OTHER, 'monitored', false);
    }

    /*
    * Returns Object as Array
    */
    function toArray() {
    	$ret = parent::toArray();
    	$ret['date_datetime'] = date('Y-m-d H:i:s', $this->getVar('date'));
    	$ret['action'] = ucfirst($this->getVar('action'));
    	foreach($ret as $key => $value)
    		$ret[str_replace('-', '_', $key)] = $value;
    	return $ret;
    }
    
    /*
	 * Runs PRE Plugin for extending xortify
	 * 
	 * @param boolean $default
	 * @return integer
	 */
	function runPrePlugin($default = true) {
		
		include_once($GLOBALS['xoops']->path('/modules/xortify/plugin/'.$this->getVar('provider').'.php'));
		$classname = 'XortifyPlugin'.ucfirst(str_replace(array('.', ',', '-','|','=','+','\'','"'), "_", $this->getVar('provider')));
		if (class_exists($classname))
			switch ($this->getVar('action')) {
				case 'banned':
				case 'blocked':
				case 'monitored':
					$class = new $classname();
					$func = ucfirst($this->getVar('action')).'PreHook';
					break;
				default:
					return $default;
					break;
			}
		else
			return $default;
		
		if (method_exists($class, $func))  {
			return @$class->$func($default, $this);
		}
		return $default;
	}
    
	/*
	 * Runs POST Plugin for extending xortify
	 * 
	 * @param integer $lid
	 * @return integer
	 */
	function runPostPlugin($lid) {
		
		include_once($GLOBALS['xoops']->path('/modules/xortify/plugin/'.$this->getVar('provider').'.php'));
		$classname = 'XortifyPlugin'.ucfirst(str_replace(array('.', ',', '-','|','=','+','\'','"'), "_", $this->getVar('provider')));
		if (class_exists($classname))
			switch ($this->getVar('action')) {
				case 'banned':
				case 'blocked':
				case 'monitored':
					$class = new $classname();
					$func = ucfirst($this->getVar('action')).'PostHook';
					break;
				default:
					return $default;
					break;
			}
		else
			return $default;
		
		if (method_exists($class, $func))  {
			return @$class->$func($this, $lid);
		}
		return $lid;
	}
}


/**
* XOOPS Xortify Log handler class.
* This class is responsible for providing data access mechanisms to the data source
* of XOOPS user class objects.
*
* @author  Simon Roberts <simon@chronolabs.coop>
* @package kernel
*/
class XortifyLogHandler extends XoopsPersistableObjectHandler
{
    /*
	 * Constructor
	 */
	function __construct(&$db) 
    {
		$this->db = $db;
        parent::__construct($db, 'xortify_log', 'XortifyLog', "lid", "network-addy");
    }
	
    /*
     * Inserts an Log Entry into log tables
     * 
     * @param XortifyLog $object
     * @param boolean $force
     * @return integer
     */
    function insert(XoopsObject $object, $force = true) {
		$criteria = new Criteria('`date`', time()-$GLOBALS['xoops']->getModuleConfig('logdrops', 'xortify'), '<=');
		$this->deleteAll($criteria, true);
		
    	if ($object->isNew()) {
    		$object->setVar('date', time());
    	}
		$run_plugin_action=false;
    	if ($object->vars['action']['changed']==true) {	
			$run_plugin_action=true;
		}
    	if ($run_plugin_action){
    		if ($object->runPrePlugin($GLOBALS['xoops']->getModuleConfig('save_'.$object->getVar('action'), 'xortify'))==true)
    			$lid = parent::insert($object, $force);
    		else 
    			return false;
    	} else 	
    		$lid = parent::insert($object, $force);		
    	if ($run_plugin_action)
    		return $object->runPostPlugin($lid);
    	else 	
    		return $lid;
    }
    
    /*
     * Returns count by field value
     */
    function getCountByField($field, $value) {
    	$criteria = new Criteria('`'.$field.'`', $value);
    	$count = $this->getCount($criteria);
    	return ($count>0?$count:'0');
    }
}

?>