<?
class GuiStatus {
	
	private $gui = false;
	private $txtbuf_output = false;
	
	private $status_cancel = false;
	private $status_status_complete = false;
	public $status_status_running = false;
	
	public function __construct($gui) {
		$this->gui = $gui;
		$this->gui->status_area_teaser_btn_hide->connect("clicked", array($this, 'toggle_output'));
		$this->gui->status_area_teaser_btn_cancel->connect_simple("clicked", array($this, 'set_canceled'));
	}
	
	public function init() {
		if ($this->status_status_running === true) {
			$title = I18N::get('popup', 'status_process_running_title');
			$msg = I18N::get('popup', 'status_process_running_msg');
			FACTORY::get('manager/Gui')->openDialogInfo($title, $msg, false, FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_mbox_error.png', true));
			return false;
		}
		
		$this->gui->status_area_working_lbl->set_markup('<b>'.sprintf(I18N::get('status', 'eccIsWorking%s'), 'emuControlCenter').'</b>');
		
		$this->update_message(I18N::get('status', 'pending').'...');
		
		$this->status_status_running = true;
		$this->status_cancel = false;
		$this->status_complete = false;
		
		$this->gui->status_area_teaser_btn_hide->set_active(false);
		
		// popup cancel
		$this->cancel_title = "";
		$this->cancel_msg = "";
		
		return true;
	}	
	
	public function set_label($txt="") {
		$this->gui->status_area_lbl->set_label($txt);
	}
	
	public function update_message($txt="") {
		if (!$this->txtbuf_output) $this->txtbuf_output = new GtkTextBuffer();
		try{
			$this->txtbuf_output->set_text(trim($txt));
		}
		catch(PhpGtkGErrorException $e){}
		
		$this->gui->status_area_output_msg->set_buffer($this->txtbuf_output);
	}
	
	public function update_progressbar($value=0, $txt="") {
		$this->gui->status_area_teaser_progressbar->set_fraction($value);
		$this->gui->status_area_teaser_progressbar->set_text($txt);
	}	
	
	public function hide_main() {
		$this->gui->status_area_teaser_btn_hide->set_active(false);
		$this->gui->status_area_main->hide();
	}
	
	public function show_main() {
		$this->gui->status_area_main->show();
	}
	
	public function toggle_output($toggle_button) {
		$state = $toggle_button->get_active();
		if ($state) {
			$this->show_output();
		}
		else {
			$this->hide_output();
		}
	}
	
	public function hide_output() {
		$this->gui->status_area_teaser_btn_hide->set_active(false);
		$this->gui->status_area_output->hide();
	}
	
	public function show_output() {
		$this->gui->status_area_teaser_btn_hide->set_active(true);
		$this->gui->status_area_output->show();
	}
	
	public function set_canceled($cancelDirect=false) {
		if($cancelDirect) {
			$this->status_cancel = true;
			$this->hide_main();
		}
		elseif ($this->status_complete) {
			$this->hide_main();
			$this->reset1();
		}
		else {
			if (FACTORY::get('manager/Gui')->openDialogConfirm($this->cancel_title, $this->cancel_msg)) {;
				$this->status_cancel = true;
				$this->hide_main();
				#$this->reset1();
			}
		}
		
	}
	
	public function is_canceled() {
		return $this->status_cancel;
	}
	
	public function set_popup_cancel_msg($title="", $msg="") {
		
		if (!$title) $title = i18n::get('popup', 'processCancelConfirmTitle');
		if (!$msg) $msg = i18n::get('popup', 'processCancelConfirmMsg');
		
		$this->cancel_title = $title;
		$this->cancel_msg = $msg;
	}
	
	public function open_popup_complete($title="", $msg="", $hideOption = false) {
		if (!$this->status_cancel) {
			
			if (!$title) $title = i18n::get('popup', 'processDoneTitle');
			if (!$msg) $msg = i18n::get('popup', 'processDoneMsg');
			
			$msg .= I18N::get('popup', 'status_dialog_close');
			if (FACTORY::get('manager/Gui')->openDialogConfirm($title, $msg, $hideOption, FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_mbox_success.png', true))) $this->hide_main();
		}
		$this->reset1();
	}
	
	public function reset1() {
		$this->status_complete = true;
		$this->status_status_running = false;
		$this->status_cancel = false;
		return false;
	}
}
?>
