<?php
require_once('items/cItem.php');

class RomMeta extends Item {
	
	// Translations
	private $translatorLangauge = false;
	private $translatorEccident = false;
	
	// UNIQUE IDENT (eccident + crc32)
	protected $eccident = false;
	protected $crc32 = false;
	
	// Fields
	protected $name = false;
	protected $year = false;
	protected $developer = false;
	protected $publisher = false;
	protected $language = array();
	protected $info = false;
	
	/**
	 * Validates the eccident+crc32 combination
	 *
	 * @return bool
	 */
	public function hasValidIdent(){
		$this->validEccident();
		$this->validCrc32();
		return !$this->hasErrors();
	}

	/**
	 * Validates the given eccident checksum
	 */
	private function validEccident(){
		if (!VALID::string($this->eccident, 10, 2, "/^[a-z0-9]*$/")) $this->addError('eccident', 'NOT_VALID');
	}
	
	/**
	 * Validates the given CRC32 checksum
	 */
	private function validCrc32(){
		if (!VALID::string($this->crc32, 8, 8, "/^[a-zA-Z0-9]*$/")) $this->addError('crc32', 'NOT_VALID');
	}
	
	/**
	 * sets an coverter array to translator
	 * the given languages to the ecc-languages
	 */
	public function translatorLanguage($translation){
		$this->translatorLangauge = $translation;
	}
	
	/**
	 * sets an coverter array to translator
	 * the given platform to the ecc-eccidents
	 */
	public function translatorEccident($translation){
		$this->translatorEccident = $translation;
	}
}
?>