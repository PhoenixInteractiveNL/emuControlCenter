<?php
/*
* Super Nintendo / Super Famicon Parser
* Dieser Parser kann informationen aus Gameboy-Roms
* extrahieren.
*/
class FileParserLynx implements FileParser {
	
	public function parse($fhdl, $file_name, $file_name_direct=false, $file_name_packed=false) {
		
		$ret = array();
		
		// Infos zur datei holen
		$file_info = FileIO::ecc_file_get_info($file_name);
		$ret['FILE_NAME'] = $file_info['NAME'];
		$ret['FILE_PATH'] = $file_name_direct;
		$ret['FILE_PATH_PACK'] = $file_name_packed;
		$ret['FILE_EXT'] = 'LYNX';
		$fstat = fstat($fhdl);
		$ret['FILE_SIZE'] = $fstat['size'];
		
		// find header
		$startOffset = 0;
		$result = trim(FileIO::ecc_read($fhdl, 0, 16));
		if(strpos($result, 'LYNX') === 0){
			$startOffset = 64;
			$ret['MDATA']['HEADER'] = 'LYNX';
		}
		elseif(strpos($result, 'BS93') === 6){
			#$startOffset = 64;
			$ret['MDATA']['HEADER'] = 'BS93';
		}
		else{
			$ret['MDATA']['HEADER'] = 'None';
		}

		$ret['FILE_CRC32'] = FileIO::ecc_get_crc32_from_string(FileIO::ecc_read_file($fhdl, $startOffset, false, $file_name));
		
		// only for debug reason of romdb entries!
		//$wrongCrc32 = FileIO::ecc_get_crc32_from_string(FileIO::ecc_read_file($fhdl, false, false, $file_name));
		//$out = "UPDATE `romdb_meta_in` SET `crc32` = '".$ret['FILE_CRC32']."' WHERE eccident = 'lynx' AND crc32 = '".$wrongCrc32."';\n";
		//$out .= "UPDATE `romdb_meta` SET `crc32` = '".$ret['FILE_CRC32']."' WHERE eccident = 'lynx' AND crc32 = '".$wrongCrc32."';\n";
		//file_put_contents('../lynx.sql', $out, FILE_APPEND);
		
		$ret['FILE_VALID'] = true;
		$ret['FILE_MD5'] = NULL;
		
		
		while (gtk::events_pending()) gtk::main_iteration();
		
		return $ret;
	}
}
?>
