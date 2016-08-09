<?php
/*
* Nintendo N64 Parser
* Dieser Parser kann informationen aus N64-Roms
* extrahieren.
*/
class FileParserGeneric implements FileParser {
	
	private $_file_ext = false;
	
	/*
	*
	*/
	public function __construct($file_ext=false) {
		$this->_file_ext = $file_ext;
	}
	
	/*
	*
	*/
	public function parse($fhdl, $file_name, $file_name_direct=false, $file_name_packed=false) {
		
		$ret = array();
		
		// Infos zur datei holen
		$file_info = FileIO::ecc_file_get_info($file_name);
		$ret['FILE_NAME'] = $file_info['NAME'];
		$ret['FILE_PATH'] = $file_name_direct;
		$ret['FILE_PATH_PACK'] = $file_name_packed;
		$ret['FILE_EXT'] = (isset($this->_file_ext)) ? strtoupper($this->_file_ext) : strtoupper($file_info['EXT']) ;
		$fstat = fstat($fhdl);
		$ret['FILE_SIZE'] = $fstat['size'];
		
		# use fsum to get the right crc32 for larger files!
		# only usable for platforms withou offsets!!!!
		if (filesize($file_name) >= SLOW_CRC32_PARSING_FROM) {
			$ret['FILE_CRC32'] = FileIO::getFsumCrc32($file_name, 1);
		}
		else{
			$ret['FILE_CRC32'] = FileIO::ecc_get_crc32_from_string(FileIO::ecc_read_file($fhdl, false, false, $file_name));
		}
		
		$ret['FILE_MD5'] = NULL;
		$ret['FILE_VALID'] = true;
		
		while (gtk::events_pending()) gtk::main_iteration();
		
		return $ret;
	}
}
?>
