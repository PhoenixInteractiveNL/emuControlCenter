<?
interface FileList {
	public function __construct($base_directory, $known_extension);
	public function get_file_list();
	public function get_known_extensions();
	public function get_base_directory();
	public function get_stats();
}
?>
