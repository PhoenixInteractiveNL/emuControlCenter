<?php
/**
 * Sega genesis "a26" validator.
 * Used to dispatch valid "bin" atari 2600 bin files
 * 
 * @return boolean
 */
class ValidatorA26 {
	
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
		# add code here
		
		// return valid state
		return $this->validState;
	}
}
?>