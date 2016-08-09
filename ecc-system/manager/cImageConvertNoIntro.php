<?php
require_once('cImageConvert.php');
class ImageConvertNoIntro extends ImageConvert {
	
	public $eccIdent = 'gg';
	
	public $imageFolderSource;
	public $imageFolderDestination;
	
	public $imageTank = array();
	
	public $folderTranslate = array(
		'backcovers' => 'cover_back',
		'carts' => false, // not supported now!
		'frontcovers' => 'cover_front',
		'snaps' => 'ingame_play_01',
		'titles' => 'ingame_title',
	);
	
	public function processImageDirectory($directory, $currentSubfolder=false) {
		if (!is_dir($directory)) return false;
		$dirHdl = opendir($directory);
		while($file = readdir($dirHdl)) {
			if ($file == '.' || $file == '..') continue;
			$filePath = $directory.'/'.$file;
			if (is_dir($filePath)) {
				if (isset($this->folderTranslate[$file]) && $this->folderTranslate[$file]) {
					$currentSubfolder = $file;
					$this->processImageDirectory($filePath, $currentSubfolder);
				}
			}
			else {
				if (false !== strpos($file, '.')) {
					list($crc32, $fileExtension) = explode('.', $file);
				}
				else continue;
				
				$eccFileName = $this->createEccImageName(strtolower($this->eccIdent), strtoupper($crc32), strtolower($this->folderTranslate[$currentSubfolder]), strtolower($fileExtension));
				
				$this->imageTank[$crc32][$currentSubfolder]['source'] = $filePath;
				$this->imageTank[$crc32][$currentSubfolder]['destination'] = $this->imageFolderDestination.'/'.$eccFileName;
			}
		}
		return $this->imageTank;
	}
}
?>