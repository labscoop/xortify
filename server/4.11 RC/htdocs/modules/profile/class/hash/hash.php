<?php

class XoopsHash {
		
	var $element_length 	= 6;
	
	var $stripe 			= '-';
	
	var $sums 				= array(	'md5'		=>	'md5Hash', 
										'qcp135'	=>	'qcp135Hash', 
										'qcp64'		=>	'qcp64Hash', 
										'qcp71'		=>	'qcp71Hash', 
										'sha1'		=>	'sha1Hash'		);
	
	var $sum 				= 'qcp71'; 
	
	var $hash_seed 			= 30;
	
	var $hash_length 		= 54;
	
	var $crc_object 		= NULL;
	
	var $isDirty	 		= false;
	
	function getHash($data, $stripe = true) {
		if ($this->isDirty = true)
			$this->init();	
		if ($stripe == true) {
			return $this->stripeHash($this->crc_object->calc($data));
		}
		return $this->crc_object->calc($data);
	}
	
	function getSums() {
		return $this->sums;
	}
	
	function setSum($sum) {
		$this->sum = $sum;
		$this->isDirty = true;
	}
	
	function setHashLength($length) {
		$this->hash_length = $length;
		$this->isDirty = true;
	}
	
	function setHashSeed($seed) {
		$this->hash_seed = $seed;
		$this->isDirty = true;
	}
	
	function setElementLength($length) {
		$this->element_length = $length;
	}
	
	function setStripe($strip) {
		$this->strip = $strip;
	}
	

	/**
	 * XoopsHash Intialisation Routine
	 */
	function init() {
		if (!in_array($this->sum, array_keys($this->sums)))
			$this->sum = 'qcp71';
		if (file_exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . $this->sum  . DIRECTORY_SEPARATOR . $this->sum . '.php')) {
			require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . $this->sum  . DIRECTORY_SEPARATOR . $this->sum . '.php';
			if (class_exists($this->sums[$this->sum])) {
				$class = $this->sums[$this->sum];
				$this->crc_object = new $class($this->hash_length, $this->hash_seed);
				$this->isDirty = false;
			}
		}
	}

	/**
	 * XoopsHash Stripe Checksum
	 */
	function stripeHash($hash)
	{
		$uu = 0;
		$strip = floor(strlen($hash) / $this->element_length);
		$ret = '';
		$ret .= substr($hash, 0, $strip) . '::';
		for($i=$strip; $i<strlen($hash); $i = $i+$strip)
			$ret .= substr($hash, $i, $strip) . $this->stripe;
		if (substr($ret, strlen($ret) - 1, 1) == $this->stripe) {
			$ret = substr($ret, 0, strlen($ret) - 1);
		}
		return $ret;
	}
}