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
class SpidersStatistics extends XoopsObject
{

    function SpidersStatistics($fid = null)
    {
        $this->initVar('id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('uri', XOBJ_DTYPE_UNICODE_TXTBOX, null, false, 65535);
        $this->initVar('useragent', XOBJ_DTYPE_UNICODE_TXTBOX, null, false, 255);
        $this->initVar('netaddy', XOBJ_DTYPE_UNICODE_TXTBOX, null, false, 255);
        $this->initVar('ip', XOBJ_DTYPE_UNICODE_TXTBOX, null, false, 65535);
        $this->initVar('server-ip', XOBJ_DTYPE_UNICODE_TXTBOX, null, false, 65535);
        $this->initVar('when', XOBJ_DTYPE_INT, time(), false);
        $this->initVar('sitename', XOBJ_DTYPE_UNICODE_TXTBOX, null, false, 255);		
    }

	function getStory() {
		$spiders_handler = xoops_getmodulehandler('spiders', 'spiders');
		$spider = $spiders_handler->get($this->getVar('id'));
		$ret = array();
		if (is_object($spider)) {
			$ret['url'] = str_replace('\\/', '/', $this->getVar('uri'));
			$ret['category'] = $spider->getVar('robot-id');
			$ret['title'] = $this->xoopsTweetString($ret['category'], true, 3).' #Crawled: '.$this->xoopsTweetString($this->getVar('sitename'), true, 4).' - #When: '.date(_DATESTRING, $this->getVar('when'));
			$ret['story'] = '<img src="'.XOOPS_URL.'/modules/spiders/images/spiders_slogo.png" />' . '<br/>';
			$ret['story'] .= 'Robot: ' . $spider->getVar('robot-name') . '<br/>';
			$ret['story'] .= 'Robot Id: ' . $spider->getVar('robot-id') . '<br/>';
			$ret['story'] .= 'Robot Exclusion: ' . $spider->getVar('robot-exclusion') . '<br/>';
			$ret['story'] .= 'Robot Useragent: ' . $this->getVar('useragent') . '<br/>';
			$ret['story'] .= 'Robot Net Bios: ' . $this->getVar('netaddy') . '<br/>';
			$ret['story'] .= 'Robot IP: ' . $this->getVar('ip') . '<br/>';
			$ret['story'] .= 'Crawled: ' . date(_DATESTRING, $this->getVar('when')) . '<br/>';
			$ret['story'] .= 'Sitename: ' . $this->getVar('sitename') . '<br/>';
			$ret['story'] .= 'Site URL: ' . $ret['url'];
		} else {
			$ret['url'] = str_replace('\\/', '/', $this->getVar('uri'));
			$ret['category'] = 'Unknown Bot';
			$ret['title'] = $this->xoopsTweetString($ret['category'], true, 3).' #Crawled: '.$this->xoopsTweetString($this->getVar('sitename'), true, 4).' - #When: '.date(_DATESTRING, $this->getVar('when'));
			$ret['story'] = '<img src="'.XOOPS_URL.'/modules/spiders/images/spiders_slogo.png" />' . '<br/>';
			$ret['story'] .= 'Robot Useragent: ' . $this->getVar('useragent') . '<br/>';
			$ret['story'] .= 'Robot Net Bios: ' . $this->getVar('netaddy') . '<br/>';
			$ret['story'] .= 'Robot IP: ' . $this->getVar('ip') . '<br/>';
			$ret['story'] .= 'Crawled: ' . date(_DATESTRING, $this->getVar('when')) . '<br/>';
			$ret['story'] .= 'Sitename: ' . $this->getVar('sitename') . '<br/>';
			$ret['story'] .= 'Site URL: ' . $ret['url'];
		}
		$ret['story'] = str_replace('\\/', '/', $ret['story']);
		return $ret;
	}
	
	function xoopsTweetString($title, $doit=false, $wordlen=4) {
		if ($doit==true) {
			$title_array = explode(' ', $title);
			$title = '';
			foreach($title_array as $item) {
				if (strlen($item)>$wordlen) 
					$title .= ' #'.$item;
				else 
					$title .= ' '.$item;
			}
		}
		return trim($title);
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
class SpidersStatisticsHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db) 
    {
        parent::__construct($db, "spiders_statistics", 'SpidersStatistics');
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

	function insert($obj, $force=true) {
		xoops_load('cache');
		$read = XoopsCache::read('spider_id%%'.$obj->getVar('id'));
		if (!is_array($read)) {
			$value = '0A';
		} else {
			$value = $read['value'];
		}
		$value++;
		$read = XoopsCache::delete('spider_id%%'.$obj->getVar('id'));
		$read = XoopsCache::write('spider_id%%'.$obj->getVar('id'), array('value' => $value));	
								
		return parent::insert($obj, $force);
	}

}
?>