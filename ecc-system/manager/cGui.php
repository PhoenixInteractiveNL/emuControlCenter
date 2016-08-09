<?
class Gui{
	
	public function openDialogConfirm($title=false, $msg=false){
		
		# set title, message and create dialog
		$title = ($title) ? $title : I18N::get('popup', 'sys_dialog_miss_title');
		$msg = ($msg) ? $msg : I18N::get('popup', 'sys_dialog_miss_msg');
		$dialog = new GtkMessageDialog(null, Gtk::DIALOG_MODAL, Gtk::MESSAGE_QUESTION, Gtk::BUTTONS_YES_NO, $msg);
		
		$dialog->set_keep_above(true);
		$dialog->set_position(Gtk::WIN_POS_CENTER);
		
		if ($title) $dialog->set_title($title);
		$response = $dialog->run();
		$dialog->destroy();
		
		if ($response == Gtk::RESPONSE_YES) return true;
		return false;
	}

	public function openDialogInfo($title=false, $msg=false){
		
		# set title, message and create dialog
		$title = ($title) ? $title : I18N::get('popup', 'sys_dialog_miss_title');
		$msg = ($msg) ? $msg : I18N::get('popup', 'sys_dialog_miss_msg');
		$dialog = new GtkMessageDialog(null, Gtk::DIALOG_MODAL, Gtk::MESSAGE_QUESTION, Gtk::BUTTONS_OK, $msg);
		$dialog->set_position(Gtk::WIN_POS_CENTER);		
	
		if ($title) $dialog->set_title($title);
		
		$response = $dialog->run();
		$dialog->destroy();
		
		if ($dialog->get_transient_for()) $dialog->get_transient_for()->present();

		if ($response == Gtk::RESPONSE_OK) return true;
		return false;
	}
	
}
?>
