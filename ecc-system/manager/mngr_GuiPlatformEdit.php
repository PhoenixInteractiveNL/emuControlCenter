<?
class GuiPlatformEdit {
	
	private $gui = false;
	
	public function __construct($gui_obj=false) {
		if (!$gui_obj) return false;
		$this->gui = $gui_obj;
	}
}
?>
