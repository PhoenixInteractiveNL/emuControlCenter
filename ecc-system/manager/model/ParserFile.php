<?php
class ParserFile{

	const DIRECT = 0;
	const ZIP = 1;
	const SZIP = 2;
	
	private $type;
	private $name;
	private $namePacked;
	private $size;
	private $crc32;
	private $multiRomFiles;
	private $extension;
	
	/**
	 * @return unknown
	 */
	public function getCrc32() {
		return $this->crc32;
	}
	
	/**
	 * @param unknown_type $crc32
	 */
	public function setCrc32($crc32) {
		$this->crc32 = $crc32;
	}
	
	/**
	 * @return unknown
	 */
	public function getFileList() {
		return $this->fileList;
	}
	
	/**
	 * @param unknown_type $fileList
	 */
	public function setFileList($fileList) {
		$this->fileList = $fileList;
	}
	
	/**
	 * @return unknown
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * @param unknown_type $name
	 */
	public function setName($name) {
		$this->name = $name;
	}
	
	/**
	 * @return unknown
	 */
	public function getNamePacked() {
		return $this->namePacked;
	}
	
	/**
	 * @param unknown_type $namePacked
	 */
	public function setNamePacked($namePacked) {
		$this->namePacked = $namePacked;
	}
	
	/**
	 * @return unknown
	 */
	public function getSize() {
		return $this->size;
	}
	
	/**
	 * @param unknown_type $Size
	 */
	public function setSize($size) {
		$this->size = $size;
	}
	/**
	 * @return unknown
	 */
	public function getExtension() {
		return $this->extension;
	}
	
	/**
	 * @param unknown_type $extension
	 */
	public function setExtension($extension) {
		$this->extension = $extension;
	}
	/**
	 * @return unknown
	 */
	public function getMultiRomFiles() {
		return $this->multiRomFiles;
	}
	
	/**
	 * @param unknown_type $multiRomFiles
	 */
	public function setMultiRomFiles($multiRomFiles) {
		$this->multiRomFiles = $multiRomFiles;
	}
	
	/**
	 * @return unknown
	 */
	public function getType() {
		return $this->type;
	}
	
	/**
	 * @param unknown_type $type
	 */
	public function setType($type) {
		$this->type = $type;
	}
	
	protected function normalizePath($path){
		return str_replace("\\", "/", $path);
	}
}
?>