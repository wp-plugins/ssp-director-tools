<?php

require_once 'config.php';

class Testing {
	
	public $key;
	public $path;
	public $secret;

	function __construct($key, $path, $secret) {
		$this->key = $key;
		$this->path = $path;
		$this->secret = $secret;
	}
	
	function is_valid() {
		if($this->key === $sspd_api_key && $this->path === $sspd_api_path && $this->secret === $sspdt_secret) {
			return true;
		} else {
			return false;
		}
	}
	
}

?>