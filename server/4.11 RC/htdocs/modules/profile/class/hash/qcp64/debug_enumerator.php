<?php
// $Id: debug_enumerator.php 1.6.4 2008-08-15 13:40:00 (final) wishcraft $
//  ------------------------------------------------------------------------ //
//                    CLORA - Chronolabs Australia                           //
//                         Copyright (c) 2008                                //
//                   <http://www.chronolabs.org.au/>                         //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the SDPL Source Directive Public Licence           //
//  as published by Chronolabs Australia; either version 2 of the License,   //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Chronolab Australia        //
//  Chronolabs International PO BOX 699, DULWICH HILL, NSW, 2203, Australia  //
//  ------------------------------------------------------------------------ //
$mt=time()+microtime();
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>QCP64 Enumerator Debug Test</title>
<style type="text/css">
<!--
body,td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 14px;
	color: #006699;
}
body {
	background-color: #FFFFCC;
	background-image: url(QCP64-background.png);
	background-repeat: repeat;
	margin-left: 35px;
	margin-top: 45px;
	margin-right: 35px;
	margin-bottom: 80px;
}
-->
</style></head>

<body>
<div style="float:left; "><a style="border:hidden;" href="http://www.chronolabs.org.au/articles/Source-Code/QCP64-Variable-Checksum/52,"><img src="QCP64-Logo.png" /></a></div>

<?php if (!isset($_POST['charstring'])&&!isset($_POST['seed'])&&!isset($_POST['length'])) { ?>
<form id="form1" name="form1" method="post" action="" target="_blank">
  <label>Seed (0-255)
  <input name="seed" type="text" id="seed" value="128" size="5" maxlength="3" />
  </label>
  <label>Length
  <input name="length" type="text" id="length" value="28" size="5" maxlength="3" />
  </label>
  <label>Characters to test
  <input name="charstring" type="text" id="charstring" value="" size="45" maxlength="2600" />
  </label>
  <label>
  <input type="submit" name="Submit" id="Submit" value="Submit" />
  </label>
</form>
<?php } ?>
<div style="clear:both">&nbsp;</div>
<h2>Debug Examples:</h2>
<ol>
  <li>
    Test Base (<a href="debug_base.php">debug_base.php</a>)
  </li>
  <li>
    Test Leaver (<a href="debug_leaver.php">debug_leaver.php</a>)<br />
  </li>
  <li>
    Index (<a href="index.php">index.php</a>)
  </li>

</ol>
<pre>

<?php 
	if (isset($_POST['charstring'])&&isset($_POST['seed'])&&isset($_POST['length']))
	{
				class qcp64
		{
		
		}
	
		require ('class/qcp64.base.php');
		require ('class/qcp64.enumerator.php');
		
		$qcp64_base = new qcp64_base((int)$_POST['seed']);
		$enumerator = new qcp64_enumerator($qcp64_base, $_POST['length']);
		
		for ($i=1; $i<strlen($_POST['charstring']); $i++)
		{
			$enum_calc = $enumerator->enum_calc(substr($_POST['charstring'],$i,1),$enum_calc,true);
		}
		echo "Milliseconds: ".(abs((time()+microtime())-$mt)*1000)."\n";
		print_r($enum_calc);
	}
?>

</pre>

</body>
</html>
