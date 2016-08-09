<?php
class FileParserZip implements FileParser {
	
	private $_file_ext = false;

	public function __construct($file_ext=false) {
		$this->_file_ext = $file_ext;
	}
	
	public function hasRipHeader(){
		return false;
	}
	
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
		
		$ret['MDATA'] = FileIO::getFileDataFromZip($file_name);
		
		# use external parser to get the right crc32 for larger files!
		# only usable for platforms withou offsets!!!!
		if (filesize($file_name) >= ExtParserTriggerSize) {
			$ret['FILE_CRC32'] = FileIO::getExternalCrc32($file_name, 1);
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
