<?php

class md5Hash {
	
	var $length;
	
	var $seed;
	
	function __construct($length = 32, $seed = 0) {
		$this->length = $length;
		$this->seed = $seed;
	}
	
	function calc($data) {
		return md5($data);
	}
}

?>