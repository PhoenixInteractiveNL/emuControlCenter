<?php
class RomFile extends Item {
	
	protected $id;
	protected $systemIdent;
	protected $crc32;
	protected $modified;
	
	protected $filePath;
	protected $filePathPacked;
	protected $fileSize;

	protected $isMultiFile;
	protected $parsedInfos;
	
	protected $launchTime;
	protected $launchCount;
	
	/**
	 * Array of all member variables used to create an crc32 checksum
	 * to compare rom metadata state changes getChecksum()
	 *
	 * @var Array of included member variables
	 */
	protected $checksumInclude = array(
		'id',
		'systemIdent',
		'crc32',
		'filePath',
		'filePathPacked',
		'fileSize',
		'isMultiFile',
		'launchTime',
		'launchCount',
	);
	
	public function finalize(){
		#$this->getChecksum(); // set checksum for this data
	}
	
	/**
	 * Get the basename of the file
	 *
	 * @return unknown
	 */
	public function getFileBasename(){
		return basename($this->filePath);
	}
	
	/**
	 * Get the filename without the fileextension
	 *
	 * @return string
	 */
	public function getFileExtension() {
		return $this->getExtension($this->filePath);
	}
	
	/**
	 * Get the filename without the fileextension
	 *
	 * @return string
	 */
	public function getPackedFileExtension() {
		return $this->getFileExtension($this->filePathPacked);
	}
	
	/**
	 * Extract and return the fileextension of the file
	 *
	 * @return string
	 */
	public function getExtension($path){
		$file = basename($path);
		if (false !== strpos($file, ".")) {
			$split = explode(".", $file);
			return array_pop($split);
		}
		return false;
	}
	
	/**
	 * Get the filename without the fileextension
	 *
	 * @return string
	 */
	public function getPlainFileName() {
		return $this->getPlainBasename($this->filePath);
	}
	
	/**
	 * Get the filename without the fileextension
	 *
	 * @return string
	 */
	public function getPlainFileNamePacked() {
		return $this->getPlainBasename($this->filePathPacked);
	}
	
	public function getPlainBasename($path){
		$file = basename($path);
		if (false !== strpos($file, ".")) {
			$split = explode(".", $file);
			array_pop($split);
			return join(".", $split);
		}
		return false;		
	}
	
	/**
	 * Return the file name without path!
	 *
	 * @return unknown
	 */
	public function getName(){
		return $this->getFileBasename();
	}
	
	/**
	 * Returns the real rom name by searching for a packed file
	 * If not packed, use filename from default path!
	 *
	 * @return string
	 */
	public function getRomFilenamePlain() {
		return ($this->getFilePathPacked()) ? $this->getPlainBasename($this->getFilePathPacked()) : $this->getPlainBasename($this->getFilePath());
	}
	
	/**
	 * Returns the real rom name without the fileextension
	 * by searching for a packed file. If not packed, use
	 * filename from default path!
	 *
	 * @return string
	 */
	public function getRomFilename() {
		return ($this->getFilePathPacked()) ? basename($this->getFilePathPacked()) : basename($this->getFilePath());
	}
	
	/**
	 * Returns the real rom name without the fileextension
	 * by searching for a packed file. If not packed, use
	 * filename from default path!
	 *
	 * @return string
	 */
	public function getRomExtension() {
		return ($this->getFilePathPacked()) ? $this->getExtension($this->getFilePathPacked()) : $this->getExtension($this->getFilePath());
	}
	
	/**
	 * Return the packed path if available, if not return the file path
	 *
	 * @return string
	 */
	public function getAvailableFilePath(){
		return ($this->filePathPacked) ? $this->filePathPacked : $this->filePath;
	}
	
	/**
	 * Convert the filesize and returns an formated string. 
	 *
	 * @param string $seperator
	 * @param mixed $usedUnits array of unitKey -> unitName pairs
	 * @return string
	 */
	public function getFileSizeString($seperator = ' / ', $usedUnits = false){
		
		if(!$usedUnits){
			$usedUnits = array(
				'mbit' => 'Mbit',
				'mb' => 'MB',
				'kb' => 'KB'
			);
		}
		
		$byte = $this->fileSize;
		
		$size = array();
		$size['b'] = $byte;
		$size['kb'] = round($byte/1024);
		$size['mb'] = round($byte/1024/1024, 1);
		$size['mbit'] = round($byte/1024/1024*8, 1);
		
		$out = array();
		foreach($usedUnits as $unitKey => $unitName){
			if($size[$unitKey] <= 0) continue;
			$out[] = $size[$unitKey].' '.$unitName;
		}
		return join($seperator, $out);
	}
	
	/**
	 * @param string $systemIdent
	 */
	public function setSystemIdent($systemIdent) {
		$this->systemIdent = strtolower($systemIdent);
	}
	
	/**
	 * Creates an array using the db result from TreeviewData getSearchResults()
	 *
	 * @param array $dbEntry of db entries from TreeviewData getSearchResults()
	 */
	public function fillFromDatabase($dbEntry){

		// set the identifier
		$this->setId($dbEntry['id']);
		$this->setSystemIdent($dbEntry['fd_eccident']);
		$this->setCrc32($dbEntry['crc32']);
		
		$this->setFilePath($dbEntry['path']);
		$this->setFilePathPacked($dbEntry['path_pack']);
		$this->setFileSize($dbEntry['size']);
		
		$this->setIsMultiFile($dbEntry['fd_isMultiFile']);
		
		$this->setParsedInfos($dbEntry['fd_mdata']);
		
		$this->setLaunchTime($dbEntry['fd_launchtime']);
		$this->setLaunchCount($dbEntry['fd_launchcnt']);
		
		$this->setModified();
	}
	
	public function isPacked(){
		return ($this->filePathPacked) ? true : false;
	}
	
	public function isMultiFile(){
		return ($this->getIsMultiFile()) ? true : false;
	}
}
?>