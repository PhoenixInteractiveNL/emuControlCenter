<?
/*
 * 
 * -11	Gtk::RESPONSE_HELP	 Returned by Help buttons in GTK+ dialogs.
 * -10	Gtk::RESPONSE_APPLY	Returned by Apply buttons in GTK+ dialogs.
 * -9	Gtk::RESPONSE_NO	Returned by No buttons in GTK+ dialogs.
 * -8	Gtk::RESPONSE_YES	Returned by Yes buttons in GTK+ dialogs.
 * -7	Gtk::RESPONSE_CLOSE	Returned by Close buttons in GTK+ dialogs.
 * -6	Gtk::RESPONSE_CANCEL	Returned by Cancel buttons in GTK+ dialogs.
 * -5	Gtk::RESPONSE_OK	Returned by OK buttons in GTK+ dialogs.
 * -4	Gtk::RESPONSE_DELETE_EVENT	Returned if the dialog is deleted.
 * -3	Gtk::RESPONSE_ACCEPT	Generic response id, not used by GTK+ dialogs.
 * -2	Gtk::RESPONSE_REJECT	Generic response id, not used by GTK+ dialogs.
 * -1	Gtk::RESPONSE_NONE	Returned if an action widget has no response id, or if the dialog gets programmatically hidden or destroyed.
 * 
 */

class Gui extends GladeXml{
	
	private $dialogIsset = false;
	private $response = NULL;

	/**
	 * This constructor is only needed to avoid an GladeXml warning!
	 *
	 */
	public function __construct(){
		$this->initGlade(false);
	}
	
	/**
	 * Opens an Dialog window
	 *
	 * @param string $title
	 * @param string $msg
	 * @param string $historyKey
	 * @return int or false
	 */
	public function openDialogConfirm($title=false, $message=false, $historyKey = false, $image = false){

		if ($this->isHistoryKeyHidden($historyKey))return true;
		
		$this->init();
		$this->setDialogContent($title, $message, $image);
		
		$this->handleHistoryKey($historyKey);
		
		$this->buttonCancel->set_visible(true);
		$this->buttonCancelText->set_text(I18N::get('global', 'dialog_button_cancel'));
		
		$this->buttonOk->set_visible(true);
		$this->buttonOkText->set_text(I18N::get('global', 'dialog_button_ok'));
		$this->buttonOk->grab_focus();
		
		return $this->getResponse($historyKey);
	}
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $title
	 * @param unknown_type $message
	 * @param unknown_type $historyKey
	 * @param unknown_type $image
	 * @return unknown
	 */
	public function openDialogInfo($title=false, $message=false, $historyKey = false, $image = false){
		
		if ($this->isHistoryKeyHidden($historyKey))return true;
		
		$this->init();
		$this->setDialogContent($title, $message, $image);
		
		$this->handleHistoryKey($historyKey);
	
		$this->buttonCancel->set_visible(false);
		$this->buttonOkText->set_text(I18N::get('global', 'dialog_button_ok'));
		$this->buttonOk->grab_focus();
		
		return $this->getResponse($historyKey);
	}
	
	public function openDialogWait($title=false, $message=false){
		$this->init(Gdk::WINDOW_TYPE_HINT_SPLASHSCREEN);
		$this->checkboxHideDialog->set_visible(false);
		$this->hbox32->set_visible(false);
		$this->setDialogContent($title, $message, $image = false);
		return $this;
	}
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $title
	 * @param unknown_type $message
	 * @param unknown_type $image
	 */
	private function setDialogContent($title, $message, $image){
		
		# default values
		$title = ($title) ? $title : I18N::get('popup', 'sys_dialog_miss_title');
		$message = ($message) ? $message : I18N::get('popup', 'sys_dialog_miss_msg');
		
		# set given contents
		$this->title->set_markup('<b>'.$title.'</b>');
		$this->message->set_markup($message);
	}
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $historyKey
	 * @return unknown
	 */
	private function isHistoryKeyHidden($historyKey){
		if (!is_array($historyKey)) return false;
		return (bool)FACTORY::get('manager/IniFile')->getHistoryKey($historyKey[0]);
	}
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $historyKey
	 */
	private function handleHistoryKey($historyKey){
		if (!is_array($historyKey)){
			$this->checkboxHideDialog->set_visible(false);
			return false;
		}
		
		if ($historyKey){
			$this->checkboxHideDialog->set_visible(true);
			$this->checkboxHideDialog->set_active(false);
			
			$message = (isset($historyKey[1])) ? $historyKey[1] : I18N::get('global', 'dialog_dont_show_again');
			$this->checkboxHideDialog->set_label($message);
		}
	}
	
	/**
	 * Store the hide dialog selection to the history.ini
	 *
	 * @param string $historyKey
	 */
	private function writeHistoryKey($historyKey){
		FACTORY::get('manager/IniFile')->storeHistoryKey($historyKey[0], $this->checkboxHideDialog->get_active());		
	}
	
	/**
	 * Wait for user action in a infinite while loop
	 *
	 * return state on user click
	 * 
	 * @return bool
	 */
	private function getResponse($historyKey){
		while(1){
			while (gtk::events_pending()) gtk::main_iteration();
			if (!isset($this->response)) continue;
			
			switch($this->response){
				case Gtk::RESPONSE_OK:
					$state = true;
					break;
				default:
					$state = false;
			}
			
			#$state = ($this->response === Gtk::RESPONSE_OK) ? true : false;
			if ($state && $historyKey) $this->writeHistoryKey($historyKey);
			$this->guiDialog->hide();
			return $state;
		}		
	}
	
	/**
	 * Initialize the Popup.
	 * 
	 * If the dialog is allready created, only init
	 * the dialog with the given parameters
	 */
	private function init($windowType = false){
		if(!$this->dialogIsset) $this->initGlade($show = true, $windowType);
		else $this->initDialog(true, $windowType);
	}
	
	/**
	 * Initializes the Dialog and reset variables
	 */
	private function initDialog($show = true, $windowType = false){
		
		# glade needs to execute an constructor
		# so, the first time, the popup isnts opened via $show
		
		if($windowType) $this->guiDialog->set_type_hint($windowType);
		
		if ($show) $this->guiDialog->show();
		
		$this->guiDialog->set_keep_above(true);
		
		unset($this->response);
	}
	
	/**
	 * Load Glade file and initialize the Dialog
	 */
	private function initGlade($show = true, $windowType = false){
		
		# glade needs to execute an constructor
		# so, the first time, the popup isnts opened via $show
		if (!$show) return false;
		
		$path = realpath(dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR).'/gui2/guiDialog.glade';
		parent::__construct($path);
		
		if($windowType) $this->guiDialog->set_type_hint($windowType);
		
		$this->guiDialog->set_position(Gtk::WIN_POS_CENTER_ALWAYS);

		$theme = FACTORY::get('manager/IniFile')->getKey('ECC_THEME', 'ecc-theme');
		if($theme == 'none'){
			$this->guiDialog->modify_bg(Gtk::STATE_NORMAL, GdkColor::parse("#FFFFFF"));			
		}
		else{
			$imageObject = FACTORY::get('manager/Image');
			$imageObject->setWidgetBackground($this->guiDialog, '/background/dialog.png');
			$imageObject->setWidgetBackground($this->eventbox1, '/background/dialog_bottom.png');			
		}
		
		$this->guiDialog->connect('delete-event', array($this, 'destroyed'));
		
		$this->guiDialog->connect('key-press-event', array($this, 'handleKeyboardShortcuts'));
		
		# connect buttons
		$this->buttonCancel->connect_simple('clicked', array($this, 'onClick'), Gtk::RESPONSE_CANCEL);
		$this->buttonOk->connect_simple('clicked', array($this, 'onClick'), Gtk::RESPONSE_OK);

		$this->dialogIsset = true;
		
		#$this->guiDialog->show();
		
		# initialize the dialog
		$this->initDialog($show);
	}
	
	public function destroyed(){
		$this->response = false;
		$this->guiDialog->hide();
		return true;
	}
	
	public function close(){
		$this->guiDialog->destroy();
	}
	
	/**
	 * Callback for the gui buttons
	 *
	 * @param int $state Id of the pressed button
	 */
	public function onClick($response){
		$this->response = $response;
		$this->guiDialog->hide();
	}
	
	/**
	 * Hadles user keystrokes
	 *
	 * @param object $widged
	 * @param object $event
	 */
	public function handleKeyboardShortcuts($widged, $event) {
		# handle exscape key
		if ($event->keyval == '65307') $this->response = false;
	}
	
	/**
	 * Magic function to get the glade widgeds easier
	 *
	 * @param string $property
	 * @return object
	 */
	private function __get($property) {
		return parent::get_widget($property);
	}
}
?>
