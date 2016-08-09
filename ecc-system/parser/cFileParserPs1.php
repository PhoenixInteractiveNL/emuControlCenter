<?php
/*
* Super Nintendo / Super Famicon Parser
* Dieser Parser kann informationen aus Gameboy-Roms
* extrahieren.
*/
class FileParserPs1 implements FileParser {
	
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
		$ret['FILE_EXT'] = 'PS1';
		$fstat = fstat($fhdl);
		$ret['FILE_SIZE'] = $fstat['size'];

		// extract PS1 id
		$psId = $this->getId($file_name);
		
		$crc32 = false;
		$isValid = false;
		if($psId) {
			$isValid = true;
			$crc32 = FileIO::ecc_get_crc32_from_string($psId);
			$ret['MDATA']['GAMEID'] = str_replace(array('_', '.'), '', $psId);
			$ret['MDATA']['GAMEID_ORIGINAL'] = $psId;
		}
		
		$ret['FILE_CRC32'] = $crc32;
		$ret['FILE_VALID'] = $isValid;
		$ret['FILE_MD5'] = NULL;
		
		while (gtk::events_pending()) gtk::main_iteration();
		
		return $ret;
	}
	
	/*
	 * Extract game id from within a iso file
	 *
	 * Supported files
	 * .bin
	 * .iso
	 * .mdf
	 * .img
	 * 
	 * @author: Zerosan (ecc-member)
	 * @since 20090215
	 */
	public function getID($filepath) {
   #works with ".bin",".iso",".mdf",".img"
   #nrg does not work.
   $handle = fopen($filepath,"r");
   // read in the first mb
   $content = fread($handle, 1048576);
   fclose($handle);
   $idtypelist = Array(
      "SLUS",
      "SCES",
      "SLES",
      "SCUS",
      "SCPS",
      "PAPX",
      "SLPM",
      "PCPX",
      "SLPS"
   );
   $idpos = false;
   foreach($idtypelist AS $idtype) {
      if($idpos = strpos($content, $idtype)) {
         break;
      }
   }
   if(!$idpos) {
      return false;
   }
   $id = substr($content, $idpos, 11);
   
   $content = "";
   return $id;
}
}
?>
