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
			
			// F�r jede file_extension daten parsen
			foreach($this->_file_list as $file_extension => $file_data) {
				
				$cnt_total = count($file_data);
				$cnt_current = 0;
				foreach($file_data as $file_name_info) {
					
					while (gtk::events_pending()) gtk::main_iteration();
					
					$file_name_direct = $file_name_info['DIRECT_FILE'];
					$file_name_packed = isset($file_name_info['PACKED_FILE']) ? $file_name_info['PACKED_FILE'] : false;
					
					// Parser für extensions suchen
					#$parser = Singleton::get_instance($this->_known_extensions[$file_extension], "parser/");
					
					$className = $this->_known_extensions[$file_extension][0];
					$parameter = false;
					if (FALSE !== $position = strpos($this->_known_extensions[$file_extension][0], "#")) {
						$className = substr($this->_known_extensions[$file_extension][0], 0, $position);
						$parameter = substr($this->_known_extensions[$file_extension][0], $position+1);
					}
					
//					print "-> ".get_class($parser)." -> ".$className." -- ".$parameter."\n";					
					
					$parser = FACTORY::getStrict('parser/'.$className, $parameter);
					

					
					// Preparse, damit nur neu geparst wird,
					// wenn eine �nderung der Filesize (bytes) aufgetreten
					// ist. Soll verhindern, das zu oft unn�tig geparst wird.
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
					
					$packed_txt = ($file_name_packed) ? I18N::get('status', 'parse_rom_pbar_file_packed') : "";
					$current_percent = (float)$cnt_current/$cnt_total;
					$progressbar_string = sprintf(I18N::get('status', 'parse_rom_pbar_file%s%s%s'), $cnt_current, $cnt_total, $packed_txt);
					$this->fileListObj->status_obj->update_progressbar($current_percent, $file_extension.": ".round($current_percent*100)."% ".$progressbar_string);
					$detail_header = sprintf(I18N::get('status', 'parse_rom_detail_header%s'), $this->format_results());
					$this->fileListObj->status_obj->update_message($detail_header);
					if ($this->fileListObj->status_obj->is_canceled()) return false;
				}
			}
		}
		else {
		}
	}
	
	public function format_results()
	{
		$txt  = "";
		if (isset($this->_parser_stats_cnt_add) && count($this->_parser_stats_cnt_add)) {
			$txt .= I18N::get('status', 'parse_rom_detail_added_header');
			foreach ($this->_parser_stats_cnt_add as $key => $value) {
				$txt .= "$key\t\t$value\n";
			}
		}
		if (isset($this->_parser_stats_cnt_notchanged) && count($this->_parser_stats_cnt_notchanged)) {
			$txt .= I18N::get('status', 'parse_rom_detail_unchanged_header');
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
