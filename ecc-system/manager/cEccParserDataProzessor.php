<?php
$iniManager = FACTORY::get('manager/IniFile');
// Variable for trigger size, added 2012-12-07 (ECC v1.13 build 12)
$ExtParserTriggerSizeMB = $iniManager->getKey('USER_SWITCHES', 'ext_parser_trigger_size');
if ($ExtParserTriggerSizeMB < 1 or $ExtParserTriggerSizeMB > 99999 or $ExtParserTriggerSizeMB == "" or !is_numeric($ExtParserTriggerSizeMB)) $ExtParserTriggerSizeMB = 100; //default value
$ExtParserTriggerSize = $ExtParserTriggerSizeMB * 1024 * 1024; // convert MB to bytes
define('ExtParserTriggerSize', $ExtParserTriggerSize);
// --->

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
	
	public function sortMixedData($a, $b){
	   return strcmp($a, $b);
	}
	
	public function parse($eccident = false){
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
			
			$log .= LOGGER::add('romparse', i18n::get('status', 'searchAndParseNewRoms'), 1);
			#$log .= LOGGER::add('romparse', join("\t", array('CRC32', 'EXT', 'NAME', 'PATH', 'PATH_PACK')));
			$log .= LOGGER::add('romparse', join("\t", array('STATE', 'CRC32', 'EXT', 'NAME')));
			
			foreach($this->_file_list as $file_extension => $file_data) {
				
				$log .= LOGGER::add('romparse', sprintf(i18n::get('status', 'addingRomsFor%s'), $file_extension));
				
				$cnt_total = count($file_data);
				$cnt_current = 0;
				
				foreach($file_data as $file_name_info) {
					
					while (gtk::events_pending()) gtk::main_iteration();
					
					$file_name_direct = $file_name_info['DIRECT_FILE'];
					$file_name_packed = isset($file_name_info['PACKED_FILE']) ? $file_name_info['PACKED_FILE'] : false;
					
					$parserFile = $file_name_info['OBJECT'];
					
					// Preparse, damit nur neu geparst wird,
					// wenn eine ï¿½nderung der Filesize (bytes) aufgetreten
					// ist. Soll verhindern, das zu oft unnï¿½tig geparst wird.
					// Sobald ein byte unterschied vorhanden ist, wird geparst.
					$size_db = $this->dataParserObj->get_file_size($file_name_direct, $file_name_packed);	// from database
					$size_fs = ($parserFile->getSize()) ? $parserFile->getSize() : FileIO::get_file_size($file_name_direct, $file_name_packed, 'B');	
					
					/*
					 * HYPERFAST MODE!!!!!!!!!!!!!!!!!
					 * THIS ONLY WORKS, IF THERE ARE FILESIZES AVAILABLE IN THE METADATA
					 */
					if ($this->connectedMetaOnlyEccident && $this->connectedMetaFilesizeCheck){
						if ((isset($availFileSizes[$size_fs]) && $availFileSizes[$size_fs]) || $availFileSizes[$size_fs] = $this->dataParserObj->findMetaByFilesize($this->connectedMetaOnlyEccident, $size_fs));
						else continue;
					}
					
					if (($size_db && $size_fs) && ($size_db == $size_fs)) {
						if (!isset($this->_parser_stats_cnt_notchanged[$file_extension])) {
							$this->_parser_stats_cnt_notchanged[$file_extension] = 0;
						}
						$this->_parser_stats_cnt_notchanged[$file_extension]++;
						$cntUnchanged++;
					}
					else {

						if ($size_fs >= ExtParserTriggerSize){
							$fileSizeMB = round($size_fs/1024/1024, 1)." MB";
							$fileName = ($file_name_packed) ? basename($file_name_packed) : basename($file_name_direct);
							$title = I18N::get('popup', 'parse_big_file_found_title');
							$msg = sprintf(I18N::get('popup', 'parse_big_file_found_msg%s%s'), $fileName, $fileSizeMB);
							if(!FACTORY::get('manager/Gui')->openDialogConfirm($title, $msg, array('dhide_big_file_warning'))) continue;
						}
						
						$out = false;
						
						// Hier beginnt das eigentliche parsen
						// File operations
						$out['IS_MULTIFILE'] = false;
						if ($file_extension == 'zip'){
							
							$zipName = $file_name_info['DIRECT_FILE'];
							$zipFileList = $file_name_info['LIST'];
							
							$completeFileSize = 0;
							$combinedCrc32String = '';
							$inZipFilesChecksums = array();
							$fileValid = true;
							
							foreach($zipFileList as $inZipFile){
								
								
								$fhdl = FileIO::fopen_zip($zipName, $inZipFile);

								$file_temp = realpath(getcwd().'/temp/'.basename($inZipFile));
								
								$out = $this->getParser('zip')->parse($fhdl, $file_temp, $zipName, false);
								$fileExt = $out['FILE_EXT'];
								$inZipFilesChecksums[$inZipFile] = $out['FILE_CRC32'];
								$inZipFilesChecksums[$inZipFile] = $out['FILE_CRC32'];
								$completeFileSize += $out['FILE_SIZE'];

								FileIO::fclose_zip($fhdl, $file_temp);
								
								if (!$out['FILE_VALID']){
									$fileValid = false;
									break;
								}
								
								FACTORY::get('manager/GuiStatus')->update_message('Parsed file '.FileIO::get_plain_filename($zipName).' -> '.$inZipFile.' ('.$out['FILE_SIZE'].' bytes)');
								
							}
							asort($inZipFilesChecksums);
							
							$out['FILE_VALID'] = $fileValid;
							$out['FILE_NAME'] = FileIO::get_plain_filename($zipName);
							$out['FILE_PATH'] = $zipName;
							$out['FILE_EXT'] = $fileExt;

							$out['FILE_SIZE'] = filesize($zipName);
							
							$out['MDATA'] = $inZipFilesChecksums;
							
							$out['IS_MULTIFILE'] = true;
							
							$out['ROM_STATE'] = array();
							$managerImportCM = FACTORY::get('manager/ImportDatControlMame');							
							if (!isset($datHandled[$out['FILE_EXT']])){
								
								$managerImportCM->resetDatfileContent();
								
								$datfile = 'datfile/'.strtolower($out['FILE_EXT']).'.dat';
								$datfileExists = (file_exists($datfile));
								
								if ($datfileExists){
									if (!isset($managerImportCM)) $managerImportCM->setStatusHandler($this->fileListObj->status_obj);
									$managerImportCM->setFromFile($datfile);
								}
							}
							$datHandled[$out['FILE_EXT']] = true;
							
							$data = $managerImportCM->searchForRom(array_flip($out['MDATA']), $out['FILE_PATH']);
							$out['ROM_STATE'] = $data;
							$bestMatch = $managerImportCM->getBestMatch($data, false);
							
							# write crc from datfile, if available
							if (isset($bestMatch['info']['mergedEccCrc32'])){
								$out['FILE_CRC32'] = $bestMatch['info']['mergedEccCrc32'];
								#print $out['FILE_CRC32']." -> ".$out['FILE_PATH']."\n";
							}
							else {
								$combinedCrc32String = join(",", $out['MDATA']);
								$out['FILE_CRC32'] = FileIO::ecc_get_crc32_from_string($combinedCrc32String);								
							}
						}
						elseif ($file_name_packed) {
							
							$parserFile = $file_name_info['OBJECT'];
							
							switch ($parserFile->getType()){
								
								// normal zip file
								case ParserFile::ZIP:

									$fhdl = FileIO::fopen_zip($file_name_direct, $file_name_packed);
									$file_temp = realpath(getcwd().'/temp/'.basename($file_name_packed));
									$parser = $this->getParser($file_extension);
									$out = $parser->parse($fhdl, $file_temp, $file_name_direct, $file_name_packed);
									if($fhdl) FileIO::fclose_zip($fhdl, $file_temp);
									
									break;

								// 7z/rar file
								case ParserFile::SZIP:
									
									$parser = $this->getParser($parserFile->getExtension());
									if(!$parser->hasRipHeader()){
										
										$out = array();
										//OLD
										//$out['FILE_NAME'] = basename($parserFile->getNamePacked());
										//
										//2014.05.25 Adjusted for ROM files in archives 7z/rar to have no extension in the TITLE column in fdata
										$out['FILE_NAME'] = FileIO::get_plain_filename(basename($parserFile->getNamePacked()));							
										$out['FILE_PATH'] = $parserFile->getName();
										$out['FILE_PATH_PACK'] = $parserFile->getNamePacked();
										$out['FILE_EXT'] = strtoupper($eccident);
										$out['FILE_SIZE'] = $parserFile->getSize();
										$out['FILE_CRC32'] = $parserFile->getCrc32();
										$out['FILE_MD5'] = NULL;
										$out['FILE_VALID'] = true;
									
									}
									else{
										
										$manager7zip = FACTORY::get('manager/cmd/php7zip/sZip');
										$manager7zip->setExecutable(SZIP_UNPACK_EXE);
										
										$outputFolder = realpath(getcwd().'/temp/');
										$manager7zip->extract($parserFile->getName(), $parserFile->getNamePacked(), $outputFolder);
	
										$tempFile = $outputFolder.'/'.basename($parserFile->getNamePacked());
										$tempFileHandle = fopen($tempFile, 'rb');
										
										$out = $parser->parse($tempFileHandle, $tempFile, $parserFile->getName(), $parserFile->getNamePacked());
										
										fclose($tempFileHandle);
										unlink($tempFile);
										
									}
									break;
							}
						}
						else {
							$fhdl = fopen($file_name_direct, 'rb');
							
							$parser = $this->getParser($file_extension);
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
							 * HYPERFAST MODE!!
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
		
		$txtStatistics = I18N::get('global', 'statistics');
		$txtParsed = I18N::get('global', 'parsed');
		$txtAdded = I18N::get('global', 'added');
		$txtInvalid = I18N::get('global', 'invalid');
		$txtUnchanged = I18N::get('global', 'unchanged');
		
		$logStats  = '';
		$logStats .= $txtStatistics."\r\n";
		$logStats .= $txtParsed.": ".$cntOverall."\r\n";
		$logStats .= $txtAdded.": ".$cntValid."\r\n";
		$logStats .= $txtInvalid.": ".$cntInvalid."\r\n";
		$logStats .= $txtUnchanged.": ".$cntUnchanged;
		$log .= LOGGER::add('romparse', $logStats, 2);
		
		$detail_header = $this->format_results(true);
		$this->fileListObj->status_obj->update_message($detail_header);
		
		return $log;
	}
	
	private function getParser($file_extension) {
		
		$parserClassNamePlain = $this->_known_extensions[$file_extension]['parser'];
		
//		if (in_array($file_extension, $this->dispatchExtensions)) {
//			#$dispatcherClassName = 'parser/dispatch/Dispatch'.ucfirst($file_extension);
//			#$dispatcher = FACTORY::get($dispatcherClassName, $fileHandle);
//			
//			$dispatcherClassName = 'parser/dispatch/cDispatch'.ucfirst($file_extension).".php";
//			$dispatcherClass = 'Dispatch'.ucfirst($file_extension);
//			
//			require_once($dispatcherClassName);
//			$dispatcher = new $dispatcherClass($fileHandle);
//
//			$dispatchedParser = $dispatcher->getValidParser();
//			if ($dispatchedParser) {
//				$parserClassNamePlain = $dispatchedParser;
//			}
//			else {
//				// unknown file
//				return false;
//			}
//		}
		
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
			$txt .= "\n".I18N::get('status', 'parse_rom_detail_invalidZip')."\n";
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
