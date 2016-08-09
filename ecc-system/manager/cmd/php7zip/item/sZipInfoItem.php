<?php
require_once 'sZipItem.php';

class sZipInfoItem extends sZipItem {
	
	private $packedSize;
	private $crc;
	private $method;
	private $block;
	
	/**
	 * @return unknown
	 */
	public function getPath() {
		return parent::getName();
	}
	
	/**
	 * @param unknown_type $block
	 */
	public function setPath($path) {
		parent::setName($path);
	}
	
	/**
	 * @return unknown
	 */
	public function getBlock() {
		return $this->block;
	}
	
	/**
	 * @param unknown_type $block
	 */
	public function setBlock($block) {
		$this->block = $block;
	}
	
	/**
	 * @return unknown
	 */
	public function getCrc() {
		return $this->crc;
	}
	
	/**
	 * @param unknown_type $crc
	 */
	public function setCrc($crc) {
		$this->crc = $crc;
	}
	
	/**
	 * @return unknown
	 */
	public function getMethod() {
		return $this->method;
	}
	
	/**
	 * @param unknown_type $method
	 */
	public function setMethod($method) {
		$this->method = $method;
	}
	
	/**
	 * @return unknown
	 */
	public function getPackedSize() {
		return $this->packedSize;
	}
	
	/**
	 * @param unknown_type $packedSize
	 */
	public function setPackedSize($packedSize) {
		$this->packedSize = $packedSize;
	}
}
?>