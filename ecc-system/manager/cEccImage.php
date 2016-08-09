<?
class EccImage {
	
	public function __construct($gui_obj=false) {}
	
	public function remove($file) {
		if (file_exists($file)) {
			unlink($file);
			return true;
		}
		return false;
	}
	
	public function save($eccident, $prefix, $file, $convert=false) {
		print "public function save($eccident, $prefix, $file, $convert=false) {";
	}
}
?>
