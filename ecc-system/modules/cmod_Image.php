<?php
class mod_Image {
	
	public $test;
	
	public function test(){
		print "TEST";
	}
	
	public function removeAllImageFromSelection($eccident, $crc32, $romTitle){
		if (!$eccident || !$crc32) return false;
		
		$title = I18N::get('popup', 'img_remove_all_title');
		$msg = sprintf(I18N::get('popup', 'img_remove_all_msg%s'), $romTitle);
		if (!FACTORY::get('manager/Gui')->openDialogConfirm($title, $msg)) return false;

		$imageManager = FACTORY::create('manager/Image');
		$imageManager->removeUserImageFolder($eccident, $crc32);

		$imageManager->resetCachedImages($eccident, $crc32);
		$this->eccGui->onReloadRecord();
	}
	
}
?>
