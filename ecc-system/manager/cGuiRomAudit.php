<?
class GuiRomAudit extends GladeXml {
	
	private $data = array();
	private $eccGui;
	
	# manager
	private $iniManager;
	private $hasErrors = false;
	
	private $metaCategories = array();
	
	
	private $isInit = false;
	
	private $onFileRenameConfig = false;
	
	public function __construct($eccGui = false) {
		if (!$this->isInit) $this->prepareGui($eccGui);
	}
	
	public function isVisible(){
		return $this->romAudit->is_visible();
	}
	
	private function prepareGui($eccGui) {
		
		$this->eccGui = $eccGui;
		
		parent::__construct(ECC_DIR_SYSTEM.'/gui/guiRomAudit.glade');
		$this->signal_autoconnect_instance($this);
		$this->romAudit->modify_bg(Gtk::STATE_NORMAL, GdkColor::parse("#FFFFFF"));
		$this->romAudit->set_modal(false);
		$this->romAudit->set_keep_above(true);
		$this->buttonNameRepair->connect_simple('clicked', array($this, 'onRenameFile'));
		
		# translation
		$this->romAudit->set_title(i18n::get('romAudit', 'winRomAudit'));
		$this->tabLabelRomAudit->set_text(i18n::get('romAudit', 'tabLabelRomAudit'));
	
		$this->labelDatFileName->set_text(i18n::get('romAudit', 'labelDatFileName'));
		$this->labelCloneOf->set_text(i18n::get('romAudit', 'labelCloneOf'));
		$this->labelRomOf->set_text(i18n::get('romAudit', 'labelRomOf'));
		$this->labelMameDriver->set_text(i18n::get('romAudit', 'labelMameDriver'));
		$this->labelLastAuditTime->set_text(i18n::get('romAudit', 'labelLastAuditTime'));
		$this->labelHasTrashfiles->set_text(i18n::get('romAudit', 'labelHasTrashfiles'));
		$this->buttonNameRepair->set_label(i18n::get('romAudit', 'buttonNameRepair'));
		
		$this->labelFileName->set_text(i18n::get('global', 'fileName'));
		$this->labelFilePath->set_text(i18n::get('global', 'filePath'));
		$this->labelCrc32->set_text(i18n::get('global', 'crc32'));
		$this->labelPlatform->set_text(i18n::get('global', 'platform'));
		
		$this->btnClose->set_label(i18n::get('global', 'closeWindow'));
		
	}
	
	public function show($compositeId){
		$this->romAudit->set_visible(true);
		$this->getFileAuditData($compositeId);
		$this->romAudit->set_keep_above(true);
	}
	
	public function hide(){
		$this->romAudit->hide();
		#$this->romAudit->set_visible(false);
	}
		
    public function __get($widgedName) {
    	return self::get_widget($widgedName);
    }
	
	private function getFileAuditData($compositeId) {
		
		$ids = $this->extractCompositeId($compositeId);
		if (!count($ids)) return false;
		
		$fdataId = $ids['fdata_id'];
		$mdataId = $ids['mdata_id'];
		
		$q = "SELECT * FROM fdata_audit fa inner join fdata fd on (fa.fdataId = fd.id) WHERE fdataId = ".(int)$fdataId." LIMIT 1";
		
		$hdl = $this->dbms->query($q);
		if($row = $hdl->fetch(SQLITE_ASSOC)){
			
			$imageAuditState = "";
			
			$romSetType = $this->getAuditStateDescription(
				$row['fa.fDataId'],
				$row['fd.isMultiFile'],
				$row['fa.isMatch'],
				$row['fa.isValidMergedSet'],	
				$row['fa.isValidNonMergedSet'],
				$row['fa.isValidSplitSet'],
				$row['fa.cloneOf'],
				$row['fd.id']
			);
			
			$isValidNonMergedSet = ($row['fa.isValidNonMergedSet']) ? I18N::get('romAudit', 'isValidNonMergedSet') : '';
			$isValidSplitSet = ($row['fa.isValidSplitSet']) ? I18N::get('romAudit', 'isValidSplitSet') : '';
			$isValidMergedSet = ($row['fa.isValidMergedSet']) ? I18N::get('romAudit', 'isValidMergedSet') : '';
			
			$imageAuditStateImagePath = $this->getAuditStateIconFilename(
				$row['fa.fDataId'],
				$row['fd.isMultiFile'],
				$row['fa.isMatch'],
				$row['fa.isValidMergedSet'],	
				$row['fa.isValidNonMergedSet'],
				$row['fa.isValidSplitSet'],
				$row['fa.cloneOf'],
				$row['fd.id']
			);
			
			$datFileName = ($row['fa.fileName']) ? $row['fa.fileName'] : '';
			$fileName = ($row['fd.title']) ? $row['fd.title'] : '';
			$filePath = ($row['fd.path']) ? $row['fd.path'] : '';
			$fileData = ($row['fd.mdata']) ? print_r(unserialize(base64_decode($row['fd.mdata'])), true) : '';
			$fileEccident = $row['fd.eccident'];
			$fileCrc32 = $row['fd.crc32'];
			
			$isValidFileName = ($datFileName && $datFileName === $fileName) ? true : false;

			$cloneOf = ($row['fa.cloneOf']) ? $row['fa.cloneOf'] : '';
			
			$romOf = ($row['fa.romOf']) ? $row['fa.romOf'] : '';

			$hasTrashfiles = ($row['fa.hasTrashfiles']) ? $row['fa.hasTrashfiles'] : 0;
			
			$mameDriver = ($row['fa.mameDriver']) ? $row['fa.mameDriver'] : '';

			$foundFiles = ($row['fa.foundFiles']) ? unserialize($row['fa.foundFiles']) : '';
			
//			print "\n<pre>";
//			print_r($foundFiles);
//			print "<pre>\n";
//			
//			print "\n<pre>";
//			print_r($row);
//			print "<pre>\n";
			
			$lastAudiTime = date(i18n::get('mainList', 'detailDateFormat'), $row['fa.cDate']);
		}
		
		# only show this data, if this is an multifile
		$this->areaRomSetDetails->set_visible($row['fd.isMultiFile'] && $row['fa.isMatch']);
		$this->areaRomSetDetailsScroll->set_visible($row['fd.isMultiFile'] && $row['fa.isMatch']);
		
		$auditStatePixbuf = (@$imageAuditStateImagePath) ? FACTORY::get('manager/GuiHelper')->getPixbuf($imageAuditStateImagePath) : NULL;
		
		$this->imageAuditState->set_from_pixbuf($auditStatePixbuf);
		
		$this->textFileEccident->set_text(@$fileEccident);
		$this->textFileCrc32->set_text(@$fileCrc32);
		
		$textBuffer = new GtkTextBuffer();
		
		$matchData = "";
		if (isset($foundFiles['complete'])){
			$matchData .= i18n::get('romAudit', 'textHitDetailCompleteHeader').":\n";
			foreach($foundFiles['complete'] as $key => $data){
				$stringCount = ' ['.sprintf(i18n::get('romAudit', 'textHitDetailEntry%s%s'), $data['crc']['hit_count'], $data['crc']['total_count']).']';
				$matchData .= " ".$data['info']['name'].': '.$data['info']['description'].$stringCount."\n";
			}
		}
		
		if (isset($foundFiles['complete']) && isset($foundFiles['incomplete'])) $matchData .= "\n";
		
		if (isset($foundFiles['incomplete'])){
			$matchData .= i18n::get('romAudit', 'textHitDetailIncompleteHeader').":\n";
			foreach($foundFiles['incomplete'] as $key => $data){
				$stringCount = ' ['.sprintf(i18n::get('romAudit', 'textHitDetailEntry%s%s'), $data['crc']['hit_count'], $data['crc']['total_count']).']';
				$stringSplitSet = ($data['valid']['splitSet']) ? " (".trim(I18N::get('romAudit', 'isValidSplitSet'))."!)" : '';
				$matchData .= " ".$data['info']['name'].': '.$data['info']['description'].$stringCount.$stringSplitSet."\n";
			}
		}
		#$matchData .= "\n".print_r($foundFiles, true);

		$this->textRomSetType->set_text(@$romSetType);
		
		$this->textDatFileName->set_markup('<b>'.@$datFileName.'</b>');
		$this->textFileName->set_text(@$fileName);
		$this->textFilePath->set_text(@$filePath);

		if (!(int)@$isValidFileName && $row['fd.id']) {
			$this->buttonNameRepair->set_sensitive(true);
			$this->onFileRenameConfig = array(
				'compositeId' => $compositeId,
				'fileId' => $row['fd.id'],
				'sourceFilePath' => $filePath,
				'destFileName' => $datFileName,
			);
		}
		else{
			$this->buttonNameRepair->set_sensitive(false);
			$this->onFileRenameConfig = false;
		}
		
		$this->textCloneOf->set_text(@$cloneOf);
		$this->textRomOf->set_text(@$romOf);
		$this->textHasTrashfiles->set_text((int)@$hasTrashfiles);
		$this->textMameDriver->set_text(@$mameDriver);
		
		$textBuffer = new GtkTextBuffer();
		$textBuffer->set_text($matchData);
		$this->textFoundFiles->set_buffer($textBuffer);
		
		$this->textLastAuditTime->set_text(@$lastAudiTime);
		
		return false;
	}

	public function extractCompositeId($compositeId) {
		if (false === strpos($compositeId, "|")) return false;
		$ret = array();
		$split = explode("|", $compositeId);
		$ret['fdata_id'] = $split[0];
		$ret['mdata_id'] = $split[1];
		return $ret;
	}
	
	public function setDbms($dbmsObject) {
		$this->dbms = $dbmsObject;
	}

	public function getAuditStateIconFilename(
		$fileAuditId,
		$isMultiFile,
		$isMatch,
		$isValidMergedSet,
		$isValidNonMergedSet,
		$isValidSplitSet,
		$cloneOf,
		$fileId,
		$returnType = false
	){
	
		$setType = '';
		$setClone = '';
		
		if (!$isMultiFile) $setType = '_single';
		else {
			if (!$fileAuditId) $setType = '_unknown';
			else {
				if (!$isMatch) $setType = '_nomatch';
				else {
					if ($isValidMergedSet) $setType = '_merged';
					elseif ($isValidNonMergedSet) $setType = '_nonmerged';
					elseif ($isValidSplitSet) $setType = '_split';
					else $setType = '_miss';
				}
				$setClone = ($cloneOf) ? '_clone' : '';
			}
		}

		if (!$fileId) $setType = '_donthave';
		
		if($returnType){
			return array(
				'type' => substr($setType, 1),
				'clone' => (int)$cloneOf,
			);
		} else {
			return ECC_DIR_SYSTEM.'/images/audit/ecc'.$setType.$setClone.'_ok.gif';	
		}
		
	}

	public function getAuditStateDescription(
		$fileAuditId,
		$isMultiFile,
		$isMatch,
		$isValidMergedSet,
		$isValidNonMergedSet,
		$isValidSplitSet,
		$cloneOf,
		$fileId
	){
		
		$setType = '';
		$setClone = '';
		
		if (!$isMultiFile) $romSetType = I18N::get('romAudit', 'isSingle');
		else {
			if (!$fileAuditId) I18N::get('romAudit', 'auditMiss');
			else {
				if (!$isMatch) $romSetType = I18N::get('romAudit', 'isNotMatched');
				else {
					if ($isValidMergedSet) $romSetType = I18N::get('romAudit', 'isValidMergedSet');
					elseif ($isValidNonMergedSet) $romSetType = I18N::get('romAudit', 'isValidNonMergedSet');
					elseif ($isValidSplitSet) $romSetType = I18N::get('romAudit', 'isValidSplitSet');
					else $romSetType = I18N::get('romAudit', 'isIncompleteSet');
				}
				$setClone = ($cloneOf) ? ' (CLONE)' : '';
			}
		}
		return $romSetType.$setClone;
	}
	
	public function onRenameFile(){
		if (!$this->onFileRenameConfig || !realpath($this->onFileRenameConfig['sourceFilePath'])) {
			FACTORY::get('manager/Gui')->openDialogInfo(i18n::get('global', 'error_title'), i18n::get('romAudit', 'popupRomRenameAllreadyDone'));
			return false;
		}
		$this->romAudit->set_keep_below(true);
		
		$pGuiFileOp = FACTORY::create('manager/GuiPopFileOperations', $this->eccGui);
		$pGuiFileOp->setFdataId($this->onFileRenameConfig['fileId']);
		$pGuiFileOp->setSourceFileName(realpath($this->onFileRenameConfig['sourceFilePath']));
		$pGuiFileOp->setDestinationFileName($this->onFileRenameConfig['destFileName']);

		$pGuiFileOp->openRenameDialog(true);
		while($pGuiFileOp->done === NULL){
			while (gtk::events_pending()) gtk::main_iteration();
			if ($pGuiFileOp->done === true) $this->getFileAuditData($this->onFileRenameConfig['compositeId']);
			elseif ($pGuiFileOp->done === false) FACTORY::get('manager/Gui')->openDialogInfo(i18n::get('global', 'error_title'), i18n::get('romAudit', 'popupRomRenameFailed'), false, FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_mbox_error.png', true));
		}
	}
}

?>
