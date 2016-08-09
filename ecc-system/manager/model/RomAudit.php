<?php
class RomAudit extends Item {
	
	protected $id;
	protected $mameDriver;
	protected $isMatch;
	protected $fileName;
	protected $isValidFileName;
	protected $isValidNonMergedSet;
	protected $isValidSplitSet;
	protected $isValidMergedSet;
	protected $hasTrashfiles;
	protected $cloneOf;
	protected $romOf;
	
	/**
	 * Array of all member variables used to create an crc32 checksum
	 * to compare rom metadata state changes getChecksum()
	 *
	 * @var Array of included member variables
	 */
	protected $checksumInclude = array(
		'id',
		'mameDriver',
		'isMatch',
		'fileName',
		'isValidFileName',
		'isValidNonMergedSet',
		'isValidSplitSet',
		'isValidMergedSet',
		'hasTrashfiles',
		'cloneOf',
		'romOf',
	);

	public function finalize(){

		// set checksum for this data
		#$this->getChecksum();
	}
	
	/**
	 * Creates an array using the db result from TreeviewData getSearchResults()
	 *
	 * @param array $dbEntry of db entries from TreeviewData getSearchResults()
	 */
	public function fillFromDatabase($dbEntry){
		
		// set the identifier
		$this->setId($dbEntry['fa_fDataId']);
		$this->setMameDriver($dbEntry['fa_mameDriver']);
		
		$this->setIsMatch($dbEntry['fa_isMatch']);
		$this->setFileName($dbEntry['fa_fileName']);
		
		$this->setIsValidFileName($dbEntry['fa_isValidFileName']);
		$this->setIsValidNonMergedSet($dbEntry['fa_isValidNonMergedSet']);
		$this->setIsValidSplitSet($dbEntry['fa_isValidSplitSet']);
		$this->setIsValidMergedSet($dbEntry['fa_isValidMergedSet']);
		
		$this->setHasTrashfiles($dbEntry['fa_hasTrashfiles']);
		
		$this->setCloneOf($dbEntry['fa_cloneOf']);
		$this->setRomOf($dbEntry['fa_romOf']);
	}
	
}
?>