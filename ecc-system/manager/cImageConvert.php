<?php
abstract class ImageConvert {
	
//	private $validEccIdents = array();

	public $eccIdent = false;

	public $imageFolderSource = false;
	public $imageFolderDestination = false;
	
	public $folderTranslate = array();
	public $imageTank = array();
	
	public function __construct() {
		print get_class($this);
	}
	
	public function setDestinationFolder($destinationFolder) {
		$this->imageFolderDestination = $destinationFolder;
	}
	
	public function setSourceFolder($sourceFolder) {
		if (!is_dir($sourceFolder)) return false;
		$this->imageFolderSource = $sourceFolder;
	}
	
	public function covertImages() {
		return $this->processImageDirectory($this->imageFolderSource);
	}
	
	public function createEccImageName($eccIdent, $crc32, $imageType, $fileExtension) {
		if (!Valid::eccident($eccIdent) || !Valid::crc32($crc32)) return false;
		$fileName = 'ecc_'.strtolower($eccIdent).'_'.$crc32.'_'.$imageType.'.'.$fileExtension;
		return $fileName;
	}
	
	abstract public function processImageDirectory($directory);
}
?>