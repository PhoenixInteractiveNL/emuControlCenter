<?php
class ImagePack {
	
	private $system;
	
	public function hasImagePack($system){
		if(!$system) return false;
		$userFolder = FACTORY::get('IniFile')->getUserFolder($system, 'images');
		if(!$userFolder || !realpath($userFolder)) return false;
		return !$this->isEmptyDir($userFolder);
	}
	
	public function createAllThumbnails($system){
		if(!$system) return false;
		$userFolder = FACTORY::get('IniFile')->getUserFolder($system, 'images');
		if(!$userFolder) return false;
		return FACTORY::get('FileIO')->readDirRecursive($userFolder, array($this, 'createAllThumbnailsCallback'));
	}

	public function createAllThumbnailsCallback($foundPath, $basePath){
		$path = str_replace($basePath, '', $foundPath); // get the path in ecc-user folder
		if(is_dir($foundPath) && basename($foundPath) == 'thumb') return false; // skip existing thumb paths
		$iniManager = FACTORY::get('IniFile');
		$imageManager = FACTORY::get('Image');
		
		$minSize =  $iniManager->getKey('USER_SWITCHES', 'image_thumb_original_min_size');
		if (!$minSize) $minSize = 30000;
		
		if(filesize($foundPath) > $minSize){
			$imageManager->createThumbnail($foundPath, $imageManager->getImageThumbFile($foundPath), true, 240, false);
		}
		return false;
	}
	
	public function removeAllThumbnails($system){
		if(!$system) return false;
		$userFolder = FACTORY::get('IniFile')->getUserFolder($system, 'images');
		if(!$userFolder) return false;

		// first delete thumbs
		$fileIoManager = FACTORY::get('FileIO');
		while($data = $fileIoManager->readDirRecursive($userFolder, array($this, 'removeAllThumbnailsCallback'))){
			FileIO::$fileList = false;
		}
		$this->removeEmptyFolder($system); // then cleanup folder
		return true;		
	}

	public function removeAllThumbnailsCallback($foundPath, $basePath){
		$path = str_replace($basePath, '', $foundPath); // get the path in ecc-user folder
		// only remove \thumb folders
		if(is_dir($foundPath) && basename($foundPath) == 'thumb'){
			FACTORY::get('FileIO')->rmdirr($foundPath);
		}
		return false;
	}
	
	public function removeImagesWithoutRomFile($system, $availableCrc32){
		if(!$system || !is_array($availableCrc32)) return false;
		$userFolder = FACTORY::get('IniFile')->getUserFolder($system, 'images');
		if(!$userFolder) return false;
		FACTORY::get('FileIO')->readDirRecursive($userFolder, array($this, 'removeImagesWithoutRomFileCallback', $availableCrc32));
		$this->removeEmptyFolder($system); // then cleanup folder
		return true;
	}

	public function removeImagesWithoutRomFileCallback($foundPath, $basePath, $availableCrc32){
		$path = str_replace($basePath, '', $foundPath); // get the path in ecc-user folder
		if(strpos($path, '\\thumb') === false && !is_dir($foundPath)){
			$crc32 = $this->getCrc32FromPath($path);
			if($crc32 && !in_array($crc32, $availableCrc32)){
				FACTORY::get('FileIO')->rmdirr(dirname($foundPath));
			}
		}
		return false;
	}
	
	public function removeEmptyFolder($system){
		if(!$system) return false;
		$userFolder = FACTORY::get('IniFile')->getUserFolder($system, 'images');
		if(!$userFolder) return false;
		$fileIoManager = FACTORY::get('FileIO');
		while($data = $fileIoManager->readDirRecursive($userFolder, array($this, 'removeEmptyFolderCallback'))){
			FileIO::$fileList = false;
		}
	}

	public function removeEmptyFolderCallback($foundPath, $basePath){
		$path = str_replace($basePath, '', $foundPath); // get the path in ecc-user folder
		if(!$foundPath || is_file($foundPath) || !FACTORY::get('FileIO')->dirIsEmpty($foundPath)) return false;
		FACTORY::get('FileIO')->rmdirr($foundPath);
		return $foundPath;
	}	
	
	public function getCrc32FromPath($path){
		$match = array();
		if(preg_match('/\\\\([0-9A-F]{8})\\\\/i', $path, $match)){
			if(isset($match[1])) return $match[1];
		}
		return false;			
	}
	
	public function isEmptyDir($path){
		$path = realpath($path);
		if(!$path || !is_dir($path)) return false;
		$dirContent = scandir($path);
		if(count($dirContent) == 2) return true;
		return false;
	}
}
?>