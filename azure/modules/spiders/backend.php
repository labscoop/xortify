<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 *  Xoops Backend
 *
 * @copyright       Chronolabs Coopertive (Australia)  http://web.labs.coop
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         kernel
 * @since           2.0.0
 * @author          Kazumi Ono <onokazu@xoops.org>
 * @version         $Id: backend.php 3538 2009-08-31 14:16:34Z trabis $
 */
include dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'mainfile.php';

if ($GLOBALS['xoopsModuleConfig']['htaccess']==true) {
	$url = XOOPS_URL.'/spiders/'.basename($_SERVER['PHP_SELF']).'?'.$_SERVER['QUERY_STRING'];
	if ($_SERVER['REQUEST_URI'].'?'.$_SERVER['QUERY_STRING']!=$url && strpos($_SERVER['REQUEST_URI'], 'odules/')>0) {
		header( "HTTP/1.1 301 Moved Permanently" ); 
		header('Location: '.$url);
		exit(0);
	}
}

$GLOBALS['xoopsLogger']->activated = false;
if (function_exists('mb_http_output')) {
    mb_http_output('pass');
}
header('Content-Type:text/xml; charset=utf-8');

include_once $GLOBALS['xoops']->path('class/template.php');
$tpl = new XoopsTpl();
$tpl->xoops_setCaching(2);
$tpl->xoops_setCacheTime(30);
$xoopsCachedTemplateId = 'mod_'.$xoopsModule->getVar('dirname').'|'.md5(str_replace(XOOPS_URL, '', $GLOBALS['xoopsRequestUri'])).'%'.(!isset($_REQUEST['twit'])?'0':'1');

if (!$tpl->is_cached('db:system_rss.html', $xoopsCachedTemplateId)) {
    xoops_load('XoopsLocal');
    $tpl->assign('channel_title', XoopsLocal::convert_encoding(htmlspecialchars($xoopsConfig['sitename'], ENT_QUOTES)));
    $tpl->assign('channel_link', XOOPS_URL . '/');
    $tpl->assign('channel_desc', XoopsLocal::convert_encoding(htmlspecialchars($xoopsConfig['slogan'], ENT_QUOTES)));
    $tpl->assign('channel_lastbuild', formatTimestamp(time(), 'rss'));
    $tpl->assign('channel_webmaster', $xoopsConfig['adminmail']);
    $tpl->assign('channel_editor', $xoopsConfig['adminmail']);
    $tpl->assign('channel_category', 'News');
    $tpl->assign('channel_generator', 'XOOPS');
    $tpl->assign('channel_language', _LANGCODE);
    $tpl->assign('image_url', XOOPS_URL . '/images/logo.png');
    $dimention = getimagesize(XOOPS_ROOT_PATH . '/images/logo.png');
    if (empty($dimention[0])) {
        $width = 88;
    } else {
        $width = ($dimention[0] > 144) ? 144 : $dimention[0];
    }
    if (empty($dimention[1])) {
        $height = 31;
    } else {
        $height = ($dimention[1] > 400) ? 400 : $dimention[1];
    }
    $tpl->assign('image_width', $width);
    $tpl->assign('image_height', $height);
   	$statistics_handler = xoops_getmodulehandler('statistics','spiders');
   	$sql = array();
   	$num = isset($_REQUEST['num'])?intval($_REQUEST['num']):35;
   	$sql['0'] = "SELECT DISTINCT id FROM ".$GLOBALS['xoopsDB']->prefix('spiders_statistics')." WHERE id <> 0 ORDER BY RAND() LIMIT ".$num;
   	$sql['1'] = "SELECT COUNT(DISTINCT id) FROM ".$GLOBALS['xoopsDB']->prefix('spiders_statistics')."  WHERE id <> 0 ORDER BY RAND() DESC LIMIT ".$num;
   	$result = $GLOBALS['xoopsDB']->queryF($sql['0']);
	list($total) = $GLOBALS['xoopsDB']->fetchRow($GLOBALS['xoopsDB']->queryF($sql['1']));
	$number = intval(floor($num/$total));
	if ($number==0) $number=1;
    while($row = $GLOBALS['xoopsDB']->fetchArray($result)) {
    	$criteria = new Criteria('id', $row['id']);
    	$criteria->setSort('`when`');
    	$criteria->setOrder('DESC');
    	$criteria->setLimit($number);        
		$statistics = $statistics_handler->getObjects($criteria);
		foreach($statistics as $id=>$statistic) {
			$story = $statistic->getStory();
			$tpl->append('items', array(
				'title' => XoopsLocal::convert_encoding(htmlspecialchars($story['title'], ENT_QUOTES)) ,
				'link' => $story['url'],
				'guid' => $story['url'],
				'category' => $story['category'],
				'pubdate' => formatTimestamp($statistic->getVar('when'), 'rss') ,
				'description' => XoopsLocal::convert_encoding(htmlspecialchars($story['story'], ENT_QUOTES))
				)
			);
		}
	
	}
}
$tpl->display('db:system_rss.html');
?>