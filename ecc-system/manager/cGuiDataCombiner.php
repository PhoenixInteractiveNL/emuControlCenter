<?
class GuiDataCombiner extends GladeXml {
	
	private $data = array();
	private $eccGui;
	
	# manager
	private $iniManager;
	private $hasErrors = false;
	
	private $metaCategories = array();
	
	public function __construct($eccGui) {
		$this->eccGui = $eccGui;
		$this->prepareGui();
	}
	
	private function prepareGui() {
		parent::__construct(ECC_DIR_SYSTEM.'/gui2/guiDataCombiner.glade');
		$this->signal_autoconnect_instance($this);
		
		$this->dataCombiner->modify_bg(Gtk::STATE_NORMAL, GdkColor::parse("#FFFFFF"));
		
		$this->dataCombiner->set_modal(true);
		$this->dataCombiner->set_keep_above(true);
		
		$this->compareItem = new CompareItem();
		
		$this->paneHelp->set_markup("Please select an option!");
		
		$this->combinerTypeSelection->connect('button-press-event', array($this, 'test'), 1);
		$this->radiobutton2->connect('button-press-event', array($this, 'test'), 2);
		$this->radiobutton3->connect('button-press-event', array($this, 'test'), 3);
		
		$this->metaCategories = FACTORY::get('manager/Validator')->getEccCoreKey('media_category');
		
		$this->paneLeftMetaTitleMerge->connect_simple('button-press-event', array($this, 'mergeData'), 'left', 'metaName');
		$this->paneLeftMetaDeveloperMerge->connect_simple('button-press-event', array($this, 'mergeData'), 'left', 'metaCreator');
		$this->paneLeftMetaPublisherMerge->connect_simple('button-press-event', array($this, 'mergeData'), 'left', 'metaPublisher');
		$this->paneLeftMetaYearMerge->connect_simple('button-press-event', array($this, 'mergeData'), 'left', 'metaYear');
		$this->paneLeftMetaCategoryMerge->connect_simple('button-press-event', array($this, 'mergeData'), 'left', 'metaCategory');
		
		$this->paneRightMetaTitleMerge->connect_simple('button-press-event', array($this, 'mergeData'), 'right', 'metaName');
		$this->paneRightMetaDeveloperMerge->connect_simple('button-press-event', array($this, 'mergeData'), 'right', 'metaCreator');
		$this->paneRightMetaPublisherMerge->connect_simple('button-press-event', array($this, 'mergeData'), 'right', 'metaPublisher');
		$this->paneRightMetaYearMerge->connect_simple('button-press-event', array($this, 'mergeData'), 'right', 'metaYear');
		$this->paneRightMetaCategoryMerge->connect_simple('button-press-event', array($this, 'mergeData'), 'right', 'metaCategory');
	}
	
	public function mergeData($side, $field){
		$this->compareItem->mergeData($side, $field);
		$this->createGui();
	}
	
	public function test($button, $event, $type) {
		
		$this->optionBoxOtherVersion->hide();
		$this->optionBoxSeries->hide();
		
		$label = $button->get_label();
		switch($type) {
			case 1:
				$msg = "Sometimes, roms with different checksums are competely the same rom. This could be because of different headers of copy-stations.";
			break;
			case 2:
				$msg = "Most versions are distributed in different countries or modified with languages differing from the original rom!";
				$this->optionBoxOtherVersion->show();
			break;
			case 3:
				$msg = "Series like the games Mario Bros or the Sonic series could be connected togehter";
				$this->optionBoxSeries->show();
			break;
		}
		$this->paneHelp->set_markup($msg);
	}
	
	public function getCompareData($idLeft, $idRight){
		$this->setLeftSide($idLeft);
		$this->setRightSide($idRight);
		$this->createGui();
	}
	
	public function createGui(){
		
		$cData = $this->compareItem->getItem();
		
		$name = (!$cData['name']['equal']) ? '<span color="red">'.htmlentities($cData['name']['left']).'</span>' : htmlentities($cData['name']['left']);
		$this->paneLeftName->set_markup("<b>".$name."</b>");
		$name = (!$cData['name']['equal']) ? '<span color="red">'.htmlentities($cData['name']['right']).'</span>' : htmlentities($cData['name']['right']);
		$this->paneRightName->set_markup("<b>".$name."</b>");

		$platform = (!$cData['platform']['equal']) ? '<span color="red">'.htmlentities($cData['platform']['left']).'</span>' : htmlentities($cData['platform']['left']);
		$this->paneLeftPlatform->set_markup($platform);
		$platform = (!$cData['platform']['equal']) ? '<span color="red">'.htmlentities($cData['platform']['right']).'</span>' : htmlentities($cData['platform']['right']);
		$this->paneRightPlatform->set_markup($platform);

		$filename = (!$cData['filename']['equal']) ? '<span color="red">'.htmlentities($cData['filename']['left']).'</span>' : htmlentities($cData['filename']['left']);
		$this->paneLeftFileName->set_markup($filename);
		$filename = (!$cData['filename']['equal']) ? '<span color="red">'.htmlentities($cData['filename']['right']).'</span>' : htmlentities($cData['filename']['right']);
		$this->paneRightFileName->set_markup($filename);
		
		$filesize = (!$cData['filesize']['equal']) ? '<span color="red">'.htmlentities($cData['filesize']['left']).'</span>' : htmlentities($cData['filesize']['left']);
		$this->paneLeftSize->set_markup($filesize);
		$filesize = (!$cData['filesize']['equal']) ? '<span color="red">'.htmlentities($cData['filesize']['right']).'</span>' : htmlentities($cData['filesize']['right']);
		$this->paneRightSize->set_markup($filesize);
		
		$filecrc32 = (!$cData['filecrc32']['equal']) ? '<span color="red">'.htmlentities($cData['filecrc32']['left']).'</span>' : htmlentities($cData['filecrc32']['left']);
		$this->paneLeftCrc32->set_markup($filecrc32);
		$filecrc32 = (!$cData['filecrc32']['equal']) ? '<span color="red">'.htmlentities($cData['filecrc32']['right']).'</span>' : htmlentities($cData['filecrc32']['right']);
		$this->paneRightCrc32->set_markup($filecrc32);

		/**
		 * META
		 */
		
		# TITLE
		$metaName = (!$cData['metaName']['equal']) ? '<span color="red">'.htmlentities($cData['metaName']['left']).'</span>' : htmlentities($cData['metaName']['left']);
		$this->paneLeftMetaTitle->set_markup($metaName);
		$metaName = (!$cData['metaName']['equal']) ? '<span color="red">'.htmlentities($cData['metaName']['right']).'</span>' : htmlentities($cData['metaName']['right']);
		$this->paneRightMetaTitle->set_markup($metaName);
		
		$sensitiveState = ($cData['metaName']['equal']) ? false : true;
		$this->paneLeftMetaTitleMerge->set_sensitive($sensitiveState);
		$this->paneRightMetaTitleMerge->set_sensitive($sensitiveState);
		
		# DEVELOPER
		$metaCreator = (!$cData['metaCreator']['equal']) ? '<span color="red">'.htmlentities($cData['metaCreator']['left']).'</span>' : htmlentities($cData['metaCreator']['left']);
		$this->paneLeftMetaDeveloper->set_markup($metaCreator);
		$metaCreator = (!$cData['metaCreator']['equal']) ? '<span color="red">'.htmlentities($cData['metaCreator']['right']).'</span>' : htmlentities($cData['metaCreator']['right']);
		$this->paneRightMetaDeveloper->set_markup($metaCreator);
		
		$sensitiveState = ($cData['metaCreator']['equal']) ? false : true;
		$this->paneLeftMetaDeveloperMerge->set_sensitive($sensitiveState);
		$this->paneRightMetaDeveloperMerge->set_sensitive($sensitiveState);
		
		# PUBLISHER
		$metaPublisher = (!$cData['metaPublisher']['equal']) ? '<span color="red">'.htmlentities($cData['metaPublisher']['left']).'</span>' : htmlentities($cData['metaPublisher']['left']);
		$this->paneLeftMetaPublisher->set_markup($metaPublisher);
		$metaPublisher = (!$cData['metaPublisher']['equal']) ? '<span color="red">'.htmlentities($cData['metaPublisher']['right']).'</span>' : htmlentities($cData['metaPublisher']['right']);
		$this->paneRightMetaPublisher->set_markup($metaPublisher);
		
		$sensitiveState = ($cData['metaPublisher']['equal']) ? false : true;
		$this->paneLeftMetaPublisherMerge->set_sensitive($sensitiveState);
		$this->paneRightMetaPublisherMerge->set_sensitive($sensitiveState);
		
		# YEAR
		$metaYear = (!$cData['metaYear']['equal']) ? '<span color="red">'.htmlentities($cData['metaYear']['left']).'</span>' : htmlentities($cData['metaYear']['left']);
		$this->paneLeftMetaYear->set_markup($metaYear);
		$metaYear = (!$cData['metaYear']['equal']) ? '<span color="red">'.htmlentities($cData['metaYear']['right']).'</span>' : htmlentities($cData['metaYear']['right']);
		$this->paneRightMetaYear->set_markup($metaYear);
		
		$sensitiveState = ($cData['metaYear']['equal']) ? false : true;
		$this->paneLeftMetaYearMerge->set_sensitive($sensitiveState);
		$this->paneRightMetaYearMerge->set_sensitive($sensitiveState);
		
		# CATEGORY
		$metaCategory = (!$cData['metaCategory']['equal']) ? '<span color="red">'.htmlentities($this->metaCategories[$cData['metaCategory']['left']]).'</span>' : htmlentities($this->metaCategories[$cData['metaCategory']['left']]);
		$this->paneLeftMetaCategory->set_markup($metaCategory);
		$metaCategory = (!$cData['metaCategory']['equal']) ? '<span color="red">'.htmlentities($this->metaCategories[$cData['metaCategory']['right']]).'</span>' : htmlentities($this->metaCategories[$cData['metaCategory']['right']]);
		$this->paneRightMetaCategory->set_markup($metaCategory);
		
		$sensitiveState = ($cData['metaCategory']['equal']) ? false : true;
		$this->paneLeftMetaCategoryMerge->set_sensitive($sensitiveState);
		$this->paneRightMetaCategoryMerge->set_sensitive($sensitiveState);
		
	}
	
	public function setLeftSide($id) {
		$data = $this->getFileDataById($id);
		$this->compareItem->fillSide('left', $data);
		$this->data['left'] = $data;
	}
	
	public function setRightSide($id) {
		$data = $this->getFileDataById($id);
		$this->compareItem->fillSide('right', $data);
		$this->data['right'] = $data;
	}
	
	public function save(){
		$this->compareItem->setDbms($this->dbms);
		$this->compareItem->saveData();	
		$this->eccGui->onReloadRecord();
		$this->hide();
	}
	
	public function show(){
		$this->dataCombiner->present();
	}
	
	public function hide(){
		$this->dataCombiner->hide();
	}
		
    public function __get($widgedName) {
    	return self::get_widget($widgedName);
    }

    // called by FACTORY
	public function setDbms($dbmsObject) {
		$this->dbms = $dbmsObject;
	}
	
	private function getFileDataById($id) {
		if (!$id) return false;
		$ids = $this->extractCompositeId($id);
		if (!count($ids)) return false;
		
		if ($ids['fdata_id']) $q = "SELECT * FROM fdata fd LEFT JOIN mdata md on (fd.eccident=md.eccident AND fd.crc32=md.crc32) WHERE fd.id = ".(int)$ids['fdata_id']." LIMIT 1";	
		else $q = "SELECT * FROM mdata md LEFT JOIN fdata fd on (md.eccident=fd.eccident AND md.crc32=fd.crc32) WHERE md.id = ".(int)$ids['mdata_id']." LIMIT 1";
		$hdl = $this->dbms->query($q);
		if($row = $hdl->fetch(SQLITE_ASSOC)) return $row;
		return false;
	}
	
	private function addConncection($type, $leftId, $rightId) {
		if (!$leftId || !$rightId || !in_array($type, array('equal', 'modification', 'series'))) return false;
		
		$value = NULL;
		switch($type) {
			case 'equal':
				$value = true;
			break;
			default:
				$type = '';
				$value = '';
		}
		
		$q = "REPLACE INTO mdata_crossing (a, b, ".$type.") VALUES (".$leftId.", ".$rightId.", ".$value.")";
		print $q.LF;
		$hdl = $this->dbms->query($q);
		$q = "REPLACE INTO mdata_crossing (b, a, ".$type.") VALUES (".$leftId.", ".$rightId.", ".$value.")";
		print $q.LF;
		$hdl = $this->dbms->query($q);
		
		print "LEFT".LF.LF;
		$q="
		select b AS theId from mdata_crossing where a = ".(int)$leftId." and equal = 1
		union
		select a AS theId from mdata_crossing where b = ".(int)$leftId." and equal = 1
		";
		$hdl = $this->dbms->query($q);
		while($row = $hdl->fetch(1)) print $row['theId'].LF;
		
		print "RIGHT".LF.LF;
		$q="
		select b AS theId from mdata_crossing where a = ".(int)$rightId." and equal = 1
		union
		select a AS theId from mdata_crossing where b = ".(int)$rightId." and equal = 1
		";
		$hdl = $this->dbms->query($q);
		while($row = $hdl->fetch(1)) print $row['theId'].LF;

	}
	
	public function extractCompositeId($compositeId) {
		if (false === strpos($compositeId, "|")) return false;
		$ret = array();
		$split = explode("|", $compositeId);
		$ret['fdata_id'] = $split[0];
		$ret['mdata_id'] = $split[1];
		return $ret;
	}
	
}

class CompareItem{
	public $data = array();
	public $dbms;
	
	public function __construct(){
		$this->metaCategories = FACTORY::get('manager/Validator')->getEccCoreKey('media_category');
	}
	
	public function fillSide($side, $data){
		
		$this->data['fileId'][$side] = $data['fd.id'];
		$this->data['metaId'][$side] = $data['md.id'];
		$this->data['eccident'][$side] = ($data['fd.eccident']) ? $data['fd.eccident'] : $data['md.eccident'];
		
		$this->data['name'][$side] = ($data['md.name']) ? $data['md.name'] : $data['fd.title'];
		# rem
		$platformName = FACTORY::get('manager/IniFile')->getPlatformName($this->data['eccident'][$side]);
		$this->data['platform'][$side] = trim($platformName);		
		
		$filename = ($data['fd.path_pack']) ? $data['fd.path_pack'] : $data['fd.path'];
		
		# filedata
		$this->data['filename'][$side] = basename($filename);
		$this->data['filesize'][$side] = $data['fd.size'];
		$this->data['filecrc32'][$side] = $data['fd.crc32'];
		
		# metadata
		$this->data['metaEccident'][$side] = ($data['fd.eccident']) ? $data['fd.eccident'] : $data['md.eccident'];
		$this->data['metaCrc32'][$side] = $data['fd.crc32'];
		
		$this->data['metaName'][$side] = trim($data['md.name']);
		$this->data['metaCreator'][$side] = trim($data['md.creator']);
		$this->data['metaPublisher'][$side] = trim($data['md.publisher']);
		$this->data['metaYear'][$side] = trim($data['md.year']);
		$this->data['metaCategory'][$side] = (int)$data['md.category'];
	}
	
	public function getMatches(){
		foreach($this->data as $key => $data) {
			$this->data[$key]['equal'] = ($data['left'] == $data['right']) ? true : false;
		}
	}
	
	public function mergeData($side, $field){
		$source = $side;
		$destination = ($side == 'left') ? 'right' : 'left';
		$this->data[$field][$destination] = $this->data[$field][$source];
		$this->data[$field]['changed'] = $destination;
		return true;
	}
	
	public function saveData(){

		$dbQuery = array();
		foreach($this->data as $dbField => $sideData){
			if (0 !== strpos($dbField, 'meta')) continue;
			$dbField = strtolower(substr($dbField, 4));
			foreach($sideData as $side => $value) {
				if (!in_array($side, array('left', 'right'))) continue;
				if ($dbField != 'id' && $dbField != 'crc32' && $dbField != 'eccident') {
					if (!isset($sideData['changed']) || $sideData['changed'] != $side) continue;
				}
				$dbQuery[$side][$dbField] = sqlite_escape_string($value);
			}
		}
		foreach($dbQuery as $side => $data) {
			if(isset($data['id']) && $data['id']) {
				$id = "id = ".(int)$data['id'];
				unset($data['id']);
				$update = array();
				foreach($data as $key => $value) $update[] = "$key = '$value'";
				if (count($update) <= 2) continue;
				$update[] = 'cdate = '.time();
				$update[] = 'uexport = NULL';
				$q = "UPDATE mdata SET ".join(', ', $update)." WHERE $id";
				#print $q.LF;
				$this->dbms->query($q);
			}
			else {
				unset($data['id']);
				if (count($data) <= 2) continue;
				$data['cdate'] = time();
				$q = "INSERT INTO mdata (".join(', ', array_keys($data)).") VALUES ('".join("', '", $data)."')";
				#print $q.LF;
				$this->dbms->query($q);
			}
		}
	}
	
	public function getItem(){
		$this->getMatches();
		return $this->data;
	}
	
	public function setDbms($dbmsObject) {
		$this->dbms = $dbmsObject;
	}
}

?>
