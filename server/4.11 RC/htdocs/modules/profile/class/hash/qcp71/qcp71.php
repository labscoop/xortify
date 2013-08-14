<?php
require_once (dirname(__FILE__).'/class/qcp71.class.php');

class qcp71Hash {
	
	var $length;
	
	var $seed;
	
	function __construct($length = 53, $seed = 32) {
		$this->length = $length;
		$this->seed = $seed;
	}
	
	function calc($data) {
		$crc = new qcp71($data, $this->seed, $this->length);
		return $crc->crc;
	}
}

?>