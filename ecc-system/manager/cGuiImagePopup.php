<?
class GuiImagePopup {
	
	private $gui = false;
	private $oImage;
	private $imagePosition = 0;
	
	private $imageTank = array();
	private $mediaInfo = array();
	private $statusbar_context_id;
	
	private $opened_state = false;
	
	private $error = false;
	
	/**
	 * 
	 */	
	public function __construct($gui_obj=false) {
		if (!$gui_obj) return false;
		$this->gui = $gui_obj;
		
//		$this->oImage = new EccImage();
		$this->oImage = FACTORY::get('manager/EccImage');
		
		$this->connect_signals();
		$this->twImageInit();
	}
	
	public function show($imageTank, $mediaInfo, $pos=0) {
		$this->imageTank = $imageTank;
		$this->mediaInfo = $mediaInfo;
		
		$this->eccident = ($this->mediaInfo['md_eccident']) ? $this->mediaInfo['md_eccident'] : $this->mediaInfo['fd_eccident'];
		
		$this->imagePosition = $pos;
		$this->updateImagePosition();
		$this->twImageFill();
		$this->updateImage();
		$this->gui->win_imagePopup->show();
		$this->gui->win_imagePopup->set_keep_above(true);
		$this->opened_state = true;
	}
	
	public function is_opened() {
		return $this->opened_state;
	}
	
	/**
	 * 
	 */
	private function connect_signals() {
		$this->gui->imgPopup_btn_prev->connect('clicked', array($this, 'updateImagePosition'));
		$this->gui->imgPopup_btn_prev_top->connect('clicked', array($this, 'updateImagePosition'));
		$this->gui->imgPopup_btn_next->connect('clicked', array($this, 'updateImagePosition'));
		$this->gui->imgPopup_btn_next_top->connect('clicked', array($this, 'updateImagePosition'));
		$this->gui->imgPopup_btn_close->connect_simple('clicked', array($this, 'hidePopup'));
		$this->statusbar_context_id = $this->gui->imgPopup_statusbar->get_context_id('imageUpdate');
		
		$this->imgPopupTreeSelection = $this->gui->imgPopup_tree->get_selection(); 
		$this->imgPopupTreeSelection->set_mode(Gtk::SELECTION_BROWSE); 
		$this->imgPopupTreeSelection->connect('changed', array($this, 'twImageSetIndex'));
		
		$this->gui->imgPopup_tree->connect('button-release-event', array($this, 'showContextMenu'));
		
	}
	
	private function twImageInit() {
		$this->imgPopup_model = new GtkListStore(Gtk::TYPE_STRING, GdkPixbuf::gtype, Gtk::TYPE_STRING);
		
		// set index
		$rIndex = new GtkCellRendererText();
		$cIndex = new GtkTreeViewColumn('index', $rIndex, 'text', 0);
		$cIndex->set_visible(false);
		
		// set image
		$rImage = new GtkCellRendererPixbuf();
		$cImage = new GtkTreeViewColumn('image', $rImage, 'pixbuf', 1);

		// set index
		$rImagePath = new GtkCellRendererText();
		$cImagePath = new GtkTreeViewColumn('imagepath', $rImagePath, 'text', 2);
		$cImagePath->set_visible(false);
		
		$this->gui->imgPopup_tree->set_model($this->imgPopup_model);
		$this->gui->imgPopup_tree->append_column($cIndex);
		$this->gui->imgPopup_tree->append_column($cImage);
		$this->gui->imgPopup_tree->append_column($cImagePath);
	}
	
	private function twImageFill() {
		if (!isset($this->imageTank)) return false;
		$this->imgPopup_model->clear();
		foreach ($this->imageTank as $index => $imagePath) {
			while (gtk::events_pending()) gtk::main_iteration();
			if (!file_exists($imagePath)) continue;
			$oPixbuf = GdkPixbuf::new_from_file($imagePath);
			$oPixbuf = $oPixbuf->scale_simple(80, 60, Gdk::INTERP_BILINEAR);
			$this->imgPopup_model->append(array($index, $oPixbuf, $imagePath));
		}
	}
	
	public function twImageSetIndex($tree) {
		list($m, $iter) = $tree->get_selected();
		if (!$iter) return false;
		$this->imagePosition = $m->get_value($iter, 0);
		$this->updateImagePosition();
		$this->updateImage();
	}
	
	public function hidePopup() {
		$this->gui->hide($this->gui->win_imagePopup);
		$this->opened_state = false;
	}
	
	public function updateImagePosition($obj=false) {
		// get current count
		$imageCount = count($this->imageTank);
		
		// dispatch the buttons
		if (is_object($obj)) {
			switch($obj->get_name()) {
				case 'imgPopup_btn_next':
				case 'imgPopup_btn_next_top':
					$this->imagePosition++;
					if ($this->imagePosition > $imageCount) {
						$this->imagePosition = $imageCount;
					}
					break;
				case 'imgPopup_btn_prev':
				case 'imgPopup_btn_prev_top':
					$this->imagePosition--;
					if ($this->imagePosition <= 0) {
						$this->imagePosition = 0;
					}
					break;
			}
		}
		
		$this->imgPopupTreeSelection->select_path((int)$this->imagePosition);
		
		$text = sprintf("<b>Image %s of %s</b>", $this->imagePosition+1, $imageCount);
		$this->gui->imgPopup_pos_state->set_markup($text);
		
		
		$sensitive = ($this->imagePosition == 0) ? false : true;
		$this->gui->imgPopup_btn_prev->set_sensitive($sensitive);
		$this->gui->imgPopup_btn_prev_top->set_sensitive($sensitive);

		$sensitive = ($this->imagePosition+1 >= $imageCount) ? false : true;
		$this->gui->imgPopup_btn_next->set_sensitive($sensitive);
		$this->gui->imgPopup_btn_next_top->set_sensitive($sensitive);

		$this->updateImage();
	}
	
	/**
	 * 
	 */
	private function updateImage() {
		if (isset($this->imageTank[$this->imagePosition])) {
			$obj_pixbuff = GdkPixbuf::new_from_file($this->imageTank[$this->imagePosition]);
			$this->gui->imgPopup_image->set_from_pixbuf($obj_pixbuff);			
		}
		$this->gui->imgPopup_statusbar->push($this->statusbar_context_id, $this->imageTank[$this->imagePosition]);
	}
	
	public function showContextMenu($obj, $event) {
		
		if ( $event->button == 3) {

			$selection = $obj->get_selection();
			list($model, $iter) = $selection->get_selected();
			if ($iter) {
				$path = $model->get_value($iter, 2);
			}

			$menu = new GtkMenu();

//			$miHeader = new GtkMenuItem('ONLY DUMMY! WILL NOT WORK!');
//			$menu->append($miHeader);
//			$menu->append(new GtkSeparatorMenuItem());
//			foreach ($this->gui->image_type as $imageIndex => $value) {
//				$miSaveType = new GtkMenuItem("save as '$value'");
//				$miSaveType->connect_simple('activate', array($this, 'dispatchContexMenu'), 'save', $imageIndex, $path);
//				$menu->append($miSaveType);
//			}
//			$menu->append(new GtkSeparatorMenuItem());

			$miRemove = new GtkMenuItem('Remove this image');
			$miRemove->connect_simple('activate', array($this, 'dispatchContexMenu'), 'remove', $path);
			$menu->append($miRemove);


			$menu->show_all();
			$menu->popup();
		}
	}
	
	public function dispatchContexMenu($type, $param1=false, $param2=false) {
		switch($type) {
			case 'save':
				$this->saveImage($param1, $param2);
				break;
			case 'remove':
				$this->removeImage($param1);
				break;
			default:
		}
	}
	
	private function removeImage($file) {
		$title = I18N::get('popup', 'img_remove_title');
		$msg = sprintf(I18N::get('popup', 'img_remove_msg%s'), $file);
		if ($this->gui->open_window_confirm($title, $msg)){
			if($this->oImage->remove($file)) {
				$this->twImageFill();
			}
			else {
				$title = I18N::get('popup', 'img_remove_error_title');
				$msg = sprintf(I18N::get('popup', 'img_remove_error_msg%s'), $file);
				if($this->gui->open_window_info($title, $msg)) return false;
			}
		}
	}
	
	/*
	private function saveImage($prefix, $file) {
		$convertImage = $this->gui->ini->get_ecc_ini_key('USER_SWITCHES', 'image_convert_to_jpg');
		$user_folder_images = $this->ini->get_ecc_ini_user_folder($this->eccident.DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR, true);
		if ($user_folder_images===false) return false;
		$res = $this->oImage->save($this->eccident, $prefix, $file, $convertImage);
		print "<pre>";
		print_r($this->mediaInfo);
		print "</pre>\n";
	}
	*/
}
?>
