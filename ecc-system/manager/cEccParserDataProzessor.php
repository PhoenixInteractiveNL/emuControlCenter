<?php
define('SLOW_CRC32_PARSING_FROM', 100663296);

class EccParserDataProzessor{
	
	// objects
	private $dataParserObj = false;
	private $fileListObj = false;
	
	private $gui;
	
	// data
	private $_file_list = array();
	private $_base_directory = false;
	private $_known_extensions = array();
	
	private $connectedMetaOnlyEccident = false;
	private $connectedMetaFilesizeCheck = false;
	
	// statistics
	private $_parser_stats_cnt_notchanged = array();
	private $_parser_stats_cnt_add = array();
	
	public function __construct($dataParserObj, $fileListObj, $dispatchExtensions, $gui)
	{
		$this->gui = $gui;
		$this->dataParserObj = $dataParserObj;
		$this->fileListObj = $fileListObj;
		
		$this->dispatchExtensions = $dispatchExtensions;
		
		$this->_file_list = $this->fileListObj->get_file_list();
		
		$this->_base_directory = $this->fileListObj->get_base_directory();
		$this->_known_extensions = $this->fileListObj->get_known_extensions();
		
		$this->invalidFiles = $this->fileListObj->invalidFile;
	}
	
	public function setConnectedMetaOnlyEccident($eccident){
		$this->connectedMetaOnlyEccident = $eccident;
	}
	public function setConnectedMetaFilesizeCheck($state){
		$this->connectedMetaFilesizeCheck = $state;
	}	
	
	public function parse()
	{
		$log = '';
		$cntValid = 0;
		$cntInvalid = 0;
		$cntUnchanged = 0;
		$cntOverall = 0;
		
		if ($this->_file_list) {
			
			/*
			print "#######################\n";
			print "START PARSING\n";
			print "+ update/insert media\n";
			print "= unchanged record\n";
			print "#######################\n";
			*/
			
			// F�r jede file_extension daten parsen
			$log .= LOGGER::add('romparse', "Search and Parse new romfiles!!!!", 1);
			#$log .= LOGGER::add('romparse', join("\t", array('CRC32', 'EXT', 'NAME', 'PATH', 'PATH_PACK')));
			$log .= LOGGER::add('romparse', join("\t", array('STATE', 'CRC32', 'EXT', 'NAME')));
			
			foreach($this->_file_list as $file_extension => $file_data) {
				
				$log .= LOGGER::add('romparse', "Adding roms for $file_extension");
				
				$cnt_total = count($file_data);
				$cnt_current = 0;
				
				foreach($file_data as $file_name_info) {
					
					while (gtk::events_pending()) gtk::main_iteration();
					
					$file_name_direct = $file_name_info['DIRECT_FILE'];
					$file_name_packed = isset($file_name_info['PACKED_FILE']) ? $file_name_info['PACKED_FILE'] : false;
					
					// Preparse, damit nur neu geparst wird,
					// wenn eine �nderung der Filesize (bytes) aufgetreten
					// ist. Soll verhindern, das zu oft unn�tig geparst wird.
					// Sobald ein byte unterschied vorhanden ist, wird geparst.
					$size_db = $this->dataParserObj->get_file_size($file_name_direct, $file_name_packed);	// from database
					$size_fs = FileIO::get_file_size($file_name_direct, $file_name_packed, 'B');
					
					/*
					 * HYPERFAST MODE!!!!!!!!!!!!!!!!!
					 * THIS ONLY WORKS, IF THERE ARE FILESIZES AVAILABLE IN THE METADATA
					 */
					if ($this->connectedMetaOnlyEccident && $this->connectedMetaFilesizeCheck){
						if ((isset($availFileSizes[$size_fs]) && $availFileSizes[$size_fs]) || $availFileSizes[$size_fs] = $this->dataParserObj->findMetaByFilesize($this->connectedMetaOnlyEccident, $size_fs));
						else continue;
						print "$size_fs $file_name_direct".LF;
					}
					
					if (($size_db && $size_fs) && ($size_db == $size_fs)) {
						if (!isset($this->_parser_stats_cnt_notchanged[$file_extension])) {
							$this->_parser_stats_cnt_notchanged[$file_extension] = 0;
						}
						$this->_parser_stats_cnt_notchanged[$file_extension]++;
						$cntUnchanged++;
					}
					else {

						if ($size_fs >= SLOW_CRC32_PARSING_FROM){
							$fileSizeMB = round($size_fs/1024/1024, 1)." MB";
							$fileName = ($file_name_packed) ? basename($file_name_packed) : basename($file_name_direct);
							
							$title = I18N::get('popup', 'parse_big_file_found_title');
							$msg = sprintf(I18N::get('popup', 'parse_big_file_found_msg%s%s'), $fileName, $fileSizeMB);
							
							#$title = "Really parse this file?";
							#$msg = "BIG FILE FOUND!!!\n\nThe found game\n\nName: ".$fileName."\nSize: ".$fileSizeMB."\n\nis very large. This can take a long time without direct feedback of emuControlCenter.\n\nDo you want parse this game?";
							if(!FACTORY::get('manager/Gui')->openDialogConfirm($title, $msg)) continue;
						}
						
						
						$out = false;
						
						// Hier beginnt das eigentliche parsen
						// File operations
						if ($file_name_packed) {
							$fhdl = FileIO::fopen_zip($file_name_direct, $file_name_packed);
							$file_temp = realpath(getcwd().'/temp/'.basename($file_name_packed));
							
							$parser = $this->getParser($file_extension, $fhdl);
							if ($parser) {
								$out = $parser->parse($fhdl, $file_temp, $file_name_direct, $file_name_packed);
							}
							else {
								print "DISPATCH_".strtoupper($file_extension)." INVALID: ".basename($file_name_direct)." / ".basename($file_name_packed)."\n";
							}
							FileIO::fclose_zip($fhdl, $file_temp);
						}
						else {
							$fhdl = fopen($file_name_direct, 'rb');
							
							$parser = $this->getParser($file_extension, $fhdl);
							if ($parser) {
								$out = $parser->parse($fhdl, $file_name_direct, $file_name_direct, false);
							}
							else {
								print "DISPATCH_".strtoupper($file_extension)." INVALID: ".basename($file_name_direct)." / ".basename($file_name_packed)."\n";
							}
							fclose($fhdl);
						}
						
						if ($out && $out['FILE_VALID']) {
							
							/*
							 * HYPERFAST MODE!!!!!!!!!!!!!!!!!
							 * THIS ONLY WORKS, IF THERE ARE FILESIZES AVAILABLE IN THE METADATA
							 */
							if ($this->connectedMetaOnlyEccident){
								if ((isset($availFileCrc32[$out['FILE_CRC32']]) && $availFileCrc32[$out['FILE_CRC32']]) || $availFileCrc32[$out['FILE_CRC32']] = $this->dataParserObj->findMetaByCrc32($this->connectedMetaOnlyEccident, $out['FILE_CRC32']));
								else continue;
								#print $out['FILE_CRC32']." -> ".$out['FILE_NAME'].LF;
							}
							
							// Db operations
							$cntValid++;
							$this->dataParserObj->add_file($out);
							$log .= LOGGER::add('romparse', join("\t", array('OK ', $out['FILE_CRC32'], $file_extension, $out['FILE_NAME'])));
							#$log .= LOGGER::add('romparse', join("\t", array($out['FILE_CRC32'], $file_extension, $out['FILE_NAME'], $out['FILE_PATH'], $out['FILE_PATH_PACK'])));
							
						}
						else {
							$log .= LOGGER::add('romparse', join("\t", array('ERR', '________', $file_extension, $out['FILE_NAME'])));
							$cntInvalid++;
						}
						
						
						if (!isset($this->_parser_stats_cnt_add[$file_extension])) {
							$this->_parser_stats_cnt_add[$file_extension] = 0;
						}
						$this->_parser_stats_cnt_add[$file_extension]++;
					}
					
					// update statusbar
					// ------------------
					$cnt_current++;
					$cntOverall++;
					
					$packed_txt = ($file_name_packed) ? I18N::get('status', 'parse_rom_pbar_file_packed') : "";
					$current_percent = (float)$cnt_current/$cnt_total;
					$progressbar_string = sprintf(I18N::get('status', 'parse_rom_pbar_file%s%s%s'), $cnt_current, $cnt_total, $packed_txt);
					$this->fileListObj->status_obj->update_progressbar($current_percent, $file_extension.": ".round($current_percent*100)."% ".$progressbar_string);
					$detail_header = sprintf(I18N::get('status', 'parse_rom_detail_header%s'), $this->format_results());
					$this->fileListObj->status_obj->update_message($detail_header);
					if ($this->fileListObj->status_obj->is_canceled()){
						#FACTORY::getDbms()->query('ROLLBACK TRANSACTION;');
						return false;
					}
				}
				$this->gui->update_treeview_nav();
			}
		}
		else {
		}
		
		$logStats  = '';
		$logStats .= "Statistics\r\n";
		$logStats .= "Parsed: ".$cntOverall."\r\n";
		$logStats .= "Added: ".$cntValid."\r\n";
		$logStats .= "Invalid: ".$cntInvalid."\r\n";
		$logStats .= "Unchanged: ".$cntUnchanged;
		$log .= LOGGER::add('romparse', $logStats, 2);
		
		$detail_header = $this->format_results(true);
		$this->fileListObj->status_obj->update_message($detail_header);
		
		return $log;
	}
	
	private function getParser($file_extension, $fileHandle) {
		
		$parserClassNamePlain = $this->_known_extensions[$file_extension][0];
		
		if (in_array($file_extension, $this->dispatchExtensions)) {
			#$dispatcherClassName = 'parser/dispatch/Dispatch'.ucfirst($file_extension);
			#$dispatcher = FACTORY::get($dispatcherClassName, $fileHandle);
			
			$dispatcherClassName = 'parser/dispatch/cDispatch'.ucfirst($file_extension).".php";
			$dispatcherClass = 'Dispatch'.ucfirst($file_extension);
			
			require_once($dispatcherClassName);
			$dispatcher = new $dispatcherClass($fileHandle);

			$dispatchedParser = $dispatcher->getValidParser();
			if ($dispatchedParser) {
				$parserClassNamePlain = $dispatchedParser;
			}
			else {
				// unknown file
				return false;
			}
		}
		
		$parameter = false;
		if (FALSE !== $position = strpos($parserClassNamePlain, "#")) {
			$className = substr($parserClassNamePlain, 0, $position);
			$parameter = substr($parserClassNamePlain, $position+1);
		}
		else {
			$className = $parserClassNamePlain;
		}

		return FACTORY::getStrict('parser/'.$className, $parameter);
	}
	
	public function format_results($addFinalNote = false)
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
		
		if ($addFinalNote && count($this->invalidFiles)) {
			$txt .= "\nFound invalid zip-files... please repack!\n";
			foreach ($this->invalidFiles as $key => $value) {
				$txt .=  "$value\n";
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
