<?php
require_once (dirname(__FILE__).'/class/qcp64.class.php');

class qcp64Hash {
	
	var $length;
	
	var $seed;
	
	function __construct($length = 50, $seed = 32) {
		$this->length = $length;
		$this->seed = $seed;
	}
	
	function calc($data) {
		$crc = new qcp64($data, $this->seed, $this->length);
		return $crc->crc;
	}
}

?>