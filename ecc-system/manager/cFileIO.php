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
	* size größe in byte
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
	* ermittelt die größe der datei
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
	
	public function fopen_zip($file_name_direct, $file_name_packed) {	
		
		// ABS-PATH TO REL-PATH...
		$file_name_direct = realpath($file_name_direct);
		$zip = zip_open($file_name_direct);
		#$zip = zip_open($file_name_direct);
		if ($zip) {
			while ($zip_entry = zip_read($zip)) {
				$current_entry =  zip_entry_name($zip_entry);
				if ($file_name_packed == $current_entry) {
					if (zip_entry_open($zip, $zip_entry, "r")) {
						
						$tempFolder = getcwd().'/temp/';
						
						$buf = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
						$file_temp = $tempFolder.basename($file_name_packed);
						
						if (!is_dir($tempFolder)) mkdir($tempFolder);
						
						$fhdl = fopen($file_temp, 'w+b');
						fwrite($fhdl, $buf);
						return $fhdl;
						zip_entry_close($zip_entry);
					}
				}
			}
			zip_close($zip);
		}
	}
	
	public function fclose_zip($fhdl, $path) {
		fclose($fhdl);
		unlink($path);
	}
	
	public function ecc_reset($fhdl) {
		fseek($fhdl, 0);
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
			// gibt den ascii-wert (integer) des strings zurück
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
	* List die Datei unter berücksichtigung eines
	* start und end offsets ein
	*/	
	public function ecc_read_file($fhdl, $start_offset=false, $end_offset=false, $file_name=false) {
		
		// Beispiel MP3
		// id3v1 (die letzten 128 bytes im mp3) darf nicht in die
		// kalkulation der checksumme einfließen
		// $file_content = FileIO::ecc_read_file($fhdl, 0, -128, $file_name);
		// liest file von byte 0 bis filesize-128
		//
		// Beispiel SNES
		// Hat manchmal einen 512 kb großen Rom-Header, der von
		// kopierstationen in das rom geschrieben wird. Er ist für die chcksumme nicht
		// relevant und muß ausgelassen werden.
		// $file_content = FileIO::ecc_read_file($fhdl, 512, false, $file_name);
		// liest datei ab byte 512 bis zum ende der datei.
		//
		// Beispiel ???
		// Datei wird von byte 100 bis 150 eingelesen
		// $file_content = FileIO::ecc_read_file($fhdl, 100, 50, $file_name);
		
		// Wenn der file_name gesetzt ist sowie der offset nicht
		// benötigt wird, kann auch direkt eingeladen werden.
		// Das ist performanter
		if (
			$file_name !== false &&
			$start_offset === false &&
			$end_offset === false
		) {
			if (is_file($file_name)) {
				return file_get_contents($file_name);
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
		return str_pad (strtoupper(dechex(crc32($string))), 8, '0', STR_PAD_LEFT);
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
		return (@unlink($fileName));
	}
	
	public function rmdirr($dirName) {
		if(empty($dirName) || !file_exists($dirName)) return false;
		$dir = dir($dirName);
		while($file = $dir->read()) {
			if($file != '.' && $file != '..') {
				if(is_dir($dirName.'/'.$file)) $this->rmdirr($dirName.'/'.$file);
				else @unlink($dirName.'/'.$file);
			}
		}
		@rmdir($dirName.'/'.$file);
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
			return array_shift($split);
		}
		return "";
	}
}

?>
