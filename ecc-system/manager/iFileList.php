<?
interface FileList {
	public function __construct($base_directory, $known_extension, $pbar_parser, $statusbar_lbl_bottom, $status_obj);
	public function get_file_list();
	public function get_known_extensions();
	public function get_base_directory();
	public function get_stats();
}
?>
