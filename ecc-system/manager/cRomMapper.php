<?php
class RomMapper{
	
	public function storeRomMeta(Rom $rom){
		
		$romMeta = $rom->getRomMeta();
		$romFile = $rom->getRomFile();
		
		// setup needed data for insert
		$romMeta->setSystemIdent($rom->getSystemIdent());
		$romMeta->setCrc32($rom->getCrc32());
		$romMeta->setExtension($romFile->getFileExtension());
		$romMeta->setFilesize($romFile->getFileSize());
		
		// if no name is set, use the plain filename
		if(!$romMeta->getName()) $romMeta->setName($romFile->getRomFilenamePlain());
		
		return $romMeta->store($this->dbms);
	}
	
	/**
	 * setter used from FACTORY
	 * @param object $dbmsObject
	 */
	public function setDbms($dbmsObject) {
		$this->dbms = $dbmsObject;
	}
}
?>