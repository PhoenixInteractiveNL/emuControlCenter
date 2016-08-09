<?php
require_once 'Item.php';
class RomMeta extends Item {

	/**
	 * Option: By activating convertNameToMeta, the meta name is
	 * splitted to metainformations, if these are available in the
	 * name. e.g. (1of5) will converted meta entries for media_current/count
	 *
	 * @var boolean
	 */
	private $convertNameToMeta = false;
	
	/**
	 * Database id of the rom, only if available!
	 *
	 * @var integer id of rom from database
	 */
	protected $id;
	
	/**
	 * Internal identifier for this rom
	 * $systemIdent|$crc32 (e.g snes|AABBCCDD)
	 *
	 * @var string concated rom identifier
	 */
	protected $romIdent;
	
	/**
	 * system identifier (e.g. snes for super nintendo)
	 * @var string
	 */
	protected $systemIdent;
	
	/**
	 * crc32 checksum of the current RomMeta item
	 * @var string 8chars crc32 checksum
	 */
	protected $crc32;

	/**
	 * The name of this rom
	 *
	 * @var stromg
	 */
	protected $name;
	
	/**
	 * Meta: Developer of this rom
	 * @var string
	 */
	protected $developer;
	
	/**
	 * meta: Year
	 * @var integer format YYYY
	 */
	protected $year;
	
	protected $running;
	protected $bugs;
	protected $trainer;
	protected $intro;
	protected $usermod;
	protected $freeware;
	protected $multiplayer;
	protected $netplay;
	protected $usk;
	protected $storage;
	protected $rating;
	protected $category;
	protected $category_base;
	protected $publisher;
	protected $programmer;
	protected $musican;
	protected $graphics;
	protected $media_type;
	protected $media_current;
	protected $media_count;
	protected $region;
	protected $info;
	protected $info_id;
	protected $extension;
	protected $dump_type; // use filesize temporary
	protected $filesize; // use filesize temporary
		
	/**
	 * meta: array of supported languages
	 *
	 * @var Array of supported languages
	 */
	protected $languages = array();

	/**
	 * unix timestamp of the last change of meta data
	 * @var integer unix timestamp
	 */
	protected $modified;
	
	/**
	 * unix timestamp of the last export of meta data to romdb
	 * @var integer unix timestamp
	 */
	protected $exported;
	
	/**
	 * Array of all member variables used to create an crc32 checksum
	 * to compare rom metadata state changes getChecksum()
	 *
	 * @var Array of included member variables
	 */
	protected $checksumInclude = array(
		'id',
		'name',
		'developer',
		'year',
		'running',
		'trainer',
		'intro',
		'freeware',
		'multiplayer',
		'netplay',
		'usk',
		'storage',
		'rating',
		'category',
		'category_base',
		'publisher',
		'programmer',
		'musican',
		'graphics',
		'media_type',
		'media_current',
		'media_count',
		'region',
		'info',
		'info_id',
		'languages',
		'dump_type',
	);
	
	/**
	 * If activated, the metadata found in names is automaticly
	 * converted to corresponding meta informations.
	 *  
	 * @return boolean
	 */
	public function getConvertNameToMeta() {
		return $this->convertNameToMeta;
	}
	
	/**
	 * If activated, the metadata found in names is automaticly
	 * converted to corresponding meta informations.
	 * 
	 * @param boolean $convertNameToMeta
	 */
	public function setConvertNameToMeta($convertNameToMeta) {
		$this->convertNameToMeta = $convertNameToMeta;
	}
	
	public function finalize(){
		// if activated, try to convert meta from name to real meta entries
		if($this->getConvertNameToMeta()) $this->extractMetaFromName();
		
		// set checksum for this data
		#$this->getChecksum();
	}
	
	public function isValid(){
		// try to create the internal eccIdent for this rom!
		if(
			!$this->createRomIdent() ||
			!$this->isValidString($this->name, 1, 255)
		) return false;
		
		return true; // all tests ok, than its valid!
	}

	public function isValidMedia(){
		
		$mediaCurrent = $this->getMedia_current();
		$mediaCount = $this->getMedia_count();

		if (
			($mediaCount && $mediaCurrent > $mediaCount) ||
			($mediaCurrent && !$mediaCount) ||
			(!$mediaCurrent && $mediaCount)
		) {
			return false;
		}
		else{
			return true;	
		}
		
	}
	
	/**
	 * Extract metadata from meta name by regular expressions
	 * and set the found data to the corresponding member variables!
	 * 
	 * Later this function will use the name stripper!
	 * 
	 * @todo using preg match callbacks to remove strings!
	 */
	public function extractMetaFromName(){
		
		// extract media informations (1of5)
		$matches = array();
		if(preg_match('/\((\d)\s*?(\/|of)\s*?(\d)\)/i', $this->getName(), $matches)){
			if($matches[3] < $matches[1]) return false;
			$this->setMedia_current((int)$matches[1]); // current
			$this->setMedia_count((int)$matches[3]); // count
			$this->setName(trim(str_replace($matches[0], '', $this->getName())));
		}
		return true;
	}
	
	/**
	 * @param string $romIdent
	 */
	public function getRomIdent() {
		return $this->createRomIdent();
	}
	
	/**
	 * @param string $romIdent
	 */
	public function setRomIdent($romIdent) {
		$this->romIdent = $romIdent;
	}
	
	/**
	 * @param string $romIdent
	 */
	private function createRomIdent() {
		if(!$this->systemIdent || !$this->crc32){
			$this->setError('systemIdent or crc32 missing');
			return false;
		}
		else{
			return $this->romIdent = $this->systemIdent.'|'.$this->crc32;;
		}
	}
	
	/**
	 * @param string $systemIdent
	 */
	public function setSystemIdent($systemIdent) {
		$this->systemIdent = strtolower($systemIdent);
	}
	
	/**
	 * @return string
	 */
	public function getCrc32() {
		return $this->crc32;
	}
	
	/**
	 * @param string $crc32
	 */
	public function setCrc32($crc32) {
		$this->crc32 = $crc32;
	}

	/**
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * @param integer $id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @return stromg
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * @param stromg $name
	 */
	public function setName($name) {
		$this->name = $name;
	}
	
	public function getFormatedName() {
		$mediaInfo = ($this->getMedia_current() && $this->getMedia_count()) ? ' ('.$this->getMedia_current().'/'.$this->getMedia_count().')' : '';
		return $this->name.$mediaInfo;
	}
	
	/**
	 * @return unknown
	 */
	public function getDeveloper() {
		return $this->developer;
	}
	
	/**
	 * @param unknown_type $developer
	 */
	public function setDeveloper($developer) {
		$this->developer = $developer;
	}
	
	/**
	 * @return unknown
	 */
	public function getYear() {
		return $this->year;
	}
	
	/**
	 * @param unknown_type $year
	 */
	public function setYear($year) {
		if((int)$year == 0) $year = null;
		$this->year = $year;
	}
	
	/**
	 * @return integer unix timestamp
	 */
	public function getModified() {
		return $this->modified;
	}
	
	/**
	 * @param integer $modified unix timestamp
	 */
	public function setModified($modified = false) {
		$this->modified = $modified;
	}
	
	/**
	 * @return integer unix timestamp
	 */
	public function getExported() {
		return $this->exported;
	}
	
	/**
	  * @param integer $exported unix timestamp
	 */
	public function setExported($exported = false) {
		$this->exported = $exported;
	}
	
	public function setMetaDefault(Array $metaDefaults){
		foreach($metaDefaults as $key => $value){
			$getFunction = 'set'.ucfirst($key);
			$this->$getFunction($value);
		}
	}
	
	/**
	 * Creates an array using the db result from TreeviewData getSearchResults()
	 *
	 * @param array $dbEntry of db entries from TreeviewData getSearchResults()
	 */
	public function fillFromDatabase($dbEntry){
		
		// set the identifier
		$this->setId($dbEntry['md_id']);
		$this->setSystemIdent($dbEntry['md_eccident']);
		$this->setCrc32($dbEntry['md_crc32']);
		
		// set metadata
		$this->setName($dbEntry['md_name']);
		$this->setDeveloper($dbEntry['md_creator']);
		$this->setYear($dbEntry['md_year']);
		
		$this->setRunning($dbEntry['md_running']);
		$this->setBugs($dbEntry['md_bugs']);
		$this->setTrainer($dbEntry['md_trainer']);
		$this->setIntro($dbEntry['md_intro']);
		$this->setUsermod($dbEntry['md_usermod']);
		$this->setFreeware($dbEntry['md_freeware']);
		$this->setMultiplayer($dbEntry['md_multiplayer']);
		$this->setNetplay($dbEntry['md_netplay']);
		$this->setUsk($dbEntry['md_usk']);
		
		$this->setStorage($dbEntry['md_storage']);
		$this->setRating($dbEntry['md_rating']);
		$this->setCategory($dbEntry['md_category']);
		$this->setCategory_base(false);
		
		$this->setPublisher($dbEntry['md_publisher']);
		$this->setProgrammer($dbEntry['md_programmer']);
		$this->setMusican($dbEntry['md_musican']);
		$this->setGraphics($dbEntry['md_graphics']);
		
		$this->setMedia_type($dbEntry['md_media_type']);
		$this->setMedia_current($dbEntry['md_media_current']);
		$this->setMedia_count($dbEntry['md_media_count']);
		$this->setRegion($dbEntry['md_region']);
		
		$this->setInfo($dbEntry['md_info']);
		$this->setInfo_id($dbEntry['md_info_id']);
		$this->setLanguages(array());
		
		$this->setModified($dbEntry['md_cdate']);
		$this->setExported($dbEntry['md_uexport']);
		
		$this->setDump_type($dbEntry['md_dump_type']);
		
	}
	
	public function getCleanInteger($field, $storeEmptyString = false, $storeNullInteger = false){
		
		if($storeNullInteger &&  $field === 0){
			return 0;
		}
		elseif(is_null($field) || $field == 0){
			return ($storeEmptyString) ? "''" : "NULL";
		}
		else{
			return (int)$field;
		}
	}
	
	public function getCleanString($field){
		if(!$field){
			return "NULL";
		}
		else{
			return "'".sqlite_escape_string($field)."'";
		}
	}
	
	public function getNullsaveInteger($value){
		if(!isset($value)){
			return 'NULL';
		}
		elseif($value === 0){
			return 0;
		}
		else{
			return (int)$value;
		}
	}
	
	/**
	 * Store the current romMeta object to the database
	 * Also update the languages
	 *
	 * $storeEmptyString is needed for media_current. The rom list query
	 * getSearchResults cant use NULL values to concat (md.name || md.media_current)
	 * 
	 * @param resource $dbms
	 */
	public function store($dbms){
		
		if($this->getId()){

			// update, because id already available
			
			$q = "
			UPDATE
			mdata
			SET
			name = ".$this->getCleanString($this->getName()).",
			info = ".$this->getCleanString($this->getInfo()).",
			info_id = ".$this->getCleanString($this->getInfo_id()).",
			
			running = ".$this->getNullsaveInteger($this->getRunning()).",
			bugs = ".$this->getNullsaveInteger($this->getBugs()).",
			trainer = ".$this->getNullsaveInteger($this->getTrainer()).",
			intro = ".$this->getNullsaveInteger($this->getIntro()).",
			usermod = ".$this->getNullsaveInteger($this->getUsermod()).",
			multiplayer = ".$this->getNullsaveInteger($this->getMultiplayer()).",
			netplay = ".$this->getNullsaveInteger($this->getNetplay()).",
			freeware = ".$this->getNullsaveInteger($this->getFreeware()).",
			
			year = ".$this->getCleanInteger($this->getYear()).",
			usk = ".$this->getCleanInteger($this->getUsk()).",
			category = ".$this->getCleanInteger($this->getCategory()).",
			creator = ".$this->getCleanString($this->getDeveloper()).",
			publisher = ".$this->getCleanString($this->getPublisher()).",
			programmer = ".$this->getCleanString($this->getProgrammer()).",
			musican = ".$this->getCleanString($this->getMusican()).",
			graphics = ".$this->getCleanString($this->getGraphics()).",
			media_type = ".$this->getCleanInteger($this->getMedia_type()).",
			media_current = ".$this->getCleanInteger($this->getMedia_current()).",
			media_count = ".$this->getCleanInteger($this->getMedia_count()).",
			storage = ".$this->getCleanInteger($this->getStorage()).",
			region = ".$this->getCleanInteger($this->getRegion()).",
			dump_type = ".$this->getCleanInteger($this->getDump_type()).",
			cdate = ".time().",
			uexport = NULL
			WHERE
			id = ".$this->getId()."
			";
			//print $q;
			$hdl = $dbms->query($q);
			
		}
		else{
			
			// insert, because id is missing
			
			$q = "
			INSERT INTO mdata (
				eccident,
				name,
				crc32,
				extension,
				info,
				info_id,
				running,
				bugs,
				trainer,
				intro,
				usermod,
				freeware,
				multiplayer,
				netplay,
				year,
				usk,
				category,
				creator,
				publisher,
				programmer,
				musican,
				graphics,
				media_type,
				media_current,
				media_count,
				storage,
				region,
				dump_type,
				cdate
			) VALUES (
				".$this->getCleanString($this->getSystemIdent()).",
				".$this->getCleanString($this->getName()).",
				".$this->getCleanString($this->getCrc32()).",
				".$this->getCleanString($this->getExtension()).",
				".$this->getCleanString($this->getInfo()).",
				".$this->getCleanString($this->getInfo_id()).",
				
				".$this->getNullsaveInteger($this->getRunning()).",
				".$this->getNullsaveInteger($this->getBugs()).",
				".$this->getNullsaveInteger($this->getTrainer()).",
				".$this->getNullsaveInteger($this->getIntro()).",
				".$this->getNullsaveInteger($this->getUsermod()).",
				".$this->getNullsaveInteger($this->getFreeware()).",
				".$this->getNullsaveInteger($this->getMultiplayer()).",
				".$this->getNullsaveInteger($this->getNetplay()).",
				
				".$this->getCleanInteger($this->getYear()).",
				".$this->getCleanInteger($this->getUsk()).",
				".$this->getCleanInteger($this->getCategory()).",
				".$this->getCleanString($this->getDeveloper()).",
				".$this->getCleanString($this->getPublisher()).",
				".$this->getCleanString($this->getProgrammer()).",
				".$this->getCleanString($this->getMusican()).",
				".$this->getCleanString($this->getGraphics()).",
				".$this->getCleanInteger($this->getMedia_type()).",
				".$this->getCleanInteger($this->getMedia_current(), $storeEmptyString = true).",
				".$this->getCleanInteger($this->getMedia_count()).",
				".$this->getCleanInteger($this->getStorage()).",
				".$this->getCleanInteger($this->getRegion()).",
				".$this->getCleanInteger($this->getDump_type()).",
				".time()."
			)
			";
			$hdl = $dbms->query($q);
			
			// set the new meta id used for store languages
			$this->setId($dbms->lastInsertRowid());
				
		}
		
		// update also the languages using the meta id!
		$this->storeLanguages($dbms);
	}
	
	/**
	 * Remove old language ans store the new selection!
	 *
	 * @param resource $dbms
	 */
	public function storeLanguages($dbms) {
		
		// first remove old entries
		$q = "
		DELETE FROM mdata_language
		WHERE
		mdata_id=".(int)$this->getId()."
		";
		$dbms->query($q);
		
		// now insert new ones
		foreach ($this->getLanguages() as $languageIdent => $void) {
			$q = "
			INSERT INTO mdata_language (
				mdata_id,
				lang_id
			) VALUES (
				".(int)$this->getId().",
				'".sqlite_escape_string($languageIdent)."'
			)
			";
			$dbms->query($q);
		}
	}
}
?>