<?php
/**
 * Sega genesis "bin" validator.
 * Used to dispatch valid "bin" genesis bin files
 * 
 * @return boolean
 */
class ValidatorGen {
	
	private $fHdl = false;
	private $validState = false;
	
	/**
	 * Setup the filehandle needed to get header informations
	 *
	 * @param filehandle $fHdl
	 */
	public function setFileHandle($fHdl) {
		$this->fHdl = $fHdl;
	}
	
	/**
	 * Validate a file by gathering data from the file header
	 * To get the header infos, this method uses
	 * the static ecc FileIO method
	 * FileIO::ecc_read($fileHandle, $start, $lenght, $type)
	 * 
	 * @return boolean
	 */
	public function validate() {
		
		// Get data to validate the file!
		$internalIdent = FileIO::ecc_read($this->fHdl, 261, 10, false);
		$internalIdent = trim($internalIdent);
		if (
			$internalIdent == 'MEGA DRIVE' ||
			$internalIdent == 'GENESIS' ||
			$internalIdent == 'MEGADRIVE' ||
			$internalIdent == '32X'
		) {
			$this->validState = true;
		}
	
		// return valid state
		return $this->validState;
	}
}
?>