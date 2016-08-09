<?php
require_once 'sZipItem.php';

class sZipListItem extends sZipItem {

	private $compressed;
	
	/**
	 * Returns the attr field from commandline output
	 * 
	 * D....	Directory
	 * ....A	File
	 * .....	File
	 * 
	 * @return string of atributes 5 char
	 */
	public function getAttr() {
		return parent::getAttributes();
	}
	
	/**
	 * Setter for attr
	 * 
	 * @param string $attr 5 char
	 */
	public function setAttr($attr) {
		parent::setAttributes($attr);
	}
	
	/**
	 * @return string
	 */
	public function getCompressed() {
		return $this->compressed;
	}
	
	/**
	 * @param string $compressed
	 */
	public function setCompressed($compressed) {
		$this->compressed = $compressed;
	}
	
	/**
	 * @return string
	 */
	public function getDate() {
		return parent::getModified();
	}
	
	/**
	 * @param string $date
	 */
	public function setDate($date) {
		parent::setModified($date);
	}
}
?>