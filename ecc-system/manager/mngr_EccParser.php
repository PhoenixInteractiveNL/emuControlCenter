<?php
require_once("iface_FileParser.php");
require_once("iface_FileList.php");
require_once("mngr_EccParserFileListDir.php");
require_once("mngr_FileIO.php");
require_once("mngr_Singleton.php");
require_once("mngr_EccParserMedia.php");
require_once("mngr_EccParserDataProzessor.php");

class EccParser {
	
	public function __construct($db, $eccident=false, $ini, $path, $statusbar, $statusbar_lbl_bottom, $status_obj) {
		
		if ($status_obj->is_canceled()) return false;
		
		if (!$db) return false;
		$dataParser = new EccParserMedia($db, $path);
		
		// parse only eccident, if set. else parse everything found
		$wanted_extensions = $ini->get_ecc_platform_parser($eccident);
		if (is_dir($path) && count($wanted_extensions)) {
			
			// retrieve list from filesystem
			$fileList = new EccParserFileListDir($path, $wanted_extensions, $statusbar, $statusbar_lbl_bottom, $status_obj);
			$file_stats = $fileList->get_stats();
			
			// parse files and write them to the dab
			$dataProzessor = new EccParserDataProzessor($dataParser, $fileList);
			$dataProzessor->parse();
			$parser_stats = $dataProzessor->get_stats();
			
			// validate older files... are all in place?
			$dataParser->optimize();
		}
		else {
			#print "pfad oder keine extensions angegeben\n";
		}
	}
}
?>
