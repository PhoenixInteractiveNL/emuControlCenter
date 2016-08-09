<?php
/*
*
*/
class FileIO {
	
	/*
	*
	*/
	public function __construct() {}
	
	
	/**
	 * replace the fileextension of a given file with the replacement!
	 */
	public function replaceFileExtension($fileName, $newExtension) {
		$fileNamePlain = $this->get_plain_filename($fileName);
		return dirname($fileName).'/'.$fileNamePlain.'.'.$newExtension;
	}
	
	/*
	* Sucht informationen zu file
	* ext extension
	* name filename ohne extension
	* size grÃ¶Ãe in byte
	*/	
	public function ecc_file_get_info($path) {
		
		$ret = array();
		$file_basename = explode('.', basename($path));
		
		// fileextension
		$ret['EXT'] = array_pop($file_basename);
		$file_name = implode('.', $file_basename);
		
		// filename
		$ret['NAME'] = trim($file_name);
		
		// filezisz kb
		$ret['SIZE'] = FileIO::get_file_size($path, false, 'B');
		
		return $ret;
	}
	
	/*
	* ermittelt die grÃ¶Ãe der datei
	*/
	public function get_file_size($file_direct, $file_packed=false, $size='KB')
	{
		if ($file_packed !== false) {
			$size_b = FileIO::get_zip_size($file_direct, $file_packed);
		}
		else {
			$size_b = filesize($file_direct);
		}
		
		switch($size) {
			// 'KB' kilobytes
			case 'KB':
				return (integer) ($size_b/1024);
				break;
			
			// 'MB' megabytes
			case 'MB':
				return (integer) ($size_b/1024/1024);
				break;
			
			// default bytes
			case 'B':
				return (integer) $size_b;
				break;
		}
	}
	
	public function get_zip_size($file_direct, $file_packed) {
		
		// ABS-PATH TO REL-PATH...
		$file_direct = realpath($file_direct);
		$zip = zip_open($file_direct);
		#$zip = zip_open($file_direct);
		if ($zip) {
			while ($zip_entry = zip_read($zip)) {
				$current_entry =  zip_entry_name($zip_entry);
				if ($file_packed == $current_entry) {
					return zip_entry_filesize($zip_entry);
				}
			}
			zip_close($zip);
		}
	}
	
	public function fopen_zip($zipFileName, $zipEntryFileName) {	
		
		$zip = new ZipArchive;
		$res = $zip->open($zipFileName);
		if($res !== true) return null;

		$tempFolder = getcwd().'/temp/';
		if (!is_dir($tempFolder)) mkdir($tempFolder);
		
		$zip->extractTo($tempFolder, array($zipEntryFileName));
		
		$tempFile = $tempFolder.$zipEntryFileName;
		$fhdl = fopen($tempFile, 'r+b');
		
//		$zip = new ZipArchive();
//		$zip->open($zipFileName);
//		$buf = $zip->getFromName($zipEntryFileName);
//		$zip->close();
//		
//		$tempFolder = getcwd().'/temp/';
//		if (!is_dir($tempFolder)) mkdir($tempFolder);
//		$tempFile = $tempFolder.basename($zipEntryFileName);
//		
//		$fhdl = fopen($tempFile, 'w+b');
//		fwrite($fhdl, $buf);
		
		# quick hack
		# fsum cannot parse an file with open filehandle
		# dont return an valid filehandle here, because the
		# zips dont need an filehandle!
		if(filesize($tempFile) >= SLOW_CRC32_PARSING_FROM){
			fclose($fhdl);
			return null;
		}
		
		return $fhdl;
	}
	
	public function extractZip($zipFile, $zipEntry, $destinationFolder = false){
		
		# if destination not set, extract to zip file folder
		if($destinationFolder === false) $destinationFolder = realpath(dirname($zipFile));
		else $destinationFolder = realpath($destinationFolder);
		
		$zip = new ZipArchive();
		$zip->open($zipFile);
		$zip->extractTo($destinationFolder, $zipEntry);
		$zip->close();
	}

	public function extractZipAll($zipFile, $zipEntry, $destinationFolder = false){
		
		# if destination not set, extract to zip file folder
		if($destinationFolder === false) $destinationFolder = realpath(dirname($zipFile));
		else $destinationFolder = realpath($destinationFolder);
		
		$zip = new ZipArchive();
		$zip->open($zipFile);
		$zip->extractTo($destinationFolder);
		$zip->close();
	}
	
	public function extractSzip($zipFile, $zipEntry, $outputFolder = false){
		
		# if destination not set, extract to zip file folder
		if($outputFolder === false) $outputFolder = realpath(dirname($zipFile));
		else $outputFolder = realpath($outputFolder);
		
		$manager7zip = FACTORY::get('manager/cmd/php7zip/sZip');
		$manager7zip->setExecutable(SZIP_UNPACK_EXE);
		$manager7zip->extract($zipFile, $zipEntry, $outputFolder);
	}

	public function extractSzipAll($zipFile, $zipEntry, $outputFolder = false){
		
		# if destination not set, extract to zip file folder
		if($outputFolder === false) $outputFolder = realpath(dirname($zipFile));
		else $outputFolder = realpath($outputFolder);
		
		$manager7zip = FACTORY::get('manager/cmd/php7zip/sZip');
		$manager7zip->setExecutable(SZIP_UNPACK_EXE);
		$manager7zip->extractAll($zipFile, $zipEntry, $outputFolder);
	}
	
	public function fclose_zip($fhdl, $path) {
		if($fhdl) fclose($fhdl);
		if($path) unlink($path);
	}
	
	public function ecc_reset($fhdl) {
		fseek($fhdl, 0);
	}
	
	public function getFileDataFromZip($filename, $include = false, $exclude = false){
		
		$data = array();
		$zip_hdl = zip_open(realpath($filename));
		if ($zip_hdl === false || is_int($zip_hdl)) return false;
		else {
			while ($zip_entry = zip_read($zip_hdl)) {
				
				$entryName = zip_entry_name($zip_entry);
				$fileExt = FileIO::get_ext_form_file($entryName);
				
				if (count($include) && !isset($include[strtolower($fileExt)])) {
					$fileValid = false;
				}
				else {
					$fileValid = true;
					if ($exclude){
						foreach($exclude as $ext){
							if (false !== stripos($fileExt, $ext)){
								$fileValid = false;
								break;
							}
						}
					}
				}
				
				if ($fileValid) $data[] = $entryName;
			}
			zip_close($zip_hdl);
		}
//		print "\n<pre>";
//		print_r($data);
//		print "</pre>\n";

		return $data;
	}
	
	/*
	* ecc_read(fHdl, 160, 12, False)
	* - liest 12 bytes der Datei ab Position 160
	* ecc_read(fHdl, False, False, False) || romData = getRomInfo(fHdl)
	* - liest die Komplette Datei ein
	* ecc_read(fHdl, False, -128, False)
	* - Liest die Datei von Position 0 bis zum (EOF-128Bytes)
	* ecc_read(fHdl, -128, False, False)
	* - Liest die Datei von (EOF-128Bytes) bis zum EOF (liest also 128byte)
	*
	* type_result:
	* False	=> return chars
	* 'DEZ'	=> return integer
	* 'HEX'	=> return hexadezimal
	*/
	public function ecc_read($fhdl, $fseek=false, $read_bytes, $type_result=false) {
		
		// Kontrolle, ob ein fseek angegeben wurde.
		// Bei negativem fssek wird vom ende des Files augegangen
		// Bei positivem fssek wird dieser von der aktuellen position gesetz!
		if ($fseek) {
			if ($fseek < 0) {
				fseek($fhdl, $fseek, SEEK_END);
			}
			else {
				fseek($fhdl, $fseek);
			}
		}
		
		// $type_result = 
		// false
		// 'DEZ'
		// 'HEX'
		switch($type_result) {
			
			// 'DEZ'
			// gibt den ascii-wert (integer) des strings zurÃ¼ck
			case 'DEZ':
				$out = 0;
				$data = fread($fhdl, $read_bytes);
				for($i=0; $i<strlen($data); $i++) {
					$out += ord($data[$i]);
				}
				return (integer)$out;
				break;
				
			// 'HEX'
			// original for ecc python version
			// result = hex(ord(result))[2:].upper().zfill(2)
			case 'HEX':
				$data = fread($fhdl, $read_bytes);
				$data = dechex(ord($data));
				return $data;
				break;
				
			// 'DEFAULT'
			default:
				return fread($fhdl, $read_bytes);
		}
	}
	
	/*
	* List die Datei unter berÃ¼cksichtigung eines
	* start und end offsets ein
	*/	
	public function ecc_read_file($fhdl, $start_offset=false, $end_offset=false, $file_name=false) {
		
		// Beispiel MP3
		// id3v1 (die letzten 128 bytes im mp3) darf nicht in die
		// kalkulation der checksumme einflieÃen
		// $file_content = FileIO::ecc_read_file($fhdl, 0, -128, $file_name);
		// liest file von byte 0 bis filesize-128
		//
		// Beispiel SNES
		// Hat manchmal einen 512 kb groÃen Rom-Header, der von
		// kopierstationen in das rom geschrieben wird. Er ist fÃ¼r die chcksumme nicht
		// relevant und muÃ ausgelassen werden.
		// $file_content = FileIO::ecc_read_file($fhdl, 512, false, $file_name);
		// liest datei ab byte 512 bis zum ende der datei.
		//
		// Beispiel ???
		// Datei wird von byte 100 bis 150 eingelesen
		// $file_content = FileIO::ecc_read_file($fhdl, 100, 50, $file_name);
		
		// Wenn der file_name gesetzt ist sowie der offset nicht
		// benÃ¶tigt wird, kann auch direkt eingeladen werden.
		// Das ist performanter
		if (
			$file_name !== false &&
			$start_offset === false &&
			$end_offset === false
		) {
			# fastest way to get the data
			if (filesize($file_name) < SLOW_CRC32_PARSING_FROM) {
				return file_get_contents($file_name);
			}
			else {
				$handle = fopen($file_name, "rb");
				$contents = '';
				$count = 0;
				$bytesPerRun = SLOW_CRC32_PARSING_FROM/4;
				$bytesTotal = filesize($file_name);
				$currentFileName = basename($file_name);
				while(!feof($handle)){
					$contents .= fread($handle, $bytesPerRun);
					
					#$test = fread($handle, $bytesPerRun);
					#$contents .= substr(trim($test), 0, 10);
					
					#file_put_contents('c:/test.cdi', $contents, FILE_APPEND);
					
					$bytesTotal -= $bytesPerRun;
					$bytesLeft = round($bytesTotal/1024/1224, 1);
					if($bytesLeft<0) $bytesLeft = 0; 
					
					FACTORY::get('manager/GuiStatus')->update_message('Parsing '.$currentFileName.'... '.$bytesLeft.' MB left');
					
					while (gtk::events_pending()) gtk::main_iteration();
					$count++;
					
					
				}
				fclose($handle);
				
				
				
				return $contents;
			}
		}
		else {
			// Startposition verschieben zum lesen!
			if ($start_offset < 0) {
				fseek($fhdl, $start_offset, SEEK_END);
			}
			else {
				fseek($fhdl, $start_offset, SEEK_SET);
			}
			
			// Datei wird nur bis zum endoffset eingelesen.
			$file_info = fstat($fhdl);
			if ($end_offset < 0) {
				$end_pos = $file_info['size']+$end_offset;
			}
			elseif ($end_offset > 0) {
				$end_pos = $start_offset+$end_offset;
			}
			else {
				$end_pos = $file_info['size'];
			}
			
			#print "start: ".ftell($fhdl)." ($start_offset) nend: ".$end_pos." ($end_offset) oend ".$file_info['size']." ".($end_pos-$file_info['size'])."\n";
			$content = fread($fhdl, $end_pos);
			return $content;
		}
		
	}
	
	/*
	*
	*/	
	public function ecc_get_md5_from_string($string) {
		return strtoupper(md5($string));
	}
	
	/*
	*
	*/	
	public function ecc_get_crc32_from_string($string) {
		return str_pad(strtoupper(dechex(crc32($string))), 8, '0', STR_PAD_LEFT);
	}
	
	public function createMergedEccCrc32($crc32Array){
		if (!is_array($crc32Array)) return false;
		if (count($crc32Array) == 1) return reset($crc32Array);
		asort($crc32Array);
		$combinedCrc32String = join(",", $crc32Array);
		return self::ecc_get_crc32_from_string($combinedCrc32String);
	}
	
	/*
	*
	*/	
	public function eccGetCrc32FromFile($fileName) {
		return str_pad (strtoupper(dechex(crc32(file_get_contents($fileName)))), 8, '0', STR_PAD_LEFT);
	}
	
	public function copyFile($fileNameSource, $fileNameDestination) {
		if (!is_file($fileNameSource)) return false;
		if ($fileNameSource == $fileNameDestination) return true;
		copy($fileNameSource, $fileNameDestination);
		return true;
	}
	
	public function renameFile($fileNameSource, $fileNameDestination) {
		if (!realpath($fileNameSource)) return false;
		//if (!VALID::fileName(basename($fileNameDestination))) return false;
		if ($fileNameSource == $fileNameDestination) return true;
		return rename($fileNameSource, $fileNameDestination);
	}
	
	public function deleteFileByFilename($fileName) {
		return @unlink($fileName);
	}
	
	public function rmDirComplete($dirName){
		if(empty($dirName) || !file_exists($dirName)) return false;
		$command = "RMDIR /S /Q ".escapeshellarg($dirName.'/')."";
		return exec($command);
	}
	
	public function rmdirr($dirName) {
		if(!$dirName || !file_exists($dirName)) return false;
		$dir = dir($dirName);
		while($file = $dir->read()) {
			if($file != '.' && $file != '..') {
				$currentPath = $dirName.'/'.$file;
				if(is_dir($currentPath)){
					$this->rmdirr($currentPath);
					@rmdir($currentPath);
				}
				else unlink($currentPath);
			}
		}
		$dir->close();
		rmdir($dirName);
	}
	
	public function dirIsEmpty($dirName){
		if (!$dirName) die("No path given in dirIsEmpty");
		$dir = dir($dirName);
		while($file = $dir->read()) {
			if($file != '.' && $file != '..') return false;
		}
		return true;
	}
	
	/*
	*
	*/
	public function get_ext_form_file($file) {
		if (false !== strpos($file, ".")) {
			$split = explode(".", $file);
			return array_pop($split);
		}
		return "";
	}
	
	/*
	*
	*/
	public function get_plain_filename($file) {
		$file = basename($file);
		if (false !== strpos($file, ".")) {
			$split = explode(".", $file);
			array_pop($split);
			$plainName = join('.', $split);
			return FileIO::covertStringToUtf8($plainName);
			#return FileIO::covertStringToUtf8(array_shift($split));
		}
		return "";
	}

	public function covertStringToUtf8($string){
		// TODO Detect encoding using mbstring functions
		return iconv('ISO-8859-1', 'UTF-8//TRANSLIT', $string);		
	}
	
	public function getFsumCrc32($filename){
		
		if(is_dir($filename)) return false;
		
		$fileSize = filesize($filename);
		
		if(!$fileSize) return false;
		
		# configuration
		$crcGeneratorFile = realpath('../ecc-core/thirdparty/fsum/fsum.exe');
		if(!$crcGeneratorFile) return false;
		
		$crcGeneratorParams = '-crc32';
		$logFile = realpath('../ecc-core/thirdparty/fsum/').'eccCrc32.chk';
		
		# create command for execution
		#$execCommand = '"'.$crcGeneratorFile.'" '.$crcGeneratorParams.' '.escapeshellarg(basename($filename)).' > '.escapeshellarg($logFile);
		$execCommand = '"'.$crcGeneratorFile.'" '.$crcGeneratorParams.' "'.basename($filename).'" > "'.$logFile.'"';
		
		# get manager os
		$mngrOs = FACTORY::getManager('Os');
		
		$commandIsExecuted = false; # set true, if command is executed
		$count = 0; # try counter
		$crc32 = false; # result
		$error = false;
		while(true){
			
			# first execute the given command
			# set $commandIsExecuted = true, if executed
			# then read logfile to get the right crc32
			if(!$commandIsExecuted){
				
				# first remove old logfile!
				@unlink($logFile); # now remove chk logfile
				
				$commandCwdPath = $mngrOs->executeCommand($execCommand, dirname($filename), $returnCwdPath = true);
				$commandIsExecuted = true;
			}
			else{
				
				# sleep 0.1 second (100000)
				$setSleep = 100000;
				#usleep($setSleep);
				
				$count++;
				
				# some status informations for gui progress!
				FACTORY::get('manager/GuiStatus')->update_message('Parsing (fsum) '.basename($filename).' ('.round($fileSize/1024/1024, 1).' MB)... pass '.$count);
				while (gtk::events_pending()) gtk::main_iteration();
				
				#print "wait for result... $count".chr(13);
				
				# read logfile
				$logFileText = file_get_contents($logFile);
				
				# if empty, next try!
				if(!trim($logFileText)) continue;
					
				# logfile contains 4 lines... frist three are comments (;)
				$data = explode("\n", trim($logFileText));
				
				# result contains 5 rows of log-data... otherwise next try!
				if (count($data) != 5) continue;
				
				# all fine now, try to get crc32 from result
					
				# get the crc32 from found line 4
				# fum-format: 7634c61e ?CRC32*romfileBasename.ext
				$data2 = explode('?', $data[4]);
				$crc32 = trim($data2[0]);
				
				# now remove the logfile!
				unlink($logFile); # now remove chk logfile
				
				# now change back to old location!
				chdir($commandCwdPath);
				
				# all done, return function
				return strtoupper($crc32);
			}
		}
		return false;
	}	

	public static $fileList;
	public static $basePath;
	public static function readDirRecursive($currentDir, $callback = false) {
		
		# store basedir
		if(!self::$basePath) self::$basePath = $currentDir;
		
		$d = opendir($currentDir);
		while(($currentFilename = readdir($d)) !== false) {
			if ($currentFilename == '.' || $currentFilename == '..') continue;
			$currentPath = realpath($currentDir.'/'.$currentFilename);
			if(!$currentPath) continue;
			
			// if is directory, read dir
			if (is_dir($currentPath)) self::readDirRecursive($currentPath, $callback);
			
			# if callback is set, and return false -> skip entry
			if(isset($callback) && is_array($callback)){
				$callbackObject = $callback[0];
				$callbackMethod = $callback[1];
				$callbackParams = @$callback[2];
				$data = $callbackObject->$callbackMethod($currentPath, self::$basePath, $callbackParams);
				if($data !== false) {
					
					if(is_array($data) && count($data) == 2){
						if(is_array($data[1])){
							if(!isset(self::$fileList[$data[0]])) self::$fileList[$data[0]] = array();
							array_push(self::$fileList[$data[0]], $data[1][0]);
						}
						else{
							self::$fileList[$data[0]] = $data[1];	
						}
					}
					else self::$fileList[] = $data;	
				}
				else continue;
			}
			else self::$fileList[] = $currentPath;
		}
		return self::$fileList;	
	}
	
	
	/**
	 * Gather info, if this device is read only
	 *
	 * @param string $path
	 * @return boolean
	 */
	public function deviceIsReadOnly($path) {
		$chkFile = $path.'/eccWrite.chk';
		$isReadOnly = true;
		if(@file_put_contents($chkFile, 'can be removed!')) {
			$isReadOnly = false;
			unlink($chkFile);
		}
		return $isReadOnly;
	}
}

?>
