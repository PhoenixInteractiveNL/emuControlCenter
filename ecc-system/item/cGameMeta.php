<?php
require_once('item/cItem.php');


$itemConfig = array(
	'type' => 'ROM',
	'categories' => array(
		'cat1',
		'cat2',
	),
	'storageTypes' => array(
		'No storage possible!',
		'Disk',
	),
);

/**
 * default castings
 * false == cast to 0
 * 0 == cast to 0
 * '' == cast to 'string'
 * 'NULL' == cast to NULL
 */
class GameMeta extends Item {
	
	public $id = 0;
	public $eccident = '';
	public $crc32 = '';
	
	public $name = '';
	
	public $category = '';				#
	
	public $developer = '';				#
	public $publisher = '';				#
	
	public $musician = '';				#
	public $programmer = '';			#
	public $graphic = '';				#
	
	public $languages = array();		# array of supported languages
	
	public $year = '';					# 1900 - now
	public $screennorm = '';			# PAL / NTSC
	public $usk = 'NULL';				# 1-21

	public $multiplayer = 'NULL';		# NO - 99
	public $netplay = 'NULL';			# NO - 99
	public $storageType = 'NULL';		# Type of storage media
		
	public $extension = '';				# the default file extension

	/*ROM SPECIFIC*/
	
	public $knownGoodDump = 'NULL';		# NO, YES
	public $goodName = '';				# Name from goodtools
	public $romIdName = '';				# Name from romId
	public $userTranslation = 'NULL';	# NO, YES
	public $trained = 'NULL';			# NO - 10
	public $introHack = 'NULL';			# NO, YES
	
	public $importDate = 'NULL';		# timestamp Imported form romdb or datfile
	public $changeDate = 'NULL';		# Imported form romdb or datfile
	
	public function __construct(){}
	
	public function isValid() {
		if (!$this->eccident) $this->addError('eccident', 'missing_eccident');
		if (!$this->crc32) $this->addError('crc32', 'missing_crc32');
		if (!$this->name) $this->addError('name', 'missing_name');
		
		print_r($this->getErrors());
		return $this->hasErrors();
	}

}
?>