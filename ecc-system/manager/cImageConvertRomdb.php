<?php
require_once('cImageConvert.php');
require_once('manager/cValid.php');
class ImageConvertRomdb extends ImageConvert {
	
	public $eccIdent = 'gg';
	
	public $imageFolderSource;
	public $imageFolderDestination;
	
	public $imageTank = array();
	
	public function processImageDirectory($directory, $currentSubfolder=false) {
		if (!is_dir($directory)) return false;
		$dirHdl = opendir($directory);

		$mngrIni = FACTORY::get('manager/IniFile');
		
		while($file = readdir($dirHdl)) {
			if ($file == '.' || $file == '..') continue;

			$filePath = $directory.'/'.$file;
			
			$split = explode('_', $file);
			if ($split[0] != 'ecc') continue;
			$eccident = $split[1];
			$crc32 = $split[2];
			$desinationPath = $this->imageFolderDestination.$eccident.'/'.substr($crc32, 0, 1).'/'.substr($crc32, 0, 2).'/'.substr($crc32, 0,3).'/'.$crc32.'/thumb/'.$file;
			
			if (!is_dir(dirname($desinationPath))) $mngrIni->createDirectoryRecursive(dirname($desinationPath));
			copy($filePath, $desinationPath);
			print '.';
		}
	}
}
?>