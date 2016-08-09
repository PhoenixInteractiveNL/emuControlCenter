<?php
/*
* Mobilephone Jar MIDlet Parser
* This parser extract data from Jar files and creates an checksum
* of the handled file
*/
class FileParserJarMIDlet implements FileParser {
	
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

		# try to get manifest data
		$eccMIDletReader =  new EccMIDletReader();
		if (false === $eccMIDletReader->parseManifest($file_name)){

			# invalid manifest found or not readable!
			
			$ret['FILE_VALID'] = false;
			return $ret;
		}
		else {
			
			# use external parser to get the right crc32 for larger files!
			# only usable for platforms withou offsets!!!!
			if (filesize($file_name) >= ExtParserTriggerSize) {
				$ret['FILE_CRC32'] = FileIO::getExternalCrc32($file_name, 1);
			}
			else{
				$ret['FILE_CRC32'] = FileIO::ecc_get_crc32_from_string(FileIO::ecc_read_file($fhdl, false, false, $file_name));
			}

			# valid midlet manifest found!			
			$ret['FILE_MD5'] = NULL;
			$ret['FILE_VALID'] = true;
			
			# on success set the data from handleMIDletManifest
			$ret['MDATA'] = $eccMIDletReader->getManifest();
		
			# try to copy the midlet icon, if available
			$eccMIDletReader->copyIconIfAvailable($this->_file_ext, $ret['FILE_CRC32']);
			
			while (gtk::events_pending()) gtk::main_iteration();
			
			return $ret;
		}
	}
}

class EccMIDletReader {
	
	private $zipArchive;
	private $manifest = array();
	private $midletIconPath = false;
	private $midletIconData = false;
	
	/**
	 * File to extract informations for the MIDlet manifest.mf
	 * 
	 * Get data from manifest and write the icon, if needed
	 * 
	 * @author Andreas Scheibel <ecc@camya.com>
	 * @copyright Andreas Scheibel (c) 2006
	 * 
	 * @param string $currentFile Filename of the 
	 * @return mixed false on failure, array on success
	 */
	public function parseManifest($currentFile){
		
		$this->zipArchive = new ZipArchive;
		if ($this->zipArchive->open($currentFile) !== TRUE) return false;
			
		# get the current manifest string from file. 
		$manifestString = $this->zipArchive->getFromName('META-INF/MANIFEST.MF');
		if (!trim($manifestString)) return false;
		
		# created an array of $manifestString lines
		$manifestData = explode("\n", $manifestString);
		
		$manifest = array();
		foreach($manifestData as $line){
			
			# No data or invalid line? Goto next line!
			if (!trim($line) || false === strpos($line, ':')) continue;
			list($key, $value) = explode(':', $line);
			
			# Keys and values are not trimmed by default.
			$key = trim($key);
			$value = trim($value);
			
			# MIDlet-1 contains 3 values, concated by comma. Explode first!
			if ($key == 'MIDlet-1'){
				
				# store original value
				$manifest['MIDlet-1'] = $value;

				# store MIDlet-1_array for better usage				
				$midlet1 = explode(',', $value);
				foreach($midlet1 as $midlet1Key => $midlet1Value) $midlet1[$midlet1Key] = trim($midlet1Value);
				$manifest['MIDlet-1_array'] = $midlet1;
			}
			else {
				$manifest[$key] = $value;
			}
		}

		# Icon handeling - try to get the right icon path!
		if (isset($manifest['MIDlet-1_array'][1]) && $manifest['MIDlet-1_array'][1]){
			$this->midletIconPath = $manifest['MIDlet-1_array'][1];
		}
		# overwrite with an given MIDlet-Icon, if existing!
		if (isset($manifest['MIDlet-Icon']) && $manifest['MIDlet-Icon']){
			$this->midletIconPath = $manifest['MIDlet-Icon'];
		}

		# strip leading slash and get data to memory
		if (0 === strpos($this->midletIconPath, '/')) $this->midletIconPath = substr($this->midletIconPath, 1);
		if (trim($this->midletIconPath)) $this->midletIconData = $this->zipArchive->getFromName($this->midletIconPath);
		
//		$test = 'MIDlet-Version'; # NEEDED!
//		if (isset($manifest[$test])) print "$test: ".$manifest[$test]."\t\t($currentFile) \n";
//		$test = 'MIDlet-Name'; # NEEDED!
//		if (isset($manifest[$test])) print "$test: ".$manifest[$test]."\t\t($currentFile) \n";
//		$test = 'MIDlet-Vendor'; # NEEDED!
//		if (isset($manifest[$test])) print "$test: ".$manifest[$test]."\t\t($currentFile) \n";
//		$test = 'MIDlet-1'; # NEEDED!
//		if (isset($manifest[$test])) print "$test: ".$manifest[$test]."\t\t($currentFile) \n";
		
		$this->zipArchive->close();
		
		$this->manifest = $manifest;

	}
	
	public function copyIconIfAvailable($eccident, $crc32){
		# write icon to disc, if available!
		if (!$eccident || !$crc32 || !$this->midletIconData) return false;
		return FACTORY::get('manager/Image')->storeUserImageStream($eccident, $crc32, $this->midletIconData, 'png', 'media_icon');
	}
	
	public function getManifest(){
		unset($this->manifest['MIDlet-1_array']);
		return $this->manifest;
	}
}
?>
