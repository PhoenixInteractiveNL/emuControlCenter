<?php
// This parser will always use the AutoIt3 CRC32 wrapper to get the
// CRC32 from the ROM/FILE to prevent a chance that ECC (PHP) will crash!
   
class FileParserExternal implements FileParser {

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
      $ret['FILE_SIZE'] = $file_info['SIZE'];
      
      # Always use FSUM
      {
         $ret['FILE_CRC32'] = FileIO::getExternalCrc32($file_name, 1);
      }
      
      $ret['FILE_MD5'] = NULL;
      $ret['FILE_VALID'] = true;
      
      while (gtk::events_pending()) gtk::main_iteration();
      
      return $ret;
   }
}
?>
