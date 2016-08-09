<?php
/*
* Mp3 MP3-Audio ID3v1 Tag-Parser
* Dieser Parser kann informationen aus Mp3-Files
* extrahieren.
*/
class FileParserMp3 implements FileParser {
	
	public function __construct() {}
	
	public function hasRipHeader(){
		return true;
	}
	
	public function parse($fhdl, $file_name, $file_name_direct=false, $file_name_packed=false) {
		
		$ret = array();
		
		// Infos zur datei holen
		$file_info = FileIO::ecc_file_get_info($file_name);
		$ret['FILE_NAME'] = $file_info['NAME'];
		$ret['FILE_PATH'] = $file_name_direct;
		$ret['FILE_PATH_PACK'] = $file_name_packed;
		$ret['FILE_EXT'] = 'MP3';
		$fstat = fstat($fhdl);
		$ret['FILE_SIZE'] = $fstat['size'];
		
		$offset = -128;
		$tag = FileIO::ecc_read($fhdl, $offset, 3, false);		
		
		// Nur, wenn string tag gefunden ist,
		// ist ein id3v1 tagset vorhanden
		if (strtolower($tag) == "tag") {
			$ret['MDATA']['ID3_TITLE'] = FileIO::ecc_read($fhdl, $offset+3, 30, false);
			$ret['MDATA']['ID3_ARTIST'] = FileIO::ecc_read($fhdl, $offset+33, 30, false);
			$ret['MDATA']['ID3_ALBUM'] = FileIO::ecc_read($fhdl, $offset+63, 30, false);
			$ret['MDATA']['ID3_YEAR'] = FileIO::ecc_read($fhdl, $offset+93, 4, false);
			$ret['MDATA']['ID3_COMMENT'] = FileIO::ecc_read($fhdl, $offset+97, 29, false);
			// genre nutzt eine ersetzungstabelle
			$ret['MDATA']['ID3_GENRE'] = FileIO::ecc_read($fhdl, $offset+127, 1, 'DEZ');
		}
		
		$ret['FILE_MD5'] = NULL;
		
		if (filesize($file_name) >= ExtParserTriggerSize) {
			$ret['FILE_VALID'] = false;
			$ret['FILE_CRC32'] = false;	
		}
		else{
			# fsum not possible here, because of offset!
			$ret['FILE_CRC32'] = FileIO::ecc_get_crc32_from_string(FileIO::ecc_read_file($fhdl, 0, $offset, $file_name));
			$ret['FILE_VALID'] = true;
		}
		
		while (gtk::events_pending()) gtk::main_iteration();
		
		return $ret;
	}
}
?>
