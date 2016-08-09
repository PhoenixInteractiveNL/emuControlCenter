<?php
/*
* Super Nintendo / Super Famicon Parser
* Dieser Parser kann informationen aus Gameboy-Roms
* extrahieren.
*/
class FileParserSnes implements FileParser {
	
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
		$ret['FILE_EXT'] = 'SNES';
		$fstat = fstat($fhdl);
		$ret['FILE_SIZE'] = $fstat['size'];
		
		// Zuerst ermitteln, ob ein Header vorhanden ist!
		// Dafuer werden die Letzten 16 Bytes eines Headers eingelesen.
		// Ergeben diese 0, so wird angenommen, das ein Header vorhanden ist
		// Wichtig, um gegebenenfalls einen offset beim seek einzustellen!
		// Dies ist bei der Ermittlung der CRC32 Checksumme wichtig!
		
		$snes_rom_has_header = false;
		
		$result = trim(FileIO::ecc_read($fhdl, 128, 16, false));
		if ($result == "") {
			$snes_rom_has_header = true;
		}
		
		$backup_unit = 'no_header';
		$snes_rom_header_offset = 0;
		
		if ($snes_rom_has_header === true) {
			
			$backup_unit = 'unknown_header';
			$snes_rom_header_offset = 512;
			
			// Jetzt wirde versucht, die Backup-Unit zu ermitteln.
			// Dies geschieht zur zeit noch nicht wirklich gut :-)
			# SWC Super Wild Card -	 header information
			# TODO - Wie kann mann SWC von FIG unterscheiden?
			
			// FIG Pro Fighter - header format
			$out = trim(FileIO::ecc_read($fhdl, 4, 2, 'DEZ'));
			if ($out == 250) {
				$backup_unit = 'FIG';
			}
			// HEADER SWF
			$out = trim(FileIO::ecc_read($fhdl, 8, 3, 'DEZ'));
			if ($out == 361) {
				$backup_unit = 'SWF';
			}
			// GD3 Game Doctor -	 file name format (GAME DOCTOR SF 3)
			$out = trim(FileIO::ecc_read($fhdl, 0, 16, 'DEZ'));
			if ($out == 1041) {
				$backup_unit = 'GD3';
			}
		}
		$ret['MDATA']['HDR_BACKUP_UNIT'] = $backup_unit;
		
		// HI/LO-ROM muï¿½ ermittelt werden, da diese unterschiedliche
		// offsets verursachen. Fï¿½hrt man diesen check nicht aus, werden
		// falsche checksummen ermittelt und die Header-Infos kï¿½nnen nicht
		// extrahiert werden.
		
		#// ROM makeup (1 byte)
		#$rom_makeup = trim(FileIO::ecc_read($fhdl, 32725+$snes_rom_header_offset, 1, false));
		// ROM makeup (1 byte)
		$rom_makeup_hi = trim(FileIO::ecc_read($fhdl, 65493+$snes_rom_header_offset, 1, false));
		
		if ($rom_makeup_hi == '1' || $rom_makeup_hi == '!') {
			$starting_offset = 32768;
		}
		else {
			$starting_offset = 0;
		}
		
		// Game title (21 bytes)
		$ret['MDATA']['GAME_TITLE'] = trim(FileIO::ecc_read($fhdl, $starting_offset+32704+$snes_rom_header_offset, 21, false));
		// ROM type (1 byte)
		$ret['MDATA']['ROM_TYPE'] = trim(FileIO::ecc_read($fhdl, $starting_offset+32726+$snes_rom_header_offset, 1, false));
		// ROM size (1 byte) 
		$ret['MDATA']['ROM_SIZE'] = trim(FileIO::ecc_read($fhdl, $starting_offset+32727+$snes_rom_header_offset, 1, 'DEZ'));
		// SRAM size (1 byte) 
		$ret['MDATA']['SRAM_SIZE'] = trim(FileIO::ecc_read($fhdl, $starting_offset+32728+$snes_rom_header_offset, 1, 'DEZ'));
		// Country (1 byte)
		$ret['MDATA']['COUNTRY_ID'] = trim(FileIO::ecc_read($fhdl, $starting_offset+32729+$snes_rom_header_offset, 1, 'DEZ'));
		// License (1 byte)
		$ret['MDATA']['LICENSE_ID'] = trim(FileIO::ecc_read($fhdl, $starting_offset+32730+$snes_rom_header_offset, 1, 'DEZ'));
		// Game Version (1 byte)
		$ret['MDATA']['GAME_VERSION'] = trim(FileIO::ecc_read($fhdl, $starting_offset+32731+$snes_rom_header_offset, 1, 'DEZ'));
		#// ROM makeup (1 byte)
		#$ret['ROM_MAKEUP'] = trim(FileIO::ecc_read($fhdl, $starting_offset+32725+$snes_rom_header_offset, 1, false));
		#// ROM makeup (1 byte)
		#$ret['ROM_MAKEUP_hi'] = trim(FileIO::ecc_read($fhdl, $starting_offset+65493+$snes_rom_header_offset, 1, false));
		
		# fsum not possible here, because of offset!
		if (filesize($file_name) >= ExtParserTriggerSize) {
			$ret['FILE_VALID'] = false;
			$ret['FILE_CRC32'] = false;	
		}
		else{
			$ret['FILE_CRC32'] = FileIO::ecc_get_crc32_from_string(FileIO::ecc_read_file($fhdl, $snes_rom_header_offset, false, $file_name));
			$ret['FILE_VALID'] = true;
		}
		
		$ret['FILE_MD5'] = NULL;

		while (gtk::events_pending()) gtk::main_iteration();
		
		return $ret;
	}
}
?>
