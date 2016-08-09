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
			$this->gui->open_window_info("Process running", "Another process is running\nYou can only start one process like parsing/import/export! Please wait until the current running process is done!");
			return false;
		}
		
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
		$this->txtbuf_output->set_text(trim($txt));
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
	
	public function set_canceled() {
		if ($this->status_complete) {
			#$this->status_cancel = true;
			$this->hide_main();
			$this->reset1();
		}
		else {
			if ($this->gui->open_window_confirm($this->cancel_title, $this->cancel_msg)) {;
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
		$this->cancel_title = $title;
		$this->cancel_msg = $msg;
	}
	
	public function open_popup_complete($title="", $msg="") {
		if (!$this->status_cancel) {
			$msg .= "\n\nShould i close the status-window?";
			if ($this->gui->open_window_confirm($title, $msg)) $this->hide_main();
		}
		else {
			#$msg .= "\n\nProcess canceled!";
			#$this->gui->open_window_info($title, $msg);
		}
		$this->reset1();
	}
	
	public function reset1() {
		$this->status_complete = true;
		$this->status_status_running = false;
		$this->status_cancel = false;
	}
}
?>
