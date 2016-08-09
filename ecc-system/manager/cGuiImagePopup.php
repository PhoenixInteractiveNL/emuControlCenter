<?
class GuiImagePopup {
	
	/**
	 * Holds the current selected rom object
	 *
	 * @var Rom object
	 */
	private $selectedRom;
	
	private $gui = false;

	private $imagePosition = 0;
	
	/**
	 * contains the given imagetypes as array
	 *
	 * @var array of image types
	 */
	private $imageTypes = array();
	
	private $selectedImageType = false;
	
	private $imageTank = array();
	
	private $imageFit = true;
	
	private $statusbar_context_id;
	
	private $opened_state = false;
	
	private $fileIoManager;
	
	private $dropZoneBgColor;
	
	private $dropZoneBgColor2;
	
	private $dropZoneBgColorSelected;
	
	/**
	 * array only used to unset unselected eventboxes
	 *
	 * @var array
	 */
	private $slotEventObjects = array();
	
	private $currentSlotSelection = false;
	
	public function __construct($gui_obj=false) {
		if (!$gui_obj) return false;
		$this->gui = $gui_obj;
		
		$this->fileIoManager = FACTORY::get('manager/FileIO');
		$this->imageManager = FACTORY::get('manager/Image');
		$this->iniManager = FACTORY::get('manager/IniFile');
		$this->guiManager = FACTORY::get('manager/Gui');

		$this->initEnviroment();
		$this->connect_signals();
		$this->twImageInit();
		$this->imageFit = !$this->gui->ini->getHistoryKey('imageCenterDefaultSize');
	}
	
	/**
	 * Set the current selected Rom object containinf RomFile,
	 * RomMeta and RomAudit objects
	 *
	 * @param Rom $rom
	 */
	public function setSelectedRom(Rom $rom){
		$this->selectedRom = $rom;
	}
	
	/**
	 * Get the current selected Rom object containinf RomFile,
	 * RomMeta and RomAudit objects
	 *
	 * @return Rom object
	 */
	public function getSelectedRom(){
		return $this->selectedRom;
	}
	
	public function initEnviroment() {

		# dropzone default background
		$this->dropZoneBgColor = $this->iniManager->getKey('GUI_COLOR', 'colImgSlotUnsetBgChooser');
		if (!$this->dropZoneBgColor) $this->dropZoneBgColor = '#EAEAEA';
		
		# dropzone image set background
		$this->dropZoneBgColor2 = $this->iniManager->getKey('GUI_COLOR', 'colImgSlotSetBgChooser');
		if (!$this->dropZoneBgColor2) $this->dropZoneBgColor2 = '#CCDDC6';
		
		# dropzone selected background
		$this->dropZoneBgColorSelected = $this->iniManager->getKey('GUI_COLOR', 'colImgSlotUnsetSelectChooser');
		if (!$this->dropZoneBgColorSelected) $this->dropZoneBgColorSelected = '#A2AF9D';
		
		# dropzone selected background
		$this->dropZoneBgColorUnsetSelected = $this->iniManager->getKey('GUI_COLOR', 'colImgSlotSetSelectChooser');
		if (!$this->dropZoneBgColorUnsetSelected) $this->dropZoneBgColorUnsetSelected = '#CECECE';
		
		# dropzone selected background
		$this->dropZoneBgColorText = $this->iniManager->getKey('GUI_COLOR', 'colImgSlotTextChooser');
		if (!$this->dropZoneBgColorText) $this->dropZoneBgColorText = '#000000';
		
		$imageCenterHidePopup = $this->iniManager->getHistoryKey('imageCenterHidePopup');
		$this->gui->mediaCenterOptConfirm->set_active(!$imageCenterHidePopup);
		
		$imageCenterTransferType = $this->iniManager->getHistoryKey('imageCenterConfirmPopupState');
		if ($imageCenterTransferType == 'MOVE') $this->gui->mediaCenterOptRadioMove->set_active(true);
		
		$imageCenterSlotsHidden = $this->iniManager->getHistoryKey('imageCenterSlotsHidden');
		$this->gui->mediaCenterOptExpand->set_expanded(!$imageCenterSlotsHidden);
		
		$this->gui->mediaCenterOptExpand->set_use_markup(true);
		
		# set position and size settings
		$this->setPopupPosition();
		$this->setPopupSize();

		$this->gui->imgPopup_btn_prev_top->set_label(i18n::get('global', 'prevShort'));
		$this->gui->imgPopup_btn_next_top->set_label(i18n::get('global', 'next'));
		$this->gui->imgCenterButtonClose->set_label(i18n::get('global', 'close'));
		$this->gui->mediaCenterOptBtnFolder->set_label(i18n::get('imageCenter', 'mediaCenterOptBtnFolder'));
		$this->gui->imageCenterStorePosition->set_label(i18n::get('imageCenter', 'imageCenterStorePosition'));
		$this->gui->mediaCenterOptConfirm->set_label(i18n::get('imageCenter', 'mediaCenterOptConfirm'));
		$this->gui->mediaCenterOptRadioMove->set_label(i18n::get('imageCenter', 'mediaCenterOptRadioMove'));
		$this->gui->mediaCenterOptRadioCopy->set_label(i18n::get('imageCenter', 'mediaCenterOptRadioCopy'));
		$this->gui->mediaCenterOptExpand->set_label('<b>'.i18n::get('imageCenter', 'imageSlotLabel').'</b>');
		
	}
	
	public function updateImages($imageType = false){
		
		$rom = $this->getSelectedRom();
		
		$romFile = $rom->getRomFile();
		$romMeta = $rom->getRomMeta();
		
		$searchNames = array(
			$romFile->getRomFilenamePlain(),
			$romFile->getFilePathPacked(),
			$romMeta->getName()
		);
		
		$imageTank = $this->imageManager->searchForRomImages(
			'SAVED',
			$rom->getSystemIdent(),
			$rom->getCrc32(),
			dirname($romFile->getFilePath()),
			$romFile->getRomExtension(),
			$searchNames,
			$imageType,
			$onlyFirstFound = false,
			$cacheImages = false
		);
		
		$imageTankTemp = array();
		foreach($this->imageManager->getEccImageTypes() as $key => $void){
			$imageTankTemp[$key] = (isset($imageTank[$key])) ? $imageTank[$key] : false;
		}
		$imageTank = $imageTankTemp;
		
		// add selected imagetype to front of the array!
		if (isset($imageTank[$imageType])) {
			$this->selectedImageType = $imageType;
			$imageTank=array($imageType=>$imageTank[$imageType]) + $imageTank;
		}
		
		// quickhack to get an indexed array
		$this->imageTankFlat = $imageTank;
		$this->imageTank = array();
		if (count($imageTank)) {
			$pos = 0;
			foreach($imageTank as $type => $path) {
				$this->imageTank[$pos]['type'] = $type;
				$this->imageTank[$pos]['path'] = $path;
				$this->imageTank[$pos]['iter'] = false;
				$pos++;
			}
		}
	}
	
	public function show(Rom $rom, $imageType) {

		// first set the selected rom object
		$this->setSelectedRom($rom);
		
		if (!$this->is_opened()) $this->setPopupPosition();
		
		// if an user has selected an slot, make this selection sticky
		if ($this->currentSlotSelection) $imageType = $this->currentSlotSelection;
		
		$this->updateImages($imageType);
		
		$this->gui->win_imagePopup->modify_bg(Gtk::STATE_NORMAL, GdkColor::parse("#FFFFFF"));
		$this->gui->viewport2->modify_bg(Gtk::STATE_NORMAL, GdkColor::parse("#FFFFFF"));
		$this->gui->win_imagePopup->show();
		
		// only some rom output!!
		$infoString = '<b>'.i18n::get('global', 'platform').':</b> '.$rom->getSystemIdent().' | <b>'.i18n::get('global', 'name').':</b> '.htmlspecialchars($rom->getName()).' | <b>'.i18n::get('global', 'crc32').':</b> '.$rom->getCrc32().' | <b>'.i18n::get('global', 'fileNameShort').':</b> '.htmlspecialchars($rom->getRomFile()->getRomFilenamePlain()).'';
		$this->gui->mediaCenterInfo->set_markup($infoString);
		
		$this->updateImagePosition();
		$this->twImageFill();
		$this->updateImage();
		
		$this->gui->win_imagePopup->set_keep_above(true);
		
		$this->opened_state = true;
		
		$this->createImageDataArray();
		$this->createExtensionTable();
	}

	private function createImageDataArray() {
		foreach ($this->gui->image_type as $key => $value) {
			$split = explode('_', $key);
			if (isset($split[2])){
				$this->imageTypes[$split[0]][$split[1]][(int)$split[2]]['pos'] = $split[2];
			}
			$this->imageTypes[$split[0]][$split[1]][(int)@$split[2]]['label'] = $value;
			$this->imageTypes[$split[0]][$split[1]][(int)@$split[2]]['key'] = $key;
		}
	}
	
	private function createExtensionTable($unavailableSlot = false) {
		
		$frameChild = $this->gui->imageTypeSelector->child;
		if ($frameChild) $this->gui->imageTypeSelector->remove($frameChild);
		
		$table = new GtkTable();
		$table->set_homogeneous(true);
		$this->gui->imageTypeSelector->add($table);
		
		$currentCol = 0;
		$currentRow = 0;
		foreach($this->imageTypes as $key => $value) {
			
			// set current type label for row			
			$widged = new GtkLabel();
			$widged->set_markup(ucfirst($key));
			$table->attach($widged, $currentCol, $currentCol+1, $currentRow, $currentRow+1, Gtk::EXPAND, Gtk::EXPAND, 0, 0);
			$currentCol++;
			
			// process image-types for current type
			foreach($value as $key2 => $value2) {
				foreach($value2 as $key3 => $value3) {
					
					$available = false;
					
					// get current label
					$label = ucfirst($key2);
					
					if (isset($value3['pos'])){
						$label .= (!is_numeric($value3['pos'])) ? ' '.ucfirst($value3['pos']) : ' '.sprintf("%02d", $value3['pos']);
					}
					
					$widged = new GtkLabel();
					$widged->set_markup('<span color="'.$this->dropZoneBgColorText.'">'.$label.'</span>');
					
					$bgColor = $this->dropZoneBgColor;
					if ($this->imageTankFlat[$value3['key']]) {
						$available = true;
						$bgColor = ($this->selectedImageType == $value3['key']) ? $this->dropZoneBgColorSelected : $this->dropZoneBgColor2;
					}
					elseif ($unavailableSlot == $value3['key']) $bgColor = $this->dropZoneBgColorUnsetSelected;
					
					$oEvent = new GtkEventBox();
					$oEvent->set_size_request(50, 21);
					$oEvent->modify_bg(Gtk::STATE_NORMAL, GdkColor::parse($bgColor));
					
					$oEvent->drag_dest_set(Gtk::DEST_DEFAULT_ALL, array(array('text/uri-list', 0, 0)), Gdk::ACTION_COPY);
					$oEvent->connect("button-press-event", array($this, 'dispatchSlotSelection'), $value3['key'], $available);
					$oEvent->connect("drag-data-received", array($this, 'onDropDragData'), $value3['key']);
					
					$oEvent->connect_simple_after('button-press-event', array($this, 'onItemSelect'), $value3['key']);
					$oEvent->add($widged);
					
					# only for unsetting
					$this->slotEventObjects[$value3['key']]['object'] = $oEvent;
					$this->slotEventObjects[$value3['key']]['color'] = $bgColor;
					
					$widged = $oEvent;
					
					$table->attach($widged, $currentCol, $currentCol+1, $currentRow, $currentRow+1, Gtk::EXPAND, Gtk::EXPAND, 0, 0);
					$currentCol++;
				}
			}
			$currentCol = 0;
			$currentRow++;
		}

		$table->set_homogeneous(true);
		$table->set_row_spacings(5);
		$table->set_col_spacings(5);
		
		$this->gui->imageTypeSelector->show_all();
		
	}

	public function onDropDragData($widged, $context, $x, $y, $data, $info, $time, $destImageType) {
		
		$uriList = explode("\n", $data->data);
		if (isset($uriList[0]) && $uriList[0]) {
			
			$filename = $uriList[0];
			
			# hack, because + signs are not urlencoded!!!
			$filename = str_replace('+', '%2B', $filename);
			$sourceImagePath = urldecode(trim(str_replace('file:///', '', $filename)));
			
			$this->addImageToSlot($sourceImagePath, $destImageType);
		}
	}
	
	private function addImageToSlot($sourceImagePath, $slotName = false){
		
		$this->imageManager = FACTORY::get('manager/Image');
		
		$this->imageManager->resetErrors();
		
		if (!file_exists($sourceImagePath)) return false;
		
		#$this->gui->win_imagePopup->set_keep_below(true);
		
		$transferModeState = $this->gui->mediaCenterOptRadioCopy->get_active();

		$transferModeStrg = ($transferModeState) ? strtoupper(i18n::get('imageCenter', 'transferModeCopy')) : strtoupper(i18n::get('imageCenter', 'transferModeMove'));
		$transferMode = ($transferModeState) ? 'COPY' : 'MOVE';

		if ($this->gui->mediaCenterOptConfirm->get_active()) {
			$title =  i18n::get('imageCenter', 'imageAddConfirmTitle');
			$msg =  sprintf(i18n::get('imageCenter', 'imageAddConfirmMsg%s%s%s'), $transferModeStrg, basename($sourceImagePath), $slotName);
			if (!$this->guiManager->openDialogConfirm($title, $msg)) return false;
		}

		$romSystemIdent = $this->getSelectedRom()->getSystemIdent();
		$romCrc32 = $this->getSelectedRom()->getCrc32();
		
		if ($sourceImagePath && $this->imageManager->storeUserImage($transferMode, $romSystemIdent, $romCrc32, $sourceImagePath, $slotName)) {
			if(LOGGER::$active) LOGGER::add('images', "img add: ".$romSystemIdent."\t".$romCrc32."\t".$sourceImagePath, 0);
			$this->updateImages($slotName);
			$this->twImageFill();
			$this->createExtensionTable();
		}
		
		if ($this->imageManager->hasErrors()) {
			$title =  i18n::get('global', 'error_title');
			$msg = i18n::get('global', 'error_text');
			$errors = $this->imageManager->getErrors();
			foreach ($errors as $error) $msg .= $error;
			if (!$this->guiManager->openDialogConfirm($title, $msg)) return false;
		}
		#$this->gui->win_imagePopup->set_keep_above(true);
		
		return true;
	}
	
	
	public function removeImageFromSlot($imageFile = false, $slotName = false){
		
		$this->gui->win_imagePopup->set_keep_below(false);
		
		# used, if remove from slot context menu is selected
		if ($imageFile == false && $slotName && $this->imageTankFlat[$slotName]) {
			$imageFile = $this->imageTankFlat[$slotName];
		}
		
		if ($this->gui->mediaCenterOptConfirm->get_active()) {
			$title = I18N::get('popup', 'img_remove_title');
			$msg = sprintf(I18N::get('popup', 'img_remove_msg%s'), basename($imageFile));
			if (!$this->guiManager->openDialogConfirm($title, $msg)) return false;
		}
		
		if($this->imageManager->removeUserImage($imageFile)) {
			
			if(LOGGER::$active) LOGGER::add('images', "img remove: ".$imageFile, 0);
			
			$this->updateImages(false);
			$this->twImageFill();
			$this->createExtensionTable();
			$this->updateImage();
		}
		else {
			$title = I18N::get('popup', 'img_remove_error_title');
			$msg = sprintf(I18N::get('popup', 'img_remove_error_msg%s'), basename($imageFile));
			if(FACTORY::get('manager/Gui')->openDialogInfo($title, $msg, false, FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_mbox_error.png', true))) return false;
		}
		
		$this->gui->win_imagePopup->set_keep_above(true);
	}

	public function is_opened() {
		return $this->opened_state;
	}
	
	private function connect_signals() {
		$this->gui->imageCenterStorePosition->connect('clicked', array($this, 'onStorePosition'));
		$this->gui->imgPopup_btn_prev_top->connect('clicked', array($this, 'updateImagePosition'));
		$this->gui->imgPopup_btn_next_top->connect('clicked', array($this, 'updateImagePosition'));
		$this->gui->imgCenterButtonClose->connect_simple('clicked', array($this, 'hidePopup'));
		$this->statusbar_context_id = $this->gui->imgPopup_statusbar->get_context_id('imageUpdate');
		$this->gui->imgPopup_tglbtn_size_fit->connect('clicked', array($this, 'setImageSizeMode'));
		$this->imgPopupTreeSelection = $this->gui->imgPopup_tree->get_selection(); 
		$this->imgPopupTreeSelection->set_mode(Gtk::SELECTION_BROWSE); 
		$this->imgPopupTreeSelection->connect('changed', array($this, 'twImageSetIndex'));
		$this->gui->imgPopup_tree->connect('button-release-event', array($this, 'showContextMenu'));
		$this->gui->mediaCenterOptBtnFolder->connect_simple('clicked', array($this, 'onOpenImageFolder'));
	}
	
	public function onOpenImageFolder() {
		$this->gui->win_imagePopup->set_keep_below(false);
		$imagePath = $this->imageManager->getUserImageCrc32Folder($this->getSelectedRom()->getSystemIdent(), $this->getSelectedRom()->getCrc32());
		FACTORY::get('manager/Os')->executeProgramDirect($imagePath, 'open');
	}
	
	public function setImageSizeMode($obj) {
		$this->imageFit = $obj->get_active();
		$this->gui->ini->storeHistoryKey('imageCenterDefaultSize', !$this->imageFit);
		$this->updateImage();
	}
	
	private function twImageInit() {
		
		$this->imgPopup_model = new GtkListStore(GObject::TYPE_STRING, GdkPixbuf::gtype, GObject::TYPE_STRING, GObject::TYPE_STRING);		
		
		// set index
		$rendererText = new GtkCellRendererText();		// set index

		$cIndex = new GtkTreeViewColumn('index', $rendererText, 'text', 0);
		$cIndex->set_visible(false);
		
		// set image
		$rImage = new GtkCellRendererPixbuf();
		$cImage = new GtkTreeViewColumn('image', $rImage, 'pixbuf', 1);

		$cImagePath = new GtkTreeViewColumn('imagepath', $rendererText, 'text', 2);
		$cImagePath->set_visible(false);
		
		$cImageType = new GtkTreeViewColumn('imagetype', $rendererText, 'text', 2);
		$cImageType->set_visible(false);
		
		$this->gui->imgPopup_tree->set_model($this->imgPopup_model);
		$this->gui->imgPopup_tree->append_column($cIndex);
		$this->gui->imgPopup_tree->append_column($cImage);
		$this->gui->imgPopup_tree->append_column($cImagePath);
		$this->gui->imgPopup_tree->append_column($cImageType);
	}
	
	private function twImageFill() {
		if (!isset($this->imageTank)) return false;
		$this->imgPopup_model->clear();
		
		foreach ($this->imageTank as $index => $data) {
			
			$fileName = $data['path'];
			if (!$fileName || !file_exists($fileName)) continue;
			
			// use thumbnail, if available
			$imageThumb = $this->imageManager->getImageThumbFile($fileName);
			$pixbufFile = (file_exists($imageThumb)) ? $imageThumb : $fileName;
			
			$oPixbuf = FACTORY::get('manager/GuiHelper')->getPixbuf($pixbufFile, 80, 60);
			$this->imageTank[$index]['iter'] = $this->imgPopup_model->append(array($index, $oPixbuf, $fileName, $data['type']));

			while (gtk::events_pending()) gtk::main_iteration();
		}
	}
	
	public function twImageSetIndex($tree) {
		
		list($model, $iter) = $tree->get_selected();
		if (!$iter) return false;
		$this->imagePosition = $model->get_value($iter, 0);
		
		# current image-type
		$this->selectedImageType = $model->get_value($iter, 3);
		
		# if an user has selected an slot,
		# make this selection sticky
		$selectedImageType = false;
		if (isset($this->imageTankFlat[$this->selectedImageType]) && $this->imageTankFlat[$this->selectedImageType]){
			#$this->currentSlotSelection = $this->selectedImageType;
			$selectedImageType = $this->currentSlotSelection;
		}
		
		$this->updateImagePosition();
		$this->updateImage();
		$this->createExtensionTable($selectedImageType);
	}
	
	public function onItemSelect($key) {
		foreach($this->imageTank as $index => $value){
			if ($value['iter'] && $value['path'] && $value['type'] == $key) {
				$this->imgPopupTreeSelection->select_iter($value['iter']);
				return true;
			}
		}
		return false;
	}
	
	public function storeSettings(){
		$transferModeState = $this->gui->mediaCenterOptRadioCopy->get_active();
		$transferMode = ($transferModeState) ? 'COPY' : 'MOVE';
		$this->iniManager->storeHistoryKey('imageCenterConfirmPopupState', $transferMode);
		$this->iniManager->storeHistoryKey('imageCenterHidePopup', !$this->gui->mediaCenterOptConfirm->get_active());
		$this->iniManager->storeHistoryKey('imageCenterSlotsHidden', !$this->gui->mediaCenterOptExpand->get_expanded());
		
		# write position and size settings
		$this->writePopupPosition();
		$this->writePopupSize();
	}
	
	public function hidePopup() {
		
		$this->storeSettings();
		
		$this->gui->hide($this->gui->win_imagePopup);
		$this->opened_state = false;
		return true;
	}
	
	public function updateImagePosition($obj=false) {
		
		// get current count
		$imageCount = count($this->imageTank);
		if (!$imageCount) return false;
		
		// dispatch the buttons
		if (is_object($obj)) {
			switch($obj->get_name()) {
				#case 'imgPopup_btn_next':
				case 'imgPopup_btn_next_top':
					$this->imagePosition++;
					if ($this->imagePosition > $imageCount) {
						$this->imagePosition = $imageCount;
					}
					break;
				#case 'imgPopup_btn_prev':
				case 'imgPopup_btn_prev_top':
					$this->imagePosition--;
					if ($this->imagePosition <= 0) {
						$this->imagePosition = 0;
					}
					break;
			}
		}
		
		$selectedImageSlot = $this->imageTank[$this->imagePosition];
		if ($selectedImageSlot['iter']) $this->imgPopupTreeSelection->select_iter($selectedImageSlot['iter']);
		else $this->imgPopupTreeSelection->unselect_all();
		#$this->imgPopupTreeSelection->select_path($this->imagePosition);
		
		$imageType = $this->imageTank[$this->imagePosition]['type'];
		
		$text = '<b>'.sprintf(i18n::get('imageCenter', 'imagePositionInfo%s%s%s'), $this->imagePosition+1, $imageCount, $imageType).'</b>';
		$this->gui->imgPopup_pos_state->set_markup($text);
		
		$sensitive = ($this->imagePosition == 0) ? false : true;
		#$this->gui->imgPopup_btn_prev->set_sensitive($sensitive);
		$this->gui->imgPopup_btn_prev_top->set_sensitive($sensitive);

		$sensitive = ($this->imagePosition+1 >= $imageCount) ? false : true;
		#$this->gui->imgPopup_btn_next->set_sensitive($sensitive);
		$this->gui->imgPopup_btn_next_top->set_sensitive($sensitive);

		if (!$this->imageTank[$this->imagePosition]['path'] || !file_exists($this->imageTank[$this->imagePosition]['path'])) $this->createExtensionTable($this->imageTank[$this->imagePosition]['type']);
		
		$this->updateImage();
	}
	
	public function updateImage() {
		
		$imageCount = count($this->imageTank);

		if ($imageCount && $this->imageTank[$this->imagePosition] && file_exists($this->imageTank[$this->imagePosition]['path'])) {
			
			$oPixbuf = FACTORY::get('manager/GuiHelper')->getPixbuf($this->imageTank[$this->imagePosition]['path']);

			// autofit image
			if ($oPixbuf !== null && $this->imageFit) {

				// get viewport size
				$size = $this->gui->viewport2->window->get_size();
				$maxViewportWidth = $size[0]-4;
				$maxViewportHeight = $size[1]-4;
				
				// get original image size
				$imageOriginalWidth = $oPixbuf->get_width();
				$imageOriginalHeight = $oPixbuf->get_height();

				// calculate new size
				list($imageNewWidth, $imageNewHeight) = $this->calculateMaxSize($maxViewportWidth, $maxViewportHeight, $imageOriginalWidth, $imageOriginalHeight);
				
				// set new size
				$objImage = $oPixbuf->scale_simple($imageNewWidth, $imageNewHeight, Gdk::INTERP_BILINEAR);
			}
			else {
				$objImage = $oPixbuf;
			}
			$this->gui->imgPopup_image->set_from_pixbuf($objImage);			
			
		}
		else {
			$this->gui->imgPopup_image->set_from_pixbuf(new GdkPixbuf(Gdk::COLORSPACE_RGB, false, 8, 1, 1));
		}
	
		if (isset($this->imageTank[$this->imagePosition]['path'])) $this->gui->imgPopup_statusbar->push($this->statusbar_context_id, $this->imageTank[$this->imagePosition]['path']);
	}
	
	public function getImageTypeFromEccImage($file) {
		$split = explode('_', basename($this->fileIoManager->get_plain_filename($file)));
		$imageType = $split[3].'_'.$split[4];
		if (isset($split[5])) $imageType .= '_'.$split[5];
		if (isset($this->gui->image_type[$imageType])) return $imageType;
		return false;
	}
	
	private function calculateMaxSize($maxViewportWidth, $maxViewportHeight, $imageOriginalWidth, $imageOriginalHeight) {
		$maxPercent =  $maxViewportWidth * 100 / $imageOriginalWidth;
		$maxHeight = $maxPercent * $imageOriginalHeight / 100;
		$maxWidth = $maxViewportWidth;
		
		if ($maxHeight > $maxViewportHeight) {
			$newMaxPercent = $maxViewportHeight * 100 / $maxHeight;
			$maxWidth = $newMaxPercent * $maxWidth / 100;
			$maxHeight = $newMaxPercent * $maxHeight / 100;
		}
		
		$size = array();
		$size[0] = $maxWidth;
		$size[1] = $maxHeight;
		return $size;
	}
	
	public function showContextMenu($obj, $event) {
		if (!count($this->imageTank)) return false;
		if ($event->button == 3) {
			$selection = $obj->get_selection();
			list($model, $iter) = $selection->get_selected();
			$path = ($iter) ? $model->get_value($iter, 2) : false;

			$menu = new GtkMenu();

			$miRemove = new GtkMenuItem(i18n::get('imageCenter', 'slotDropDownRemove'));
			$miRemove->connect_simple('activate', array($this, 'dispatchContexMenu'), 'remove', $path);
			$menu->append($miRemove);


			$menu->show_all();
			$menu->popup();
		}
	}
	
	public function dispatchContexMenu($type, $param1=false, $param2=false) {
		switch($type) {
			case 'remove':
				$this->removeImageFromSlot($param1);
				break;
			default:
		}
	}

	
	public function dispatchSlotSelection($oEvent, $event, $slotName, $available){
		
		# if an user has selected an slot,
		# make this selection sticky
		#$this->currentSlotSelection = $slotName;
		
		# right or doubleclick
		if ($event->button == 3 || ($event->button == 1 && $event->type == 5)) {
			$this->hilightSlot($oEvent, $slotName, $available);
			$this->showSlotContextMenu($slotName, $available);
		}
		elseif($event->button == 1) {
			$this->hilightSlot($oEvent, $slotName, $available);
		}
	}
	
	private function showSlotContextMenu($slotName, $available){
		$menu = new GtkMenu();

		# header
		$subMenu = new GtkMenuItem(i18n::get('global', 'image').": ".$slotName);
		$subMenu->set_sensitive(false);
		$menu->append($subMenu);
		
		$menu->append(new GtkSeparatorMenuItem());
		
		if ($available) {
			# add new image
			$subMenu = new GtkMenuItem(i18n::get('imageCenter', 'slotDropDownReplace'));
			$subMenu->connect_simple('activate', array($this, 'addImageByFileDialog'), $slotName);
			$menu->append($subMenu);
			
			# remove image
			$subMenu = new GtkMenuItem(i18n::get('imageCenter', 'slotDropDownRemove'));
			$subMenu->connect_simple('activate', array($this, 'removeImageFromSlot'), false, $slotName);
			$menu->append($subMenu);
		}
		else {
			# add new image
			$subMenu = new GtkMenuItem(i18n::get('imageCenter', 'slotDropDownAdd'));
			$subMenu->connect_simple('activate', array($this, 'addImageByFileDialog'), $slotName);
			$menu->append($subMenu);
		}
		
		# footer
		$menu->append(new GtkSeparatorMenuItem());
		$subMenu = new GtkMenuItem(i18n::get('imageCenter', 'slotDropDownHint'));
		$subMenu->set_sensitive(false);
		$menu->append($subMenu);
		
		$menu->show_all();
		$menu->popup();
	}
	
	private function hilightSlot($oEvent, $slotName, $available){
		
		foreach($this->slotEventObjects as $eventObjectData){
			$eventObjectData['object']->modify_bg(Gtk::STATE_NORMAL, GdkColor::parse($eventObjectData['color']));
		}
		$bgColor = ($available) ? $this->dropZoneBgColorSelected : $this->dropZoneBgColorUnsetSelected;
		$oEvent->modify_bg(Gtk::STATE_NORMAL, GdkColor::parse($bgColor));
	}
	
	public function addImageByFileDialog($slotName){
		$this->gui->win_imagePopup->set_keep_below(false);
		$iniManager = FACTORY::get('manager/IniFile');
		$path = realpath($iniManager->getHistoryKey('selPathImageCenter'));

		$title = i18n::get('imageCenter', 'addImageForSlot').": ".$slotName."";
		
		$shorcutFolder = $iniManager->getShortcutPaths($this->getSelectedRom()->getSystemIdent());
		$sourceImagePath = FACTORY::get('manager/Os')->openChooseFileDialog($path, $title, false, false, false, $shorcutFolder);
		if ($sourceImagePath){
			$sourceImagePath = realpath($sourceImagePath);
			# get tempname, if the original image is moved!
			$tempFileDirname = dirname($sourceImagePath);
			if ($this->addImageToSlot($sourceImagePath, $slotName)){
				$iniManager->storeHistoryKey('selPathImageCenter', $tempFileDirname);				
			}
		}
		$this->gui->win_imagePopup->set_keep_above(true);
	}
	
	/**
	 * store window size setup to ini file
	 *
	 */
	public function writePopupSize(){
		list($width, $height) = $this->gui->win_imagePopup->get_size();
		$this->iniManager->storeHistoryKey('imageCenterPopupSize', $width.'x'.$height);
	}
	
	/**
	 * store window position setup to ini file
	 *
	 */
	public function writePopupPosition(){
		$imageCenterPopupPosition = $this->gui->win_imagePopup->get_position();
		list($width, $height) = $imageCenterPopupPosition;
		$this->iniManager->storeHistoryKey('imageCenterPopupPosition', $width.'x'.$height);
	}
	
	/**
	 * read and set window size from ini
	 *
	 */
	public function setPopupSize(){
		if ($imageCenterPopupSize = $this->iniManager->getHistoryKey('imageCenterPopupSize')) {
			list($width, $height) = explode('x', $imageCenterPopupSize);
			if ($width && $height){
				$this->gui->win_imagePopup->set_size_request(1, 1);
				$this->gui->win_imagePopup->resize($width, $height);
			}
		}
	}
	
	private $lastPopupPosition = false;
	
	/**
	 * read and set window position from ini
	 *
	 */
	public function setPopupPosition(){
		if ($imageCenterPopupPosition = $this->iniManager->getHistoryKey('imageCenterPopupPosition')) {
			list($width, $height) = explode('x', $imageCenterPopupPosition);
			if ($width && $height) $this->gui->win_imagePopup->move($width, $height);
			$this->lastPopupPosition = $width.'x'.$height;
		}
	}
	
	/**
	 * Callback for store position button
	 * write position and size settings
	 *
	 */
	public function onStorePosition() {
		$this->storeSettings();
	}
}
?>
