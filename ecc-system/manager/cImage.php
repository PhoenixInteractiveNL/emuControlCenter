<?php
class Image {
	
	private $imageThumbQuality = 80;
	private $imageThumbType = 'jpg';
	private $imageThumbSourceMinSizeKb = '30000';
	private $imageThumbSubfolder = 'thumb/';
	
	private $matchImageType = false;
	
	protected $supportedExtensions;
	protected $eccImageTypes;
	
	private $errors;
	
	public $cachedRomImages = array();
	
	/* MANAGER */
	private $fileIoManager;
	private $iniManager;
	
	public function __construct(){
		$this->fileIoManager = FACTORY::get('manager/FileIO');
		$this->iniManager = FACTORY::get('manager/IniFile');
		$this->resetErrors();
		
		$originalMinSize = $this->iniManager->getKey('USER_SWITCHES', 'image_thumb_original_min_size');
		if ($originalMinSize) $this->imageThumbSourceMinSizeKb = $originalMinSize;
		$imageThumbQuality = $this->iniManager->getKey('USER_SWITCHES', 'image_thumb_quality');
		if ($imageThumbQuality) $this->imageThumbQuality = $imageThumbQuality;
	}
	
	public function getCachedImages($eccident, $crc32) {
		return (isset($this->cachedRomImages[$eccident][$crc32])) ? $this->cachedRomImages[$eccident][$crc32] : false;
	}
	
	public function resetCachedImages($eccident = false, $crc32 = false) {
		if ($eccident && $crc32) {
			#print "RESET: $eccident && $crc32".LF;
			if (isset($this->cachedRomImages[$eccident][$crc32])) {
				#print "-->DONE!".LF;
				$this->cachedRomImages[$eccident][$crc32] = array();
			}
		}
		elseif($eccident) {
			#print "RESET: $eccident".LF;
			if (isset($this->cachedRomImages[$eccident])) {
				#print "-->DONE!".LF;
				$this->cachedRomImages[$eccident] = array();
			}
		}
		else {
			#print "RESET: ALL DONE!!!!!!!!!!!!!!!!!!!!!!!".LF;
			$this->cachedRomImages = array();
		}
	}
	
	public function setMatchImageType($state) {
		$this->matchImageType = $state;
	}
	
	public function setSupportedExtensions($supportedExtensions){
		$this->supportedExtensions = $supportedExtensions;
	}
	
	public function setThumbQuality($imageThumbQuality){
		$this->imageThumbQuality = $imageThumbQuality;
	}
	
	public function setEccImageTypes($eccImageTypes){
		$this->eccImageTypes = $eccImageTypes;
	}
	
	public function getImageByType($eccident, $crc32, $imageType, $useThumb=true){
		#print "eccident $eccident, crc32 $crc32, imageType $imageType, useThumb $useThumb\n";
		
		$this->matchImageType = true;
		$image = $this->searchForSavedRomImagesExtended($eccident, $crc32, $imageType, true, false);
		$this->matchImageType = false;
				
		return $image;
	}
	
	public function storeUserImageStream($eccident, $crc32, $imageData, $imageExtension, $destImageType){

		$this->resetErrors();
		
		if (!$eccident || !$crc32 || !trim($imageData) || !$imageExtension || !$destImageType) return false;
		
		# test if extension is supported!
		if (!isset($this->supportedExtensions[strtolower($imageExtension)])) {
			$this->setError('image', 'type_not_supported');
			return false;
		}

		# is the destination typ allowed?
		if (!isset($this->eccImageTypes[strtolower($destImageType)])) return false;
		
		# get/create userfolder, if needed!
		$imageDestFolder = $this->getUserImageCrc32Folder($eccident, $crc32, true);
		if (!$imageDestFolder) return false;
		
		$destImagePath = $this->getUserImageFileName($imageDestFolder, $eccident, $crc32, $destImageType, $imageExtension);
		
		if (!$this->hasErrors() && !file_exists($destImagePath)) file_put_contents($destImagePath, $imageData);
		else return false;
		
	}
	
	public function storeUserImage($transferMode, $eccident, $crc32, $sourceImagePath, $destImageType, $cleanupRemoved = true){
		
		$this->resetErrors();
		
		if (!$eccident || !$crc32) return false;
		if (!in_array($transferMode, array('COPY', 'MOVE'))) return false;

		# source-file exists?
		if (!is_file($sourceImagePath)) return false;
		
		# get image destination file path
		$fileExtension = $this->fileIoManager->get_ext_form_file(basename($sourceImagePath));
		
		# test if extension is supported!
		if (!isset($this->supportedExtensions[strtolower($fileExtension)])) {
			$this->setError('image', 'type_not_supported');
			return false;
		}

		# is the destination typ allowed?
		if (!isset($this->eccImageTypes[strtolower($destImageType)])) return false;
		
		# get/create userfolder, if needed!
		$imageDestFolder = $this->getUserImageCrc32Folder($eccident, $crc32, true);
		if (!$imageDestFolder) return false;
		
		# $destImagePath = $imageDestFolder.DIRECTORY_SEPARATOR."ecc_".$eccident."_".$crc32."_".$destImageType.".".$fileExtension;
		$destImagePath = $this->getUserImageFileName($imageDestFolder, $eccident, $crc32, $destImageType, $fileExtension);
		
		# is this image allready saved?
		if ($sourceImagePath == $destImagePath) $this->setError('image', 'image_allready_inplace');
		
		# only create thumb, if sourceimage is bigger then minsize
		$createThumb = (filesize($sourceImagePath) >= $this->imageThumbSourceMinSizeKb) ? true : false;
		
		if (!$this->hasErrors()) {
			if ($cleanupRemoved) $this->searchAndRemoveOldImages($eccident, $crc32, $destImageType);
			if ($transferMode == 'MOVE') return $this->moveImage($sourceImagePath, $destImagePath, $createThumb);
			else return $this->copyImage($sourceImagePath, $destImagePath, $createThumb);
		}
		else return false;
	}
	
	public function removeUserImage($imageFile)
	{
		$imageFullFile = $imageFile;
		$imageThumbFile = $this->getImageThumbFile($imageFile, false);
		
		if (file_exists($imageFullFile)) unlink($imageFullFile);
		if (file_exists($imageThumbFile)) unlink($imageThumbFile);
		
		return true;
	}
	
	public function removeUserImageFolder($eccident, $crc32) {
		$imageFolder = $this->getUserImageCrc32Folder($eccident, $crc32);
		if (is_dir($imageFolder)) FACTORY::get('manager/FileIO')->rmdirr($imageFolder);
	}
	
	public function searchAndRemoveOldImages($eccident, $crc32, $imageType) {
		if (!$eccident || !$crc32 || !$imageType) return false;
		$images = $this->searchForSavedRomImagesExtended($eccident, $crc32, $imageType, true, true);
		if (isset($images[0])) $this->removeUserImage($images[0]);
	}
	
	/**
	 * old system to get userimage path
	 */
	public function getUserImageFolder($eccident, $createOnDemand = false){
		return realpath($this->iniManager->getUserFolder($this->getUserImageFolderSubdir($eccident), $createOnDemand));
	}
	
	/**
	 * old system to get userimage path
	 */
	public function getOldUserImageFolder($eccident, $basePath = false){
		return realpath($basePath.'/'.$this->getUserImageFolderSubdir($eccident));
	}
	
	public function getUserImageFolderSubdir($eccident){
		return $eccident.DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR;
	}
	
	/**
	 * new optimized version to get userimage-folder
	 */
	public function getUserImageCrc32Folder($eccident, $crc32, $createOnDemand = false){
		if (!$eccident || !$crc32) return false;
		$imageDestFolder = $this->iniManager->getUserFolder($eccident.'/images/'.substr($crc32, 0, 2).'/'.$crc32, $createOnDemand);
		return $imageDestFolder;
	}
	
	public function isEmptyUserImageCrc32Folder($eccident, $crc32){
		$imageFolder = $this->getUserImageCrc32Folder($eccident, $crc32);
		print "$imageFolder".LF;
		return FACTORY::get('manager/FileIO')->dirIsEmpty($imageFolder);
	}
	
	public function getUserImageFileName($imageDestFolder, $eccident, $crc32, $imageType, $fileExtension = ''){
		return $imageDestFolder.DIRECTORY_SEPARATOR."ecc_".$eccident."_".$crc32."_".$imageType.".".$fileExtension;
	}
	
	public function moveImage($sourceImagePath, $destImagePath, $createThumb = false) {
		if ($createThumb) $this->createThumbnail($sourceImagePath, $this->getImageThumbFile($destImagePath, true), true, 240, false);
		@unlink($destImagePath);
		@rename($sourceImagePath, $destImagePath);
		return true;
	}
	
	public function copyImage($sourceImagePath, $destImagePath, $createThumb = false) {
		if ($createThumb) $this->createThumbnail($sourceImagePath, $this->getImageThumbFile($destImagePath, true), true, 240, false);
		@copy($sourceImagePath, $destImagePath);
		return true;
	}
	
	public function getImageThumbFile($destImagePath, $createDir = false){
		$destThumbPath = dirname($destImagePath).'/'.$this->imageThumbSubfolder;
		if ($createDir && !is_dir($destThumbPath)) mkdir($destThumbPath, 0777);
		return $this->fileIoManager->replaceFileExtension($destThumbPath.basename($destImagePath), $this->imageThumbType);
	}
	
	/**
	 * creates thumbnails from the user-images
	 * only convert jpg, png, bmp files
	 * gif files a small enougth, so these files are not thumbnailed!
	 */
	public function createThumbnail($sourceImage, $thumbFile, $aspectRatio = true, $maxWidth = false, $maxHeight = false){
		if (!$maxWidth) return false;

		// get current image-extension
		$fileExtension = strtolower($this->fileIoManager->get_ext_form_file(basename($sourceImage)));
		
		// get image-informations
		$imageInfo = @getimagesize($sourceImage);
		if (!$imageInfo) return false;
		list($sourceImageWidth, $sourceImageHeight) = $imageInfo;
		list($destImageWidth, $destImageHeight) = $this->calculateMaxSize($sourceImageWidth, $sourceImageHeight, $maxWidth, $maxHeight);
		
		// dont convert gif-files!!!!
		if ($fileExtension == 'gif') return false;
		// if format (with+extension) matches, dont create thumbnails!!!!
		if ($sourceImageWidth <= $maxWidth && $fileExtension == 'jpg') return true;
		
		// now begin thumb-creation
		$image = false;
		switch($imageInfo['mime']) {
			case 'image/jpeg': $image = imagecreatefromjpeg($sourceImage); break;
			case 'image/png': $image = imagecreatefrompng($sourceImage); break;
			case 'image/bmp': $image = imagecreatefrombmp($sourceImage); break;
		}
		if (!$image) return false;
		$imageThumb = imagecreatetruecolor($destImageWidth, $destImageHeight);
		imagecopyresampled($imageThumb, $image, 0, 0, 0, 0, $destImageWidth, $destImageHeight, $sourceImageWidth, $sourceImageHeight);
		imageinterlace($imageThumb);
		
		// write the thumbnail to harddrive
		switch($this->imageThumbType) {
			case 'jpg': imagejpeg($imageThumb, $thumbFile, $this->imageThumbQuality); break;
			case 'png': print $this->imageThumbType." not implemented now!".LF; break;
			case 'bmp': print $this->imageThumbType." not implemented now!".LF; break;
			case 'gif': imagegif($imageThumb, $thumbFile); break;
		}
		imagedestroy($imageThumb);
		return true;
	}
	
	private function calculateMaxSize($sourceWidth, $sourceHeight, $maxWidth, $maxHeight = false) {
		$percWidth = $maxWidth * 100 / $sourceWidth;
		$destWidth = $sourceWidth * $percWidth / 100;
		$destHeight = $sourceHeight * $percWidth / 100;
		if ($maxHeight && $destHeight > $maxHeight) {
			$percHeight = $maxHeight * 100 / $destHeight;
			$destHeight = $destHeight * $percHeight / 100;
			$destWidth = $destWidth * $percHeight / 100;
		}
		return array($destWidth, $destHeight);
	}
	
	
	public function searchForRomImages($source = 'SAVED', $eccident, $crc32, $filePath = false, $fileExtension = false, $searchNames = false, $imageType = false, $onlyFirstFound = true) {
		if (!in_array($source, array('SAVED', 'UNSAVED'))) return false;
		if ($source == 'SAVED') return $this->searchForSavedRomImagesExtended($eccident, $crc32, $imageType, $onlyFirstFound);
		#else return $this->searchForUnavedRomImages($eccident, $crc32, $filePath, $fileExtension, $searchNames, $onlyFirstFound);
	}
	
	/***
	 * @todo remove UNK! :-)
	 */
	public function searchForSavedRomImagesExtended($eccident, $crc32, $imageType = false, $onlyFirstFound = true, $searchForThumb = false){
		
		$cacheImages = true;
		
		$imageDestFolder = $this->getUserImageCrc32Folder($eccident, $crc32, false);
		if (!$imageDestFolder || !is_dir($imageDestFolder)) return array();
		
		$imageData = array();
		
		if ($searchForThumb) {
			$imageDestFolder = $imageDestFolder.'/'.$this->imageThumbSubfolder;
			$cacheImages = false;
		}
		if (!is_dir($imageDestFolder)) return false;
		
		$dHdl = opendir($imageDestFolder);
		while(false !== $file = readdir($dHdl)) {
			if ($file == '.' || $file == '..') continue;
			$fileExtension = $this->fileIoManager->get_ext_form_file(basename($file));
			if (!$fileExtension) continue;
			
			if (isset($this->supportedExtensions[strtolower($fileExtension)]) && false !== strpos($file, $crc32)) {
				
				// new version of imagehandeling
				$possibleEccImageType = $this->exctractPossibleEccImageType($file);
				
				// direct return match!!!
				if ($onlyFirstFound && $possibleEccImageType == $imageType) {
					$this->cachedRomImages[$eccident][$crc32] = array(realpath($imageDestFolder.'/'.$file));
					return $this->cachedRomImages[$eccident][$crc32];
				}
				elseif ($onlyFirstFound && $this->matchImageType) {
					continue;
				}
				
				if (isset($this->eccImageTypes[$possibleEccImageType])) {
					$imageData['ecc'][$possibleEccImageType] = realpath($imageDestFolder.'/'.$file);
				}
				else $imageData['unk'][realpath($imageDestFolder.'/'.$file)] = true;
			}
		}
		
		# sort the selected image-type to front!
		if ($imageType && isset($imageData['ecc'][$imageType])) {
			$tmp = array();
			$tmp['ecc'][$imageType] = $imageData['ecc'][$imageType];
			unset($imageData['ecc'][$imageType]);
			foreach($imageData['ecc'] as $key => $value) $tmp['ecc'][$key] = $value;
			$imageData = $tmp;
		}
		if ($cacheImages) {
			return $this->cachedRomImages[$eccident][$crc32] = @$imageData['ecc'];
		}
		else {
			return @$imageData['ecc'];;
		}
		 
	}
	
	public function exctractPossibleEccImageType($fileName) {
		$plainName = $this->fileIoManager->get_plain_filename($fileName);
		$split = explode('_', $plainName);
		$output = @$split[3].'_'.@$split[4];
		if (isset($split[5])) $output .= '_'.@$split[5];
		return $output;
	}
	
	public function getValidFileName($fileName, $searchNames) {
		$fileExtension = $this->fileIoManager->get_ext_form_file(basename($fileName));
		if (isset($this->supportedExtensions[$fileExtension]) && $this->supportedExtensions[$fileExtension]) {
			foreach($searchNames as $name) {
				if (!$name) continue;
				if (false !== strpos(basename($fileName), $name)) return realpath($fileName);
			}
		}
		return false;
	}
	
	public function convertOldEccImages($eccident, $convert = true, $statusObject = false){
		
		if (!$eccident) return false;
		
		$sourceFolder = $this->getOldUserImageFolder($eccident, $this->iniManager->getUserFolder());
		if (!$sourceFolder || !is_dir($sourceFolder)) return false;
		
		$dHdl = opendir($sourceFolder);
		$count = 0;
		$char = '-';
		$progress = 0;
		while(false !== $file = readdir($dHdl)) {
			
			if ($convert && $statusObject) {
				if ($progress < 1000) $progress++;
				else $progress = 1;
				$statusObject->update_progressbar($progress/1000, $count);
				if ($statusObject->is_canceled()) return false;
			}
			
			if ($file == '.' || $file == '..') continue;
			$split = explode('_', $file);
			if ($split[0] != 'ecc') continue;
			
			$eccident = $split[1];
			$crc32 = $split[2];
			$sourceImagePath = $sourceFolder.'/'.$file;
			$destImageType = $this->exctractPossibleEccImageType($file);
			if (!$destImageType) continue;
			
			if($convert) {
				while (gtk::events_pending()) gtk::main_iteration();
				$this->storeUserImage('MOVE', $eccident, $crc32, $sourceImagePath, $destImageType, false);
				$char = (!isset($char) || $char == '--') ? '||' : '--';
				print $char." (".$count.") \r";
				$count++;
			}
			else {
				return true;
			}
		}
		return ($convert) ? $count : false;
	}
	
	public function convertAllOldEccImages($convert = true, $statusObject = false){
		$navigation = FACTORY::get('manager/IniFile')->getPlatformNavigation(false, false, true);
		$data = array();
		
		foreach ($navigation as $eccident => $platformName) {
			if ($eccident == 'NULL') continue;
			
			if ($convert && $statusObject) {
				$platformName = $this->iniManager->getPlatformNavigation($eccident);
				if (is_array($platformName)) $platformName = '';
				$message = "Converting images for $platformName ($eccident)".chr(13);
				$statusObject->update_message($message);
			}
			$data[$eccident] = $this->convertOldEccImages($eccident, $convert, $statusObject);
		}
		return $data;
	}
	
	/**
	 * simple error handeling
	 *
	 */
	
	public function setError($key, $value){
		$this->errors[$key] = $value;
	}
	
	public function hasErrors(){
		if (count($this->errors)) return true;
	}
	
	public function getErrorByKey($key){
		return (isset($this->errors[$key])) ? $this->errors[$key] : false;
	}
	
	public function getErrors(){
		return ($this->hasErrors()) ? $this->errors : array();
	}
	
	public function resetErrors(){
		$this->errors = array();
	}
}
?>
