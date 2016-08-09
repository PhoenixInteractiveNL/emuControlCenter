<?php
class EccParserDataProzessor{
	
	// objects
	private $dataParserObj = false;
	private $fileListObj = false;
	
	// data
	private $_file_list = array();
	private $_base_directory = false;
	private $_known_extensions = array();
	
	// statistics
	private $_parser_stats_cnt_notchanged = array();
	private $_parser_stats_cnt_add = array();
	
	public function __construct($dataParserObj, $fileListObj)
	{
		$this->dataParserObj = $dataParserObj;
		$this->fileListObj = $fileListObj;
		
		$this->_file_list = $this->fileListObj->get_file_list();
		$this->_base_directory = $this->fileListObj->get_base_directory();
		$this->_known_extensions = $this->fileListObj->get_known_extensions();
	}
	
	public function parse()
	{
		if ($this->_file_list) {
			
			/*
			print "#######################\n";
			print "START PARSING\n";
			print "+ update/insert media\n";
			print "= unchanged record\n";
			print "#######################\n";
			*/
			
			// Für jede file_extension daten parsen
			foreach($this->_file_list as $file_extension => $file_data) {
				
				$cnt_total = count($file_data);
				$cnt_current = 0;
				foreach($file_data as $file_name_info) {
					
					while (gtk::events_pending()) gtk::main_iteration();
					
					$file_name_direct = $file_name_info['DIRECT_FILE'];
					$file_name_packed = isset($file_name_info['PACKED_FILE']) ? $file_name_info['PACKED_FILE'] : false;
					
					// Parser für extensions suchen
					$parser = Singleton::get_instance($this->_known_extensions[$file_extension], "parser/");
					
					// Preparse, damit nur neu geparst wird,
					// wenn eine änderung der Filesize (bytes) aufgetreten
					// ist. Soll verhindern, das zu oft unnötig geparst wird.
					// Sobald ein byte unterschied vorhanden ist, wird geparst.
					$size_db = $this->dataParserObj->get_file_size($file_name_direct, $file_name_packed);	// from database
					$size_fs = FileIO::get_file_size($file_name_direct, $file_name_packed, 'B');
					
					if (($size_db && $size_fs) && ($size_db == $size_fs)) {
						if (!isset($this->_parser_stats_cnt_notchanged[$file_extension])) {
							$this->_parser_stats_cnt_notchanged[$file_extension] = 0;
						}
						$this->_parser_stats_cnt_notchanged[$file_extension]++;
					}
					else {
						// Hier beginnt das eigentliche parsen
						// File operations
						if ($file_name_packed) {
							$fhdl = FileIO::fopen_zip($file_name_direct, $file_name_packed);
							$file_temp = realpath(getcwd().'/temp/'.basename($file_name_packed));
							$out = $parser->parse($fhdl, $file_temp, $file_name_direct, $file_name_packed);
							FileIO::fclose_zip($fhdl, $file_temp);
						}
						else {
							$fhdl = fopen($file_name_direct, 'rb');
							$out = $parser->parse($fhdl, $file_name_direct, $file_name_direct, false);
							fclose($fhdl);
						}
						
						// Db operations
						$this->dataParserObj->add_file($out);
						
						if (!isset($this->_parser_stats_cnt_add[$file_extension])) {
							$this->_parser_stats_cnt_add[$file_extension] = 0;
						}
						$this->_parser_stats_cnt_add[$file_extension]++;
					}
					
					// update statusbar
					// ------------------
					$cnt_current++;
					
					$packed_txt = ($file_name_packed) ? "(PACKED)" : "";
					$current_percent = (float)$cnt_current/$cnt_total;
					
					#$this->fileListObj->pbar_parser->set_fraction($current_percent);
					#$this->fileListObj->pbar_parser->set_text($file_extension.": ".round($current_percent*100)."% - FILE ".$cnt_current." of ".$cnt_total." ".$packed_txt);
					
					#$out = ""; 
					#$out .= "_parser_stats_cnt_add\n".print_r($this->_parser_stats_cnt_add,true);
					#$out .= "_parser_stats_cnt_notchanged\n".print_r($this->_parser_stats_cnt_notchanged,true);
					
					#$this->fileListObj->statusbar_lbl_bottom->set_text("Now parsing ".$cnt_total." ".$file_extension." files\n".$this->format_results());
				
#sb####################
					$this->fileListObj->status_obj->update_progressbar($current_percent, $file_extension.": ".round($current_percent*100)."% - FILE ".$cnt_current." of ".$cnt_total." ".$packed_txt);
					$this->fileListObj->status_obj->update_message("Now parsing\n".$this->format_results());
					if ($this->fileListObj->status_obj->is_canceled()) return false;
#sb####################
				}
			}
			#$this->fileListObj->statusbar_lbl_bottom->set_text("DONE- PLEASE HIDE WINDOW");
		}
		else {
			#$this->fileListObj->statusbar_lbl_bottom->set_text("SORRY - NO MEDIA FOUND");
		}
	}
	
	public function format_results()
	{
		$txt  = "";
		if (isset($this->_parser_stats_cnt_add) && count($this->_parser_stats_cnt_add)) {
			$txt .= "New media added to ecc\n";
			foreach ($this->_parser_stats_cnt_add as $key => $value) {
				$txt .= "$key\t\t$value\n";
			}
		}
		if (isset($this->_parser_stats_cnt_notchanged) && count($this->_parser_stats_cnt_notchanged)) {
			$txt .= "No change since last parsing\n";
			foreach ($this->_parser_stats_cnt_notchanged as $key => $value) {
				$txt .=  "$key\t\t$value\n";
			}
		}
		return $txt;
	}
	
	public function get_stats() {
		$this->_stats['UNCHANGED'] = $this->_parser_stats_cnt_notchanged;
		$this->_stats['CHANGED'] = $this->_parser_stats_cnt_add;
		return $this->_stats;
	}
}
?>
