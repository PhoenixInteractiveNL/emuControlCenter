<?
class DatFileImport extends App {
	
	private $dbms = false;
	public $ini = false;
	public $dat = array();
	private $status_obj = false;
	private $count_eccident = 0;
	private $datfileContent = false;
	
	private $dat_filename = false;
	
	private $log = '';
	
	public function __construct($eccident=false, $status_obj, $ini)
	{
		$this->eccident = $eccident;
		$this->status_obj = $status_obj;
		$this->ini = $ini;
	}
	
	public function setDbms($dbmsObject) {
		$this->dbms = $dbmsObject;
	}
	
	public function parse($filename=false)
	{
		$this->dat_filename = $filename;
		$this->find_dat_type();
	}
	
	public function setDirectDatfileContent($datfileContent){
		$this->datfileContent = $datfileContent;
	}
	
	public function getLog() {
		return $this->log;
	}
	
	public function find_dat_type()
	{
		$dat_header = $this->parse_ini_file_quotes_safe($this->dat_filename, 20);
		
		if (!$dat_header) {
			$this->status_obj->update_progressbar(0, "ERROR 1!");
			$message = "This is not a compatible Romcenter-Dat!\n";
			$message .= '"'.$this->dat_filename.'"'."";
			$this->status_obj->update_message($message);
		}
		
		$dat_header_lowercase = array();
		foreach ($dat_header as $key_dim_1 => $void) {
			if (is_array($void)) {
				foreach ($void as $key_dim_2 => $value) {
				 $dat_header_lowercase[strtoupper($key_dim_1)][strtoupper($key_dim_2)] = $value;
				}
			}
		}
		$dat_header = $dat_header_lowercase;
		
		if (
			isset($dat_header['ECC']['GENERATOR']) &&
			isset($dat_header['ECC']['ECC-VERSION']) &&
			isset($dat_header['ECC']['DAT-TYPE']) &&
			isset($dat_header['ECC']['DAT-VERSION'])
		) {
			
			#if(LOGGER::$active) {
				$msg  = "";
				$msg .= "Import emuControlCenter datfile\r\n";
				$msg .= "Platform: ".$this->ini->getPlatformName($this->eccident)." (".$this->eccident.")\r\n";
				$msg .= "ECC-VERSION:".$dat_header['ECC']['ECC-VERSION']."  / DAT-VERSION:".$dat_header['ECC']['DAT-VERSION']."\r\n";
				$msg .= "FILE:".$this->dat_filename;
				$this->log .= LOGGER::add('datiecc', $msg, 1);
				$this->log .= LOGGER::add('datiecc', join("\t", array('ACTION', 'IHAVE', 'ECCIDENT', 'CRC32', 'name')));
			#}
			
			$this->parse_dat_emucontrolcenter($dat_header['ECC']['DAT-VERSION']);
		}
		elseif (
			//(isset($dat_header['DAT']['version']) && $dat_header['DAT']['version'] == "2.00") &&
			isset($dat_header['DAT']['VERSION']) &&
			isset($dat_header['CREDITS']['AUTHOR']) &&
			isset($dat_header['CREDITS']['VERSION'])
		) {
			$first_game = key($dat_header['GAMES']);

			if (!$this->eccident) {
				$this->status_obj->update_progressbar(0, "ERROR 2!");
				$message = "Romcenter: You have to choose a Platform!\n\n";
				$message .= "You cannot import a Romcenter-Datfile without selecting the Platform!\n";
				$message .= "Please choose the correct Platform in the navigation and try again!\n";
				$this->status_obj->update_message($message);
			}
			elseif ($this->validate_romcenter_format($dat_header['DAT']['VERSION'], $first_game)) {
				
				#if(LOGGER::$active) {
					$msg  = "";
					$msg .= "Import romcenter datfile\r\n";
					$msg .= "Platform: ".$this->ini->getPlatformName($this->eccident)." (".$this->eccident.")\r\n";
					$msg .= "DAT-VERSION:".$dat_header['DAT']['VERSION']."  / AUTHOR:".$dat_header['CREDITS']['AUTHOR'];
					$this->log .= LOGGER::add('datirc', $msg, 1);
					$this->log .= LOGGER::add('datirc', join("\t", array('ACTION', 'IHAVE', 'ECCIDENT', 'CRC32', 'name')));
				#}
				
				$this->parse_dat_romcenter($this->dat_filename);
			}
			else {
				$this->status_obj->update_progressbar(0, "ERROR 3!");
				$message = "Sorry... missing extension in Romcenter-Dat!\n";
				$message .= '"'.basename($this->dat_filename).'"'."\n\n";
				
				$possible_extensions = $this->ini->getPlatformFileExtensions($this->eccident);
				$message .= "ecc searching for extension \"*.".implode("; *.", array_keys($possible_extensions))."\" but dont find any of these in this dat-file! (Row 5)\n\n";
				$message .= "You can modify the datfile placing the fileextension in the 5. row and try again!\n";
				$message .= "-rname (U)-rname (U)-rname (U)-rname (U)-rname.ext (U)-f2ee11f9-2097152---\n";
				$this->status_obj->update_message($message);
			}
		}
		/*
		elseif (
			$this->get_ext_form_file($this->dat_filename)=='rdx' &&
			isset($dat_header['META']['AUTHOR']) &&
			isset($dat_header['META']['VERSION']) &&
			isset($dat_header['META']['DATE']) &&
			isset($dat_header['META']['HOMEPAGE'])
		) {
			$this->parse_dat_project64($this->dat_filename);
		}
		*/
		else {
			$this->status_obj->update_progressbar(0, "ERROR 4!");
			$message = "Unknown Dat-Format in file\n";
			$message .= '"'.$this->dat_filename.'"'."\n";
			$this->status_obj->update_message($message);
		}
	}
	
	public function validate_romcenter_format($rc_dat_version=false, $first_game = array()) {
		
		# chr(172) is the seperator "¬"
		$split_row = explode(chr(172), $first_game);
		#$split_row = explode("Â¬", iconv('ISO-8859-1', 'UTF-8', $first_game));
		
		switch ($rc_dat_version) {
			case '2.00':
			case '2.50':
				// count of fields match version
				if (count($split_row) != 11) return false;
				// if there is no extension in row5, its not valid!
				if (!strpos($split_row[5], ".")) return false;
				// all ok!
				return true;
				break;
			default:
				$this->status_obj->update_progressbar(0, "ERROR 5!");
				$message = "Romcenter-Dat-Version '".$rc_dat_version."' is not supported\n";
				$message .= '"'.$this->dat_filename.'"'."\n";
				$this->status_obj->update_message($message);
				return false;
				break;
		}
	}
	
	public function parse_dat_romcenter() {
		
		// regular expresions to clean-up
		// the dat-file! The array is
		// only strip data, if activated in ecc_general_ini
		$dat_strip_data = array();
		if ($this->ini->getKey('USER_SWITCHES','dat_import_rc_namestrip')) {
			include('system/datfile/ecc_dat_stripper.php');
		}
		
		$ecc_ident = $this->eccident;
		$file_ext_default = false;
		$name_from_field = 1;
		$extension_from_field = 5;
		$use_extesion_as_eccident = false;
		
		// strip data after the specified string
		// eg. $strip_info_direct_after="("
		// remove all data following the first "("
		// Stripped data is added to INFO!
		$strip_info_direct_after = false;
		
		// strip info from filename between
		// $strip_info_pos_start and $strip_info_pos_end
		// stripped data added to INFO!
		$strip_info_pos_start = false;			// position eg 0
		$strip_info_pos_end = false;			// position eg 10
		
		// get extensions assigned to ecc-platform
		// only do something, if a extension is assigned to the platform (eccident)
		$possible_extensions = $this->ini->getPlatformFileExtensions($this->eccident);
		if (count($possible_extensions) == 0) return false;
		
		// get data from ini-file
		$ini_data = $this->parse_ini_file_quotes_safe($this->dat_filename);
		
		$ret = array();
		$ret['CREDITS'] = $ini_data['CREDITS'];
		$ret['DAT'] = $ini_data['DAT'];
		$ret['EMULATOR'] = $ini_data['EMULATOR'];
		
		$count_valid = 0;
		$count_total = 0;
		
		$rc_status = array();
		foreach ($ini_data['GAMES'] as $key => $value) {
			
			while (gtk::events_pending()) gtk::main_iteration();
			
			# get data for a row
			# chr(172) is the seperator "¬"
			$split = explode(chr(172), $key);
			#$split = explode("Â¬", iconv('ISO-8859-1', 'UTF-8', $key));
			
			// corrupt data... jump to next entry and log!
			if (!isset($split[$name_from_field]) || !isset($split[$extension_from_field])) {
				if (!isset($rc_status['CORRUPT'])) $rc_status['CORRUPT'] = 0;
				$rc_status['CORRUPT']++;
				continue;
			}
			
			// get valid data
			$current_extension = $this->get_file_extension_from_string($split[$extension_from_field]);
			if (isset($possible_extensions[$current_extension])) {
				
				// einige felder sind in lowercase
				// hier kann angegeben werden, welches feld
				// fï¿½r den file_namen genutzt werden soll
				if ($extension_from_field !== false) {
					
					// there is no extension
					if (false === strpos($split[$extension_from_field], ".")) {
						$file_name = trim($split[$name_from_field]);
						$file_ext = "";
					}
					else {
						$file_ext = $this->get_file_extension_from_string($split[$extension_from_field]);
						if (isset($name_from_field) && $name_from_field !== false) {
							$file_name = trim(str_replace($file_ext_default, "", $split[$name_from_field]));
						}
						else {
							$file_name = $split[$extension_from_field];
						}
					}
				}
				else {
					$file_ext = $file_ext_default;
					$file_name = trim(str_replace($file_ext_default, "", $split[$name_from_field]));
				}
				$eccident = ($use_extesion_as_eccident===true) ? trim(str_replace(".", "", $file_ext)) : $ecc_ident;
				$file_ext = trim(str_replace(".", "", $file_ext));
			
				$file_crc32 = $split[6];
				$file_id = $split[7];
				
				$stripped_infos = "";
				
				if ($strip_info_pos_start || $strip_info_pos_end) {
					$strip_info_pos_start = ($strip_info_pos_start) ? $strip_info_pos_start : 0;
					$strip_info_pos_end = ($strip_info_pos_end) ? $strip_info_pos_end : strlen($file_name);
					$stripped_infos .= substr($file_name, $strip_info_pos_start, $strip_info_pos_end);
					$file_name = str_replace($stripped_infos, '', $file_name);
				}
				
				if (isset($strip_info_direct_after) && $strip_info_direct_after !== false) {
					if ($pos = strpos($file_name, $strip_info_direct_after)) {
						$file_name_tmp = $file_name;
						$file_name = trim(substr($file_name_tmp, 0, $pos));
						$stripped_infos .= trim(substr($file_name_tmp, $pos));
					}
				}
				else {
					// remove crap from info-file-data
					foreach ($dat_strip_data as $search => $regex) {
						if (stripos($file_name, $search)!==false) {
							
							if ($regex===false) {
								$file_name_original_tmp = $file_name;
								$file_name = str_ireplace($search, "", $file_name);
								if ($file_name != $file_name_original_tmp) {
									$stripped_infos .= trim($search)."|";
								}
							}
							else {
								if (is_array($regex)) {
									
									$regex_tmp = $regex;
									$regex = $regex_tmp['regex'];
									$save_type = $regex_tmp['save_type'];
									$save_string = $regex_tmp['save_string'];
									$save_match = (isset($regex_tmp['save_match'])) ?  $regex_tmp['save_match'] : 0;
									$remove_match = (isset($regex_tmp['remove_match'])) ?  $regex_tmp['remove_match'] : 0;
									
									if ($regex!==false) {
										preg_match('/'.$regex.'/i', $file_name, $matches);
										if (isset($matches[0])) {
											$stripped_infos .= trim($matches[0])."|";
											$file_name = preg_replace('/'.$regex.'/i', "", $file_name);
											
											// auto detect
											if ($save_match) {
												$save_string = $matches[$save_match];
											}
											else {
												$save_string = ($save_string!==false) ? $save_string : $matches[0];
											}
											$ret['GAMES'][$file_crc32]['ECC_INFOS'][$save_type][$save_string] = true;
										}
									}
									else {
										$file_name_original_tmp = $file_name;
										$file_name = str_ireplace($search, "", $file_name);
										if ($file_name != $file_name_original_tmp) {
											$stripped_infos .= trim($search)."|";
											
											// auto detect
											$save_string = ($save_string!==false) ? $save_string : $search;
											$ret['GAMES'][$file_crc32]['ECC_INFOS'][$save_type][$save_string] = true;
										}
									}
									
									

								}
								else {
									preg_match('/'.$regex.'/i', $file_name, $matches);
									if (isset($matches[0])) {
										$stripped_infos .= trim($matches[0])."|";
										$file_name = preg_replace('/'.$regex.'/i', "", $file_name);
									}
								}
							}
						}
					}
				}
				
				$file_name = str_replace("  ", " ", $file_name);
				$ret['GAMES'][$file_crc32]['ECCIDENT'] = trim(strtolower($eccident));
				$ret['GAMES'][$file_crc32]['NAME'] = trim($file_name);
				$ret['GAMES'][$file_crc32]['CRC32'] = strtoupper($file_crc32);
				$ret['GAMES'][$file_crc32]['EXT'] = trim($file_ext);
				$ret['GAMES'][$file_crc32]['INFO'] = trim($stripped_infos);
				$ret['GAMES'][$file_crc32]['INFO_ID'] = trim($file_id);
				$count_valid++;
				
				$count_total++;
				
				if ($count_total >= 100) $count_total = 0;
				
				// status-area update
				// --------------------
				if ($count_total%25==0) {
					
					if (!isset($sb_counter)) $sb_counter = 0;
					if ($sb_counter >= 100) $sb_counter = 0;
					$sb_counter++;
					
					// statusbar
					$msg = "Analyze data...";
					$percent = (float)$sb_counter/100;
					$this->status_obj->update_progressbar($percent, $msg);
					
					// Output
					$percent_string = $count_total;
					$message  = "Preparing Romcenter-DAT\n(".basename($this->dat_filename).") for:\n";
					$message .= $this->ini->getPlatformName($this->eccident)." (".$this->eccident.")\n\n";
					$message .= "Prepare only data for extensions assigned to ".$this->eccident."\n";
					$message .= "Extensions: *.".implode("; *.", array_keys($possible_extensions))."\n";
					$this->status_obj->update_message($message);
					if ($this->status_obj->is_canceled()) return false;
				}
				// --------------------
				
				if (!isset($rc_status['EXT_ADDED'][$current_extension])) $rc_status['EXT_ADDED'][$current_extension] = 0;
				$rc_status['EXT_ADDED'][$current_extension]++;
			}
			else {
				if (!isset($rc_status['EXT_NOT_VALID'][$current_extension])) $rc_status['EXT_NOT_VALID'][$current_extension] = 0;
				$rc_status['EXT_NOT_VALID'][$current_extension]++;
			}
			
		}
		
		if (!isset($ret['GAMES'])) {
			$platform_name = $this->ini->getPlatformName($this->eccident);
			$this->status_obj->update_progressbar(0, "ERROR 6!");
			$message = "No matches in Dat-File\n(".basename($this->dat_filename).")\n";
			$message .= "found for ".$platform_name." (".$this->eccident.")\n\n";
			$message .= "Search for this extensions: *.".implode("; *.", array_keys($possible_extensions))." - and found nothing :-(\n";
			$message .= "Maybe this wasnt the right dat-file? :-)\n";
			$message .= "Please use a Dat-File for $platform_name\n";
			$this->status_obj->update_message($message);
			
		}
		else {
			$cnt_total = count($ret['GAMES']);
			$cnt_current = 0;
			$logCntHave = 0;
			
			####
			$this->dbms->query('BEGIN TRANSACTION;');
			####
			
			foreach ($ret['GAMES'] as $crc32 => $infos) {
				
				while (gtk::events_pending()) gtk::main_iteration();
				
				#print $ret['GAMES'][$crc32]['NAME']." - ".$ret['GAMES'][$crc32]['CRC32']."\n";
				
				$file_ext = ($infos['EXT']!="") ? strtolower($infos['EXT']) : strtolower($file_ext_default);
				$file_ext = trim(str_replace(".", "", $file_ext));
				
				$q = "
					SELECT
					*
					FROM
					mdata
					WHERE
					eccident = '".sqlite_escape_string($infos['ECCIDENT'])."' AND
					crc32 = '".sqlite_escape_string($infos['CRC32'])."'
					LIMIT
					0,1
				";
				#print $q."\n";
				$hdl = $this->dbms->query($q);
				
				if ($res = $hdl->fetch(SQLITE_ASSOC)) {
					
					$aaa = $res['info']."".$infos['INFO'];
					
					$bbb = explode("|", $aaa);
					$infos_merged = array_unique($bbb);
					$new_infos = implode("|", $infos_merged);
					
					$q = "
						update
						mdata
						set
						info = '".sqlite_escape_string($new_infos)."'
						where
						id = ".$res['id']."
					";
					#print $q."\n";
					$hdl = $this->dbms->query($q);
					$id = $res['id'];
					
					$log = 'U';
				}
				else {
					
					$running = (isset($infos['ECC_INFOS']['running'])) ? current(array_keys($infos['ECC_INFOS']['running'])) : "NULL";
					$usermod = (isset($infos['ECC_INFOS']['usermod'])) ? current(array_keys($infos['ECC_INFOS']['usermod'])) : "NULL";
					$freeware = (isset($infos['ECC_INFOS']['freeware'])) ? current(array_keys($infos['ECC_INFOS']['freeware'])) : "NULL";
					$year = (isset($infos['ECC_INFOS']['year'])) ? current(array_keys($infos['ECC_INFOS']['year'])) : "NULL";
					$usk = (isset($infos['ECC_INFOS']['usk'])) ? current(array_keys($infos['ECC_INFOS']['usk'])) : "NULL";
					$creator = (isset($infos['ECC_INFOS']['creator'])) ? current(array_keys($infos['ECC_INFOS']['creator'])) : "NULL";
					
					$q = "
						INSERT INTO
						mdata
						(
						id,
						eccident,
						name,
						extension,
						crc32,
						info,
						info_id,
						running,
						usermod,
						freeware,
						year,
						usk,
						creator
						)
						values
						(
						null,
						'".sqlite_escape_string($infos['ECCIDENT'])."',
						'".sqlite_escape_string($infos['NAME'])."',
						'".sqlite_escape_string($file_ext)."',
						'".sqlite_escape_string($infos['CRC32'])."',
						'".sqlite_escape_string($infos['INFO'])."',
						'".sqlite_escape_string($infos['INFO_ID'])."',
						".sqlite_escape_string($running).",
						".sqlite_escape_string($usermod).",
						".sqlite_escape_string($freeware).",
						".sqlite_escape_string($year).",
						".sqlite_escape_string($usk).",
						".sqlite_escape_string($creator)."
						)
					";
					#print $q."\n";
					
					$hdl = $this->dbms->query($q);
					$id = $this->dbms->lastInsertRowid();
					
					$log = 'A';
				}
				
				// process languages
				if (isset($infos['ECC_INFOS']['languages']) && count($infos['ECC_INFOS']['languages'])) {
					$this->save_language_romcenter($id, array_keys($infos['ECC_INFOS']['languages']));
				}
				
				$cnt_current++;
				
				// status-area update
				// --------------------
				
				$percent_string = sprintf("%02d", ($cnt_current*100)/$cnt_total);
				$msg = "".$percent_string." % ($cnt_current/$cnt_total)";
				$percent = (float)$cnt_current/$cnt_total;
				$this->status_obj->update_progressbar($percent, $msg);
				
				$message  = "Importing Romcenter-DAT\n(".basename($this->dat_filename).") for:\n";
				$message .= $this->ini->getPlatformName($this->eccident)." (".$this->eccident.")\n\n";
				$message .= "Search for: *.".implode("; *.", array_keys($possible_extensions))."\n";
				$message .= "File $cnt_current of $cnt_total\n";
				$message .= $infos['ECCIDENT']."\t".$infos['NAME'].chr(13);
				
				$this->status_obj->update_message($message);
				if ($this->status_obj->is_canceled()){
					$this->dbms->query('ROLLBACK TRANSACTION;');
					return false;
				}
				// --------------------

				$iHave = $this->fileAvailable($infos['ECCIDENT'], $infos['CRC32']);
				if ($iHave) $logCntHave++;
				$this->log .= LOGGER::add('datirc', join("\t", array($log, (int)$iHave, $infos['ECCIDENT'], $infos['CRC32'], $infos['NAME'])));
				
			}
			
			####
			$this->dbms->query('COMMIT TRANSACTION;');
			####
			
			// ---------------
			// ALL DONE
			// ---------------
			
			// Output
			$message  = "Importing Romcenter-DAT!\n";
			$message  = "from Dat-File (".basename($this->dat_filename).") for\n";
			$message .= $this->ini->getPlatformName($this->eccident)." (".$this->eccident.") DONE! :-)\n\n";
			$stats = "Statistics:\n";
			if (isset($rc_status['EXT_ADDED'])) {
				$msg_ext_added = array();
				foreach ($rc_status['EXT_ADDED'] as $msg_ext => $msg_ext_count) {
					$msg_ext_added[] = "*.".$msg_ext." (".$msg_ext_count.")";
				}
				$stats .= "Added by extension: ".implode(", ", $msg_ext_added)."\n";
			}
			if (isset($rc_status['EXT_NOT_VALID'])) {
				$msg_ext_added = array();
				foreach ($rc_status['EXT_NOT_VALID'] as $msg_ext => $msg_ext_count) {
					$msg_ext_added[] = "*.".$msg_ext." (".$msg_ext_count.")";
				}
				$stats .= "Extensions dont match: ".implode("; ", $msg_ext_added)."\n";
			}
			if (isset($rc_status['CORRUPT'])) $stats .= "Corrupt entries in DAT: ".$rc_status['CORRUPT'];
			
			$message .= $stats;
			
			
			#if(LOGGER::$active) {
				$msg  = "DONE: RC-Datfile import\r\n";
				$msg .= "Platform: ".$this->ini->getPlatformName($this->eccident)." (".$this->eccident.")\r\n";
				$msg .= "--> IHAVE: ".$logCntHave." | DONTHAVE: ".($cnt_current-$logCntHave)." | TOTAL: ".$cnt_current."\r\n";
				$msg .= "File: ".basename($this->dat_filename)."\r\n";
				$msg .= "Processed: ".$cnt_current."\r\n";
				$msg .= $stats;
				$this->log .= LOGGER::add('datirc', $msg, 2);
			#}
			
			$this->status_obj->update_message($message);
			if ($this->status_obj->is_canceled()) return false;
		}
	}
	
	/*
	*
	*/
	public function save_language_romcenter($mdata_id, $languages) {
		foreach ($languages as $void => $lang_ident) {
			$q = "SELECT * FROM mdata_language WHERE mdata_id=".$mdata_id." AND lang_id='".$lang_ident."'";
			#print $q."\n";
			$hdl = $this->dbms->query($q);
			if ($res = $hdl->fetch(SQLITE_ASSOC)) {
			}
			else {
				$q = "INSERT INTO mdata_language ( mdata_id, lang_id) VALUES ('".(int)$mdata_id."', '".sqlite_escape_string($lang_ident)."')";
				#print $q."\n";
				$this->dbms->query($q);
			}
		}
		return true;
	}
	
	/*
	* get the fileextension (eg pce, fig, v64) from string
	*/
	public function get_file_extension_from_string($file_name) {
		if (!strpos($file_name, ".")) return false;
		$split = explode(".", $file_name);
		return strtolower(array_pop($split));
	}
	
	/*
	* Fï¿½R DAT-VERSION 0.5.0
	* $res[20] == '*'
	* wenn *, dann valid.
	*/
	public function parse_dat_emucontrolcenter($version){
		
		$this->dat = $this->parse_ini_file_quotes_safe($this->dat_filename);
		$ret = array();
		
		// if eccident is set, 100 percent is the count of ecc-ident-roms
		// in datfile! for statusbar!
		if ($this->eccident) {
			$this->count_eccident = 0;
			foreach ($this->dat['ECC_MEDIA'] as $key => $value) {
				$split = explode(";", $key);
				if ($this->eccident == $split[0]) {
					$this->count_eccident++;
				}
			}
			$cnt_total = $this->count_eccident;
		} else {
			$cnt_total = count($this->dat['ECC_MEDIA']);
		}
		
		$cnt_current = 0;
		$logCntHave = 0;
		
		####
		$this->dbms->query('BEGIN TRANSACTION;');
		####
		
		foreach($this->dat['ECC_MEDIA'] as $mdata => $void) {
			
			while (gtk::events_pending()) gtk::main_iteration();
			
			$res = explode(";", $mdata);
			
			if ($res[0] == 'eccident') continue;
			
			$terminator = false;
			switch ($version) {
				case '0.7':
				case '0.9':
				case '0.95':
					$terminator = 21;
					break;
				case '0.96':
					# publisher and storage added
					$terminator = 23;
					break;
				case '0.97':
					# filesize added (isnt added to database)
					$terminator = 24;
					break;
				case '0.98':
					# filesize added (isnt added to database)
					$terminator = 32;
					if(count($res) == 24) $terminator = 24; // hotfix for an datfile bug.
					break;
			}

			$is_valid = (isset($res[$terminator]) && $res[$terminator] == '#') ? true : false;
		
			// if eccident is set, dont import not matching items.
			if ($this->eccident && $this->eccident!=$res[0]) continue;
			
			if ($is_valid) {
				
				$data = array();
				$data['eccident'] = 	strtolower($res[0]);
				$data['name'] = 		trim($res[1]);
				$data['extension'] = 	trim($res[2]);
				$data['crc32'] = 		strtoupper($res[3]);
				$data['running'] = 		(($res[4] != "")) ? $res[4] : "NULL" ;
				$data['bugs'] = 		(($res[5] != "")) ? $res[5] : "NULL" ;
				$data['trainer'] = 		(($res[6] != "")) ? $res[6] : "NULL" ;
				$data['intro'] = 		(($res[7] != "")) ? $res[7] : "NULL" ;
				$data['usermod'] = 		(($res[8] != "")) ? $res[8] : "NULL" ;
				$data['freeware'] = 	(($res[9] != "")) ? $res[9] : "NULL" ;
				$data['multiplayer'] = 	(($res[10] != "")) ? $res[10] : "NULL" ;
				$data['netplay'] = 		(($res[11] != "")) ? $res[11] : "NULL" ;
				$data['year'] = 		(($res[12] != "")) ? $res[12] : "" ;
				$data['usk'] = 			(is_numeric($res[13])) ? (int)$res[13] : "NULL" ;
				$data['category'] = 	(($res[14] != "")) ? $res[14] : "NULL" ;
				$data['languages'] = 	(($res[15] != "")) ? $res[15] : false ;
				$data['creator'] = 		(($res[16] != "")) ? $res[16] : "" ;
				$data['hardware'] = 	(($res[17] != "")) ? $res[17] : "NULL" ;
				$data['doublettes'] = 	(($res[18] != "")) ? $res[18] : "NULL" ;
				$data['info'] = 		(($res[19] != "")) ? $res[19] : "" ;
				$data['info_id'] = 		(($res[20] != "")) ? $res[20] : "" ;
				
				// v0.96
				
				$data['publisher'] = "";
				$data['storage'] = "NULL";
				if ($version >= '0.96') {
					$data['publisher'] = (($res[21] != "")) ? $res[21] : "" ;
					$data['storage'] = (($res[22] != "")) ? $res[22] : "NULL" ;				
				}
				
				# dont import $res[23]!!!! filesize!
				
				// v0.98
				$data['programmer'] = "";
				$data['musican'] = "";
				$data['graphics'] = "";
				$data['media_type'] = "NULL";
				$data['media_current'] = "NULL";
				$data['media_count'] = "NULL";
				$data['region'] = "NULL";
				$data['category_base'] = "NULL";
				if ($version >= '0.98') {
					$data['programmer'] = (($res[24] != "")) ? $res[24] : "";
					$data['musican'] = (($res[25] != "")) ? $res[25] : "";
					$data['graphics'] = (($res[26] != "")) ? $res[26] : "";
					$data['media_type'] = (($res[27] != "")) ? $res[27] : "NULL";
					$data['media_current'] = (($res[28] != "")) ? $res[28] : "NULL";
					$data['media_count'] = (($res[29] != "")) ? $res[29] : "NULL";
					$data['region'] = (($res[30] != "")) ? $res[30] : "NULL";
					$data['category_base'] = (($res[31] != "")) ? $res[31] : "NULL";
				}
				
				$q = "
					SELECT
					id
					FROM
					mdata
					WHERE
					eccident = '".sqlite_escape_string($data['eccident'])."' AND
					crc32 = '".sqlite_escape_string($data['crc32'])."'
					LIMIT
					0,1
				";
				#print $q;
				$hdl = $this->dbms->query($q);
				
				$id = false;
				if ($res = $hdl->fetch(SQLITE_ASSOC)) {
					$id = $res['id'];
					$this->update_mdata($id, $data);
					$log = 'U';
				}
				else {
					$id = $this->insert_mdata($data);
					$log = 'A';
				}
				
				$iHave = $this->fileAvailable($data['eccident'], $data['crc32']);
				if ($iHave) $logCntHave++;
				$this->log .= LOGGER::add('datiecc', join("\t", array($log, (int)$iHave, $data['eccident'], $data['crc32'], $data['name'])));
				
				// save images
				if ($id !== false && $data['languages'] !== false) {
					$languages = explode("|", $data['languages']);
					$this->save_language($id, array_flip($languages));
				}
			}
			
			$cnt_current++;
			
			// status-area update
			// --------------------
			// statusbar
			$percent_string = sprintf("%02d", ($cnt_current*100)/$cnt_total);
			$msg = "Import: ".$percent_string." % ($cnt_current/$cnt_total)";
			$percent = (float)$cnt_current/$cnt_total;
			$this->status_obj->update_progressbar($percent, $msg);
			// output
			$message  = "Importing emuControlCenter-DAT (".basename($this->dat_filename).") for:\n";
			$message .= $this->ini->getPlatformName($this->eccident)." (".$this->eccident.")\n\n";
			$message .= "Import only data for Platform: '".$this->eccident."'\n";
			$message .= "Entry $cnt_current of $cnt_total processed\n";
			if ($data) $message .= $data['eccident']."\t".$data['name'].chr(13);
			$this->status_obj->update_message($message);
			
			if ($this->status_obj->is_canceled()) {
				$this->dbms->query('ROLLBACK TRANSACTION;');
				return false;
			}
		}
			
		####
		$this->dbms->query('COMMIT TRANSACTION;');
		####
		
		$msg  = "DONE: emuControlCenter Datfile import\r\n";
		$msg .= "Platform: ".$this->ini->getPlatformName($this->eccident)." (".$this->eccident.")\r\n";
		$msg .= "--> IHAVE: ".$logCntHave." | DONTHAVE: ".($cnt_current-$logCntHave)." | TOTAL: ".$cnt_current."\r\n";
		$msg .= "File: ".basename($this->dat_filename)."\r\n";
		$msg .= "Processed: ".$cnt_total;
		$this->log .= LOGGER::add('datiecc', $msg, 2);
	}
	
	public function fileAvailable($eccident, $crc32) {
		$q = "SELECT id FROM fdata WHERE eccident = '".sqlite_escape_string($eccident)."' AND crc32 = '".sqlite_escape_string($crc32)."' LIMIT 1";
		$hdl = $this->dbms->query($q);
		return ($res = $hdl->fetch(SQLITE_ASSOC)) ? true : false; 
	}
	
	public function insert_mdata($data){
		
		$q = "
			INSERT INTO
			mdata
			(
				eccident,
				name,
				crc32,
				extension,
				info,
				info_id,
				running,
				bugs,
				trainer,
				intro,
				usermod,
				freeware,
				multiplayer,
				netplay,
				year,
				usk,
				category,
				creator,
				publisher,
				storage,
				programmer,
				musican,
				graphics,
				media_type,
				media_current,
				media_count,
				region,
				category_base
			)
			VALUES
			(
				'".sqlite_escape_string($data['eccident'])."',
				'".sqlite_escape_string($data['name'])."',
				'".sqlite_escape_string($data['crc32'])."',
				'".sqlite_escape_string($data['extension'])."',
				'".sqlite_escape_string($data['info'])."',
				'".sqlite_escape_string($data['info_id'])."',
				".sqlite_escape_string($data['running']).",
				".sqlite_escape_string($data['bugs']).",
				".sqlite_escape_string($data['trainer']).",
				".sqlite_escape_string($data['intro']).",
				".sqlite_escape_string($data['usermod']).",
				".sqlite_escape_string($data['freeware']).",
				".sqlite_escape_string($data['multiplayer']).",
				".sqlite_escape_string($data['netplay']).",
				'".sqlite_escape_string($data['year'])."',
				".sqlite_escape_string($data['usk']).",
				".sqlite_escape_string($data['category']).",
				'".sqlite_escape_string($data['creator'])."',
				'".sqlite_escape_string($data['publisher'])."',
				".sqlite_escape_string($data['storage']).",
				'".sqlite_escape_string($data['programmer'])."',
				'".sqlite_escape_string($data['musican'])."',
				'".sqlite_escape_string($data['graphics'])."',
				".sqlite_escape_string($data['media_type']).",
				".sqlite_escape_string($data['media_current']).",
				".sqlite_escape_string($data['media_count']).",
				".sqlite_escape_string($data['region']).",
				".sqlite_escape_string($data['category_base'])."
			)
		";
		$this->dbms->query($q);
		return $this->dbms->lastInsertRowid();
	}
	
	public function update_mdata($id, $data)
	{
		$q = "
			UPDATE
			mdata
			SET
			eccident = '".sqlite_escape_string($data['eccident'])."',
			name = '".sqlite_escape_string($data['name'])."',
			crc32 = '".sqlite_escape_string($data['crc32'])."',
			extension ='".sqlite_escape_string($data['extension'])."',
			info = '".sqlite_escape_string($data['info'])."',
			info_id = '".sqlite_escape_string($data['info_id'])."',
			running = ".sqlite_escape_string($data['running']).",
			bugs = ".sqlite_escape_string($data['bugs']).",
			trainer = ".sqlite_escape_string($data['trainer']).",
			intro = ".sqlite_escape_string($data['intro']).",
			usermod = ".sqlite_escape_string($data['usermod']).",
			freeware =  ".sqlite_escape_string($data['freeware']).",
			multiplayer = ".sqlite_escape_string($data['multiplayer']).",
			netplay = ".sqlite_escape_string($data['netplay']).",
			year = '".sqlite_escape_string($data['year'])."',
			usk = ".sqlite_escape_string($data['usk']).",
			category = ".sqlite_escape_string($data['category']).",
			creator = '".sqlite_escape_string($data['creator'])."',
			publisher = '".sqlite_escape_string($data['publisher'])."',
			storage = ".sqlite_escape_string($data['storage']).",
			
			programmer = '".sqlite_escape_string($data['programmer'])."',
			musican = '".sqlite_escape_string($data['musican'])."',
			graphics = '".sqlite_escape_string($data['graphics'])."',
			media_type = ".sqlite_escape_string($data['media_type']).",
			media_current = ".sqlite_escape_string($data['media_current']).",
			media_count = ".sqlite_escape_string($data['media_count']).",
			region = ".sqlite_escape_string($data['region']).",
			category_base = ".sqlite_escape_string($data['category_base']).",
			
			uexport = NULL,
			cdate = NULL
			WHERE
			id = ".$id."
		";
		
//				$data['programmer'] = "";
//				$data['musican'] = "";
//				$data['graphics'] = "";
//				$data['media_type'] = "NULL";
//				$data['media_current'] = "NULL";
//				$data['media_count'] = "NULL";
//				$data['region'] = "NULL";
//				$data['category_base'] = "NULL";
		
		#print $q."\n";
		$this->dbms->query($q);
	}
	
	/*
	*
	*/
	public function save_language($mdata_id, $languages) {
		$q = "DELETE FROM mdata_language WHERE mdata_id=".$mdata_id;
		#print $q;
		$hdl = $this->dbms->query($q);
		foreach ($languages as $lang_ident => $void) {
			$q = "INSERT INTO mdata_language ( mdata_id, lang_id) VALUES ('".$mdata_id."', '".$lang_ident."')";
			#print $q;
			$hdl = $this->dbms->query($q);
		}
		return true;
	}
	
	public function parse_ini_file_quotes_safe($f, $row_count_limit=false)
	{
		$newline = "
		";
		$null = "";
		$r=$null;
		$first_char = "";
		$sec=$null;
		$comment_chars="/*<;#?>";
		$num_comments = "0";
		$header_section = "";
		
		//Read to end of file with the newlines still attached into $f

		if ($this->datfileContent) {
			$f = explode("\r\n", $this->datfileContent);
		}
		else {
			$f=file($f);
		}
		
		$row_count = ($row_count_limit) ? $row_count_limit : count($f);
		
		// Process all lines from 0 to count($f) 
		for ($i=0;$i<@$row_count;$i++) {
			
			while (gtk::events_pending()) gtk::main_iteration();
			
			$newsec=0;
			$w=@trim($f[$i]);
			
			if ($w) $w = MultiByte::convertToUtf8($w);
			
			$first_char = @substr($w,0,1);
			if ($w) {
				if ((!$r) or ($sec)) {
					// Look for [] chars round section headings
					if ((@substr($w,0,1)=="[") and (@substr($w,-1,1))=="]") {$sec=@substr($w,1,@strlen($w)-2);$newsec=1;}
					// Look for comments and number into array
					if ((stristr($comment_chars, $first_char) === FALSE)) {} else {$sec=$w;$k="Comment".$num_comments;$num_comments = $num_comments +1;$v=$w;$newsec=1;$r[$k]=$v;/*echo "comment".$w.$newline;*/}
					//
				}
				if (!$newsec) {
					//
					// Look for the = char to allow us to split the section into key and value 
					$w=@explode("=",$w);$k=@trim($w[0]);unset($w[0]); $v=@trim(@implode("=",$w));
					// look for the new lines 
					if ((@substr($v,0,1)=="\"") and (@substr($v,-1,1)=="\"")) {$v=@substr($v,1,@strlen($v)-2);}
					if ($sec) {$r[$sec][$k]=$v;} else {$r[$k]=$v;}
				}
			}
		}
		return $r;
	}
}
?>
