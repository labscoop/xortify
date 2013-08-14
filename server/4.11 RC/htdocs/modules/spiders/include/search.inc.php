<?php

function spiders_search($queryarray, $andor, $limit, $offset, $userid)
{
	xoops_loadLanguage('modinfo', 'spiders');
	$ret = array();
	$statistics_handler = xoops_getmodulehandler('statistics', 'spiders');
	$spiders_user_handler = xoops_getmodulehandler('spiders_user', 'spiders');	
	$criteria = array();
	if ($queryarray == ''||$userid>0){
		$criteria = new CriteriaCompo(new Criteria('uid', $userid));
		$spiders_user_obj = $spiders_user_handler->getObjects($criteria, false);
		if (is_object($spiders_user_obj[0])) {
			$keywords= '';
			$hightlight_key = '';
			$criteria_final = new CriteriaCompo(new Criteria('id', $spiders_user_obj[0]->getVar('spider_id')), "AND");
		} else {
			$keywords= '';
			$hightlight_key = '';
			$criteria_final = new CriteriaCompo(new Criteria('1', '2'), "AND");
		}
	} else {
		$criteria_final = new CriteriaCompo();
		foreach($queryarray as $id => $keyword) {
			$criteria[$id] = new CriteriaCompo(new Criteria('`ip4`', $keyword), 'OR');
			$criteria[$id]->add(new Criteria('`uri`', $keyword), 'OR');
			$criteria[$id]->add(new Criteria('`useragent`', $keyword), 'OR');
			$criteria[$id]->add(new Criteria('`netaddy`', $keyword), 'OR');
			$criteria[$id]->add(new Criteria('`ip`', $keyword), 'OR');
		}
		foreach($criteria as $id => $crit) {
			$criteria_final->add($criteria[$id], $andor);
		}
	}	

	$criteria_final->setLimit($limit);
	$criteria_final->setStart($offset);
	$criteria_final->setSort('`when`');
	$criteria_final->setOrder('DESC');
	$statsObj = $statistics_handler->getObjects($criteria_final, false);
	
	foreach ($statsObj as $member_id => $result) {
		$item['image'] = "images/spiders.png";
		$item['link'] = 'go.php?uri='.urlencode(str_replace('\\/', '/', $result->getVar('uri')));
		$criteria = new CriteriaCompo(new Criteria('spider_id', $result->getVar('id')));
		$spiders_user_obj = $spiders_user_handler->getObjects($criteria, false);
		if (is_object($spiders_user_obj[0])) {
			$item['uid'] = $spiders_user_obj[0]->getVar('uid');
		}
		$item['title'] = "Spider Crawled: " . $result->getVar('sitename') . ' : '.$result->getVar('netaddy');
		$item['time'] = $result->getVar('when');
		
		$ret[] = $item;
		unset($item);
	}	
	
	return $ret;
}

?>