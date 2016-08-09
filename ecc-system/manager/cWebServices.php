<?php
/*
 * Created on 29.09.2006
 *
 * Functions to interact with the emuControlCenter Webservices
 * on www.camya.com
 */
class WebServices {
	
	private $dbms;
	
	private $serviceUrl = false;
	
	
	public function __construct() {
	}
	
	// called by FACTORY
	public function setDbms($dbmsObject) {
		$this->dbms = $dbmsObject;
	}
	
	public function setServiceUrl($url) {
		$this->serviceUrl = $url;
	}
	
	public function get() {
		$data = @file_get_contents($this->serviceUrl);
		return $this->unpackData($data);
	}
	
	public function set($data) {
		$data = $this->packData($data);
		$path = $this->serviceUrl."?d=".$data;
		return $path;
	}
	
	private function packData(Array $data) {
		return base64_encode(serialize($data));
	}
	
	private function unpackData($data) {
		$data = base64_decode($data);
		if ($data = unserialize($data)) {
			return $data;
		}
		return FALSE;
	}
}
?>