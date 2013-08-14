<?php
	include dirname(__FILE__).'/include/common.php';
	?>
	<h1><?php echo (isset($_SESSION['xortify']['xoops_pagetitle'])?$_SESSION['xortify']['xoops_pagetitle']:'You are banned!'); ?></h1>
	<p align="center" style='font-size:1.24em'}><?php echo (isset($_SESSION['xortify']['description'])?$_SESSION['xortify']['description']:'You have been banned by one of our affilate honeypots!'); ?></p>
	<p align="center" style='font-size:1.34em; border:dashed; margin:10 10 10 10; background-color:#feedcd;'}><u>Ban Provider:</u>&nbsp;<?php echo (isset($_SESSION['xortify']['provider'])?$_SESSION['xortify']['provider']:'Unknown Provider!'); ?></p>
	<p align="center" style='font-size:1.14em; border:dashed; margin:10 10 10 10; background-color:#feedad;'}><u>Xortify Version:</u>&nbsp;<?php echo (isset($_SESSION['xortify']['version'])?$_SESSION['xortify']['version']:'Unknown Provider!'); ?><br/><u>Agent:</u>&nbsp;<?php echo (isset($_SESSION['xortify']['agent'])?$_SESSION['xortify']['agent']:'Unknown User Agent!'); ?><br/><u>PHP Version:</u>&nbsp;<?php echo (PHP_VERSION); ?></p>
	<?php 
?>
