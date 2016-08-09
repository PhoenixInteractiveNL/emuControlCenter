<?php
class LOGGER {
	
	public static $active;
	private static $fHdl = array();
	private static $fName = array();
	
	private static $types = array(
		'def' => 'status',
		'datiecc' => 'dat_import_ecc',
		'romparse' => 'rom_parsing',
		'images' => 'images',
		'files' => 'files',
		'datirc' => 'dat_import_rc',
		'dateecc' => 'dat_export_ecc',
		'romdbadd' => 'romdb_add',
		'datimportcm' => 'dat_import_cm',
		'transferbysearchresult' => 'transfer_by_searchresult',
	);
	
	public static function setActiveState($state = false){
		self::$active = $state;
	}
	
	public static function add($type= false, $text, $headerType = false, $typePrefix = false, $mode = false){
		
		# if not active -- return!
		if (self::$active) self::setLogfile($type, $typePrefix, $mode);

		$out = '';
		if (substr($text, -4) !== "\r\n") $text .= "\r\n";
		if ($headerType !== false) {
			switch($headerType) {
				case '0':
					$out .= date('Y-m-d H:i:s').': '.$text;
					break;
				case '1':
					$logfile = self::$types[$type].'.txt';
					$out .= str_repeat('#', 80)."\r\n";
					$out .= "GENERATOR: emuControlCenter (log: $logfile)\r\n";
					$out .= date('Y-m-d H:i:s').': '.$text;
					$out .= str_repeat('#', 80)."\r\n";
				break;
				case '2':
					$out .= str_repeat('-', 80)."\r\n";
					$out .= date('Y-m-d H:i:s').': '.$text;
					$out .= str_repeat('-', 80)."\r\n";
				break;
				case '3':
					$out .= str_repeat('*', 80)."\r\n";
					$out .= date('Y-m-d H:i:s').': '.$text;
					$out .= str_repeat('*', 80)."\r\n";
				break;
				default:
					$out .= $text;
			}
		}
		else {
			$out .= $text;
		}
		
		if (self::$active) fwrite(self::$fHdl[$type], $out);
		
		return $out;
	}

	public static function getLogfileName($type) {
		if (self::$fName[$type]) {
			return self::$fName[$type];
		}
		return false;
	}
	
	private static function setLogfile($type= false, $typePrefix = false, $mode = 'a+'){
		
		if(!isset(self::$types[$type])) $type = 'def';
		if(isset(self::$fHdl[$type])) return true;
		if(!$mode) $mode = 'a+';
		
		# create logfile path 
		$validator = FACTORY::get('manager/Validator');
		$coreKey = $validator->getEccCoreKey('eccHelpLocations');
		$dir = ECC_DIR.'/'.$coreKey['LOG_DIR'];
		if (!is_dir($dir)) mkdir($dir);
		$typePrefix = ($typePrefix) ? '_'.$typePrefix : '';
		$logfile = self::$types[$type].$typePrefix.'.txt';
		
		self::$fName[$type] = $dir.DIRECTORY_SEPARATOR.$logfile;
		
		self::$fHdl[$type] = fopen($dir.DIRECTORY_SEPARATOR.$logfile, 'a+');
		
		# session header
		self::add($type, "NEW SESSION", 3);
		
		return true;
	}
	
	public function close($type){
		fclose(self::$fHdl[$type]);
		unset(self::$fHdl[$type]);
	}
}
?>