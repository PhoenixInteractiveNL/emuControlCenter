<?
class Gui extends GladeXml{
	
	public function openWindowConfirm($title=false, $msg=false){
		
//		$win = new GtkWindow();
//		$win->set_type_hint(Gdk::WINDOW_TYPE_HINT_SPLASHSCREEN);
//		
//		$win->set_title($title);
//		$win->set_position(Gtk::WIN_POS_CENTER);
//		
//		$message = new GtkLabel();
//		$message->set_markup($msg);
//		
//		$buttonCancel = new GtkButton();
//		$buttonOk = new GtkButton();
//		
//		$bBox = new GtkHButtonBox();
//		$bBox->set_layout(Gtk::BUTTONBOX_EDGE);
//		$bBox->add($buttonCancel);
//		$bBox->add($buttonOk);
//		
//		
//		$vBox = new GtkVBox();
//		$vBox->pack_start($message);
//		$vBox->pack_start($bBox);
//		
//		
//		$win->add($vBox);
//		$win->show_all();
		$path = realpath(dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR).'/gui2/guiDialog.glade';
		print "\n<pre>";
		print_r($path);
		print "</pre>\n";
		parent::__construct($path);
		


		
		print "\n<pre>";
		print_r($title);
		print "</pre>\n";
		
		print "\n<pre>";
		print_r($msg);
		print "</pre>\n";
		
		#Gtk::Main();
		
	}
	
	private function __get($property) {
		return parent::get_widget($property);
	}
	
	public function openDialogConfirm($title=false, $msg=false){
		
		# set title, message and create dialog
		$title = ($title) ? $title : I18N::get('popup', 'sys_dialog_miss_title');
		$msg = ($msg) ? $msg : I18N::get('popup', 'sys_dialog_miss_msg');
		$dialog = new GtkMessageDialog(null, Gtk::DIALOG_MODAL, Gtk::MESSAGE_QUESTION, Gtk::BUTTONS_YES_NO, '');
		
		$dialog->set_keep_above(true);
		$dialog->set_position(Gtk::WIN_POS_CENTER);
		
		if ($title) $dialog->set_title($title);
		if ($msg) $dialog->set_markup($msg);
		
		$response = $dialog->run();
		$dialog->destroy();
		
		if ($response == Gtk::RESPONSE_YES) return true;
		return false;
	}

	public function openDialogInfo($title=false, $msg=false){
		
		# set title, message and create dialog
		$title = ($title) ? $title : I18N::get('popup', 'sys_dialog_miss_title');
		$msg = ($msg) ? $msg : I18N::get('popup', 'sys_dialog_miss_msg');
		$dialog = new GtkMessageDialog(null, Gtk::DIALOG_MODAL, Gtk::MESSAGE_QUESTION, Gtk::BUTTONS_OK, '');
			
		$dialog->set_keep_above(true);
		$dialog->set_position(Gtk::WIN_POS_CENTER);
	
		if ($title) $dialog->set_title($title);
		if ($msg) $dialog->set_markup($msg);
		
		$response = $dialog->run();
		$dialog->destroy();

		if ($response == Gtk::RESPONSE_OK) return true;
		return false;
	}
	
}
?>
