<?php
	include('../../mainfile.php');
	
	$uri = isset($_REQUEST['uri'])?$_REQUEST['uri']:'';
	
	if ($url='') {
		header( "HTTP/1.1 301 Moved Permanently" ); 
		header('Location: '.XOOPS_URL);
		exit(0);
	}

	header( "HTTP/1.1 301 Moved Permanently" ); 
	header('Location: '.$uri);

?>