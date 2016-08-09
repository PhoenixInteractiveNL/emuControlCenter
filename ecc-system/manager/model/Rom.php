<?php
require_once 'Item.php';
class Rom extends Item {
	
	/**
	 * RomFile object
	 * This object contains all available rom file infos
	 * @var RomFile
	 */
	private $romFile;
	
	/**
	 * RomMeta object
	 * This object contains all available rom meta infos
	 * @var RomMeta
	 */
	private $romMeta;
	
	/**
	 * RomAudit object
	 * This object contains all available rom audit infos for multi file roms
	 * @var RomAudit
	 */
	private $romAudit;
	
	public function getCompositeId(){
		return $this->romFile->getId()."|".$this->romMeta->getId();
	}
	
	/**
	 * Search for the available name in the RomMeta and RomFile object
	 * If found, use name from RomMeta!
	 * 
	 * Used to show the user also a name, if no meta info available!
	 *
	 * @return string found best name
	 */
	public function getName() {
		return ($this->romMeta->getName()) ? $this->romMeta->getName() : $this->romFile->getPlainFileName();
	}

	public function getFormatedName(){
		return ($this->romMeta->getName()) ? $this->romMeta->getFormatedName() : $this->romFile->getRomFilenamePlain();
	}
	
	/**
	 * Search for the available SystemIdent in the RomFile and RomMeta object
	 * If found, use SystemIdent from RomFile!
	 * 
	 * It could be, that in list only the file or only the meta informations
	 * are available. So this function search for the available one in this case!
	 *
	 * @return string found available SystemIdent
	 */
	public function getSystemIdent() {
		return ($this->romMeta->getSystemIdent()) ? $this->romMeta->getSystemIdent() : $this->romFile->getSystemIdent();
	}

	/**
	 * Search for the available crc32 checksum in the RomFile and RomMeta object
	 * If found, use crc32 checksum from RomFile!
	 *
	 * It could be, that in list only the file or only the meta informations
	 * are available. So this function search for the available one in this case!
	 * 
	 * @return string found available crc32 checksum
	 */
	public function getCrc32() {
		return ($this->romFile->getCrc32()) ? $this->romFile->getCrc32() : $this->romMeta->getCrc32();
	}
	
	/**
	 * Get the RomFile object
	 * 
	 * @return RomFile
	 */
	public function getRomFile() {
		return $this->romFile;
	}
	
	/**
	 * Set the RomFile object
	 *  
	 * @param RomFile $romFile
	 */
	public function setRomFile(RomFile $romFile) {
		$this->romFile = $romFile;
	}
	
	/**
	 * Get the RomMeta object
	 * 
	 * @return RomMeta
	 */
	public function getRomMeta() {
		return $this->romMeta;
	}
	
	/**
	 * Set the RomMeta object
	 * 
	 * @param RomMeta $romMeta
	 */
	public function setRomMeta(RomMeta $romMeta) {
		$this->romMeta = $romMeta;
	}
	
	/**
	 * Get the RomAudit object
	 * 
	 * @return RomAudit
	 */
	public function getRomAudit() {
		return $this->romAudit;
	}
	
	/**
	 * Set the RomAudit object
	 * 
	 * @param RomAudit $romAudit
	 */
	public function setRomAudit(RomAudit $romAudit) {
		$this->romAudit = $romAudit;
	}

	
}
?>