<?php
class MetaData {

	public $sourceName;
	public $strippedName;
	public $strippedMetaInfo = array();
	
	public $year;
	public $languages = array();
	public $media = array();
	public $publicDomain = array();
	public $usk = array();
	public $trainer = array();
	public $goodDump = array();
	
	/**
	 * @return unknown
	 */
	public function getGoodDump() {
		return $this->goodDump;
	}
	
	/**
	 * @return unknown
	 */
	public function getLanguages() {
		return $this->languages;
	}
	
	/**
	 * @return unknown
	 */
	public function getMedia() {
		return $this->media;
	}
	
	/**
	 * @return unknown
	 */
	public function getPublicDomain() {
		return $this->publicDomain;
	}
	
	/**
	 * @return unknown
	 */
	public function getSourceName() {
		return $this->sourceName;
	}
	
	/**
	 * @return unknown
	 */
	public function getStrippedMetaInfo() {
		return $this->strippedMetaInfo;
	}
	
	/**
	 * @return unknown
	 */
	public function getStrippedName() {
		return $this->strippedName;
	}
	
	/**
	 * @return unknown
	 */
	public function getTrainer() {
		return $this->trainer;
	}
	
	/**
	 * @return unknown
	 */
	public function getUsk() {
		return $this->usk;
	}
	
	/**
	 * @return unknown
	 */
	public function getYear() {
		return $this->year;
	}
	
	/**
	 * @param unknown_type $goodDump
	 */
	public function setGoodDump($goodDump) {
		$this->goodDump = $goodDump;
	}
	
	/**
	 * @param unknown_type $publicDomain
	 */
	public function setPublicDomain($publicDomain) {
		$this->publicDomain = $publicDomain;
	}
	
	/**
	 * @param unknown_type $sourceName
	 */
	public function setSourceName($sourceName) {
		$this->sourceName = $sourceName;
	}
	
	/**
	 * @param unknown_type $strippedMetaInfo
	 */
	public function setStrippedMetaInfo($strippedMetaInfo) {
		$this->strippedMetaInfo = $strippedMetaInfo;
	}
	
	/**
	 * @param unknown_type $strippedName
	 */
	public function setStrippedName($strippedName) {
		$this->strippedName = $strippedName;
	}
	
	/**
	 * @param unknown_type $trainer
	 */
	public function setTrainer($trainer) {
		$this->trainer = $trainer;
	}
	
	/**
	 * @param unknown_type $usk
	 */
	public function setUsk($usk) {
		$this->usk = $usk;
	}
	public function __construct($name) {
		$this->sourceName = $name;
		$this->strippedName = $name;
	}
	
	public function setMedia($media){
		foreach ($media as $key => $value) $this->media[$key] = $value;
	}
	
	public function setYear($year){
		$this->year = $year;
	}
	
	public function setLanguages($languages){
		$this->languages[$languages] = true;
	}

}

class DatFileHelper {
	
	/**
	 * The config is an array containing the regex rules
	 * to clean up a given name and extract meta informations
	 *
	 * @var string contains a array of regex rules
	 */
	private $nameStripConfig;
	
	/**
	 * Strip the metadata from the title
	 * 
	 * Metadata could be languages informations like (de, en, fr) or year (YYYY) aso
	 * Configuration is stored in the config imported by getNameStripConfig()
	 *
	 * Search for '(19' -> match regex, callback setYear with the match at index[1] (unnamed)
	 * '(19' => array(
	 *	'\((\d\d\d\d)\)',
	 *	'setYear',
	 *	array(1 => false),
	 * ),
	 * 
	 * Simple remove the string '(elf)' from the name
	 * '(elf)' => false,
	 * 
	 * Search for string beta, if found try the regex, if match remove only from name!
	 * 'beta' => array(
	 *	'\(.*?beta.*?\)'
	 * ),
	 * 
	 * Search for string '(E)', if found call callback and set 'E'
	 * '(E)' => array(
	 *	false,
	 *	'setLanguages',
	 *	'E'
	 * ),
	 * 
	 * It is alowed to concat more that one search word using ';' if the processing is the same for all
	 * '(EN);(E);(U)' => array(false, 'setLanguages', 'E'),
	 * 
	 * Search for string 'Disk', if found use regex to get the values -> call callback and set
	 * the current and the last disk as keys from index[1] and index[2]
	 * Name = (Disk 3 of 10) will produce the entry 'current' = 3, 'last' = 10
	 * '(Disk' => array(
	 *	'\(Disk (\d+).*?(\d+)\)',
	 *	'setMedia',
	 *	array(1 => 'current', 2 => 'last'),
	 * ),
	 * 
	 * @param string $name containing meta informations
	 * @return MetaData object
	 */
	public function stripMetaFromName($name) {
	
		// init the MetaData object!
		$meta = new MetaData($name);
		
		foreach ($this->nameStripConfig as $searchString => $searchConfig) {
			
			// it is allowed to concat searchStrings by ';',
			// e.g '(EN);(US);(E)' create an array first
			$split = explode(';', $searchString);
			foreach($split as $searchString){
			
				if (stripos($meta->strippedName, $searchString) === false) continue;
				
				if ($searchConfig === false) {
					// if no regex is given,  remove match from filename
					$this->stripByStringMatch($meta, $searchString);
				}
				else {
					// regex found
					if (isset($searchConfig[1]) && $searchConfig[1]) {
						// if there is an setter function given, use advanced replace
						$this->stripByAdvancedRegexMatch($meta, $searchString, $searchConfig);
					}
					else {
						// The simple regex match, remove match from filename
						$this->stripBySimpleRegexMatch($meta, $searchConfig[0]);
					}
				}
			}
		}
		return $meta;

	}
	
	/**
	 * Enter description here...
	 *
	 * @param MetaData $meta
	 * @param unknown_type $search
	 */
	private function stripByStringMatch(MetaData $meta, $search) {
		$nameTemp = $meta->strippedName;
		$meta->strippedName = str_ireplace($search, '', $meta->strippedName);
		if ($meta->strippedName != $nameTemp) array_push($meta->strippedMetaInfo, trim($search));
	}
	
	/**
	 * Enter description here...
	 *
	 * @param MetaData $meta
	 * @param unknown_type $regex
	 */
	private function stripBySimpleRegexMatch(MetaData $meta, $regex) {
		$matches = array();
		preg_match('/'.$regex.'/i', $meta->strippedName, $matches);
		if (isset($matches[0])) {
			$meta->strippedName = preg_replace('/'.$regex.'/i', "", $meta->strippedName);
			array_push($meta->strippedMetaInfo, trim($matches[0]));
		}
	}

	/**
	 * Enter description here...
	 *
	 * @param MetaData $meta
	 * @param unknown_type $searchString
	 * @param unknown_type $searchConfig
	 */
	private function stripByAdvancedRegexMatch(MetaData $meta, $searchString, $searchConfig) {
		
		$regex = $searchConfig[0];
		$callback = $searchConfig[1];
		$callbackParam = $searchConfig[2];
		
		if ($regex !== false) {
			
			$matches = array();
			
			preg_match('/'.$regex.'/i', $meta->strippedName, $matches);
			
			if (isset($matches[0])) {

				$meta->strippedName = preg_replace('/'.$regex.'/i', "", $meta->strippedName);

				$save_match = (isset($callbackParam)) ?  $callbackParam : false;
				
				// sometimes its needed to store the found matches
				if (isset($callbackParam) && is_array($callbackParam)) {
					// format in config: 'save_match' => array(1 => 'current', 2 => 'last'),
					foreach ($callbackParam as $position => $positionName){
						if($positionName === false){
							$saveString = $matches[$position];
						}
						else{
							$saveString[$positionName] = $matches[$position];
						}
					}
				}
				else {
					// format in config: 'save_match' => 2, or if this value is not set
					//$saveString = $matches[0];
					$saveString = $callbackParam;
				}
				
				$meta->$callback($saveString);
				array_push($meta->strippedMetaInfo, trim($matches[0]));
			}
		}
		else {
			$tempName = $meta->strippedName;
			$meta->strippedName = str_ireplace($searchString, "", $meta->strippedName);
			if ($meta->strippedName != $tempName) {
				$saveString = ($callbackParam !== false) ? $callbackParam : $searchString;
				$meta->$callback($saveString);
				array_push($meta->strippedMetaInfo, trim($searchString));
			}
		}
	}
	

	/**
	 * Enter description here...
	 *
	 */
	public function loadNameStripConfig() {
		$nameStripConfig = array();
		require_once getcwd().'/conf/datfile/eccNameStripConfig.php';
		$this->setNameStripConfig($nameStripConfig);
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $nameStripConfig
	 */
	private function setNameStripConfig($nameStripConfig) {
		$this->nameStripConfig = $nameStripConfig;
	}
	
	/**
	 * Enter description here...
	 *
	 * @return unknown
	 */
	private function getNameStripConfig() {
		return $this->nameStripConfig;
	}
	
}
?>