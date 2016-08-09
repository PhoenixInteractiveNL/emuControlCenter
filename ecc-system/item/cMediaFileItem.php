<?php
require_once('item/cItem.php');

/**
 * default castings
 * false == cast to 0
 * 0 == cast to 0
 * '' == cast to 'string'
 * 'NULL' == cast to NULL
 */
class MediaFileItem extends Item {
	
	public $id = 0;
	public $eccident = '';
	public $crc32 = '';
	public $name = '';
	public $extension = '';
	public $path = '';
	public $pathPack = '';
	public $sizeKb = false;
	public $cdate = 'NULL';
	
	public $_meta;
	
	public function __construct(){}
	
	public function isValid() {
		if (!$this->eccident) $this->addError('eccident', 'missing_eccident');
		if (!$this->crc32) $this->addError('crc32', 'missing_crc32');
		
		print_r($this->getErrors());
		return $this->hasErrors();
	}

}
?>