<?php
/*
* Gb Gameboy Parser
* Dieser Parser kann informationen aus Gameboy-Roms
* extrahieren.
*/
class FileParserGb implements FileParser {
	
	/*
	*
	*/
	public function __construct() {
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
		$ret['FILE_EXT'] = strtoupper($file_info['EXT']);
		$fstat = fstat($fhdl);
		$ret['FILE_SIZE'] = $fstat['size'];
		
		$ret['TITLE'] = trim(FileIO::ecc_read($fhdl, 308, 16, false));
		
		// Neuer License Code (2 char ascii)
		$ret['MAKER_ID'] = FileIO::ecc_read($fhdl, 324, 2, false);
		
		// Alter License Code. Wenn 33, dann wird der neue License-Code genutzt
		$ret['LICENSE_ID'] = FileIO::ecc_read($fhdl, 331, 1, 'HEX');
		
		// Mask ROM Version number
		$ret['VERSION_NUMBER'] = FileIO::ecc_read($fhdl, 332, 1, 'DEZ');
		
		// Header Checksum 1 byte
		$ret['CHKSUM_HEAD'] = FileIO::ecc_read($fhdl, 333, 1, 'DEZ');
		
		// Global Checksum  2 byte
		$ret['CHKSUM_GLOBAL'] = FileIO::ecc_read($fhdl, 334, 2, 'DEZ');
		
		// Cartridge Type
		$ret['CART_TYPE'] = FileIO::ecc_read($fhdl, 327, 1, 'DEZ');
		
		// ROM Size
		$ret['ROM_SIZE'] = FileIO::ecc_read($fhdl, 328, 1, 'DEZ');
		
		// RAM Size
		$ret['RAM_SIZE'] = FileIO::ecc_read($fhdl, 329, 1, 'DEZ');
		
		// Destination Code - (00h - Japanese | 01h - Non-Japanese)
		$ret['DEST_CODE'] = FileIO::ecc_read($fhdl, 330, 1, 'DEZ');
		
		// 0143 - CGB Flag
		// TODO - Herausfinden, was gilt:
		// Only one byte long.  A HEX value of 03 says that the cartridge has added	
		// features for Super Gameboy.  Any other value, especially HEX 00 denotes 
		// a non-SGB cart.
		// ODER ANDERE DOCU:
		// 80h - Game supports CGB functions, but works on old gameboys also.
		// C0h - Game works on CGB only (physically the same as 80h).
		$ret['SGB_FEATURES'] = FileIO::ecc_read($fhdl, 323, 1, 'HEX');
		
		// Checksummen ermitteln.
		// Aus performancegründen zuerst in string einlesen
		while (gtk::events_pending()) gtk::main_iteration();
		$file_content = FileIO::ecc_read_file($fhdl, false, false, $file_name);
		
		#while (gtk::events_pending()) gtk::main_iteration();
		#$ret['FILE_MD5'] = FileIO::ecc_get_md5_from_string($file_content);
		$ret['FILE_MD5'] = NULL;
		
		while (gtk::events_pending()) gtk::main_iteration();
		$ret['FILE_CRC32'] = FileIO::ecc_get_crc32_from_string($file_content);
		
		$ret['FILE_VALID'] = true;
		
		return $ret;
	}
}
?>
