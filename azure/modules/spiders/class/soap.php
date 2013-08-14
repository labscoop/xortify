<?php
define('XORTIFY_API_LOCAL', 'http://www.xortify.com/soap/');
define('XORTIFY_API_URI', 'http://www.xortify.com/soap/');

foreach (get_loaded_extensions() as $ext){
	if ($ext=="soap")
		$nativesoap=true;
}

if ($nativesoap==true)
	define('XOOPS_SOAP_LIB', 'PHPSOAP');

class SOAPSpidersExchange {

	var $soap_client;
	var $soap_xoops_username = '';
	var $soap_xoops_password = '';
	var $refresh = 600;
	
	function SOAPSpidersExchange () {
	
		$module_handler =& xoops_gethandler('module');
		$config_handler =& xoops_gethandler('config');
		$xoModule = $module_handler->getByDirname('spiders');
		$configs = $config_handler->getConfigList($xoModule->mid());
		
		$this->soap_xoops_username = $configs['xortify_username'];
		$this->soap_xoops_password = $configs['xortify_password'];
		$this->refresh = $configs['xortify_records'];
			
		switch (XOOPS_SOAP_LIB){
		case "PHPSOAP":
			$this->soap_client = new soapclient(NULL, array('location' => XORTIFY_API_LOCAL, 'uri' => XORTIFY_API_URI));
			break;
		}
	}
	
	function sendSpider($spider) {

		switch (XOOPS_SOAP_LIB){
		case "PHPSOAP":
			$result = $this->soap_client->__soapCall('spider',
				array(      "username"	=> 	$this->soap_xoops_username, 
							"password"	=> 	$this->soap_xoops_password, 
							"spider"	=> 	$spider	) );
			break;
		}
			
		return $result;	
	}
	
	function sendStatistic($statistic) {

		switch (XOOPS_SOAP_LIB){
		case "PHPSOAP":
			$result = $this->soap_client->__soapCall('spiderstat',
				array(      "username"	=> 	$this->soap_xoops_username, 
							"password"	=> 	$this->soap_xoops_password, 
							"statistic"	=> 	$statistic	) );
			break;
		}
			
		return $result;	
	}
	
	function getSpiders() {
			
		switch (XOOPS_SOAP_LIB){
		case "PHPSOAP":
			$result = $this->soap_client->__soapCall('spiders',
						array(  "username"	=> 	$this->soap_xoops_username, 
								"password"	=> 	$this->soap_xoops_password));
			break;
		}
			
		return $result['robots'];		
	}
	
	function getSEOLinks() {
			
		switch (XOOPS_SOAP_LIB){
		case "PHPSOAP":
			$result = $this->soap_client->__soapCall('seolinks',
						array(  "username"	=> 	$this->soap_xoops_username, 
								"password"	=> 	$this->soap_xoops_password));
			break;
		}
			
		return $result['seolinks'];		
	}
}


?>