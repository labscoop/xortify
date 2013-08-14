<?php

class sha1Hash {
	
	var $length;
	
	var $seed;
	
	function __construct($length = 44, $seed = 0) {
		$this->length = $length;
		$this->seed = $seed;
	}
	
	function calc($data) {
		return sha1($data);
	}
}

?>