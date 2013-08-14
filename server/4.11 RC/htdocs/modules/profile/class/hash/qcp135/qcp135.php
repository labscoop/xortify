<?php
require_once (dirname(__FILE__).'/class/qcp135.class.php');

class qcp135Hash {
	
	var $length;
	
	var $seed;
	
	function __construct($length = 54, $seed = 32) {
		$this->length = $length;
		$this->seed = $seed;
	}
	
	function calc($data) {
		$crc = new qcp135($data, $this->seed, $this->length);
		return $crc->crc;
	}
}

?>