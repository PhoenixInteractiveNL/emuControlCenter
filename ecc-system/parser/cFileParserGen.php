<?php
class FileParserGen implements FileParser {
	
	private $eccIdent = 'gen';
	private $fHdl = false;
	private $fileData = array();
	private $fileName = false;
	private $fileNameDirect = false;
	private $fileNamePacked = false;
	
	
	public function parse($fhdl, $file_name, $file_name_direct=false, $file_name_packed=false) {
		
		$this->fHdl = $fhdl;
		$this->fileName = $file_name;
		$this->fileNameDirect = $file_name_direct;
		$this->fileNamePacked = $file_name_packed;
		
		// get general data for all roms
		$this->setGeneralFileData();
		
		// If invalid file, return this invalid data
		if (!$this->isValid()) return $this->fileData;
		
		// redraw gui!
		$this->whilePending();
		
		// Get all binary data from file
		$file_content = FileIO::ecc_read_file($this->fHdl, false, false, $this->fileName);

		// redraw gui!
		$this->whilePending();
		
		// create crc32 from string
		$this->fileData['FILE_CRC32'] = FileIO::ecc_get_crc32_from_string($file_content);
		
		return $this->fileData;
	}
	
	private function isValid() {
		
		// Get data to validate the file!
		$internalIdent = FileIO::ecc_read($this->fHdl, 261, 10, false);
		$internalIdent = trim($internalIdent);
		if (
			!(
				$internalIdent == 'MEGA DRIVE' ||
				$internalIdent == 'GENESIS' ||
				$internalIdent == 'MEGADRIVE' ||
				$internalIdent == 'MEGA_DRIVE' ||
				$internalIdent == '32X'
			)
		) {
			$this->fileData['FILE_VALID'] = false;
			print $internalIdent."\n";
			return false;
		}
		return true;
	}
	
	private function setGeneralFileData() {
		
		// Infos zur datei holen
		$this->fileData['FILE_VALID'] = true;
		$file_info = FileIO::ecc_file_get_info($this->fileName);
		$this->fileData['FILE_NAME'] = $file_info['NAME'];
		$this->fileData['FILE_PATH'] = $this->fileNameDirect;
		$this->fileData['FILE_PATH_PACK'] = $this->fileNamePacked;
		$this->fileData['FILE_EXT'] = $this->eccIdent;
		$fstat = fstat($this->fHdl);
		$this->fileData['FILE_SIZE'] = $fstat['size'];
		$this->fileData['FILE_CRC32'] = NULL;
		$this->fileData['FILE_MD5'] = NULL;
	}
	
	private function whilePending() {
		while (gtk::events_pending()) gtk::main_iteration();
	}
}
?>
