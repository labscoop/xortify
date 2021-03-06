<?php
$mt=time()+microtime();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>QCP71 Variable None Reversible Checksum</title>
<style type="text/css">
<!--
body,td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 14px;
	color: #006699;
}
body {
	background-color: #FFFFCC;
	background-image: url(QCP71-background.png);
	background-repeat: repeat;
	margin-left: 35px;
	margin-top: 45px;
	margin-right: 35px;
	margin-bottom: 80px;
}
-->
</style></head>

<body>
<div style="float:left; padding-right: 10px; "><a style="border:hidden;" href="http://www.chronolabs.org.au/articles/Source-Code/QCP71-Variable-Checksum/52,"><img src="QCP71-Logo.png" /></a></div>

<?php if (!isset($_POST['charstring'])&&!isset($_POST['seed'])&&!isset($_POST['length'])) { ?>
<form id="form1" name="form1" method="post" action="" target="_blank">
  <label>Seed (0-255)
  <input name="seed" type="text" id="seed" value="128" size="5" maxlength="3" />
  </label>
  <label>Length
  <input name="length" type="text" id="length" value="28" size="8" maxlength="7" />
  </label>
  <label>Characters to test
  <input name="charstring" type="text" id="charstring" value="" size="21" maxlength="32" />
  </label>
  <label>
  <input type="submit" name="Submit" id="Submit" value="Submit" />
  </label>
  <label>
  <input name="debug" type="checkbox" id="debug" value="1" />
  Show Debug</label>
</form>
<?php } ?>
<div style="clear:both">&nbsp;</div>
<h2>Debug Examples:</h2>
<ol>
  <li>
    Test Base (<a href="debug_base.php">debug_base.php</a>)
  </li>
  <li>
    Test Enumerator (<a href="debug_enumerator.php">debug_enumerator.php</a>)
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
				require ('class/qcp71.class.php');
		set_time_limit(120);
		$crc = new qcp71($_POST['charstring'], $_POST['seed'], $_POST['length']);

		echo "Seed: ".$crc->seed."\n";
		echo "Lenght: ".$crc->length."\n";
		echo "Milliseconds: ".(abs((time()+microtime())-$mt)*1000)."\n\n";
		echo "Data sha1: ".sha1($_POST['charstring'])."\n";
		echo "Data md5: ".md5($_POST['charstring'])."\n";		
		echo "QCP71 sha1: ".sha1($crc->crc)."\n";
		echo "QCP71 md5: ".md5($crc->crc)."\n";		

		echo '</pre>'."\n\n".'<h2>QCP71 Checksum</h2>'."\n".'<p> <textarea name="textarea" id="textarea" cols="120" rows="24">'.$crc->crc.'</textarea></p>';
		if ($_POST['debug']==1)
		{
			echo "<h3>Debug</h3><pre>";
			print_r($crc);
		}
	}
?>

</pre>
</body>
</html>
